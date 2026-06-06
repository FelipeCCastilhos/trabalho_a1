<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title', 'Locadora Prendatta')</title>
    <style>
        /* Estilos centralizados no layout para dispensar Vite/Tailwind no trabalho. */
        :root {
            --bg: #f7f7f4;
            --panel: #ffffff;
            --line: #deded6;
            --text: #20221f;
            --muted: #666b63;
            --accent: #276749;
            --accent-strong: #1f5139;
            --danger: #b42318;
            --warning: #a15c07;
            --info: #2f5f98;
        }

        * { box-sizing: border-box; }
        body {
            margin: 0;
            background: var(--bg);
            color: var(--text);
            font-family: Arial, Helvetica, sans-serif;
            font-size: 15px;
            line-height: 1.45;
        }
        a { color: inherit; text-decoration: none; }
        .topbar {
            background: #20221f;
            color: #fff;
            border-bottom: 4px solid #c7a246;
        }
        .topbar-inner, .page {
            width: min(1180px, calc(100% - 32px));
            margin: 0 auto;
        }
        .topbar-inner {
            display: flex;
            align-items: center;
            justify-content: space-between;
            min-height: 68px;
            gap: 20px;
        }
        .brand {
            font-weight: 800;
            font-size: 19px;
            letter-spacing: 0;
        }
        .nav {
            display: flex;
            gap: 8px;
            flex-wrap: wrap;
        }
        .nav a, .nav button {
            padding: 9px 12px;
            border-radius: 6px;
            color: #eef1ed;
            font-weight: 700;
            font-size: 14px;
            background: transparent;
            border: 0;
            cursor: pointer;
        }
        .nav a.active, .nav a:hover, .nav button:hover { background: #384037; }
        .nav form { margin: 0; }
        .user-chip {
            color: #d8ddd6;
            font-size: 13px;
            font-weight: 700;
            padding: 9px 0;
        }
        .page { padding: 28px 0 48px; }
        .page-header {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            gap: 16px;
            margin-bottom: 18px;
        }
        h1, h2, h3 { margin: 0; line-height: 1.2; letter-spacing: 0; }
        h1 { font-size: 30px; }
        h2 { font-size: 20px; margin-bottom: 14px; }
        h3 { font-size: 16px; }
        .muted { color: var(--muted); }
        .grid { display: grid; gap: 16px; }
        .grid.stats {
            grid-template-columns: repeat(auto-fit, minmax(180px, 1fr));
            margin-bottom: 22px;
        }
        .grid.two { grid-template-columns: repeat(2, minmax(0, 1fr)); }
        .panel, .stat, .form-panel {
            background: var(--panel);
            border: 1px solid var(--line);
            border-radius: 8px;
            padding: 18px;
        }
        .stat-value {
            display: block;
            font-size: 30px;
            font-weight: 800;
            margin-top: 8px;
        }
        .toolbar {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 12px;
            margin-bottom: 14px;
            flex-wrap: wrap;
        }
        .search { display: flex; gap: 8px; flex-wrap: wrap; }
        input, select, textarea {
            width: 100%;
            border: 1px solid #c9c9bf;
            border-radius: 6px;
            padding: 10px 11px;
            background: #fff;
            color: var(--text);
        }
        textarea { min-height: 110px; }
        .search input, .search select { width: 260px; }
        label {
            display: block;
            font-weight: 700;
            margin-bottom: 6px;
        }
        .field { margin-bottom: 14px; }
        .field-row {
            display: grid;
            grid-template-columns: repeat(2, minmax(0, 1fr));
            gap: 14px;
        }
        .checkbox-row {
            display: flex;
            align-items: center;
            gap: 8px;
            font-weight: 700;
        }
        .checkbox-row input { width: auto; }
        .error-text {
            color: var(--danger);
            font-size: 13px;
            margin-top: 4px;
        }
        .alert {
            padding: 12px 14px;
            border-radius: 8px;
            margin-bottom: 16px;
            font-weight: 700;
        }
        .alert.success { background: #e6f4ed; color: #185c3a; border: 1px solid #b7dec9; }
        .alert.error { background: #fdecec; color: var(--danger); border: 1px solid #f5b5af; }
        .btn {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 6px;
            min-height: 39px;
            padding: 9px 13px;
            border: 1px solid transparent;
            border-radius: 6px;
            background: #e9e9e1;
            color: var(--text);
            font-weight: 800;
            cursor: pointer;
            white-space: nowrap;
        }
        .btn.primary { background: var(--accent); color: #fff; }
        .btn.primary:hover { background: var(--accent-strong); }
        .btn.danger { background: #fff; color: var(--danger); border-color: #efc2bd; }
        .btn.secondary { background: #fff; border-color: #c9c9bf; }
        .actions {
            display: flex;
            gap: 8px;
            align-items: center;
            flex-wrap: wrap;
        }
        .actions form { margin: 0; }
        table {
            width: 100%;
            border-collapse: collapse;
            background: var(--panel);
        }
        th, td {
            text-align: left;
            padding: 12px;
            border-bottom: 1px solid var(--line);
            vertical-align: top;
        }
        th {
            color: var(--muted);
            font-size: 12px;
            text-transform: uppercase;
        }
        .badge {
            display: inline-block;
            border-radius: 999px;
            padding: 4px 9px;
            font-size: 12px;
            font-weight: 800;
            background: #ecece5;
            color: #3f423d;
        }
        .badge.ok { background: #dff3e9; color: #185c3a; }
        .badge.warn { background: #fff0d6; color: var(--warning); }
        .badge.info { background: #e6eff8; color: var(--info); }
        .badge.danger { background: #fdecec; color: var(--danger); }
        .detail-list {
            display: grid;
            grid-template-columns: repeat(2, minmax(0, 1fr));
            gap: 12px;
        }
        .detail-item {
            border-bottom: 1px solid var(--line);
            padding-bottom: 10px;
        }
        .detail-item strong {
            display: block;
            color: var(--muted);
            font-size: 12px;
            text-transform: uppercase;
            margin-bottom: 3px;
        }
        .empty {
            padding: 24px;
            text-align: center;
            color: var(--muted);
        }
        .pagination { margin-top: 14px; }

        @media (max-width: 760px) {
            .topbar-inner, .page-header, .grid.two, .field-row, .detail-list { display: block; }
            .nav { margin-top: 12px; }
            .topbar-inner { padding: 14px 0; }
            .page-header .actions { margin-top: 12px; }
            .search input, .search select { width: 100%; }
            table { display: block; overflow-x: auto; white-space: nowrap; }
        }
    </style>
</head>
<body>
    {{-- Layout base reaproveitado por todas as telas para manter navegacao e visual padronizados. --}}
    <header class="topbar">
        <div class="topbar-inner">
            <a class="brand" href="{{ route('dashboard') }}">Locadora Prendatta</a>
            {{-- Menu adaptado por perfil: admin ve tudo; atendente ve apenas Clientes e Locacoes. --}}
            <nav class="nav" aria-label="Menu principal">
                @auth
                    <span class="user-chip">{{ auth()->user()->name }} | {{ \App\Models\User::PROFILE_LABELS[auth()->user()->profile] ?? auth()->user()->profile }}</span>
                    <a href="{{ route('clientes.index') }}" @class(['active' => request()->routeIs('clientes.*')])>Clientes</a>
                    @if (auth()->user()->isAdmin())
                        <a href="{{ route('veiculos.index') }}" @class(['active' => request()->routeIs('veiculos.*')])>Veiculos</a>
                    @endif
                    <a href="{{ route('locacoes.index') }}" @class(['active' => request()->routeIs('locacoes.*')])>Locacoes</a>
                    @if (auth()->user()->isAdmin())
                        <a href="{{ route('usuarios.index') }}" @class(['active' => request()->routeIs('usuarios.*')])>Usuarios</a>
                    @endif
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit">Sair</button>
                    </form>
                @else
                    <a href="{{ route('login') }}" @class(['active' => request()->routeIs('login')])>Login</a>
                    <a href="{{ route('register') }}" @class(['active' => request()->routeIs('register')])>Cadastro</a>
                @endauth
            </nav>
        </div>
    </header>

    <main class="page">
        {{-- Mensagens de feedback vindas dos controllers depois de salvar, editar ou excluir. --}}
        @if (session('success'))
            <div class="alert success">{{ session('success') }}</div>
        @endif

        @if (session('error'))
            <div class="alert error">{{ session('error') }}</div>
        @endif

        {{-- Cada view filha injeta aqui seu conteudo especifico. --}}
        @yield('content')
    </main>
</body>
</html>
