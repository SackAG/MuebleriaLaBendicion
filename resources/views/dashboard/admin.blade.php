@extends('layouts.app')

@section('title', 'Dashboard - Admin')

@section('breadcrumb')
    <li class="breadcrumb-item active">Resumen General</li>
@endsection

@section('content')

<!-- Page Title & Header -->
<div class="page-header">
    <div>
        <h1 class="page-title">
            <i class="bi bi-grid-1x2-fill text-primary"></i>
            Resumen General
        </h1>
        <p class="page-subtitle">
            Mueblería La Bendición · {{ now()->translatedFormat('l, d \d\e F \d\e Y') }}
        </p>
    </div>
    <div class="d-flex align-items-center gap-2">
        <span class="badge badge-secondary-soft px-3 py-2">
            <i class="bi bi-clock me-1"></i> Actualizado {{ now()->format('H:i') }}
        </span>
    </div>
</div>

<!-- Primary KPIs -->
<div class="row g-3 mb-4">
    <div class="col-12 col-sm-6 col-xl-3">
        <div class="stat-card">
            <div class="d-flex align-items-center justify-content-between">
                <div>
                    <div class="stat-label">Cobrado este mes</div>
                    <div class="stat-value text-success">${{ number_format($stats['cobros_mes'], 2) }}</div>
                </div>
                <div class="stat-icon" style="background: var(--success-subtle); color: var(--success);">
                    <i class="bi bi-cash-stack"></i>
                </div>
            </div>
            <div class="stat-delta">
                <i class="bi bi-arrow-up-right text-success"></i>
                <span>Ingresos efectivos</span>
            </div>
        </div>
    </div>

    <div class="col-12 col-sm-6 col-xl-3">
        <div class="stat-card">
            <div class="d-flex align-items-center justify-content-between">
                <div>
                    <div class="stat-label">Saldo pendiente</div>
                    <div class="stat-value text-danger">${{ number_format($stats['saldo_pendiente_total'], 2) }}</div>
                </div>
                <div class="stat-icon" style="background: var(--danger-subtle); color: var(--danger);">
                    <i class="bi bi-hourglass-split"></i>
                </div>
            </div>
            <div class="stat-delta">
                <i class="bi bi-info-circle text-muted"></i>
                <span>Por cobrar a clientes</span>
            </div>
        </div>
    </div>

    <div class="col-12 col-sm-6 col-xl-3">
        <div class="stat-card">
            <div class="d-flex align-items-center justify-content-between">
                <div>
                    <div class="stat-label">Ventas activas</div>
                    <div class="stat-value text-primary">{{ number_format($stats['ventas_activas']) }}</div>
                </div>
                <div class="stat-icon" style="background: var(--primary-subtle); color: var(--primary);">
                    <i class="bi bi-cart-check"></i>
                </div>
            </div>
            <div class="stat-delta">
                <i class="bi bi-shield-check text-muted"></i>
                <span>Cuentas con cuotas vigentes</span>
            </div>
        </div>
    </div>

    <div class="col-12 col-sm-6 col-xl-3">
        <div class="stat-card">
            <div class="d-flex align-items-center justify-content-between">
                <div>
                    <div class="stat-label">Total Clientes</div>
                    <div class="stat-value">{{ number_format($stats['total_clientes']) }}</div>
                </div>
                <div class="stat-icon" style="background: #f1f5f9; color: var(--text-secondary);">
                    <i class="bi bi-people-fill"></i>
                </div>
            </div>
            <div class="stat-delta">
                <i class="bi bi-person-check text-muted"></i>
                <span>Cartera registrada</span>
            </div>
        </div>
    </div>
</div>

<!-- Secondary Inventory & Commercial Strip -->
<div class="row g-3 mb-4">
    <div class="col-6 col-md-3">
        <div class="card p-3 mb-0 h-100">
            <div class="d-flex align-items-center gap-3">
                <div class="rounded-3 p-2 bg-light text-primary">
                    <i class="bi bi-box-seam fs-4"></i>
                </div>
                <div>
                    <div class="text-muted small fw-semibold">Total Productos</div>
                    <div class="fs-5 fw-bold text-dark">{{ number_format($stats['total_productos']) }}</div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-6 col-md-3">
        <div class="card p-3 mb-0 h-100">
            <div class="d-flex align-items-center gap-3">
                <div class="rounded-3 p-2 {{ $stats['productos_bajo_stock'] > 0 ? 'bg-warning-subtle text-warning' : 'bg-light text-muted' }}">
                    <i class="bi bi-exclamation-triangle fs-4"></i>
                </div>
                <div>
                    <div class="text-muted small fw-semibold">Bajo Stock</div>
                    <div class="fs-5 fw-bold {{ $stats['productos_bajo_stock'] > 0 ? 'text-warning' : 'text-dark' }}">
                        {{ number_format($stats['productos_bajo_stock']) }}
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-6 col-md-3">
        <div class="card p-3 mb-0 h-100">
            <div class="d-flex align-items-center gap-3">
                <div class="rounded-3 p-2 bg-light text-success">
                    <i class="bi bi-graph-up fs-4"></i>
                </div>
                <div>
                    <div class="text-muted small fw-semibold">Ventas del Mes</div>
                    <div class="fs-5 fw-bold text-dark">${{ number_format($stats['ventas_mes'], 2) }}</div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-6 col-md-3">
        <div class="card p-3 mb-0 h-100">
            <div class="d-flex align-items-center gap-3">
                <div class="rounded-3 p-2 bg-light text-secondary">
                    <i class="bi bi-truck fs-4"></i>
                </div>
                <div>
                    <div class="text-muted small fw-semibold">Deuda Proveedores</div>
                    <div class="fs-5 fw-bold text-dark">${{ number_format($stats['compras_pendientes'], 2) }}</div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Two Columns: Cobradores del mes + Ventas Recientes -->
