@extends('layouts.app')

@section('title', 'Mi Dashboard')

@section('breadcrumb')
    <li class="breadcrumb-item active">Mi Dashboard</li>
@endsection

@section('content')

<div class="d-flex align-items-center justify-content-between mb-4">
    <div>
        <h4 class="fw-bold mb-0">Bienvenido, {{ auth()->user()->name }}</h4>
        <p class="text-muted small mb-0">Tus cobros y clientes asignados</p>
    </div>
    <span class="text-muted small"><i class="bi bi-calendar3 me-1"></i>{{ now()->format('d/m/Y') }}</span>
</div>

<!-- Stats -->
<div class="row g-3 mb-4">
    <div class="col-6 col-md-3">
        <div class="card stat-card p-3">
            <div class="d-flex align-items-center gap-3">
                <div class="stat-icon" style="background:#e8f5e9;color:#198754;"><i class="bi bi-people-fill"></i></div>
                <div>
                    <div class="text-muted small">Mis Clientes</div>
                    <div class="fw-bold fs-5">{{ $stats['mis_clientes'] }}</div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-6 col-md-3">
        <div class="card stat-card p-3">
            <div class="d-flex align-items-center gap-3">
                <div class="stat-icon" style="background:#e3f2fd;color:#0d6efd;"><i class="bi bi-cart-fill"></i></div>
                <div>
                    <div class="text-muted small">Ventas Activas</div>
                    <div class="fw-bold fs-5">{{ $stats['ventas_activas'] }}</div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-6 col-md-3">
        <div class="card stat-card p-3">
            <div class="d-flex align-items-center gap-3">
                <div class="stat-icon" style="background:#fff3e0;color:#fd7e14;"><i class="bi bi-calendar-check"></i></div>
                <div>
                    <div class="text-muted small">Cobrado Hoy</div>
                    <div class="fw-bold fs-5">${{ number_format($stats['cobros_hoy'], 2) }}</div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-6 col-md-3">
        <div class="card stat-card p-3">
            <div class="d-flex align-items-center gap-3">
                <div class="stat-icon" style="background:#fce4ec;color:#dc3545;"><i class="bi bi-hourglass-split"></i></div>
                <div>
                    <div class="text-muted small">Saldo Pendiente</div>
                    <div class="fw-bold fs-5">${{ number_format($stats['saldo_pendiente'], 2) }}</div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row g-3">
    <!-- Ventas con saldo pendiente -->
    <div class="col-md-7">
        <div class="card stat-card">
            <div class="card-header bg-transparent border-0 pt-3 pb-0 d-flex justify-content-between align-items-center">
                <h6 class="fw-bold mb-0"><i class="bi bi-hourglass-split me-1 text-warning"></i> Cuentas por Cobrar</h6>
                <a href="{{ route('pagos.pendientes') }}" class="btn btn-sm btn-outline-warning">Ver todas</a>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover mb-0">
                        <thead>
                            <tr>
                                <th>Cliente</th>
                                <th>N° Venta</th>
                                <th>Saldo</th>
                                <th>Acción</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($ventasPendientes as $venta)
                            <tr>
                                <td class="fw-semibold">{{ $venta->cliente->nombre_completo }}</td>
                                <td>{{ $venta->numero_venta }}</td>
                                <td class="text-danger fw-bold">${{ number_format($venta->saldo_pendiente, 2) }}</td>
                                <td>
                                    <a href="{{ route('pagos.create', $venta) }}" class="btn btn-sm btn-success">
                                        <i class="bi bi-plus-circle-fill"></i> Cobrar
                                    </a>
                                </td>
                            </tr>
                            @empty
                            <tr><td colspan="4" class="text-center text-muted py-3">¡Sin pendientes! 🎉</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <!-- Cobros recientes -->
    <div class="col-md-5">
        <div class="card stat-card">
            <div class="card-header bg-transparent border-0 pt-3 pb-0 d-flex justify-content-between align-items-center">
                <h6 class="fw-bold mb-0"><i class="bi bi-cash-stack me-1 text-success"></i> Cobros Recientes</h6>
                <a href="{{ route('pagos.index') }}" class="btn btn-sm btn-outline-secondary">Ver todos</a>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover mb-0">
                        <thead>
                            <tr>
                                <th>Fecha</th>
                                <th>Cliente</th>
                                <th>Monto</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($cobrosRecientes as $pago)
                            <tr>
                                <td class="small">{{ $pago->fecha_pago->format('d/m') }}</td>
                                <td class="small">{{ $pago->venta->cliente->nombre_completo }}</td>
                                <td class="text-success fw-bold small">${{ number_format($pago->total_cobrado, 2) }}</td>
                            </tr>
                            @empty
                            <tr><td colspan="3" class="text-center text-muted py-3">Sin cobros aún</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
