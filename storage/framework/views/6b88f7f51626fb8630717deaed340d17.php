<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="<?php echo e(csrf_token()); ?>">
    <title><?php echo $__env->yieldContent('title', 'Mueblería La Bendición'); ?></title>

    <!-- Bootstrap 5 -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
    <!-- Plus Jakarta Sans font -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">

    <style>
        :root {
            --sidebar-width: 256px;
            --sidebar-bg: #0f172a;
            --sidebar-border: rgba(255, 255, 255, 0.07);
            --sidebar-hover: rgba(255, 255, 255, 0.06);
            --sidebar-active: #1e293b;
            
            --primary: #4f46e5;
            --primary-hover: #4338ca;
            --primary-subtle: #eef2ff;
            --primary-text: #3730a3;
            
            --accent: #4f46e5;
            --accent-hover: #4338ca;
            --accent-subtle: #eef2ff;
            --accent-focus: rgba(79, 70, 229, 0.2);
            
            --bg: #f8fafc;
            --surface: #ffffff;
            --surface-subtle: #f1f5f9;
            --border: #e2e8f0;
            --border-hover: #cbd5e1;
            
            --text: #0f172a;
            --text-secondary: #475569;
            --muted: #64748b;
            
            --success: #059669;
            --success-subtle: #ecfdf5;
            --success-text: #065f46;
            --success-border: #a7f3d0;
            
            --danger: #dc2626;
            --danger-subtle: #fef2f2;
            --danger-text: #991b1b;
            --danger-border: #fecaca;
            
            --warning: #d97706;
            --warning-subtle: #fffbeb;
            --warning-text: #92400e;
            --warning-border: #fde68a;
            
            --info: #0284c7;
            --info-subtle: #f0f9ff;
            --info-text: #075985;
            --info-border: #bae6fd;
            
            --shadow-xs: 0 1px 2px 0 rgba(0, 0, 0, 0.05);
            --shadow-sm: 0 1px 3px 0 rgba(0, 0, 0, 0.07), 0 1px 2px -1px rgba(0, 0, 0, 0.05);
            --shadow-md: 0 4px 6px -1px rgba(0, 0, 0, 0.07), 0 2px 4px -2px rgba(0, 0, 0, 0.05);
            
            --radius-sm: 6px;
            --radius-md: 10px;
            --radius-lg: 14px;
        }

        *, *::before, *::after { box-sizing: border-box; }

        body {
            font-family: 'Plus Jakarta Sans', system-ui, -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, sans-serif;
            font-size: 0.875rem;
            line-height: 1.5;
            background: var(--bg);
            color: var(--text);
            margin: 0;
            -webkit-font-smoothing: antialiased;
            -moz-osx-font-smoothing: grayscale;
        }

        /* ── Sidebar ── */
        #sidebar {
            width: var(--sidebar-width);
            min-height: 100vh;
            background: var(--sidebar-bg);
            position: fixed;
            top: 0; left: 0;
            z-index: 1000;
            transition: transform .25s cubic-bezier(0.4, 0, 0.2, 1);
            overflow-y: auto;
            display: flex;
            flex-direction: column;
            border-right: 1px solid var(--sidebar-border);
        }

        .sidebar-brand {
            padding: 1.25rem 1.1rem;
            border-bottom: 1px solid var(--sidebar-border);
            display: flex;
            align-items: center;
            gap: .85rem;
        }
        .sidebar-brand-icon {
            width: 38px; height: 38px;
            background: linear-gradient(135deg, #6366f1, #4f46e5);
            border-radius: var(--radius-md);
            display: flex; align-items: center; justify-content: center;
            flex-shrink: 0;
            box-shadow: 0 2px 8px rgba(99, 102, 241, 0.35);
        }
        .sidebar-brand-icon i { color: #fff; font-size: 1.15rem; }
        .sidebar-brand-name {
            font-size: .925rem;
            font-weight: 700;
            color: #ffffff;
            line-height: 1.2;
            letter-spacing: -0.01em;
        }
        .sidebar-brand-sub {
            font-size: .7rem;
            color: #94a3b8;
            font-weight: 500;
            margin-top: 1px;
        }

        #sidebar nav {
            flex: 1;
            padding: .85rem .75rem 2rem;
        }

        .nav-section-label {
            display: flex;
            align-items: center;
            gap: .5rem;
            padding: 1.2rem .6rem .45rem;
            font-size: .675rem;
            font-weight: 700;
            color: #64748b;
            text-transform: uppercase;
            letter-spacing: .06em;
        }
        .nav-section-label::after {
            content: '';
            flex: 1;
            height: 1px;
            background: rgba(255, 255, 255, 0.08);
        }

        .sidebar-link {
            display: flex;
            align-items: center;
            gap: .75rem;
            padding: .55rem .75rem;
            margin-bottom: 2px;
            border-radius: var(--radius-sm);
            color: #94a3b8;
            text-decoration: none;
            font-size: .84rem;
            font-weight: 500;
            transition: all .15s ease;
            position: relative;
        }
        .sidebar-link i { font-size: 1.05rem; width: 20px; text-align: center; color: #64748b; transition: color .15s ease; }
        .sidebar-link:hover {
            background: var(--sidebar-hover);
            color: #f8fafc;
        }
        .sidebar-link:hover i { color: #cbd5e1; }
        .sidebar-link.active {
            background: var(--sidebar-active);
            color: #ffffff;
            font-weight: 600;
            box-shadow: inset 3px 0 0 #6366f1;
        }
        .sidebar-link.active i { color: #818cf8; }

        /* ── Backdrop for mobile drawer ── */
        #sidebarBackdrop {
            display: none;
            position: fixed;
            top: 0; left: 0; right: 0; bottom: 0;
            background: rgba(15, 23, 42, 0.6);
            backdrop-filter: blur(2px);
            z-index: 999;
        }
        #sidebarBackdrop.show { display: block; }

        /* ── Main content ── */
        #content {
            margin-left: var(--sidebar-width);
            transition: margin .25s cubic-bezier(0.4, 0, 0.2, 1);
            min-height: 100vh;
            display: flex;
            flex-direction: column;
        }

        /* ── Topbar ── */
        .topbar {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(8px);
            border-bottom: 1px solid var(--border);
            padding: .75rem 1.75rem;
            position: sticky;
            top: 0;
            z-index: 900;
            display: flex;
            align-items: center;
            justify-content: space-between;
        }
        .topbar .breadcrumb { margin: 0; font-size: .82rem; }
        .topbar .breadcrumb a { color: var(--muted); text-decoration: none; font-weight: 500; }
        .topbar .breadcrumb a:hover { color: var(--primary); }
        .topbar .breadcrumb-item.active { color: var(--text); font-weight: 600; }

        .user-chip {
            background: #ffffff;
            border: 1px solid var(--border);
            border-radius: var(--radius-full);
            padding: .35rem .85rem .35rem .45rem;
            display: inline-flex;
            align-items: center;
            gap: .5rem;
            box-shadow: var(--shadow-xs);
            transition: border-color .15s ease;
        }
        .user-chip:hover { border-color: var(--border-hover); }
        .user-avatar {
            width: 28px; height: 28px;
            border-radius: 50%;
            background: var(--primary-subtle);
            color: var(--primary);
            font-size: .75rem;
            font-weight: 700;
            display: flex; align-items: center; justify-content: center;
        }

        /* ── Page Layout ── */
        .page-body {
            padding: 1.75rem;
            flex: 1;
        }
        .page-header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-bottom: 1.5rem;
            flex-wrap: wrap;
            gap: 1rem;
        }
        .page-title {
            font-size: 1.25rem;
            font-weight: 700;
            color: var(--text);
            margin: 0;
            letter-spacing: -0.015em;
            display: flex;
            align-items: center;
            gap: .5rem;
        }
        .page-subtitle {
            font-size: .8125rem;
            color: var(--muted);
            margin-top: .2rem;
            margin-bottom: 0;
        }

        /* ── Cards ── */
        .card {
            background: var(--surface);
            border: 1px solid var(--border);
            border-radius: var(--radius-lg);
            box-shadow: var(--shadow-sm);
            overflow: hidden;
            margin-bottom: 1.5rem;
        }
        .card-header {
            background: #ffffff;
            border-bottom: 1px solid var(--border);
            padding: 1rem 1.25rem;
            display: flex;
            align-items: center;
            justify-content: space-between;
        }
        .card-header h5, .card-header h6 {
            font-size: .925rem;
            font-weight: 700;
            margin: 0;
            color: var(--text);
            letter-spacing: -0.01em;
        }
        .card-body { padding: 1.25rem; }
        .card-footer {
            background: #fafafa;
            border-top: 1px solid var(--border);
            padding: .9rem 1.25rem;
        }

        /* ── KPI / Stat Cards ── */
        .stat-card {
            background: var(--surface);
            border: 1px solid var(--border);
            border-radius: var(--radius-lg);
            padding: 1.2rem 1.25rem;
            box-shadow: var(--shadow-sm);
            position: relative;
            transition: transform .15s ease, box-shadow .15s ease;
        }
        .stat-card:hover {
            transform: translateY(-1px);
            box-shadow: var(--shadow-md);
        }
        .stat-card .stat-icon {
            width: 42px; height: 42px;
            border-radius: var(--radius-md);
            display: flex; align-items: center; justify-content: center;
            font-size: 1.2rem;
            flex-shrink: 0;
        }
        .stat-card .stat-label {
            font-size: .75rem;
            font-weight: 600;
            color: var(--muted);
            margin-bottom: .25rem;
            text-transform: uppercase;
            letter-spacing: .04em;
        }
        .stat-card .stat-value {
            font-size: 1.6rem;
            font-weight: 800;
            color: var(--text);
            line-height: 1.1;
            letter-spacing: -0.02em;
            font-variant-numeric: tabular-nums;
        }
        .stat-card .stat-delta {
            font-size: .75rem;
            color: var(--muted);
            margin-top: .4rem;
            display: flex;
            align-items: center;
            gap: .3rem;
        }

        /* ── Tables ── */
        .table-responsive {
            border-radius: var(--radius-md);
        }
        .table {
            margin-bottom: 0;
            border-collapse: separate;
            border-spacing: 0;
        }
        .table thead th {
            font-size: .7rem;
            font-weight: 700;
            color: #64748b;
            text-transform: uppercase;
            letter-spacing: .05em;
            background: #f8fafc;
            border-bottom: 1px solid var(--border);
            border-top: none;
            padding: .75rem 1rem;
            white-space: nowrap;
        }
        .table tbody td {
            font-size: .845rem;
            color: var(--text);
            padding: .85rem 1rem;
            border-bottom: 1px solid #f1f5f9;
            vertical-align: middle;
        }
        .table tbody tr:last-child td { border-bottom: none; }
        .table tbody tr {
            transition: background .12s ease;
        }
        .table tbody tr:hover {
            background: #f8fafc;
        }
        .table td.num, .table th.num {
            text-align: right;
            font-variant-numeric: tabular-nums;
        }
        .table td.num { font-weight: 600; }

        /* ── Badges ── */
        .badge {
            font-weight: 600;
            font-size: .725rem;
            padding: .35em .65em;
            border-radius: var(--radius-full);
            display: inline-flex;
            align-items: center;
            gap: .3rem;
            line-height: 1.2;
            letter-spacing: 0.01em;
        }
        .badge-activa, .badge-pagada, .badge-success-soft {
            background: var(--success-subtle);
            color: var(--success-text);
            border: 1px solid var(--success-border);
        }
        .badge-liquidada, .badge-info-soft {
            background: var(--info-subtle);
            color: var(--info-text);
            border: 1px solid var(--info-border);
        }
        .badge-pendiente, .badge-warning-soft {
            background: var(--warning-subtle);
            color: var(--warning-text);
            border: 1px solid var(--warning-border);
        }
        .badge-cancelada, .badge-danger-soft {
            background: var(--danger-subtle);
            color: var(--danger-text);
            border: 1px solid var(--danger-border);
        }
        .badge-parcial {
            background: #fff7ed;
            color: #9a3412;
            border: 1px solid #ffedd5;
        }
        .badge-secondary-soft {
            background: #f1f5f9;
            color: #475569;
            border: 1px solid #e2e8f0;
        }

        /* ── Buttons ── */
        .btn {
            font-size: .84rem;
            font-weight: 600;
            border-radius: var(--radius-sm);
            padding: .5rem .95rem;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: .4rem;
            transition: all .15s cubic-bezier(0.4, 0, 0.2, 1);
            min-height: 38px;
        }
        .btn-sm {
            padding: .35rem .75rem;
            font-size: .78rem;
            min-height: 32px;
            border-radius: var(--radius-sm);
        }
        .btn-primary {
            background: var(--primary);
            border-color: var(--primary);
            color: #ffffff;
            box-shadow: 0 1px 2px rgba(79, 70, 229, 0.2);
        }
        .btn-primary:hover, .btn-primary:focus {
            background: var(--primary-hover);
            border-color: var(--primary-hover);
            color: #ffffff;
            box-shadow: 0 2px 4px rgba(79, 70, 229, 0.3);
        }
        .btn-outline-secondary {
            border-color: var(--border-hover);
            color: var(--text-secondary);
            background: #ffffff;
        }
        .btn-outline-secondary:hover {
            background: #f8fafc;
            border-color: #94a3b8;
            color: var(--text);
        }

        /* Standardized Action Buttons (Icon Buttons) */
        .btn-action {
            width: 32px;
            height: 32px;
            min-height: 32px;
            padding: 0;
            border-radius: var(--radius-sm);
            display: inline-flex;
            align-items: center;
            justify-content: center;
            background: #ffffff;
            border: 1px solid var(--border);
            color: var(--text-secondary);
            text-decoration: none;
            transition: all .15s ease;
        }
        .btn-action:hover {
            background: #f8fafc;
            color: var(--text);
            border-color: var(--border-hover);
        }
        .btn-action.action-primary:hover {
            background: var(--primary-subtle);
            color: var(--primary);
            border-color: #c7d2fe;
        }
        .btn-action.action-success:hover {
            background: var(--success-subtle);
            color: var(--success);
            border-color: var(--success-border);
        }
        .btn-action.action-warning:hover {
            background: var(--warning-subtle);
            color: var(--warning);
            border-color: var(--warning-border);
        }
        .btn-action.action-danger:hover {
            background: var(--danger-subtle);
            color: var(--danger);
            border-color: var(--danger-border);
        }

        /* ── Form Controls ── */
        .form-label {
            font-size: .8rem;
            font-weight: 600;
            color: var(--text-secondary);
            margin-bottom: .4rem;
        }
        .form-control, .form-select {
            font-size: .845rem;
            border-radius: var(--radius-sm);
            border: 1px solid var(--border-hover);
            padding: .5rem .8rem;
            color: var(--text);
            transition: border-color .15s ease, box-shadow .15s ease;
        }
        .form-control:focus, .form-select:focus {
            border-color: var(--primary);
            box-shadow: 0 0 0 3px var(--accent-focus);
            outline: none;
        }
        .form-control-sm, .form-select-sm {
            padding: .35rem .65rem;
            font-size: .8rem;
            min-height: 32px;
        }
        .input-group-text {
            background: #f8fafc;
            border-color: var(--border-hover);
            color: var(--muted);
            font-size: .845rem;
        }

        /* ── Alerts ── */
        .alert {
            font-size: .845rem;
            border-radius: var(--radius-md);
            border: 1px solid transparent;
            padding: .9rem 1.1rem;
            margin-bottom: 1.25rem;
            display: flex;
            align-items: center;
            gap: .75rem;
        }
        .alert-success {
            background: var(--success-subtle);
            border-color: var(--success-border);
            color: var(--success-text);
        }
        .alert-danger {
            background: var(--danger-subtle);
            border-color: var(--danger-border);
            color: var(--danger-text);
        }
        .alert-warning {
            background: var(--warning-subtle);
            border-color: var(--warning-border);
            color: var(--warning-text);
        }
        .alert-info {
            background: var(--info-subtle);
            border-color: var(--info-border);
            color: var(--info-text);
        }

        /* ── Empty States ── */
        .empty-state {
            padding: 3rem 1.5rem;
            text-align: center;
        }
        .empty-state-icon {
            width: 56px;
            height: 56px;
            border-radius: var(--radius-full);
            background: #f1f5f9;
            color: var(--muted);
            display: inline-flex;
            align-items: center;
            justify-content: center;
            font-size: 1.75rem;
            margin-bottom: 1rem;
        }
        .empty-state-title {
            font-size: .95rem;
            font-weight: 700;
            color: var(--text);
            margin-bottom: .25rem;
        }
        .empty-state-text {
            font-size: .8125rem;
            color: var(--muted);
            max-width: 320px;
            margin: 0 auto 1.25rem;
        }

        /* ── Search & Filter Bar ── */
        .filter-card {
            background: #ffffff;
            border: 1px solid var(--border);
            border-radius: var(--radius-md);
            padding: .85rem 1rem;
            margin-bottom: 1rem;
        }

        /* ── Code / Monospace Chip ── */
        code {
            font-size: .75rem;
            padding: .2em .4em;
            border-radius: var(--radius-sm);
            background: #f1f5f9;
            color: #475569;
            font-weight: 600;
        }

        /* ── Mobile & Responsive ── */
        @media (max-width: 768px) {
            #sidebar {
                transform: translateX(-100%);
                box-shadow: 4px 0 24px rgba(0,0,0,0.25);
            }
            #sidebar.show {
                transform: translateX(0);
            }
            #content {
                margin-left: 0;
            }
            .page-body {
                padding: 1rem;
            }
            .topbar {
                padding: .65rem 1rem;
            }
        }
    </style>
    <?php echo $__env->yieldPushContent('styles'); ?>
