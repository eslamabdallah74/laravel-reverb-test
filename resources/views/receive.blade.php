<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reverb Messages — Receive Events</title>

    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
            font-family: 'Plus Jakarta Sans', sans-serif;
        }

        body {
            background-color: #070913;
            background-image: 
                radial-gradient(circle at 85% 20%, rgba(6, 182, 212, 0.15) 0%, transparent 45%),
                radial-gradient(circle at 15% 80%, rgba(16, 185, 129, 0.12) 0%, transparent 45%);
            color: #f8fafc;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 1.5rem;
        }

        .card {
            background: rgba(15, 23, 42, 0.7);
            backdrop-filter: blur(20px);
            -webkit-backdrop-filter: blur(20px);
            border: 1px solid rgba(255, 255, 255, 0.1);
            border-radius: 1.5rem;
            padding: 2.5rem;
            width: 100%;
            max-width: 620px;
            box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.7), 0 0 40px 0 rgba(6, 182, 212, 0.1);
            display: flex;
            flex-direction: column;
            max-height: 85vh;
            position: relative;
            overflow: hidden;
        }

        .card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 3px;
            background: linear-gradient(90deg, #06b6d4, #10b981, #3b82f6);
        }

        .header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-bottom: 0.5rem;
        }

        .title-area {
            display: flex;
            align-items: center;
            gap: 0.75rem;
        }

        .badge {
            background: linear-gradient(135deg, #10b981, #059669);
            color: #ffffff;
            font-size: 0.7rem;
            font-weight: 700;
            padding: 0.3rem 0.7rem;
            border-radius: 9999px;
            text-transform: uppercase;
            letter-spacing: 0.08em;
            box-shadow: 0 0 12px rgba(16, 185, 129, 0.4);
        }

        .count-pill {
            background: rgba(16, 185, 129, 0.12);
            color: #34d399;
            border: 1px solid rgba(16, 185, 129, 0.25);
            border-radius: 9999px;
            padding: 0.35rem 0.85rem;
            font-size: 0.8rem;
            font-weight: 700;
        }

        h1 {
            font-size: 1.65rem;
            font-weight: 800;
            color: #ffffff;
            letter-spacing: -0.02em;
        }

        .toolbar {
            display: flex;
            align-items: center;
            gap: 0.85rem;
        }

        .connection-status {
            display: flex;
            align-items: center;
            gap: 0.45rem;
            font-size: 0.8rem;
            font-weight: 600;
            color: #94a3b8;
            background: rgba(15, 23, 42, 0.6);
            padding: 0.35rem 0.75rem;
            border-radius: 9999px;
            border: 1px solid rgba(255, 255, 255, 0.08);
        }

        .dot {
            width: 8px;
            height: 8px;
            border-radius: 50%;
            background-color: #f59e0b;
        }

        .dot.connected {
            background-color: #10b981;
            box-shadow: 0 0 10px #10b981;
            animation: pulseGlow 2s infinite;
        }

        @keyframes pulseGlow {
            0% { box-shadow: 0 0 0 0 rgba(16, 185, 129, 0.7); }
            70% { box-shadow: 0 0 0 6px rgba(16, 185, 129, 0); }
            100% { box-shadow: 0 0 0 0 rgba(16, 185, 129, 0); }
        }

        .clear-btn {
            background: rgba(239, 68, 68, 0.12);
            color: #fca5a5;
            border: 1px solid rgba(239, 68, 68, 0.25);
            border-radius: 0.6rem;
            padding: 0.4rem 0.85rem;
            font-size: 0.8rem;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.2s ease;
        }

        .clear-btn:hover {
            background: rgba(239, 68, 68, 0.25);
            color: #ffffff;
            transform: translateY(-1px);
        }

        p.subtitle {
            color: #94a3b8;
            font-size: 0.9rem;
            margin-bottom: 1.25rem;
        }

        .search-bar {
            margin-bottom: 1.25rem;
            position: relative;
        }

        .search-input {
            width: 100%;
            padding: 0.75rem 1.1rem;
            background: rgba(15, 23, 42, 0.8);
            border: 1px solid rgba(255, 255, 255, 0.12);
            border-radius: 0.8rem;
            color: #f8fafc;
            font-size: 0.925rem;
            outline: none;
            transition: all 0.25s ease;
        }

        .search-input:focus {
            border-color: #06b6d4;
            box-shadow: 0 0 0 3px rgba(6, 182, 212, 0.25);
            background: rgba(15, 23, 42, 0.95);
        }

        .messages-container {
            flex: 1;
            overflow-y: auto;
            display: flex;
            flex-direction: column;
            gap: 0.75rem;
            padding-right: 0.4rem;
            min-height: 220px;
        }

        .messages-container::-webkit-scrollbar {
            width: 6px;
        }

        .messages-container::-webkit-scrollbar-track {
            background: rgba(15, 23, 42, 0.5);
            border-radius: 4px;
        }

        .messages-container::-webkit-scrollbar-thumb {
            background: #334155;
            border-radius: 4px;
        }

        .message-item {
            background: rgba(15, 23, 42, 0.8);
            border: 1px solid rgba(255, 255, 255, 0.08);
            border-left: 4px solid #06b6d4;
            border-radius: 0.85rem;
            padding: 1rem 1.25rem;
            animation: slideIn 0.3s cubic-bezier(0.16, 1, 0.3, 1) forwards;
            display: flex;
            justify-content: space-between;
            align-items: center;
            transition: all 0.2s ease;
        }

        .message-item:hover {
            border-color: rgba(6, 182, 212, 0.3);
            background: rgba(15, 23, 42, 0.95);
            transform: translateX(2px);
        }

        .message-text {
            color: #f1f5f9;
            font-size: 0.975rem;
            font-weight: 500;
            word-break: break-word;
        }

        .message-time {
            font-size: 0.75rem;
            font-weight: 600;
            color: #64748b;
            margin-left: 1rem;
            white-space: nowrap;
            background: rgba(255, 255, 255, 0.05);
            padding: 0.2rem 0.5rem;
            border-radius: 0.4rem;
        }

        .empty-state {
            margin: auto;
            text-align: center;
            color: #64748b;
            font-size: 0.95rem;
            padding: 2.5rem 0;
        }

        .empty-state span {
            display: block;
            font-size: 2.5rem;
            margin-bottom: 0.75rem;
            filter: drop-shadow(0 0 10px rgba(6, 182, 212, 0.3));
        }

        @keyframes slideIn {
            from {
                opacity: 0;
                transform: translateY(12px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .nav-link {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 0.4rem;
            width: 100%;
            margin-top: 1.75rem;
            color: #38bdf8;
            text-decoration: none;
            font-size: 0.875rem;
            font-weight: 600;
            transition: color 0.2s ease;
        }

        .nav-link:hover {
            color: #7dd3fc;
            text-decoration: underline;
        }
    </style>
</head>
<body>
    <div class="card">
        <div class="header">
            <div class="title-area">
                <h1>Reverb Messages</h1>
                <span class="badge">Receiver</span>
                <span class="count-pill">Received: <span id="received-count">0</span></span>
            </div>
            <div class="toolbar">
                <div class="connection-status">
                    <span id="status-dot" class="dot"></span>
                    <span id="status-text">Connecting...</span>
                </div>
                <button id="clear-btn" class="clear-btn" onclick="clearLog()">🗑 Clear</button>
            </div>
        </div>
        <p class="subtitle">Real-time WebSocket event feed from Reverb channel <code>messages</code>.</p>

        <div class="search-bar">
            <input type="text" id="search-input" class="search-input" placeholder="🔍 Search or filter messages..." oninput="filterMessages()">
        </div>

        <div id="messages-list" class="messages-container">
            <div id="empty-state" class="empty-state">
                <span>📡</span>
                Waiting for incoming broadcasts...
            </div>
        </div>

        <a href="/send" target="_blank" class="nav-link">
            <span>Open Sender Window</span>
            <span>↗</span>
        </a>
    </div>

    <script>
        let receivedCount = 0;

        document.addEventListener('DOMContentLoaded', () => {
            const statusDot = document.getElementById('status-dot');
            const statusText = document.getElementById('status-text');

            if (window.Echo) {
                // Monitor Echo connection state
                window.Echo.connector.pusher.connection.bind('connected', () => {
                    statusDot.classList.add('connected');
                    statusText.textContent = 'Connected';
                });

                window.Echo.connector.pusher.connection.bind('disconnected', () => {
                    statusDot.classList.remove('connected');
                    statusText.textContent = 'Disconnected';
                });

                // Single listener for .MessageSent event
                window.Echo.channel('messages')
                    .listen('.MessageSent', (data) => {
                        appendMessage(data.message);
                    });
            }
        });

        function appendMessage(msgText) {
            const messagesList = document.getElementById('messages-list');
            const emptyState = document.getElementById('empty-state');

            if (emptyState) {
                emptyState.style.display = 'none';
            }

            receivedCount++;
            document.getElementById('received-count').textContent = receivedCount;

            const item = document.createElement('div');
            item.className = 'message-item';

            const textSpan = document.createElement('span');
            textSpan.className = 'message-text';
            textSpan.textContent = msgText;

            const timeSpan = document.createElement('span');
            timeSpan.className = 'message-time';
            const now = new Date();
            timeSpan.textContent = now.toLocaleTimeString([], { hour: '2-digit', minute: '2-digit', second: '2-digit' });

            item.appendChild(textSpan);
            item.appendChild(timeSpan);

            messagesList.appendChild(item);
            messagesList.scrollTop = messagesList.scrollHeight;

            filterMessages();
        }

        function filterMessages() {
            const query = document.getElementById('search-input').value.toLowerCase().trim();
            const items = document.querySelectorAll('.message-item');

            items.forEach(item => {
                const text = item.querySelector('.message-text').textContent.toLowerCase();
                if (!query || text.includes(query)) {
                    item.style.display = 'flex';
                } else {
                    item.style.display = 'none';
                }
            });
        }

        function clearLog() {
            const messagesList = document.getElementById('messages-list');
            document.getElementById('search-input').value = '';
            receivedCount = 0;
            document.getElementById('received-count').textContent = '0';

            messagesList.innerHTML = `
                <div id="empty-state" class="empty-state">
                    <span>📡</span>
                    Waiting for incoming broadcasts...
                </div>
            `;
        }
    </script>
</body>
</html>