<div class="row g-4 mb-4">
    <!-- Cobradores del mes -->
    <div class="col-lg-4">
        <div class="card h-100 mb-0">
            <div class="card-header">
                <h6><i class="bi bi-person-badge me-2 text-primary"></i> Cobradores del Mes</h6>
            </div>
            <div class="card-body p-0">
                @forelse($stats['cobradores'] as $cobrador)
                <div class="d-flex align-items-center justify-content-between p-3 border-bottom">
                    <div class="d-flex align-items-center gap-3">
                        <div class="user-avatar" style="width:36px;height:36px;font-size:.85rem;">
                            {{ strtoupper(substr($cobrador->name, 0, 1)) }}
                        </div>
                        <div>
                            <div class="fw-bold text-dark" style="font-size:.85rem;">{{ $cobrador->name }}</div>
                            <div class="text-muted small">{{ $cobrador->clientes_count }} clientes asignados</div>
                        </div>
                    </div>
                    <div class="text-end">
                        <div class="fw-bold text-success font-monospace" style="font-size:.9rem;">
                            ${{ number_format($cobrador->pagos_sum_total_cobrado ?? 0, 2) }}
                        </div>
                        <span class="badge badge-success-soft" style="font-size:.65rem;">Cobrado</span>
                    </div>
                </div>
                @empty
                <div class="empty-state py-4">
                    <div class="empty-state-icon" style="width:40px;height:40px;font-size:1.25rem;">
                        <i class="bi bi-people"></i>
                    </div>
                    <div class="empty-state-title">Sin cobradores registrados</div>
                </div>
                @endforelse
            </div>
        </div>
    </div>

    <!-- Ventas Recientes -->
    <div class="col-lg-8">
        <div class="card h-100 mb-0">
            <div class="card-header">
                <h6><i class="bi bi-cart3 me-2 text-primary"></i> Ventas Recientes</h6>
                <a href="{{ route('ventas.index') }}" class="btn btn-sm btn-outline-secondary">
                    Ver todas <i class="bi bi-arrow-right ms-1"></i>
                </a>
            </div>
            <div class="table-responsive">
                <table class="table align-middle">
                    <thead>
                        <tr>
                            <th>N° Venta</th>
                            <th>Cliente</th>
                            <th>Cobrador</th>
                            <th class="num">Total</th>
                            <th class="num">Saldo</th>
                            <th>Estado</th>
                            <th class="text-end">Acción</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($ventasRecientes as $venta)
                        <tr>
                            <td>
                                <a href="{{ route('ventas.show', $venta) }}" class="fw-bold text-primary text-decoration-none">
                                    {{ $venta->numero_venta }}
                                </a>
                            </td>
                            <td class="fw-medium text-dark">{{ $venta->cliente->nombre_completo }}</td>
                            <td class="text-secondary small">{{ $venta->cobrador->name }}</td>
                            <td class="num">${{ number_format($venta->total, 2) }}</td>
                            <td class="num {{ $venta->saldo_pendiente > 0 ? 'text-danger' : 'text-success' }}">
                                ${{ number_format($venta->saldo_pendiente, 2) }}
                            </td>
                            <td>
                                <span class="badge badge-{{ $venta->estado }}">
                                    {{ ucfirst($venta->estado) }}
                                </span>
                            </td>
                            <td class="text-end">
                                <a href="{{ route('ventas.show', $venta) }}" class="btn-action action-primary" title="Ver detalle">
                                    <i class="bi bi-eye"></i>
                                </a>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="7" class="text-center py-4 text-muted">
                                No hay ventas recientes registradas
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<!-- Bajo Stock Alert Section -->
@if($productosBajoStock->count() > 0)
<div class="card border-warning mb-0" style="border-width: 1px;">
    <div class="card-header bg-warning-subtle text-warning-emphasis">
        <h6 class="text-warning-emphasis">
            <i class="bi bi-exclamation-triangle-fill me-2 text-warning"></i>
            {{ $productosBajoStock->count() }} producto{{ $productosBajoStock->count() > 1 ? 's' : '' }} con bajo stock
        </h6>
        <a href="{{ route('productos.index', ['bajo_stock' => 1]) }}" class="btn btn-sm btn-outline-warning">
            Ver inventario bajo <i class="bi bi-arrow-right ms-1"></i>
        </a>
    </div>
    <div class="table-responsive">
        <table class="table align-middle">
            <thead>
                <tr>
                    <th>Código</th>
                    <th>Producto</th>
                    <th>Categoría</th>
                    <th class="num">Stock Actual</th>
                    <th class="num">Stock Mínimo</th>
                    <th class="text-end">Acción</th>
                </tr>
            </thead>
            <tbody>
                @foreach($productosBajoStock as $prod)
                <tr>
                    <td><code>{{ $prod->codigo }}</code></td>
                    <td>
                        <a href="{{ route('productos.show', $prod) }}" class="fw-semibold text-dark text-decoration-none">
                            {{ $prod->nombre }}
                        </a>
                    </td>
                    <td class="text-muted">{{ $prod->categoria->nombre ?? '—' }}</td>
                    <td class="num text-danger fw-bold">{{ $prod->stock }}</td>
                    <td class="num text-muted">{{ $prod->stock_minimo }}</td>
                    <td class="text-end">
                        <a href="{{ route('productos.edit', $prod) }}" class="btn-action action-warning" title="Actualizar stock">
                            <i class="bi bi-pencil"></i>
                        </a>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endif

@endsection