</head>
<body>

<?php if(auth()->guard()->check()): ?>
<!-- Backdrop overlay for mobile drawer -->
<div id="sidebarBackdrop"></div>

<!-- Sidebar -->
<aside id="sidebar">
    <div class="sidebar-brand">
        <div class="sidebar-brand-icon">
            <i class="bi bi-house-heart-fill"></i>
        </div>
        <div>
            <div class="sidebar-brand-name">La Bendición</div>
            <div class="sidebar-brand-sub">Mueblería & Cobros</div>
        </div>
    </div>

    <nav>
        <a href="<?php echo e(route('dashboard')); ?>"
           class="sidebar-link <?php echo e(request()->routeIs('dashboard') ? 'active' : ''); ?>">
            <i class="bi bi-grid-1x2-fill"></i>
            <span>Dashboard</span>
        </a>

        <div class="nav-section-label">Ventas & Cobros</div>

        <a href="<?php echo e(route('clientes.index')); ?>"
           class="sidebar-link <?php echo e(request()->routeIs('clientes.*') ? 'active' : ''); ?>">
            <i class="bi bi-people-fill"></i>
            <span>Clientes</span>
        </a>
        <a href="<?php echo e(route('ventas.index')); ?>"
           class="sidebar-link <?php echo e(request()->routeIs('ventas.*') ? 'active' : ''); ?>">
            <i class="bi bi-cart3"></i>
            <span>Ventas</span>
        </a>
        <a href="<?php echo e(route('pagos.pendientes')); ?>"
           class="sidebar-link <?php echo e(request()->routeIs('pagos.pendientes') ? 'active' : ''); ?>">
            <i class="bi bi-hourglass-split"></i>
            <span>Pagos Pendientes</span>
        </a>
        <a href="<?php echo e(route('pagos.index')); ?>"
           class="sidebar-link <?php echo e(request()->routeIs('pagos.index') || request()->routeIs('pagos.show') ? 'active' : ''); ?>">
            <i class="bi bi-cash-stack"></i>
            <span>Historial Cobros</span>
        </a>

        <?php if(auth()->user()->isAdmin()): ?>
        <div class="nav-section-label">Inventario</div>

        <a href="<?php echo e(route('productos.index')); ?>"
           class="sidebar-link <?php echo e(request()->routeIs('productos.*') ? 'active' : ''); ?>">
            <i class="bi bi-box-seam-fill"></i>
            <span>Productos</span>
        </a>
        <a href="<?php echo e(route('compras.index')); ?>"
           class="sidebar-link <?php echo e(request()->routeIs('compras.*') ? 'active' : ''); ?>">
            <i class="bi bi-truck"></i>
            <span>Compras</span>
        </a>
        <a href="<?php echo e(route('proveedores.index')); ?>"
           class="sidebar-link <?php echo e(request()->routeIs('proveedores.*') ? 'active' : ''); ?>">
            <i class="bi bi-building"></i>
            <span>Proveedores</span>
        </a>

        <div class="nav-section-label">Administración</div>

        <a href="<?php echo e(route('usuarios.index')); ?>"
           class="sidebar-link <?php echo e(request()->routeIs('usuarios.*') ? 'active' : ''); ?>">
            <i class="bi bi-person-badge-fill"></i>
            <span>Usuarios & Cobradores</span>
        </a>
        <?php endif; ?>
    </nav>
