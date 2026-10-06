<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Reverb Test — Send Events</title>

    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">

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
                radial-gradient(circle at 15% 20%, rgba(99, 102, 241, 0.15) 0%, transparent 45%),
                radial-gradient(circle at 85% 80%, rgba(168, 85, 247, 0.12) 0%, transparent 45%);
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
            max-width: 520px;
            box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.7), 0 0 40px 0 rgba(99, 102, 241, 0.1);
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
            background: linear-gradient(90deg, #6366f1, #a855f7, #ec4899);
        }

        .header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-bottom: 0.75rem;
        }

        .header-title {
            display: flex;
            align-items: center;
            gap: 0.75rem;
        }

        .badge {
            background: linear-gradient(135deg, #ec4899, #be185d);
            color: #ffffff;
            font-size: 0.7rem;
            font-weight: 700;
            padding: 0.3rem 0.7rem;
            border-radius: 9999px;
            text-transform: uppercase;
            letter-spacing: 0.08em;
            box-shadow: 0 0 12px rgba(236, 72, 153, 0.4);
        }

        .count-pill {
            background: rgba(99, 102, 241, 0.12);
            color: #818cf8;
            border: 1px solid rgba(99, 102, 241, 0.25);
            border-radius: 9999px;
            padding: 0.35rem 0.85rem;
            font-size: 0.8rem;
            font-weight: 700;
            display: flex;
            align-items: center;
            gap: 0.4rem;
        }

        .count-pill span {
            color: #a5b4fc;
        }

        h1 {
            font-size: 1.65rem;
            font-weight: 800;
            color: #ffffff;
            letter-spacing: -0.02em;
        }

        p.subtitle {
            color: #94a3b8;
            font-size: 0.925rem;
            line-height: 1.5;
            margin-bottom: 2.25rem;
        }

        .input-group {
            display: flex;
            flex-direction: column;
            gap: 1.25rem;
        }

        .form-row {
            display: flex;
            gap: 1rem;
        }

        .field-box {
            display: flex;
            flex-direction: column;
            gap: 0.4rem;
        }

        label.field-label {
            font-size: 0.8rem;
            font-weight: 600;
            color: #cbd5e1;
            letter-spacing: 0.02em;
        }

        input[type="text"] {
            flex: 1;
            padding: 0.95rem 1.25rem;
            background: rgba(15, 23, 42, 0.8);
            border: 1px solid rgba(255, 255, 255, 0.12);
            border-radius: 0.85rem;
            color: #f8fafc;
            font-size: 0.975rem;
            outline: none;
            transition: all 0.25 ease;
        }

        input[type="number"] {
            width: 110px;
            padding: 0.95rem 1rem;
            background: rgba(15, 23, 42, 0.8);
            border: 1px solid rgba(255, 255, 255, 0.12);
            border-radius: 0.85rem;
            color: #f8fafc;
            font-size: 0.975rem;
            outline: none;
            text-align: center;
            font-weight: 600;
            transition: all 0.25s ease;
        }

        input[type="text"]:focus, input[type="number"]:focus {
            border-color: #8b5cf6;
            box-shadow: 0 0 0 3px rgba(139, 92, 246, 0.3);
            background: rgba(15, 23, 42, 0.95);
        }

        button {
            width: 100%;
            padding: 1rem 1.5rem;
            background: linear-gradient(135deg, #6366f1 0%, #8b5cf6 50%, #d946ef 100%);
            color: #ffffff;
            font-size: 1rem;
            font-weight: 700;
            border: none;
            border-radius: 0.85rem;
            cursor: pointer;
            transition: all 0.25s cubic-bezier(0.16, 1, 0.3, 1);
            box-shadow: 0 10px 25px -5px rgba(139, 92, 246, 0.5);
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 0.5rem;
        }

        button:hover {
            transform: translateY(-2px);
            box-shadow: 0 14px 30px -5px rgba(139, 92, 246, 0.65);
        }

        button:active {
            transform: translateY(0);
        }

        .status {
            margin-top: 1.25rem;
            font-size: 0.875rem;
            font-weight: 600;
            color: #34d399;
            text-align: center;
            opacity: 0;
            transition: opacity 0.3s ease, transform 0.3s ease;
            transform: translateY(4px);
            min-height: 1.25rem;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 0.4rem;
        }

        .status.show {
            opacity: 1;
            transform: translateY(0);
        }

        .nav-link {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 0.4rem;
            width: 100%;
            margin-top: 1.75rem;
            color: #a78bfa;
            text-decoration: none;
            font-size: 0.875rem;
            font-weight: 600;
            transition: color 0.2s ease;
        }

        .nav-link:hover {
            color: #c084fc;
            text-decoration: underline;
        }
    </style>
</head>
<body>
    <div class="card">
        <div class="header">
            <div class="header-title">
                <h1>Reverb Test</h1>
                <span class="badge">Sender</span>
            </div>
            <div class="count-pill">
                🚀 Sent: <span id="sent-count">0</span>
            </div>
        </div>
        <p class="subtitle">Broadcast real-time events over WebSockets. Set message text and batch count for parallel testing.</p>

        <form id="message-form" onsubmit="sendMessage(event)">
            <div class="input-group">
                <div class="form-row">
                    <div class="field-box" style="flex: 1;">
                        <label class="field-label">MESSAGE TEXT</label>
                        <input type="text" id="message-input" placeholder="Type a message..." autocomplete="off" autofocus required>
                    </div>
                    <div class="field-box">
                        <label class="field-label">COUNT</label>
                        <input type="number" id="count-input" value="1" min="1" max="100" required>
                    </div>
                </div>
                <button type="submit" id="send-btn">
                    <span>Send Message(s)</span>
                    <span>⚡</span>
                </button>
            </div>
        </form>

        <div id="status-msg" class="status"></div>

        <a href="/receive" target="_blank" class="nav-link">
            <span>Open Receiver Window</span>
            <span>↗</span>
        </a>
    </div>

    <script>
        let sentCount = 0;

        async function sendMessage(e) {
            e.preventDefault();
            const input = document.getElementById('message-input');
            const countInput = document.getElementById('count-input');
            const status = document.getElementById('status-msg');
            const btn = document.getElementById('send-btn');
            
            const text = input.value.trim();
            const count = parseInt(countInput.value, 10) || 1;

            if (!text || count < 1) return;

            btn.disabled = true;
            btn.style.opacity = '0.75';
            btn.innerHTML = `<span>Dispatching ${count} message(s)...</span> ⏳`;

            const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
            
            const promises = [];
            for (let i = 1; i <= count; i++) {
                const messagePayload = count > 1 ? `${i}-${text}` : text;
                
                const promise = fetch('/send', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': csrfToken
                    },
                    body: JSON.stringify({ message: messagePayload })
                }).then(res => res.ok);

                promises.push(promise);
            }

            try {
                const results = await Promise.all(promises);
                const successCount = results.filter(Boolean).length;

                sentCount += successCount;
                document.getElementById('sent-count').textContent = sentCount;

                if (successCount === count) {
                    input.value = '';
                    status.textContent = `⚡ ${count} message(s) broadcasted via Reverb!`;
                } else {
                    status.textContent = `⚠️ Sent ${successCount}/${count} messages successfully.`;
                }
                status.classList.add('show');
                setTimeout(() => status.classList.remove('show'), 4000);
            } catch (err) {
                console.error(err);
                status.textContent = 'Failed to dispatch messages.';
                status.classList.add('show');
            } finally {
                btn.disabled = false;
                btn.style.opacity = '1';
                btn.innerHTML = `<span>Send Message(s)</span> ⚡`;
                input.focus();
            }
        }
    </script>
</body>
</html>
