@extends('layouts.app')
@section('title', 'Pagos Pendientes')

@section('breadcrumb')
    <li class="breadcrumb-item active">Pagos Pendientes</li>
@endsection

@section('content')
<div class="page-header">
    <div>
        <h1 class="page-title">
            <i class="bi bi-hourglass-split text-warning"></i>
            Cuentas por Cobrar
        </h1>
        <p class="page-subtitle">Monitoreo de carteras y cuotas pendientes de abono</p>
    </div>
    <div class="d-flex align-items-center gap-2">
        <div class="stat-card py-2 px-3 mb-0 border-danger-subtle bg-white">
            <span class="text-muted small fw-semibold me-2">Total por Cobrar:</span>
            <span class="fs-5 fw-bold text-danger font-monospace">${{ number_format($ventas->sum('saldo_pendiente'), 2) }}</span>
        </div>
    </div>
</div>

<div class="card mb-0">
    <div class="table-responsive">
        <table class="table align-middle">
            <thead>
                <tr>
                    <th>Cliente</th>
                    <th>N° Venta</th>
                    @if(auth()->user()->isAdmin())
                    <th>Cobrador Asignado</th>
                    @endif
                    <th class="num">Total Venta</th>
                    <th class="num">Cuota Sugerida</th>
                    <th class="num">Saldo Pendiente</th>
                    <th>Frecuencia</th>
                    <th class="text-end">Acción</th>
                </tr>
            </thead>
            <tbody>
                @forelse($ventas as $venta)
                <tr>
                    <td>
                        <a href="{{ route('clientes.show', $venta->cliente) }}" class="fw-bold text-dark text-decoration-none hover-primary">
                            {{ $venta->cliente->nombre_completo }}
                        </a>
                        @if($venta->cliente->telefono)
                        <div class="small mt-1">
                            <a href="tel:{{ $venta->cliente->telefono }}" class="text-muted text-decoration-none d-inline-flex align-items-center gap-1">
                                <i class="bi bi-telephone text-primary"></i> {{ $venta->cliente->telefono }}
                            </a>
                        </div>
                        @endif
                    </td>
                    <td>
                        <a href="{{ route('ventas.show', $venta) }}" class="fw-semibold text-primary text-decoration-none">
                            {{ $venta->numero_venta }}
                        </a>
                    </td>
                    @if(auth()->user()->isAdmin())
                    <td>
                        <span class="badge badge-secondary-soft">{{ $venta->cobrador->name }}</span>
                    </td>
                    @endif
                    <td class="num text-muted">${{ number_format($venta->total, 2) }}</td>
                    <td class="num text-dark fw-semibold">${{ number_format($venta->monto_cuota, 2) }}</td>
                    <td class="num text-danger fw-bold fs-6">
                        ${{ number_format($venta->saldo_pendiente, 2) }}
                    </td>
                    <td>
                        <span class="badge badge-warning-soft">
                            {{ ucfirst($venta->frecuencia_pago) }}
                        </span>
                    </td>
                    <td class="text-end">
                        <a href="{{ route('pagos.create', $venta) }}" class="btn btn-sm btn-success px-3">
                            <i class="bi bi-cash me-1"></i> Cobrar
                        </a>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="8">
                        <div class="empty-state">
                            <div class="empty-state-icon">
                                <i class="bi bi-check2-circle text-success"></i>
                            </div>
                            <div class="empty-state-title">¡Sin cuentas pendientes!</div>
                            <div class="empty-state-text">Todas las cuentas por cobrar se encuentran liquidadas al día.</div>
                        </div>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    @if($ventas->hasPages())
    <div class="card-footer bg-white border-top">
        {{ $ventas->links() }}
    </div>
    @endif
</div>
@endsection
