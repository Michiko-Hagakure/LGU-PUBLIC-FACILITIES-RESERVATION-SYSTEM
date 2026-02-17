/**
 * LGU1 PFRS - IndexedDB Offline Data Cache
 * Stores read-only data locally so users can view cached content when offline.
 * Supports: facilities, bookings, announcements, user profile data.
 */

const LGU1OfflineDB = (function () {
    const DB_NAME = 'lgu1_offline_db';
    const DB_VERSION = 1;

    // Store definitions
    const STORES = {
        facilities: { keyPath: 'id', indexes: ['name', 'type', 'status'] },
        bookings: { keyPath: 'id', indexes: ['facility_id', 'status', 'booking_date'] },
        announcements: { keyPath: 'id', indexes: ['created_at'] },
        userProfile: { keyPath: 'id' },
        metadata: { keyPath: 'key' },
    };

    let dbInstance = null;

    /**
     * Open or create the IndexedDB database
     */
    function openDB() {
        if (dbInstance) {
            return Promise.resolve(dbInstance);
        }

        return new Promise((resolve, reject) => {
            const request = indexedDB.open(DB_NAME, DB_VERSION);

            request.onupgradeneeded = (event) => {
                const db = event.target.result;

                Object.entries(STORES).forEach(([storeName, config]) => {
                    if (!db.objectStoreNames.contains(storeName)) {
                        const store = db.createObjectStore(storeName, { keyPath: config.keyPath });
                        if (config.indexes) {
                            config.indexes.forEach((idx) => {
                                store.createIndex(idx, idx, { unique: false });
                            });
                        }
                    }
                });
            };

            request.onsuccess = (event) => {
                dbInstance = event.target.result;

                dbInstance.onclose = () => {
                    dbInstance = null;
                };

                resolve(dbInstance);
            };

            request.onerror = (event) => {
                console.error('[OfflineDB] Failed to open database:', event.target.error);
                reject(event.target.error);
            };
        });
    }

    /**
     * Save a single record to a store
     */
    async function put(storeName, data) {
        const db = await openDB();
        return new Promise((resolve, reject) => {
            const tx = db.transaction(storeName, 'readwrite');
            const store = tx.objectStore(storeName);
            const request = store.put(data);
            request.onsuccess = () => resolve(request.result);
            request.onerror = () => reject(request.error);
        });
    }

    /**
     * Save multiple records to a store (bulk insert/update)
     */
    async function putAll(storeName, dataArray) {
        const db = await openDB();
        return new Promise((resolve, reject) => {
            const tx = db.transaction(storeName, 'readwrite');
            const store = tx.objectStore(storeName);

            dataArray.forEach((item) => store.put(item));

            tx.oncomplete = () => resolve(true);
            tx.onerror = () => reject(tx.error);
        });
    }

    /**
     * Get a single record by key
     */
    async function get(storeName, key) {
        const db = await openDB();
        return new Promise((resolve, reject) => {
            const tx = db.transaction(storeName, 'readonly');
            const store = tx.objectStore(storeName);
            const request = store.get(key);
            request.onsuccess = () => resolve(request.result || null);
            request.onerror = () => reject(request.error);
        });
    }

    /**
     * Get all records from a store
     */
    async function getAll(storeName) {
        const db = await openDB();
        return new Promise((resolve, reject) => {
            const tx = db.transaction(storeName, 'readonly');
            const store = tx.objectStore(storeName);
            const request = store.getAll();
            request.onsuccess = () => resolve(request.result || []);
            request.onerror = () => reject(request.error);
        });
    }

    /**
     * Get records by index value
     */
    async function getByIndex(storeName, indexName, value) {
        const db = await openDB();
        return new Promise((resolve, reject) => {
            const tx = db.transaction(storeName, 'readonly');
            const store = tx.objectStore(storeName);
            const index = store.index(indexName);
            const request = index.getAll(value);
            request.onsuccess = () => resolve(request.result || []);
            request.onerror = () => reject(request.error);
        });
    }

    /**
     * Delete a single record by key
     */
    async function remove(storeName, key) {
        const db = await openDB();
        return new Promise((resolve, reject) => {
            const tx = db.transaction(storeName, 'readwrite');
            const store = tx.objectStore(storeName);
            const request = store.delete(key);
            request.onsuccess = () => resolve(true);
            request.onerror = () => reject(request.error);
        });
    }

    /**
     * Clear all records from a store
     */
    async function clear(storeName) {
        const db = await openDB();
        return new Promise((resolve, reject) => {
            const tx = db.transaction(storeName, 'readwrite');
            const store = tx.objectStore(storeName);
            const request = store.clear();
            request.onsuccess = () => resolve(true);
            request.onerror = () => reject(request.error);
        });
    }

    /**
     * Save metadata (last sync time, data version, etc.)
     */
    async function setMeta(key, value) {
        return put('metadata', { key: key, value: value, updatedAt: Date.now() });
    }

    /**
     * Get metadata by key
     */
    async function getMeta(key) {
        const record = await get('metadata', key);
        return record ? record.value : null;
    }

    /**
     * Cache API response data into the appropriate store.
     * Call this after successful fetch requests to keep offline data fresh.
     */
    async function cacheApiResponse(storeName, data) {
        try {
            if (Array.isArray(data)) {
                await putAll(storeName, data);
            } else if (data && typeof data === 'object') {
                // Handle paginated responses (Laravel default: { data: [...] })
                if (Array.isArray(data.data)) {
                    await putAll(storeName, data.data);
                } else {
                    await put(storeName, data);
                }
            }
            await setMeta(`${storeName}_lastSync`, Date.now());
            console.log(`[OfflineDB] Cached ${storeName} data`);
        } catch (error) {
            console.error(`[OfflineDB] Failed to cache ${storeName}:`, error);
        }
    }

    /**
     * Check if cached data is stale (older than maxAge in minutes)
     */
    async function isStale(storeName, maxAgeMinutes = 30) {
        const lastSync = await getMeta(`${storeName}_lastSync`);
        if (!lastSync) return true;
        const age = (Date.now() - lastSync) / (1000 * 60);
        return age > maxAgeMinutes;
    }

    /**
     * Get the count of records in a store
     */
    async function count(storeName) {
        const db = await openDB();
        return new Promise((resolve, reject) => {
            const tx = db.transaction(storeName, 'readonly');
            const store = tx.objectStore(storeName);
            const request = store.count();
            request.onsuccess = () => resolve(request.result);
            request.onerror = () => reject(request.error);
        });
    }

    // Public API
    return {
        openDB,
        put,
        putAll,
        get,
        getAll,
        getByIndex,
        remove,
        clear,
        setMeta,
        getMeta,
        cacheApiResponse,
        isStale,
        count,
        STORES,
    };
})();

// Make available globally
window.LGU1OfflineDB = LGU1OfflineDB;