</aside>

<!-- Main Content -->
<div id="content">
    <!-- Topbar -->
    <header class="topbar">
        <div class="d-flex align-items-center gap-3">
            <button class="btn btn-sm btn-outline-secondary d-md-none" id="sidebarToggle" aria-label="Abrir menú" style="width:38px;height:38px;padding:0;">
                <i class="bi bi-list fs-5"></i>
            </button>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-0">
                    <?php echo $__env->yieldContent('breadcrumb'); ?>
                </ol>
            </nav>
        </div>
        <div class="d-flex align-items-center gap-2">
            <div class="dropdown">
                <button class="user-chip btn p-0 border-0 dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                    <span class="user-avatar">
                        <?php echo e(strtoupper(substr(auth()->user()->name, 0, 1))); ?>

                    </span>
                    <span class="d-none d-sm-inline fw-semibold text-dark"><?php echo e(auth()->user()->name); ?></span>
                    <span class="badge <?php echo e(auth()->user()->isAdmin() ? 'badge-info-soft' : 'badge-secondary-soft'); ?> ms-1">
                        <?php echo e(ucfirst(auth()->user()->role->nombre)); ?>

                    </span>
                </button>
                <ul class="dropdown-menu dropdown-menu-end shadow-sm border mt-2">
                    <li class="px-3 py-2 border-bottom">
                        <div class="fw-bold small"><?php echo e(auth()->user()->name); ?></div>
                        <div class="text-muted" style="font-size:.75rem;"><?php echo e(auth()->user()->email); ?></div>
                    </li>
                    <li>
                        <form method="POST" action="<?php echo e(route('logout')); ?>">
                            <?php echo csrf_field(); ?>
                            <button type="submit" class="dropdown-item text-danger py-2">
                                <i class="bi bi-box-arrow-right me-2"></i> Cerrar Sesión
                            </button>
                        </form>
                    </li>
                </ul>
            </div>
        </div>
    </header>

    <!-- Page Content -->
    <main class="page-body">
        <?php if(session('success')): ?>
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <i class="bi bi-check-circle-fill fs-5 text-success"></i>
                <div class="flex-grow-1"><?php echo e(session('success')); ?></div>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Cerrar"></button>
            </div>
        <?php endif; ?>
        <?php if(session('error')): ?>
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <i class="bi bi-exclamation-triangle-fill fs-5 text-danger"></i>
                <div class="flex-grow-1"><?php echo e(session('error')); ?></div>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Cerrar"></button>
            </div>
        <?php endif; ?>
        <?php if($errors->any()): ?>
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <i class="bi bi-exclamation-triangle-fill fs-5 text-danger"></i>
                <div class="flex-grow-1">
                    <div class="fw-bold mb-1">Por favor revise los siguientes errores:</div>
                    <ul class="mb-0 ps-3">
                        <?php $__currentLoopData = $errors->all(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $error): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <li><?php echo e($error); ?></li>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </ul>
                </div>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Cerrar"></button>
            </div>
        <?php endif; ?>

        <?php echo $__env->yieldContent('content'); ?>
    </main>
</div>
<?php else: ?>
    <?php echo $__env->yieldContent('content'); ?>
<?php endif; ?>

<!-- Bootstrap JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

<script>
document.addEventListener('DOMContentLoaded', function () {
    const sidebar = document.getElementById('sidebar');
    const backdrop = document.getElementById('sidebarBackdrop');
    const toggle = document.getElementById('sidebarToggle');

    function toggleMenu() {
        if (!sidebar || !backdrop) return;
        sidebar.classList.toggle('show');
        backdrop.classList.toggle('show');
    }

    function closeMenu() {
        if (!sidebar || !backdrop) return;
        sidebar.classList.remove('show');
        backdrop.classList.remove('show');
    }

    toggle?.addEventListener('click', toggleMenu);
    backdrop?.addEventListener('click', closeMenu);
});
</script>
<?php echo $__env->yieldPushContent('scripts'); ?>
</body>
</html>
<?php /**PATH C:\Users\isaac\Documents\MuebleriaBendicion\resources\views/layouts/app.blade.php ENDPATH**/ ?>