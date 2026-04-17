<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="LaundryLaundryan - Sistem Manajemen Laundry Professional">
    <title>@yield('title', 'Dashboard') - LaundryLaundryan</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <style>
        :root {
            --primary: #16a34a;
            --primary-dark: #15803d;
            --primary-light: #4ade80;
            --secondary: #eab308;
            --success: #16a34a;
            --warning: #facc15;
            --danger: #ef4444;
            --info: #3b82f6;
            --white: #ffffff;
            --text: #1f2937;
            --text-muted: #6b7280;
            --border: #e5e7eb;
            --card: #ffffff;
            --sidebar-w: 260px;
        }
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body {
            font-family: 'Inter', sans-serif;
            background: #f8fafc;
            color: var(--text);
            display: flex;
            min-height: 100vh;
        }
        /* ---- SIDEBAR ---- */
        .sidebar {
            width: var(--sidebar-w);
            background: var(--primary);
            position: fixed;
            top: 0; left: 0; bottom: 0;
            overflow-y: auto;
            border-right: 1px solid var(--border);
            display: flex;
            flex-direction: column;
            z-index: 100;
            transition: transform .3s;
        }
        .sidebar-logo {
            padding: 24px 20px;
            display: flex;
            align-items: center;
            gap: 12px;
            border-bottom: 1px solid var(--border);
        }
        .sidebar-logo .logo-icon {
            width: 42px; height: 42px;
            background: var(--secondary);
            border-radius: 12px;
            display: flex; align-items: center; justify-content: center;
            font-size: 20px;
            color: var(--primary);
        }
        .sidebar-logo .logo-text { font-size: 18px; font-weight: 700; color: #fff; }
        .sidebar-logo .logo-sub  { font-size: 11px; color: rgba(255,255,255,.7); }
        .sidebar-nav { padding: 16px 12px; flex: 1; }
        .nav-section { margin-bottom: 8px; }
        .nav-label {
            font-size: 10px; font-weight: 600; color: rgba(255,255,255,.6);
            text-transform: uppercase; letter-spacing: 1px;
            padding: 8px 10px 4px;
        }
        .nav-item {
            display: flex; align-items: center; gap: 12px;
            padding: 10px 12px; border-radius: 10px;
            color: #fff; text-decoration: none;
            font-size: 14px; font-weight: 500;
            transition: all .2s;
            margin-bottom: 2px;
        }
        .nav-item:hover {
            background: rgba(255,255,255,.12);
        }
        .nav-item.active {
            background: var(--secondary);
            color: var(--primary-dark);
        }
        .nav-item i { width: 20px; text-align: center; font-size: 15px; }
        .sidebar-footer {
            padding: 16px;
            border-top: 1px solid var(--border);
        }
        .user-info {
            display: flex; align-items: center; gap: 10px;
            padding: 10px 12px; border-radius: 10px;
            background: rgba(255,255,255,.05);
            margin-bottom: 8px;
        }
        .user-avatar {
            width: 36px; height: 36px;
            background: var(--secondary);
            color: var(--primary);
            border-radius: 50%; display: flex; align-items: center; justify-content: center;
            font-weight: 700; font-size: 14px;
        }
        .user-name  { font-size: 13px; font-weight: 600; color: #fff; }
        .user-email { font-size: 11px; color: rgba(255,255,255,.7); }
        .btn-logout {
            width: 100%;
            display: flex; align-items: center; justify-content: center; gap: 8px;
            padding: 9px; border-radius: 10px;
            background: rgba(239,68,68,.1); color: #ef4444;
            border: 1px solid rgba(239,68,68,.2);
            cursor: pointer; font-size: 13px; font-weight: 500;
            transition: all .2s; text-decoration: none;
        }
        .btn-logout:hover { background: rgba(239,68,68,.2); }
        /* ---- MAIN ---- */
        .main-wrap {
            margin-left: var(--sidebar-w);
            flex: 1;
            display: flex;
            flex-direction: column;
            min-height: 100vh;
        }
        .topbar {
            height: 64px;
            background: #fff;
            border-bottom: 1px solid var(--border);
            display: flex; align-items: center; justify-content: space-between;
            padding: 0 24px;
            position: sticky; top: 0; z-index: 50;
        }
        .topbar-title { font-size: 18px; font-weight: 700; }
        .topbar-breadcrumb { font-size: 12px; color: var(--text-muted); margin-top: 2px; }
        .page-content { padding: 24px; flex: 1; }
        /* ---- CARDS ---- */
        .card {
            background: var(--card);
            border: 1px solid var(--border);
            border-radius: 16px;
            padding: 24px;
            margin-bottom: 24px;
        }
        .card-header { display: flex; align-items: center; justify-content: space-between; margin-bottom: 20px; }
        .card-title { font-size: 16px; font-weight: 700; }
        /* ---- ALERTS ---- */
        .alert {
            padding: 12px 16px; border-radius: 10px;
            margin-bottom: 16px; font-size: 14px;
            display: flex; align-items: center; gap: 10px;
        }
        .alert-success { background: rgba(16,185,129,.15); border: 1px solid rgba(16,185,129,.3); color: #6ee7b7; }
        .alert-danger  { background: rgba(239,68,68,.15);  border: 1px solid rgba(239,68,68,.3);  color: #fca5a5; }
        .alert-warning { background: rgba(245,158,11,.15); border: 1px solid rgba(245,158,11,.3); color: #fcd34d; }
        /* ---- TABLE ---- */
        .table-wrap { overflow-x: auto; border-radius: 12px; }
        table { width: 100%; border-collapse: collapse; }
        thead th {
            padding: 12px 16px; text-align: left;
            font-size: 11px; font-weight: 600;
            text-transform: uppercase; letter-spacing: .8px;
            color: var(--text-muted);
            background: rgba(255,255,255,.03);
            border-bottom: 1px solid var(--border);
        }
        tbody td {
            padding: 13px 16px; font-size: 14px;
            border-bottom: 1px solid rgba(255,255,255,.04);
            vertical-align: middle;
        }
        tbody tr:hover { background: rgba(255,255,255,.025); }
        tbody tr:last-child td { border-bottom: none; }
        /* ---- BADGES ---- */
        .badge {
            display: inline-flex; align-items: center;
            padding: 4px 10px; border-radius: 20px;
            font-size: 11px; font-weight: 600;
        }
        .badge-success  { background: #dcfce7; color: #16a34a; }
        .badge-warning  { background: #fef9c3; color: #a16207; }
        .badge-info     { background: #fef9c3; color: #a16207; }
        .badge-primary  { background: #dcfce7; color: #16a34a; }
        .badge-danger   { background: #fee2e2; color: #dc2626; }
        .badge-secondary{ background: #f1f5f9; color: #475569; }
        /* ---- BUTTONS ---- */
        .btn {
            display: inline-flex; align-items: center; gap: 6px;
            padding: 8px 16px; border-radius: 8px;
            font-size: 13px; font-weight: 600;
            text-decoration: none; border: none; cursor: pointer;
            transition: all .2s;
        }
        .btn-primary {
            background: var(--primary);
            color: #fff;
            box-shadow: 0 4px 12px rgba(22,163,74,.3);
        }
        .btn-primary:hover { transform: translateY(-1px); box-shadow: 0 6px 16px rgba(22,163,74,.4); }
        .btn-secondary { background: #f1f5f9; color: var(--text); border: 1px solid var(--border); }
        .btn-secondary:hover { background: #e2e8f0; }
        .btn-success { background: var(--primary); color: #fff; }
        .btn-success:hover { background: var(--primary-dark); }
        .btn-danger  { background: #dc2626; color: #fff; }
        .btn-danger:hover { background: #b91c1c; }
        .btn-warning { background: var(--secondary); color: var(--primary-dark); }
        .btn-warning:hover { background: #eab308; }
        .btn-info    { background: var(--secondary); color: var(--primary-dark); }
        .btn-info:hover { background: #eab308; }
        .btn-sm { padding: 5px 10px; font-size: 12px; border-radius: 6px; }
        /* ---- FORMS ---- */
        .form-group { margin-bottom: 20px; }
        .form-label { display: block; font-size: 13px; font-weight: 600; margin-bottom: 8px; color: var(--text); }
        .form-control {
            width: 100%; padding: 10px 14px;
            background: #fff;
            border: 1px solid var(--border);
            border-radius: 10px; color: var(--text);
            font-size: 14px; font-family: inherit;
            transition: border-color .2s, box-shadow .2s;
        }
        .form-control:focus {
            outline: none;
            border-color: var(--primary);
            box-shadow: 0 0 0 3px rgba(22,163,74,.2);
        }
        .form-control::placeholder { color: var(--text-muted); }
        select.form-control option { background: var(--dark2); }
        textarea.form-control { resize: vertical; min-height: 80px; }
        .invalid-feedback { color: #f87171; font-size: 12px; margin-top: 5px; }
        .is-invalid { border-color: var(--danger) !important; }
        /* ---- GRID ---- */
        .grid { display: grid; gap: 20px; }
        .grid-2 { grid-template-columns: repeat(2, 1fr); }
        .grid-3 { grid-template-columns: repeat(3, 1fr); }
        .grid-4 { grid-template-columns: repeat(4, 1fr); }
        /* ---- PAGINATION ---- */
        .pagination { display: flex; gap: 6px; justify-content: center; margin-top: 20px; flex-wrap: wrap; }
        .pagination a, .pagination span {
            padding: 7px 12px; border-radius: 8px; font-size: 13px;
            text-decoration: none; border: 1px solid var(--border);
            background: var(--card); color: var(--text-muted);
            transition: all .2s;
        }
        .pagination a:hover { background: rgba(79,70,229,.15); color: var(--primary-light); border-color: var(--primary); }
        .pagination .active span { background: var(--primary); color: #fff; border-color: var(--primary); }
        /* ---- SEARCH BAR ---- */
        .search-wrap { display: flex; gap: 10px; align-items: center; }
        .search-input-wrap { position: relative; }
        .search-input-wrap i {
            position: absolute; left: 12px; top: 50%;
            transform: translateY(-50%); color: var(--text-muted); font-size: 14px;
        }
        .search-input-wrap .form-control { padding-left: 38px; width: 260px; }
        /* ---- DETAIL ---- */
        .detail-row { display: flex; align-items: flex-start; gap: 16px; margin-bottom: 12px; font-size: 14px; }
        .detail-label { color: var(--text-muted); min-width: 160px; font-weight: 500; }
        .detail-value { color: var(--text); flex: 1; }
        hr.divider { border: none; border-top: 1px solid var(--border); margin: 20px 0; }
        /* ---- MOBILE ---- */
        @media (max-width: 768px) {
            .sidebar { transform: translateX(-100%); }
            .main-wrap { margin-left: 0; }
            .grid-2, .grid-3, .grid-4 { grid-template-columns: 1fr; }
        }
    </style>
    @stack('styles')
</head>
<body>
    {{-- SIDEBAR --}}
    <aside class="sidebar">
        <div class="sidebar-logo">
            <div class="logo-icon"><i class="bi bi-droplet-half"></i></div>
            <div>
                <div class="logo-text">LaundryPPKD</div>
                <div class="logo-sub">Laundry</div>
            </div>
        </div>
        <nav class="sidebar-nav">
            @if(in_array(auth()->user()->id_level, [3])) {{-- Pimpinan --}}
            <div class="nav-section">
                <div class="nav-label">Menu Utama</div>
                <a href="{{ route('dashboard') }}" class="nav-item {{ request()->routeIs('dashboard') ? 'active' : '' }}">
                    <i class="fas fa-chart-pie"></i> Dashboard
                </a>
            </div>
            @endif

            @if(in_array(auth()->user()->id_level, [1])) {{-- Admin --}}
            <div class="nav-section">
                <div class="nav-label">Master Data</div>
                <a href="{{ route('users.index') }}" class="nav-item {{ request()->routeIs('users.*') ? 'active' : '' }}">
                    <i class="fas fa-user-shield"></i> Manajemen User
                </a>
                <a href="{{ route('services.index') }}" class="nav-item {{ request()->routeIs('services.*') ? 'active' : '' }}">
                    <i class="fas fa-concierge-bell"></i> Jenis Layanan
                </a>
                <a href="{{ route('vouchers.index') }}" class="nav-item {{ request()->routeIs('vouchers.*') ? 'active' : '' }}">
                    <i class="bi bi-ticket-perforated"></i> Manajemen Voucher
                </a>
            </div>
            @endif

            @if(in_array(auth()->user()->id_level, [1, 2])) {{-- Admin & Operator --}}
            <div class="nav-section">
                <div class="nav-label">Kemitraan</div>
                <a href="{{ route('customers.index') }}" class="nav-item {{ request()->routeIs('customers.*') ? 'active' : '' }}">
                    <i class="fas fa-users"></i> Data Pelanggan
                </a>
            </div>
            @endif

            @if(in_array(auth()->user()->id_level, [2])) {{-- Operator --}}
            <div class="nav-section">
                <div class="nav-label">Transaksi</div>
                <a href="{{ route('orders.index') }}" class="nav-item {{ request()->routeIs('orders.*') ? 'active' : '' }}">
                    <i class="fas fa-clipboard-list"></i> Order Laundry
                </a>
            </div>
            @endif

            @if(in_array(auth()->user()->id_level, [3])) {{-- Pimpinan --}}
            <div class="nav-section">
                <div class="nav-label">Laporan</div>
                <a href="{{ route('report.index') }}" class="nav-item {{ request()->routeIs('report.*') ? 'active' : '' }}">
                    <i class="fas fa-print"></i> Laporan Penjualan
                </a>
            </div>
            @endif
        </nav>
        <div class="sidebar-footer">
            <div class="user-info">
                <div class="user-avatar">{{ strtoupper(substr(auth()->user()->name, 0, 1)) }}</div>
                <div>
                    <div class="user-name">{{ auth()->user()->name }}</div>
                    <div class="user-email">{{ auth()->user()->email }}</div>
                </div>
            </div>
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="btn-logout">
                    <i class="fas fa-sign-out-alt"></i> Keluar
                </button>
            </form>
        </div>
    </aside>

    {{-- MAIN CONTENT --}}
    <div class="main-wrap">
        <header class="topbar">
            <div>
                <div class="topbar-title">@yield('page-title', 'Dashboard')</div>
                <div class="topbar-breadcrumb">LaundryLaundryan &rsaquo; @yield('page-title', 'Dashboard')</div>
            </div>
            
        </header>

        <main class="page-content">
            @if(session('success'))
                <div class="alert alert-success">
                    <i class="fas fa-check-circle"></i> {{ session('success') }}
                </div>
            @endif
            @if(session('error'))
                <div class="alert alert-danger">
                    <i class="fas fa-exclamation-circle"></i> {{ session('error') }}
                </div>
            @endif

            @yield('content')
        </main>
    </div>
    @stack('scripts')
</body>
</html>
