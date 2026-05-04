<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Chat</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-sRIl4kxILFvY47J16cr9ZwB07vP4J8+LH7qKQnuqkuIAvNWLzeN8tE5YBujZqJLB" crossorigin="anonymous">
    <link
        href="https://fonts.googleapis.com/css2?family=Syne:wght@400;600;700;800&family=DM+Sans:wght@300;400;500&display=swap"
        rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        :root {
            --bg: #0c0d10;
            --surface: #13151a;
            --surface-2: #1c1f27;
            --border: rgba(255, 255, 255, 0.07);
            --accent: #7b6ef6;
            --accent-glow: rgba(123, 110, 246, 0.35);
            --text: #e8eaf0;
            --muted: #5a5f72;
            --online: #3dd68c;
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

        /* Ambient background blobs */
        body::before {
            content: '';
            position: fixed;
            top: -200px;
            left: -200px;
            width: 600px;
            height: 600px;
            background: radial-gradient(circle, rgba(123, 110, 246, 0.12) 0%, transparent 70%);
            pointer-events: none;
            z-index: 0;
        }

        body::after {
            content: '';
            position: fixed;
            bottom: -200px;
            right: -100px;
            width: 500px;
            height: 500px;
            background: radial-gradient(circle, rgba(61, 214, 140, 0.07) 0%, transparent 70%);
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

        .panel-header {
            padding: 28px 28px 20px;
            border-bottom: 1px solid var(--border);
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            gap: 16px;
        }

        .header-left h1 {
            font-family: 'Syne', sans-serif;
            font-weight: 800;
            font-size: 1.5rem;
            letter-spacing: -0.02em;
            color: #fff;
            line-height: 1.2;
        }

        .header-left p {
            font-size: 0.82rem;
            color: var(--muted);
            margin-top: 4px;
        }

        .avatar-stack {
            display: flex;
        }

        .logout-btn {
            background: transparent;
            border: 1px solid rgba(255, 255, 255, 0.12);
            color: var(--muted);
            font-size: 0.78rem;
            font-family: 'DM Sans', sans-serif;
            font-weight: 500;
            padding: 7px 14px;
            border-radius: 10px;
            cursor: pointer;
            transition: all 0.2s;
            white-space: nowrap;
            flex-shrink: 0;
        }

        .logout-btn:hover {
            border-color: rgba(239, 68, 68, 0.5);
            color: #f87171;
            background: rgba(239, 68, 68, 0.08);
        }

        .search-bar {
            padding: 16px 20px;
            border-bottom: 1px solid var(--border);
        }

        .search-bar input {
            width: 100%;
            background: var(--surface-2);
            border: 1px solid var(--border);
            border-radius: 12px;
            padding: 10px 16px 10px 40px;
            color: var(--text);
            font-size: 0.88rem;
            font-family: 'DM Sans', sans-serif;
            outline: none;
            transition: border-color 0.2s;
            background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='16' height='16' fill='none' viewBox='0 0 24 24'%3E%3Ccircle cx='11' cy='11' r='8' stroke='%235a5f72' stroke-width='2'/%3E%3Cpath d='m21 21-4.35-4.35' stroke='%235a5f72' stroke-width='2' stroke-linecap='round'/%3E%3C/svg%3E");
            background-repeat: no-repeat;
            background-position: 14px center;
        }

        .search-bar input::placeholder {
            color: var(--muted);
        }

        .search-bar input:focus {
            border-color: rgba(123, 110, 246, 0.5);
        }

        .user-list {
            padding: 8px 0;
            max-height: 60vh;
            overflow-y: auto;
        }

        .user-list::-webkit-scrollbar {
            width: 4px;
        }

        .user-list::-webkit-scrollbar-track {
            background: transparent;
        }

        .user-list::-webkit-scrollbar-thumb {
            background: var(--border);
            border-radius: 2px;
        }

        .section-label {
            padding: 10px 20px 6px;
            font-size: 0.7rem;
            font-weight: 600;
            letter-spacing: 0.1em;
            text-transform: uppercase;
            color: var(--muted);
        }

        .user-item {
            display: flex;
            align-items: center;
            gap: 14px;
            padding: 12px 20px;
            text-decoration: none;
            color: var(--text);
            transition: background 0.15s;
            position: relative;
            animation: fadeIn 0.4s ease both;
        }

        .user-item:nth-child(1) {
            animation-delay: 0.05s;
        }

        .user-item:nth-child(2) {
            animation-delay: 0.1s;
        }

        .user-item:nth-child(3) {
            animation-delay: 0.15s;
        }

        .user-item:nth-child(4) {
            animation-delay: 0.2s;
        }

        .user-item:nth-child(5) {
            animation-delay: 0.25s;
        }

        @keyframes fadeIn {
            from {
                opacity: 0;
                transform: translateX(-8px);
            }

            to {
                opacity: 1;
                transform: translateX(0);
            }
        }

        .user-item:hover {
            background: var(--surface-2);
        }

        .user-item:hover .user-arrow {
            opacity: 1;
            transform: translateX(0);
        }

        .user-avatar {
            width: 46px;
            height: 46px;
            border-radius: 14px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-family: 'Syne', sans-serif;
            font-weight: 700;
            font-size: 1.1rem;
            flex-shrink: 0;
            position: relative;
        }

        .user-avatar-colors {
            --c1: #7b6ef6;
            --c2: #6358e8;
        }

        .user-status-dot {
            position: absolute;
            bottom: -2px;
            right: -2px;
            width: 12px;
            height: 12px;
            border-radius: 50%;
            background: var(--online);
            border: 2px solid var(--surface);
        }

        .user-info {
            flex: 1;
            min-width: 0;
        }

        .user-name {
            font-weight: 600;
            font-size: 0.93rem;
            color: #fff;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }

        .user-sub {
            font-size: 0.78rem;
            color: var(--muted);
            margin-top: 2px;
        }

        .user-arrow {
            color: var(--muted);
            opacity: 0;
            transform: translateX(-4px);
            transition: all 0.2s;
            font-size: 0.9rem;
        }

        .panel-footer {
            padding: 16px 20px;
            border-top: 1px solid var(--border);
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .status-badge {
            width: 8px;
            height: 8px;
            border-radius: 50%;
            background: var(--online);
            box-shadow: 0 0 8px var(--online);
        }

        .footer-text {
            font-size: 0.78rem;
            color: var(--muted);
        }

        /* Avatar color variants */
        .av-0 {
            background: linear-gradient(135deg, #7b6ef6, #6358e8);
            color: #fff;
        }

        .av-1 {
            background: linear-gradient(135deg, #f97316, #ea580c);
            color: #fff;
        }

        .av-2 {
            background: linear-gradient(135deg, #3dd68c, #16a34a);
            color: #fff;
        }

        .av-3 {
            background: linear-gradient(135deg, #f43f5e, #e11d48);
            color: #fff;
        }

        .av-4 {
            background: linear-gradient(135deg, #38bdf8, #0284c7);
            color: #fff;
        }

        .av-5 {
            background: linear-gradient(135deg, #facc15, #ca8a04);
            color: #000;
        }

        .av-6 {
            background: linear-gradient(135deg, #e879f9, #a21caf);
            color: #fff;
        }

        .av-7 {
            background: linear-gradient(135deg, #fb923c, #9a3412);
            color: #fff;
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
            <div class="panel-header">
                <div class="header-left">
                    <h1>Messages</h1>
                    <p>{{ count($users) }} kontak tersedia</p>
                </div>
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="logout-btn">Keluar</button>
                </form>
            </div>

            <div class="search-bar">
                <input type="text" placeholder="Cari kontak...">
            </div>

            <div class="user-list">
                <div class="section-label">Semua Kontak</div>
                @foreach ($users as $i => $user)
                    <a href="{{ route('chat.show', $user) }}" class="user-item" id="user-item-{{ $user->id }}">
                        <div class="user-avatar av-{{ $i % 8 }}">
                            {{ strtoupper(substr($user->name, 0, 1)) }}
                            <span class="user-status-dot"></span>
                        </div>
                        <div class="user-info">
                            <div class="user-name">{{ $user->name }}</div>
                            <div class="user-sub">Ketuk untuk mulai chat</div>
                        </div>
                        {{-- menampilkan unread count --}}
                        @if (isset($unreadCount[$user->id]))
                            <span id="unread-count-{{ $user->id }}"
                                class="unread-count btn btn-warning btn-sm"><strong>{{ $unreadCount[$user->id] }}</strong></span>
                        @endif
                        <span class="user-arrow">›</span>
                    </a>
                @endforeach
            </div>

            <div class="panel-footer">
                <div class="status-badge"></div>
                <span class="footer-text">Realtime · Terenkripsi End-to-End</span>
            </div>
        </div>
    </div>

    <!-- Toast container -->
    <div class="toast-container" id="toast-container"></div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const myId = {{ auth()->id() }};
            

            function showToast(title, body) {
                const container = document.getElementById('toast-container');
                const el = document.createElement('div');
                el.className = 'toast-item';
                el.innerHTML = `<div class="toast-title">${title}</div><div class="toast-body">${body}</div>`;
                container.appendChild(el);
                setTimeout(() => el.remove(), 5000);
            }

            const initEcho = setInterval(() => {
                if (window.Echo) {
                    clearInterval(initEcho);

                    @foreach ($users as $user)
                        (function() {
                            const otherId = {{ $user->id }};
                            const ids = [myId, otherId].sort((a, b) => a - b);
                            const channelName = `chat.${ids[0]}.${ids[1]}`;

                            window.Echo.private(channelName)
                                .listen('MessageSent', (e) => {
                                    if (e.user_id === otherId) {
                                        const unreadBadge = document.getElementById(
                                            `unread-count-${otherId}`);
                                        if (unreadBadge) {
                                            const countEl = unreadBadge.querySelector('strong');
                                            countEl.innerText = parseInt(countEl.innerText) + 1;
                                        } else {
                                            const userItem = document.getElementById(
                                                `user-item-${otherId}`);
                                            if (userItem) {
                                                const badgeHtml =
                                                    `<span id="unread-count-${otherId}" class="unread-count btn btn-warning btn-sm"><strong>1</strong></span>`;
                                                userItem.querySelector('.user-arrow')
                                                    .insertAdjacentHTML('beforebegin', badgeHtml);
                                            }
                                        }
                                    }
                                });
                        })();
                    @endforeach
                }
            }, 100);

            // tampilkan notifikasi jika ada pesan baru
            const initEcho2 = setInterval(() => {
                if (window.Echo) {
                    clearInterval(initEcho2);

                    window.Echo.private(`notifikasi.${myId}`)
                        .listen('NotifikasiSent', (e) => {
                            showToast(`Notifikasi dari ${e.pengirim}`, e.notifikasi);
                        });
                }
            }, 100);
        });
    </script>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-FKyoEForCGlyvwx9Hj09JcYn3nv7wiPVlz7YYwJrWVcXK/BmnVDxM+D2scQbITxI" crossorigin="anonymous">
    </script>
</body>

</html>
