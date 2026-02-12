/**
 * LGU1 PFRS - Offline Write Queue
 * Queues POST/PUT/DELETE requests when offline and syncs them when connectivity returns.
 * Uses IndexedDB to persist the queue across page reloads.
 */

const LGU1OfflineQueue = (function () {
    const DB_NAME = 'lgu1_offline_queue';
    const DB_VERSION = 1;
    const STORE_NAME = 'pending_requests';

    let dbInstance = null;
    let isSyncing = false;

    /**
     * Open the queue database
     */
    function openDB() {
        if (dbInstance) return Promise.resolve(dbInstance);

        return new Promise((resolve, reject) => {
            const request = indexedDB.open(DB_NAME, DB_VERSION);

            request.onupgradeneeded = (event) => {
                const db = event.target.result;
                if (!db.objectStoreNames.contains(STORE_NAME)) {
                    const store = db.createObjectStore(STORE_NAME, { keyPath: 'id', autoIncrement: true });
                    store.createIndex('timestamp', 'timestamp', { unique: false });
                    store.createIndex('status', 'status', { unique: false });
                    store.createIndex('url', 'url', { unique: false });
                }
            };

            request.onsuccess = (event) => {
                dbInstance = event.target.result;
                dbInstance.onclose = () => { dbInstance = null; };
                resolve(dbInstance);
            };

            request.onerror = (event) => {
                console.error('[OfflineQueue] DB open failed:', event.target.error);
                reject(event.target.error);
            };
        });
    }

    /**
     * Add a request to the offline queue
     */
    async function enqueue(url, options = {}) {
        const db = await openDB();

        const queueItem = {
            url: url,
            method: options.method || 'POST',
            headers: options.headers || {},
            body: options.body || null,
            timestamp: Date.now(),
            status: 'pending',
            retries: 0,
            maxRetries: 3,
            description: options._description || 'Queued request',
        };

        return new Promise((resolve, reject) => {
            const tx = db.transaction(STORE_NAME, 'readwrite');
            const store = tx.objectStore(STORE_NAME);
            const request = store.add(queueItem);

            request.onsuccess = () => {
                console.log('[OfflineQueue] Request queued:', queueItem.description);
                updatePendingBadge();
                resolve(request.result);
            };
            request.onerror = () => reject(request.error);
        });
    }

    /**
     * Get all pending requests
     */
    async function getPending() {
        const db = await openDB();
        return new Promise((resolve, reject) => {
            const tx = db.transaction(STORE_NAME, 'readonly');
            const store = tx.objectStore(STORE_NAME);
            const index = store.index('status');
            const request = index.getAll('pending');
            request.onsuccess = () => resolve(request.result || []);
            request.onerror = () => reject(request.error);
        });
    }

    /**
     * Get count of pending requests
     */
    async function getPendingCount() {
        const pending = await getPending();
        return pending.length;
    }

    /**
     * Update a queue item's status
     */
    async function updateStatus(id, status, error = null) {
        const db = await openDB();
        return new Promise((resolve, reject) => {
            const tx = db.transaction(STORE_NAME, 'readwrite');
            const store = tx.objectStore(STORE_NAME);
            const getRequest = store.get(id);

            getRequest.onsuccess = () => {
                const item = getRequest.result;
                if (item) {
                    item.status = status;
                    item.lastAttempt = Date.now();
                    if (error) item.lastError = error;
                    if (status === 'failed') item.retries = (item.retries || 0) + 1;
                    store.put(item);
                }
                resolve(true);
            };
            getRequest.onerror = () => reject(getRequest.error);
        });
    }

    /**
     * Remove a completed item from the queue
     */
    async function dequeue(id) {
        const db = await openDB();
        return new Promise((resolve, reject) => {
            const tx = db.transaction(STORE_NAME, 'readwrite');
            const store = tx.objectStore(STORE_NAME);
            const request = store.delete(id);
            request.onsuccess = () => {
                updatePendingBadge();
                resolve(true);
            };
            request.onerror = () => reject(request.error);
        });
    }

    /**
     * Sync all pending requests to the server
     */
    async function sync() {
        if (isSyncing) return;
        if (!navigator.onLine) return;

        isSyncing = true;
        console.log('[OfflineQueue] Starting sync...');

        try {
            const pending = await getPending();

            if (pending.length === 0) {
                console.log('[OfflineQueue] No pending requests to sync');
                isSyncing = false;
                return;
            }

            console.log(`[OfflineQueue] Syncing ${pending.length} pending request(s)...`);
            let successCount = 0;
            let failCount = 0;

            // Process requests in order (FIFO)
            for (const item of pending) {
                if (item.retries >= item.maxRetries) {
                    await updateStatus(item.id, 'max_retries_exceeded');
                    failCount++;
                    continue;
                }

                try {
                    // Refresh CSRF token before sending
                    const freshCsrfToken = document.querySelector('meta[name="csrf-token"]');
                    const headers = { ...item.headers };
                    if (freshCsrfToken) {
                        headers['X-CSRF-TOKEN'] = freshCsrfToken.content;
                    }

                    const response = await fetch(item.url, {
                        method: item.method,
                        headers: headers,
                        body: item.body,
                    });

                    if (response.ok) {
                        await dequeue(item.id);
                        successCount++;
                        console.log(`[OfflineQueue] Synced: ${item.description}`);
                    } else if (response.status === 419) {
                        // CSRF token expired - try refreshing
                        console.warn('[OfflineQueue] CSRF expired, will retry on next sync');
                        await updateStatus(item.id, 'pending', 'CSRF token expired');
                        failCount++;
                    } else if (response.status === 409) {
                        // Conflict (e.g., booking slot already taken)
                        await updateStatus(item.id, 'conflict', `Server returned ${response.status}`);
                        failCount++;
                    } else {
                        await updateStatus(item.id, 'failed', `Server returned ${response.status}`);
                        failCount++;
                    }
                } catch (error) {
                    await updateStatus(item.id, 'failed', error.message);
                    failCount++;
                }
            }

            // Show sync result notification
            if (successCount > 0 && typeof Swal !== 'undefined') {
                Swal.fire({
                    title: 'Offline Data Synced',
                    html: `<p>${successCount} pending action(s) synced successfully.</p>` +
                          (failCount > 0 ? `<p class="text-red-500">${failCount} action(s) failed.</p>` : ''),
                    icon: 'success',
                    confirmButtonColor: '#faae2b',
                    timer: 3000,
                    showConfirmButton: false,
                });
            }

            updatePendingBadge();
        } catch (error) {
            console.error('[OfflineQueue] Sync error:', error);
        } finally {
            isSyncing = false;
        }
    }

    /**
     * Update the pending request badge in the UI
     */
    async function updatePendingBadge() {
        try {
            const count = await getPendingCount();
            const badge = document.getElementById('offline-queue-badge');
            if (badge) {
                badge.textContent = count;
                badge.style.display = count > 0 ? 'flex' : 'none';
            }

            // Dispatch custom event for other components to react
            window.dispatchEvent(new CustomEvent('offlineQueueUpdate', { detail: { count } }));
        } catch (e) {
            // Silently fail
        }
    }

    /**
     * Enhanced fetch wrapper that queues on failure
     * Use this instead of regular fetch() for write operations
     */
    async function offlineFetch(url, options = {}) {
        // If online, try the normal request first
        if (navigator.onLine) {
            try {
                const response = await fetch(url, options);
                return response;
            } catch (error) {
                // Network error while supposedly online - queue it
                console.warn('[OfflineQueue] Network error, queuing request:', url);
                const queueId = await enqueue(url, options);
                return new Response(JSON.stringify({
                    queued: true,
                    queueId: queueId,
                    message: 'Request has been queued and will be sent when connection is restored.',
                }), {
                    status: 202,
                    headers: { 'Content-Type': 'application/json' },
                });
            }
        }

        // If offline, queue directly
        const queueId = await enqueue(url, options);
        return new Response(JSON.stringify({
            queued: true,
            queueId: queueId,
            message: 'You are offline. This action has been saved and will be submitted when your connection is restored.',
        }), {
            status: 202,
            headers: { 'Content-Type': 'application/json' },
        });
    }

    /**
     * Clear all queue items (including failed)
     */
    async function clearAll() {
        const db = await openDB();
        return new Promise((resolve, reject) => {
            const tx = db.transaction(STORE_NAME, 'readwrite');
            const store = tx.objectStore(STORE_NAME);
            const request = store.clear();
            request.onsuccess = () => {
                updatePendingBadge();
                resolve(true);
            };
            request.onerror = () => reject(request.error);
        });
    }

    /**
     * Get all items (for debugging / UI display)
     */
    async function getAll() {
        const db = await openDB();
        return new Promise((resolve, reject) => {
            const tx = db.transaction(STORE_NAME, 'readonly');
            const store = tx.objectStore(STORE_NAME);
            const request = store.getAll();
            request.onsuccess = () => resolve(request.result || []);
            request.onerror = () => reject(request.error);
        });
    }

    // Auto-sync when coming back online
    window.addEventListener('online', () => {
        console.log('[OfflineQueue] Back online - starting sync...');
        setTimeout(sync, 1000); // Small delay to let connection stabilize
    });

    // Update badge on page load
    document.addEventListener('DOMContentLoaded', () => {
        updatePendingBadge();
    });

    // Public API
    return {
        enqueue,
        dequeue,
        getPending,
        getPendingCount,
        sync,
        offlineFetch,
        clearAll,
        getAll,
        updatePendingBadge,
    };
})();

// Make available globally
window.LGU1OfflineQueue = LGU1OfflineQueue;
