<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Mueblería La Bendición')</title>

    <!-- Bootstrap 5 -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">

    <style>
        :root {
            --sidebar-width: 260px;
            --brand-color: #4a1942;
            --brand-light: #6d2c63;
        }
        body { background-color: #f4f6f9; font-size: 0.9rem; }

        /* Sidebar */
        #sidebar {
            width: var(--sidebar-width);
            min-height: 100vh;
            background: var(--brand-color);
            position: fixed;
            top: 0; left: 0;
            z-index: 1000;
            transition: transform .3s ease;
            overflow-y: auto;
        }
        #sidebar .sidebar-brand {
            padding: 1.2rem 1rem;
            border-bottom: 1px solid rgba(255,255,255,.15);
        }
        #sidebar .sidebar-brand h5 {
            color: #fff;
            font-weight: 700;
            font-size: 1rem;
            margin: 0;
        }
        #sidebar .sidebar-brand small { color: rgba(255,255,255,.6); font-size: .75rem; }
        #sidebar .nav-link {
            color: rgba(255,255,255,.8);
            padding: .55rem 1.2rem;
            border-radius: 6px;
            margin: 1px 8px;
            transition: background .2s;
            display: flex;
            align-items: center;
            gap: .55rem;
        }
        #sidebar .nav-link:hover,
        #sidebar .nav-link.active {
            background: rgba(255,255,255,.15);
            color: #fff;
        }
        #sidebar .nav-section {
            color: rgba(255,255,255,.4);
            font-size: .7rem;
            font-weight: 600;
            letter-spacing: .08em;
            text-transform: uppercase;
            padding: .9rem 1.2rem .3rem;
        }

        /* Main content */
        #content {
            margin-left: var(--sidebar-width);
            transition: margin .3s ease;
        }
        .topbar {
            background: #fff;
            border-bottom: 1px solid #dee2e6;
            padding: .6rem 1.5rem;
            position: sticky;
            top: 0;
            z-index: 900;
        }

        /* Cards */
        .stat-card {
            border: none;
            border-radius: 10px;
            box-shadow: 0 1px 4px rgba(0,0,0,.08);
        }
        .stat-card .stat-icon {
            width: 48px; height: 48px;
            border-radius: 10px;
            display: flex; align-items: center; justify-content: center;
            font-size: 1.4rem;
        }

        /* Tables */
        .table thead th { font-size: .78rem; text-transform: uppercase; letter-spacing: .04em; color: #6c757d; }

        /* Badges */
        .badge-activa    { background-color: #198754; }
        .badge-liquidada { background-color: #0d6efd; }
        .badge-cancelada { background-color: #dc3545; }
        .badge-pendiente { background-color: #ffc107; color: #000; }
        .badge-parcial   { background-color: #fd7e14; }
        .badge-pagada    { background-color: #198754; }

        /* Mobile */
        @media (max-width: 768px) {
            #sidebar { transform: translateX(-100%); }
            #sidebar.show { transform: translateX(0); }
            #content { margin-left: 0; }
        }
    </style>
    @stack('styles')
</head>
<body>

@auth
<!-- Sidebar -->
<nav id="sidebar">
    <div class="sidebar-brand">
        <div class="d-flex align-items-center gap-2">
            <div style="width:36px;height:36px;background:rgba(255,255,255,.2);border-radius:8px;display:flex;align-items:center;justify-content:center;">
                <i class="bi bi-house-heart-fill text-white"></i>
            </div>
            <div>
                <h5>La Bendición</h5>
                <small>Sistema de Inventario</small>
            </div>
        </div>
    </div>

    <ul class="nav flex-column mt-2 pb-4">
        <li class="nav-item">
            <a href="{{ route('dashboard') }}" class="nav-link {{ request()->routeIs('dashboard') ? 'active' : '' }}">
                <i class="bi bi-speedometer2"></i> Dashboard
            </a>
        </li>

        <li class="nav-section">Ventas & Cobros</li>

        <li class="nav-item">
            <a href="{{ route('clientes.index') }}" class="nav-link {{ request()->routeIs('clientes.*') ? 'active' : '' }}">
                <i class="bi bi-people-fill"></i> Clientes
            </a>
        </li>
        <li class="nav-item">
            <a href="{{ route('ventas.index') }}" class="nav-link {{ request()->routeIs('ventas.*') ? 'active' : '' }}">
                <i class="bi bi-cart-fill"></i> Ventas
            </a>
        </li>
        <li class="nav-item">
            <a href="{{ route('pagos.pendientes') }}" class="nav-link {{ request()->routeIs('pagos.pendientes') ? 'active' : '' }}">
                <i class="bi bi-hourglass-split"></i> Pagos Pendientes
            </a>
        </li>
        <li class="nav-item">
            <a href="{{ route('pagos.index') }}" class="nav-link {{ request()->routeIs('pagos.index') || request()->routeIs('pagos.show') ? 'active' : '' }}">
                <i class="bi bi-cash-stack"></i> Historial de Cobros
            </a>
        </li>

        @if(auth()->user()->isAdmin())
        <li class="nav-section">Inventario</li>
        <li class="nav-item">
            <a href="{{ route('productos.index') }}" class="nav-link {{ request()->routeIs('productos.*') ? 'active' : '' }}">
                <i class="bi bi-box-seam-fill"></i> Productos
            </a>
        </li>
        <li class="nav-item">
            <a href="{{ route('compras.index') }}" class="nav-link {{ request()->routeIs('compras.*') ? 'active' : '' }}">
                <i class="bi bi-truck"></i> Compras
            </a>
        </li>
        <li class="nav-item">
            <a href="{{ route('proveedores.index') }}" class="nav-link {{ request()->routeIs('proveedores.*') ? 'active' : '' }}">
                <i class="bi bi-building"></i> Proveedores
            </a>
        </li>

        <li class="nav-section">Administración</li>
        <li class="nav-item">
            <a href="{{ route('usuarios.index') }}" class="nav-link {{ request()->routeIs('usuarios.*') ? 'active' : '' }}">
                <i class="bi bi-person-badge-fill"></i> Cobradores / Usuarios
            </a>
        </li>
        @endif
    </ul>
</nav>

<!-- Main Content -->
<div id="content">
    <!-- Topbar -->
    <div class="topbar d-flex align-items-center justify-content-between">
        <div class="d-flex align-items-center gap-3">
            <button class="btn btn-sm btn-outline-secondary d-md-none" id="sidebarToggle">
                <i class="bi bi-list"></i>
            </button>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-0 small">
                    @yield('breadcrumb')
                </ol>
            </nav>
        </div>
        <div class="d-flex align-items-center gap-2">
            <span class="badge bg-{{ auth()->user()->isAdmin() ? 'danger' : 'primary' }} rounded-pill">
                {{ ucfirst(auth()->user()->role->nombre) }}
            </span>
            <div class="dropdown">
                <button class="btn btn-sm btn-light dropdown-toggle d-flex align-items-center gap-1" type="button" data-bs-toggle="dropdown">
                    <i class="bi bi-person-circle"></i>
                    <span>{{ auth()->user()->name }}</span>
                </button>
                <ul class="dropdown-menu dropdown-menu-end">
                    <li>
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit" class="dropdown-item text-danger">
                                <i class="bi bi-box-arrow-right"></i> Cerrar Sesión
                            </button>
                        </form>
                    </li>
                </ul>
            </div>
        </div>
    </div>

    <!-- Page Content -->
    <div class="container-fluid py-4 px-4">
        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <i class="bi bi-check-circle-fill me-1"></i> {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif
        @if(session('error'))
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <i class="bi bi-exclamation-triangle-fill me-1"></i> {{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif
        @if($errors->any())
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <i class="bi bi-exclamation-triangle-fill me-1"></i>
                <strong>Por favor corrija los errores:</strong>
                <ul class="mb-0 mt-1 small">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        @yield('content')
    </div>
</div>
@else
    @yield('content')
@endauth

<!-- Bootstrap JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

<script>
// Sidebar toggle for mobile
document.getElementById('sidebarToggle')?.addEventListener('click', function () {
    document.getElementById('sidebar').classList.toggle('show');
});
</script>
@stack('scripts')
</body>
</html>
