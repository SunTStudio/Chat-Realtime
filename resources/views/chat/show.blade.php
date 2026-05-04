<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Chat · {{ $user->name }}</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-sRIl4kxILFvY47J16cr9ZwB07vP4J8+LH7qKQnuqkuIAvNWLzeN8tE5YBujZqJLB" crossorigin="anonymous">
    <link
        href="https://fonts.googleapis.com/css2?family=Syne:wght@400;600;700;800&family=DM+Sans:wght@300;400;500&display=swap"
        rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        :root {
            --bg: #0c0d10;
            --surface: #13151a;
            --surface-2: #1c1f27;
            --surface-3: #22262f;
            --border: rgba(255, 255, 255, 0.07);
            --accent: #7b6ef6;
            --accent-dark: #5a50d8;
            --accent-glow: rgba(123, 110, 246, 0.25);
            --text: #e8eaf0;
            --muted: #5a5f72;
            --online: #3dd68c;
            --sent-bg: #7b6ef6;
            --recv-bg: #1c1f27;
        }

        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
        }

        body {
            background-color: var(--bg);
            font-family: 'DM Sans', sans-serif;
            color: var(--text);
            min-height: 100vh;
            overflow-x: hidden;
        }

        /* Subtle grid overlay */
        body::before {
            content: '';
            position: fixed;
            inset: 0;
            background-image:
                linear-gradient(rgba(255, 255, 255, 0.012) 1px, transparent 1px),
                linear-gradient(90deg, rgba(255, 255, 255, 0.012) 1px, transparent 1px);
            background-size: 40px 40px;
            pointer-events: none;
            z-index: 0;
        }

        /* Glow blob */
        body::after {
            content: '';
            position: fixed;
            top: -150px;
            left: 50%;
            transform: translateX(-50%);
            width: 700px;
            height: 400px;
            background: radial-gradient(ellipse, rgba(123, 110, 246, 0.1) 0%, transparent 70%);
            pointer-events: none;
            z-index: 0;
        }

        .app-shell {
            position: relative;
            z-index: 1;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 24px 16px;
        }

        .panel {
            width: 100%;
            max-width: 440px;
            height: calc(100vh - 48px);
            max-height: 800px;
            min-height: 500px;
            display: flex;
            flex-direction: column;
            background: var(--surface);
            border: 1px solid var(--border);
            border-radius: 24px;
            overflow: hidden;
            box-shadow: 0 0 0 1px var(--border), 0 32px 80px rgba(0, 0, 0, 0.5);
            animation: slideUp 0.5s cubic-bezier(0.22, 1, 0.36, 1) both;
        }

        @keyframes slideUp {
            from {
                opacity: 0;
                transform: translateY(28px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        /* ── HEADER ── */
        .chat-header {
            position: relative;
            z-index: 10;
            display: flex;
            align-items: center;
            gap: 14px;
            padding: 14px 20px;
            background: rgba(19, 21, 26, 0.85);
            backdrop-filter: blur(20px);
            -webkit-backdrop-filter: blur(20px);
            border-bottom: 1px solid var(--border);
            flex-shrink: 0;
        }

        .back-btn {
            display: flex;
            align-items: center;
            justify-content: center;
            width: 36px;
            height: 36px;
            border-radius: 10px;
            border: 1px solid var(--border);
            background: var(--surface-2);
            color: var(--muted);
            text-decoration: none;
            font-size: 1.1rem;
            transition: all 0.2s;
            flex-shrink: 0;
        }

        .back-btn:hover {
            background: var(--surface-3);
            color: var(--text);
            border-color: rgba(255, 255, 255, 0.15);
        }

        .header-avatar {
            width: 40px;
            height: 40px;
            border-radius: 12px;
            background: linear-gradient(135deg, #7b6ef6, #6358e8);
            display: flex;
            align-items: center;
            justify-content: center;
            font-family: 'Syne', sans-serif;
            font-weight: 700;
            font-size: 1rem;
            color: #fff;
            position: relative;
            flex-shrink: 0;
        }

        .header-avatar .dot {
            position: absolute;
            bottom: -2px;
            right: -2px;
            width: 11px;
            height: 11px;
            border-radius: 50%;
            background: var(--online);
            border: 2px solid var(--bg);
        }

        .header-info {
            flex: 1;
            min-width: 0;
        }

        .header-name {
            font-family: 'Syne', sans-serif;
            font-weight: 700;
            font-size: 0.95rem;
            color: #fff;
            letter-spacing: -0.01em;
        }

        .header-status {
            font-size: 0.75rem;
            color: var(--online);
            margin-top: 1px;
        }

        .notif-btn {
            background: var(--surface-2);
            border: 1px solid var(--border);
            color: var(--muted);
            font-size: 0.75rem;
            font-family: 'DM Sans', sans-serif;
            padding: 7px 13px;
            border-radius: 10px;
            cursor: pointer;
            transition: all 0.2s;
            display: flex;
            align-items: center;
            gap: 6px;
            flex-shrink: 0;
        }

        .notif-btn:hover {
            background: var(--surface-3);
            color: var(--text);
            border-color: rgba(123, 110, 246, 0.4);
        }

        .notif-btn::before {
            content: '🔔';
            font-size: 0.85rem;
        }

        /* ── CHAT BOX ── */
        #chat-box {
            flex: 1;
            overflow-y: auto;
            padding: 24px 20px;
            display: flex;
            flex-direction: column;
            gap: 6px;
            position: relative;
            z-index: 1;
        }

        #chat-box::-webkit-scrollbar {
            width: 4px;
        }

        #chat-box::-webkit-scrollbar-track {
            background: transparent;
        }

        #chat-box::-webkit-scrollbar-thumb {
            background: var(--border);
            border-radius: 2px;
        }

        /* Date divider */
        .date-divider {
            display: flex;
            align-items: center;
            gap: 12px;
            margin: 12px 0;
        }

        .date-divider::before,
        .date-divider::after {
            content: '';
            flex: 1;
            height: 1px;
            background: var(--border);
        }

        .date-divider span {
            font-size: 0.7rem;
            color: var(--muted);
            letter-spacing: 0.06em;
            text-transform: uppercase;
        }

        /* Message rows */
        .msg-row {
            display: flex;
            align-items: flex-end;
            gap: 8px;
            margin-bottom: 4px;
        }

        .msg-row.sent {
            justify-content: flex-end;
        }

        .msg-row.received {
            justify-content: flex-start;
        }

        .msg-avatar {
            width: 28px;
            height: 28px;
            border-radius: 8px;
            background: linear-gradient(135deg, #7b6ef6, #6358e8);
            display: flex;
            align-items: center;
            justify-content: center;
            font-family: 'Syne', sans-serif;
            font-weight: 700;
            font-size: 0.7rem;
            color: #fff;
            flex-shrink: 0;
        }

        .msg-content-wrap {
            display: flex;
            flex-direction: column;
            max-width: 75%;
        }

        .msg-row.sent .msg-content-wrap {
            align-items: flex-end;
        }

        .msg-row.received .msg-content-wrap {
            align-items: flex-start;
        }

        .msg-bubble {
            padding: 10px 14px;
            border-radius: 18px;
            font-size: 0.9rem;
            line-height: 1.5;
            position: relative;
            word-break: break-word;
        }

        .msg-row.sent .msg-bubble {
            background: var(--sent-bg);
            color: #fff;
            border-bottom-right-radius: 5px;
            box-shadow: 0 4px 20px var(--accent-glow);
        }

        .msg-row.received .msg-bubble {
            background: var(--recv-bg);
            color: var(--text);
            border-bottom-left-radius: 5px;
            border: 1px solid var(--border);
        }

        .msg-sender-name {
            font-size: 0.7rem;
            font-weight: 600;
            color: var(--accent);
            margin-bottom: 3px;
        }

        .msg-time {
            font-size: 0.65rem;
            margin-top: 4px;
            text-align: right;
        }

        .msg-row.sent .msg-time {
            color: rgba(255, 255, 255, 0.55);
        }

        .msg-row.received .msg-time {
            color: var(--muted);
        }

        /* ── INPUT AREA ── */
        .chat-footer {
            position: relative;
            z-index: 10;
            padding: 14px 16px;
            background: rgba(19, 21, 26, 0.9);
            backdrop-filter: blur(20px);
            -webkit-backdrop-filter: blur(20px);
            border-top: 1px solid var(--border);
            flex-shrink: 0;
        }

        .input-row {
            display: flex;
            align-items: center;
            gap: 10px;
            background: var(--surface-2);
            border: 1px solid var(--border);
            border-radius: 16px;
            padding: 6px 6px 6px 16px;
            transition: border-color 0.2s;
        }

        .input-row:focus-within {
            border-color: rgba(123, 110, 246, 0.5);
            box-shadow: 0 0 0 3px rgba(123, 110, 246, 0.08);
        }

        #message-input {
            flex: 1;
            background: none;
            border: none;
            outline: none;
            color: var(--text);
            font-family: 'DM Sans', sans-serif;
            font-size: 0.9rem;
            padding: 6px 0;
        }

        #message-input::placeholder {
            color: var(--muted);
        }

        .send-btn {
            width: 40px;
            height: 40px;
            border-radius: 12px;
            background: var(--accent);
            border: none;
            color: #fff;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            transition: all 0.2s;
            flex-shrink: 0;
        }

        .send-btn:hover {
            background: var(--accent-dark);
            box-shadow: 0 4px 16px var(--accent-glow);
        }

        .send-btn:active {
            transform: scale(0.94);
        }

        .send-btn svg {
            width: 18px;
            height: 18px;
            fill: none;
            stroke: currentColor;
            stroke-width: 2;
            stroke-linecap: round;
            stroke-linejoin: round;
        }

        .typing-hint {
            text-align: center;
            font-size: 0.7rem;
            color: var(--muted);
            margin-top: 8px;
        }

        /* Toast notification */
        .toast-container {
            position: fixed;
            top: 80px;
            right: 20px;
            z-index: 9999;
            display: flex;
            flex-direction: column;
            gap: 10px;
        }

        .toast-item {
            background: var(--surface-2);
            border: 1px solid rgba(123, 110, 246, 0.3);
            border-radius: 14px;
            padding: 14px 18px;
            box-shadow: 0 8px 32px rgba(0, 0, 0, 0.4), 0 0 0 1px rgba(123, 110, 246, 0.15);
            max-width: 320px;
            animation: toastIn 0.3s cubic-bezier(0.22, 1, 0.36, 1) both;
        }

        @keyframes toastIn {
            from {
                opacity: 0;
                transform: translateX(20px) scale(0.95);
            }

            to {
                opacity: 1;
                transform: translateX(0) scale(1);
            }
        }

        .toast-title {
            font-size: 0.8rem;
            font-weight: 600;
            color: var(--accent);
            margin-bottom: 3px;
        }

        .toast-body {
            font-size: 0.83rem;
            color: var(--text);
        }
    </style>
