{{-- Offline/Online Status Indicator --}}
{{-- Include this component in all layouts to show connectivity status --}}

<!-- Offline Status Banner (hidden by default, shown when offline) -->
<div id="offline-status-banner" 
     class="fixed top-0 left-0 right-0 z-[9999] transform -translate-y-full transition-transform duration-300 ease-in-out"
     style="display: none;">
    <div class="bg-amber-500 text-amber-900 px-4 py-2.5 shadow-lg">
        <div class="flex items-center justify-center gap-2 text-sm font-semibold">
            <svg class="w-5 h-5 animate-pulse" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                      d="M18.364 5.636a9 9 0 010 12.728M5.636 5.636a9 9 0 000 12.728M13.414 10.586a2 2 0 010 2.828M10.586 10.586a2 2 0 000 2.828"></path>
                <line x1="4" y1="4" x2="20" y2="20" stroke="currentColor" stroke-width="2" stroke-linecap="round"></line>
            </svg>
            <span>You are offline. Some features may be limited.</span>
            <span id="offline-queue-badge" 
                  class="hidden ml-2 bg-amber-800 text-white text-xs rounded-full h-5 min-w-[20px] px-1.5 items-center justify-center font-bold"
                  style="display: none;">0</span>
            <span id="offline-queue-text" class="hidden ml-1 text-xs opacity-80" style="display: none;">pending action(s)</span>
        </div>
    </div>
</div>

<!-- Back Online Toast (hidden by default, briefly shown when reconnecting) -->
<div id="online-status-toast"
     class="fixed top-4 right-4 z-[9999] transform translate-x-full transition-transform duration-500 ease-in-out"
     style="display: none;">
    <div class="bg-green-600 text-white px-5 py-3 rounded-xl shadow-2xl flex items-center gap-3">
        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                  d="M8.111 16.404a5.5 5.5 0 017.778 0M12 20h.01M1.394 9.393a11.5 11.5 0 0121.212 0M5.636 13.636a8 8 0 0112.728 0"></path>
        </svg>
        <div>
            <p class="font-semibold text-sm">Back Online</p>
            <p class="text-xs opacity-90" id="sync-status-text">Syncing pending actions...</p>
        </div>
    </div>
</div>

<style>
    /* Offset page content when offline banner is visible */
    body.is-offline .lg\:ml-72 > header.sticky,
    body.is-offline header.sticky {
        top: 40px !important;
    }
    
    @media print {
        #offline-status-banner,
        #online-status-toast {
            display: none !important;
        }
    }
</style>

<script>
/**
 * LGU1 Offline Status Manager
 * Manages the UI indicators for online/offline state transitions.
 */
(function() {
    const banner = document.getElementById('offline-status-banner');
    const toast = document.getElementById('online-status-toast');
    const syncText = document.getElementById('sync-status-text');
    const queueBadge = document.getElementById('offline-queue-badge');
    const queueText = document.getElementById('offline-queue-text');
    let wasOffline = !navigator.onLine;
    let toastTimeout = null;

    function showOfflineBanner() {
        if (!banner) return;
        banner.style.display = 'block';
        document.body.classList.add('is-offline');
        // Trigger animation after display is set
        requestAnimationFrame(() => {
            banner.classList.remove('-translate-y-full');
            banner.classList.add('translate-y-0');
        });
        updateQueueBadge();
    }

    function hideOfflineBanner() {
        if (!banner) return;
        banner.classList.remove('translate-y-0');
        banner.classList.add('-translate-y-full');
        document.body.classList.remove('is-offline');
        setTimeout(() => {
            banner.style.display = 'none';
        }, 300);
    }

    function showOnlineToast() {
        if (!toast) return;
        toast.style.display = 'block';
        requestAnimationFrame(() => {
            toast.classList.remove('translate-x-full');
            toast.classList.add('translate-x-0');
        });

        // Update sync text based on queue
        if (typeof LGU1OfflineQueue !== 'undefined') {
            LGU1OfflineQueue.getPendingCount().then(count => {
                if (syncText) {
                    syncText.textContent = count > 0 
                        ? `Syncing ${count} pending action(s)...` 
                        : 'All data is up to date.';
                }
            });
        } else if (syncText) {
            syncText.textContent = 'Connection restored.';
        }

        // Auto-hide after 4 seconds
        if (toastTimeout) clearTimeout(toastTimeout);
        toastTimeout = setTimeout(() => {
            toast.classList.remove('translate-x-0');
            toast.classList.add('translate-x-full');
            setTimeout(() => { toast.style.display = 'none'; }, 500);
        }, 4000);
    }

    async function updateQueueBadge() {
        if (!queueBadge || typeof LGU1OfflineQueue === 'undefined') return;
        try {
            const count = await LGU1OfflineQueue.getPendingCount();
            queueBadge.textContent = count;
            queueBadge.style.display = count > 0 ? 'flex' : 'none';
            if (queueText) {
                queueText.textContent = `${count} pending action(s)`;
                queueText.style.display = count > 0 ? 'inline' : 'none';
            }
        } catch(e) { /* ignore */ }
    }

    function updateSystemStatusPill(isOnline) {
        var dot = document.getElementById('system-status-dot');
        var text = document.getElementById('system-status-text');
        if (!dot || !text) return;
        if (isOnline) {
            dot.className = 'w-2 h-2 bg-green-500 rounded-full animate-pulse';
            text.textContent = 'System Online';
        } else {
            dot.className = 'w-2 h-2 bg-red-500 rounded-full animate-pulse';
            text.textContent = 'Offline';
        }
    }

    // Handle going offline
    window.addEventListener('offline', () => {
        wasOffline = true;
        showOfflineBanner();
        updateSystemStatusPill(false);
        console.log('[Offline] Connection lost');
    });

    // Handle coming back online
    window.addEventListener('online', () => {
        hideOfflineBanner();
        updateSystemStatusPill(true);
        if (wasOffline) {
            showOnlineToast();
            wasOffline = false;
        }
        console.log('[Offline] Connection restored');
    });

    // Listen for queue updates
    window.addEventListener('offlineQueueUpdate', (e) => {
        updateQueueBadge();
    });

    // Check initial state
    if (!navigator.onLine) {
        showOfflineBanner();
    }
})();
</script>
