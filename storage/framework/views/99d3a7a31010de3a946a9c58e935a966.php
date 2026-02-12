<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Offline - LGU1 PFRS</title>
    <link rel="icon" type="image/png" href="/assets/images/logo.png">
    <style>
        :root {
            --bg-color: #f2f7f5;
            --headline: #00473e;
            --paragraph: #475d5b;
            --button: #faae2b;
            --button-text: #00473e;
        }
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body {
            font-family: 'Poppins', -apple-system, BlinkMacSystemFont, 'Segoe UI', sans-serif;
            background-color: var(--bg-color);
            color: var(--paragraph);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        .offline-container {
            text-align: center;
            padding: 2rem;
            max-width: 480px;
        }
        .offline-icon {
            width: 120px;
            height: 120px;
            margin: 0 auto 2rem;
            background: white;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            box-shadow: 0 10px 40px rgba(0,71,62,0.1);
        }
        .offline-icon svg {
            width: 60px;
            height: 60px;
            color: var(--headline);
            opacity: 0.6;
        }
        h1 {
            font-size: 1.75rem;
            color: var(--headline);
            margin-bottom: 0.75rem;
            font-weight: 700;
        }
        p {
            font-size: 1rem;
            line-height: 1.6;
            margin-bottom: 1.5rem;
            opacity: 0.8;
        }
        .retry-btn {
            display: inline-block;
            padding: 0.875rem 2rem;
            background: var(--button);
            color: var(--button-text);
            border: none;
            border-radius: 0.75rem;
            font-size: 1rem;
            font-weight: 600;
            cursor: pointer;
            text-decoration: none;
            transition: all 0.3s ease;
            box-shadow: 0 4px 15px rgba(250,174,43,0.3);
        }
        .retry-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(250,174,43,0.4);
        }
        .status-dot {
            display: inline-block;
            width: 10px;
            height: 10px;
            border-radius: 50%;
            background: #ef4444;
            margin-right: 8px;
            animation: pulse 2s infinite;
        }
        .status-dot.online {
            background: #22c55e;
            animation: none;
        }
        .status-text {
            font-size: 0.875rem;
            margin-top: 1.5rem;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        @keyframes pulse {
            0%, 100% { opacity: 1; }
            50% { opacity: 0.4; }
        }
        .logo {
            width: 48px;
            height: 48px;
            margin: 0 auto 1rem;
            border-radius: 50%;
            overflow: hidden;
        }
        .logo img { width: 100%; height: 100%; object-fit: cover; }
    </style>
</head>
<body>
    <div class="offline-container">
        <div class="logo">
            <img src="/assets/images/logo.png" alt="LGU1" onerror="this.style.display='none'">
        </div>
        
        <div class="offline-icon">
            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" 
                      d="M18.364 5.636a9 9 0 010 12.728M5.636 5.636a9 9 0 000 12.728M13.414 10.586a2 2 0 010 2.828M10.586 10.586a2 2 0 000 2.828"></path>
                <line x1="4" y1="4" x2="20" y2="20" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"></line>
            </svg>
        </div>

        <h1>You're Offline</h1>
        <p>It looks like your internet connection is down. Don't worry — any actions you've taken will be saved and synced automatically when you reconnect.</p>
        
        <button class="retry-btn" onclick="tryReconnect()">Try Again</button>
        
        <div class="status-text">
            <span class="status-dot" id="statusDot"></span>
            <span id="statusText">No internet connection</span>
        </div>
    </div>

    <script>
        function tryReconnect() {
            if (navigator.onLine) {
                window.location.href = document.referrer || '/';
            } else {
                const btn = document.querySelector('.retry-btn');
                btn.textContent = 'Still offline...';
                setTimeout(() => { btn.textContent = 'Try Again'; }, 2000);
            }
        }

        window.addEventListener('online', () => {
            const dot = document.getElementById('statusDot');
            const text = document.getElementById('statusText');
            dot.classList.add('online');
            text.textContent = 'Back online! Redirecting...';
            setTimeout(() => {
                window.location.href = document.referrer || '/';
            }, 1500);
        });
    </script>
</body>
</html>
<?php /**PATH C:\laragon\www\local-government-unit-1-ph.com\resources\views/offline.blade.php ENDPATH**/ ?>