</head>

<body>
    <div class="app-shell">
        <div class="panel">
            <!-- Header -->
            <div class="chat-header">
                <a href="{{ route('chat.index') }}" class="back-btn">‹</a>
                <div class="header-avatar">
                    {{ strtoupper(substr($user->name, 0, 1)) }}
                    <span class="dot"></span>
                </div>
                <div class="header-info">
                    <div class="header-name">{{ $user->name }}</div>
                    <div class="header-status">Online sekarang</div>
                </div>
                <button id="trigger-notifikasi" class="notif-btn">Notif</button>
            </div>

            <!-- Messages -->
            <div id="chat-box">
                <div class="date-divider"><span>Hari ini</span></div>

                @foreach ($messages as $msg)
                    @php $isMine = $msg->user_id === auth()->id(); @endphp
                    <div class="msg-row {{ $isMine ? 'sent' : 'received' }}">
                        @if (!$isMine)
                            <div class="msg-avatar">{{ strtoupper(substr($msg->user->name, 0, 1)) }}</div>
                        @endif
                        <div class="msg-content-wrap">
                            @if (!$isMine)
                                <div class="msg-sender-name">{{ $msg->user->name }}</div>
                            @endif
                            <div class="msg-bubble">
                                {{ $msg->content }}
                                <div class="msg-time">
                                    {{ $msg->created_at->format('H:i') }}
                                    @if ($isMine)
                                        <i class="fas {{ $msg->read ? 'fa-check-double' : 'fa-check' }}"></i>
                                    @endif
                                </div>
                            </div>

                        </div>
                    </div>
                @endforeach
            </div>

            <!-- Input -->
            <div class="chat-footer">
                <form id="chat-form">
                    @csrf
                    <div class="input-row">
                        <input type="text" id="message-input" placeholder="Ketik pesan..." autocomplete="off">
                        <button type="submit" class="send-btn" title="Kirim">
                            <svg viewBox="0 0 24 24">
                                <line x1="22" y1="2" x2="11" y2="13" />
                                <polygon points="22 2 15 22 11 13 2 9 22 2" />
                            </svg>
                        </button>
                    </div>
                </form>
                <div class="typing-hint">Enter untuk mengirim</div>
            </div>
        </div>
    </div>

    <!-- Toast container -->
    <div class="toast-container" id="toast-container"></div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const box = document.getElementById('chat-box');
            const form = document.getElementById('chat-form');
            const input = document.getElementById('message-input');
            const myId = {{ auth()->id() }};
            const myName = "{{ auth()->user()->name }}";

            const ids = [myId, {{ $user->id }}].sort((a, b) => a - b);
            const channelName = `chat.${ids[0]}.${ids[1]}`;

            box.scrollTop = box.scrollHeight;

            function showToast(title, body) {
                const container = document.getElementById('toast-container');
                const el = document.createElement('div');
                el.className = 'toast-item';
                el.innerHTML = `<div class="toast-title">${title}</div><div class="toast-body">${body}</div>`;
                container.appendChild(el);
                setTimeout(() => el.remove(), 5000);
            }

            function renderMessage(isMine, content, senderName, senderInitial, timeStr) {
                return `
                <div class="msg-row ${isMine ? 'sent' : 'received'}">
                    ${!isMine ? `<div class="msg-avatar">${senderInitial}</div>` : ''}
                    <div class="msg-content-wrap">
                        ${!isMine ? `<div class="msg-sender-name">${senderName}</div>` : ''}
                        <div class="msg-bubble">
                            ${content}
                            <div class="msg-time">${timeStr} ${isMine ? '<i class="fas fa-check"></i>' : ''}</div>
                        </div>
                    </div>
                </div>`;
            }

            function nowTime() {
                return new Date().toLocaleTimeString('id-ID', {
                    hour: '2-digit',
                    minute: '2-digit'
                });
            }

            const initEcho = setInterval(() => {
                if (window.Echo) {
                    clearInterval(initEcho);

                    window.Echo.private(channelName)
                        .listen('MessageSent', (e) => {
                            const isMine = e.user_id === myId;
                            box.innerHTML += renderMessage(
                                isMine, e.content, e.user,
                                e.user.charAt(0).toUpperCase(), nowTime()
                            );
                            box.scrollTop = box.scrollHeight;

                            // Jika menerima pesan baru dari lawan bicara, langsung otomatis tandai dibaca ke server
                            if (!isMine) {
                                fetch(`/check-messages/{{ $user->id }}`, {
                                    method: 'GET',
                                    headers: {
                                        'Accept': 'application/json'
                                    }
                                });
                            }
                        });

                    // Mendengarkan event pesan telah dibaca untuk update UI jadi centang dua
                    window.Echo.private(channelName)
                        .listen('MessageRead', (e) => {
                            if (parseInt(e.user_id) === myId) {
                                document.querySelectorAll('.fa-check').forEach(icon => {
                                    icon.classList.replace('fa-check', 'fa-check-double');
                                });
                            }
                        });

                    window.Echo.private(`notifikasi.${myId}`)
                        .listen('NotifikasiSent', (e) => {
                            showToast(`Notifikasi dari ${e.pengirim}`, e.notifikasi);
                        });
                }
            }, 100);

            form.addEventListener('submit', async (ev) => {
                ev.preventDefault();
                const content = input.value.trim();
                if (!content) return;

                const res = await fetch(`/chat/{{ $user->id }}`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('[name=_token]').value,
                        'X-Socket-ID': window.Echo ? window.Echo.socketId() : '',
                    },
                    body: JSON.stringify({
                        content
                    }),
                });

                const msg = await res.json();
                box.innerHTML += renderMessage(true, msg.content, myName, myName.charAt(0)
                    .toUpperCase(), nowTime());
                box.scrollTop = box.scrollHeight;
                input.value = '';
            });
        });

        document.getElementById('trigger-notifikasi').addEventListener('click', () => {
            fetch(`/trigger-notifikasi/{{ $user->id }}`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('[name=_token]').value,
                },
                body: JSON.stringify({
                    user_id: {{ $user->id }},
                    notifikasi: `Notifikasi dari {{ auth()->user()->name }} pada ${new Date().toLocaleTimeString()}`
                }),
            });
        });

        // Pengecekan berkala (fallback jaga-jaga kalau event websocket tertunda)
        setInterval(async () => {
            await fetch(`/check-messages/{{ $user->id }}`, {
                method: 'GET',
                headers: {
                    'Accept': 'application/json',
                },
            });
        }, 5000);

        // Tandai pesan sudah dibaca otomatis ketika pertama kali halaman chat ini dibuka
        fetch(`/check-messages/{{ $user->id }}`, {
            method: 'GET',
            headers: {
                'Accept': 'application/json'
            }
        });
    </script>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-FKyoEForCGlyvwx9Hj09JcYn3nv7wiPVlz7YYwJrWVcXK/BmnVDxM+D2scQbITxI" crossorigin="anonymous">
    </script>
</body>

</html>
