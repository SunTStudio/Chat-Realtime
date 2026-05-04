<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Permintaan Teman</title>
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
            from { opacity: 0; transform: translateY(28px); }
            to { opacity: 1; transform: translateY(0); }
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

        .btn-action {
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
            text-decoration: none;
            display: inline-block;
        }

        .btn-action:hover { border-color: var(--accent); color: #fff; background: var(--accent-glow); }

        .btn-accept {
            background: rgba(61, 214, 140, 0.1);
            border: 1px solid rgba(61, 214, 140, 0.3);
            color: var(--online);
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

        .btn-accept:hover {
            background: rgba(61, 214, 140, 0.25);
            border-color: var(--online);
        }

        .user-list { padding: 8px 0; max-height: 60vh; overflow-y: auto; }
        .user-list::-webkit-scrollbar { width: 4px; }
        .user-list::-webkit-scrollbar-track { background: transparent; }
        .user-list::-webkit-scrollbar-thumb { background: var(--border); border-radius: 2px; }

        .section-label { padding: 10px 20px 6px; font-size: 0.7rem; font-weight: 600; letter-spacing: 0.1em; text-transform: uppercase; color: var(--muted); }

        .user-item { display: flex; align-items: center; gap: 14px; padding: 12px 20px; color: var(--text); transition: background 0.15s; position: relative; animation: fadeIn 0.4s ease both; }
        @keyframes fadeIn { from { opacity: 0; transform: translateX(-8px); } to { opacity: 1; transform: translateX(0); } }
        .user-item:hover { background: var(--surface-2); }
        .user-avatar { width: 46px; height: 46px; border-radius: 14px; display: flex; align-items: center; justify-content: center; font-family: 'Syne', sans-serif; font-weight: 700; font-size: 1.1rem; flex-shrink: 0; }
        .user-info { flex: 1; min-width: 0; }
        .user-name { font-weight: 600; font-size: 0.93rem; color: #fff; white-space: nowrap; overflow: hidden; text-overflow: ellipsis; }
        .user-sub { font-size: 0.78rem; color: var(--muted); margin-top: 2px; }

        .panel-footer { padding: 16px 20px; border-top: 1px solid var(--border); display: flex; align-items: center; gap: 10px; font-size: 0.78rem; color: var(--muted); }

        .av-0 { background: linear-gradient(135deg, #7b6ef6, #6358e8); color: #fff; }
        .av-1 { background: linear-gradient(135deg, #f97316, #ea580c); color: #fff; }
        .av-2 { background: linear-gradient(135deg, #3dd68c, #16a34a); color: #fff; }
        .av-3 { background: linear-gradient(135deg, #f43f5e, #e11d48); color: #fff; }
        .av-4 { background: linear-gradient(135deg, #38bdf8, #0284c7); color: #fff; }
        .av-5 { background: linear-gradient(135deg, #facc15, #ca8a04); color: #000; }
        .av-6 { background: linear-gradient(135deg, #e879f9, #a21caf); color: #fff; }
        .av-7 { background: linear-gradient(135deg, #fb923c, #9a3412); color: #fff; }

        .empty-state { padding: 30px 20px; text-align: center; color: var(--muted); font-size: 0.88rem; }
    </style>
</head>

<body>
    <div class="app-shell">
        <div class="panel">
            <div class="panel-header">
                <div class="header-left">
                    <h1>Permintaan Teman</h1>
                    <p>Kelola permintaan masuk</p>
                </div>
                <a href="{{ route('chat.index') }}" class="btn-action">Kembali</a>
            </div>

            <div class="user-list">
                @if (session('success'))
                    <div class="alert alert-success mx-3 my-2 px-3 py-2" style="font-size: 0.8rem; background: rgba(61, 214, 140, 0.1); border: 1px solid rgba(61, 214, 140, 0.3); color: var(--online); border-radius: 8px;">
                        {{ session('success') }}
                    </div>
                @endif

                <div class="section-label">Menunggu Persetujuan</div>
                
                @forelse ($requests as $i => $req)
                    @if($req->user)
                    <div class="user-item" style="animation-delay: {{ $i * 0.05 }}s">
                        <div class="user-avatar av-{{ $i % 8 }}">
                            {{ strtoupper(substr($req->user->name, 0, 1)) }}
                        </div>
                        <div class="user-info">
                            <div class="user-name">{{ $req->user->name }}</div>
                            <div class="user-sub">Mengirim permintaan pertemanan</div>
                        </div>
                        <form method="POST" action="{{ route('friends.requestStore') }}">
                            @csrf
                            <input type="hidden" name="friend_id" value="{{ $req->user_id }}">
                            <button type="submit" class="btn-accept">Terima</button>
                        </form>
                    </div>
                    @endif
                @empty
                    <div class="empty-state">
                        Tidak ada permintaan pertemanan masuk saat ini.
                    </div>
                @endforelse
            </div>
            
            <div class="panel-footer">
                Terima permintaan untuk mulai bertukar pesan
            </div>
        </div>
    </div>
</body>

</html>
