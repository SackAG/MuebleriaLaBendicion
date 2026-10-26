@extends('layouts.app')

@section('title', 'Mi Dashboard')

@section('breadcrumb')
    <li class="breadcrumb-item active">Mi Jornada</li>
@endsection

@section('content')

<!-- Header -->
<div class="page-header">
    <div>
        <h1 class="page-title">
            <i class="bi bi-person-badge-fill text-primary"></i>
            Hola, {{ auth()->user()->name }}
        </h1>
        <p class="page-subtitle">
            Ruta y cobranza del día · {{ now()->translatedFormat('l, d \d\e F \d\e Y') }}
        </p>
    </div>
    <div>
        <a href="{{ route('pagos.pendientes') }}" class="btn btn-primary">
            <i class="bi bi-hourglass-split me-1"></i> Ver Pagos Pendientes
        </a>
    </div>
</div>

<!-- Primary KPIs -->
<div class="row g-3 mb-4">
    <div class="col-12 col-sm-6 col-xl-3">
        <div class="stat-card">
            <div class="d-flex align-items-center justify-content-between">
                <div>
                    <div class="stat-label">Cobrado hoy</div>
                    <div class="stat-value text-success">${{ number_format($stats['cobros_hoy'], 2) }}</div>
                </div>
                <div class="stat-icon" style="background: var(--success-subtle); color: var(--success);">
                    <i class="bi bi-cash-stack"></i>
                </div>
            </div>
            <div class="stat-delta">
                <i class="bi bi-calendar-check text-success"></i>
                <span>Ingreso acumulado hoy</span>
            </div>
        </div>
    </div>

    <div class="col-12 col-sm-6 col-xl-3">
        <div class="stat-card">
            <div class="d-flex align-items-center justify-content-between">
                <div>
                    <div class="stat-label">Saldo pendiente</div>
                    <div class="stat-value text-danger">${{ number_format($stats['saldo_pendiente'], 2) }}</div>
                </div>
                <div class="stat-icon" style="background: var(--danger-subtle); color: var(--danger);">
                    <i class="bi bi-clock-history"></i>
                </div>
            </div>
            <div class="stat-delta">
                <i class="bi bi-exclamation-circle text-danger"></i>
                <span>En mi cartera asignada</span>
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
                <i class="bi bi-receipt text-muted"></i>
                <span>Cuentas con cuotas</span>
            </div>
        </div>
    </div>

    <div class="col-12 col-sm-6 col-xl-3">
        <div class="stat-card">
            <div class="d-flex align-items-center justify-content-between">
                <div>
                    <div class="stat-label">Mis clientes</div>
                    <div class="stat-value">{{ number_format($stats['mis_clientes']) }}</div>
                </div>
                <div class="stat-icon" style="background: #f1f5f9; color: var(--text-secondary);">
                    <i class="bi bi-people-fill"></i>
                </div>
            </div>
            <div class="stat-delta">
                <i class="bi bi-person-check text-muted"></i>
                <span>Asignados directamente</span>
            </div>
        </div>
    </div>
</div>

<!-- Main content grid -->
<div class="row g-4">
    <!-- Cuentas por cobrar prioritarias -->
    <div class="col-lg-8">
        <div class="card mb-0 h-100">
            <div class="card-header">
                <h6><i class="bi bi-hourglass-bottom me-2 text-primary"></i> Cuentas por Cobrar Prioritarias</h6>
                <a href="{{ route('pagos.pendientes') }}" class="btn btn-sm btn-outline-secondary">
                    Ver todas <i class="bi bi-arrow-right ms-1"></i>
                </a>
            </div>
            <div class="table-responsive">
                <table class="table align-middle">
                    <thead>
                        <tr>
                            <th>Cliente</th>
                            <th>N° Venta</th>
                            <th class="num">Saldo Deudor</th>
                            <th class="text-end">Acción</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($ventasPendientes as $venta)
                        <tr>
                            <td>
                                <div class="fw-bold text-dark">{{ $venta->cliente->nombre_completo }}</div>
                                @if($venta->cliente->telefono)
                                <a href="tel:{{ $venta->cliente->telefono }}" class="text-muted small text-decoration-none d-inline-flex align-items-center gap-1 mt-1">
                                    <i class="bi bi-telephone text-primary"></i> {{ $venta->cliente->telefono }}
                                </a>
                                @endif
                            </td>
                            <td>
                                <a href="{{ route('ventas.show', $venta) }}" class="fw-semibold text-primary text-decoration-none">
                                    {{ $venta->numero_venta }}
                                </a>
                            </td>
                            <td class="num text-danger fw-bold fs-6">
                                ${{ number_format($venta->saldo_pendiente, 2) }}
                            </td>
                            <td class="text-end">
                                <a href="{{ route('pagos.create', $venta) }}" class="btn btn-sm btn-success px-3">
                                    <i class="bi bi-cash me-1"></i> Cobrar
                                </a>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="4" class="text-center py-5">
                                <div class="empty-state-icon">
                                    <i class="bi bi-check2-circle text-success"></i>
                                </div>
                                <div class="empty-state-title">¡Al día!</div>
                                <div class="empty-state-text">No tienes cuentas pendientes por cobrar en este momento.</div>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Cobros recientes -->
    <div class="col-lg-4">
        <div class="card mb-0 h-100">
            <div class="card-header">
                <h6><i class="bi bi-clock-history me-2 text-primary"></i> Cobros Recientes</h6>
                <a href="{{ route('pagos.index') }}" class="btn btn-sm btn-outline-secondary">
                    Historial
                </a>
            </div>
            <div class="card-body p-0">
                @forelse($cobrosRecientes as $pago)
                <div class="d-flex align-items-center justify-content-between p-3 border-bottom">
                    <div class="d-flex align-items-center gap-2">
                        <span class="badge badge-secondary-soft text-muted font-monospace" style="font-size:.7rem;">
                            {{ $pago->fecha_pago->format('d/m') }}
                        </span>
                        <div>
                            <div class="fw-semibold text-dark" style="font-size:.825rem;">
                                {{ $pago->venta->cliente->nombre_completo }}
                            </div>
                            <div class="text-muted" style="font-size:.72rem;">
                                Recibo: {{ $pago->numero_recibo }}
                            </div>
                        </div>
                    </div>
                    <div class="fw-bold text-success font-monospace" style="font-size:.875rem;">
                        ${{ number_format($pago->total_cobrado, 2) }}
                    </div>
                </div>
                @empty
                <div class="empty-state py-5">
                    <div class="empty-state-icon" style="width:40px;height:40px;font-size:1.25rem;">
                        <i class="bi bi-cash-stack"></i>
                    </div>
                    <div class="empty-state-title">Sin cobros aún hoy</div>
                    <div class="empty-state-text">Los recibos registrados aparecerán aquí.</div>
                </div>
                @endforelse
            </div>
        </div>
    </div>
</div>

@endsection
