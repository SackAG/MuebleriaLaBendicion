@extends('layouts.app')
@section('title', 'Pagos Pendientes')

@section('breadcrumb')
    <li class="breadcrumb-item active">Pagos Pendientes</li>
@endsection

@section('content')
<div class="d-flex align-items-center justify-content-between mb-3">
    <h5 class="fw-bold mb-0"><i class="bi bi-hourglass-split me-1 text-warning"></i> Cuentas por Cobrar</h5>
    <span class="text-muted small">Total: <strong class="text-danger">${{ number_format($ventas->sum('saldo_pendiente'), 2) }}</strong></span>
</div>

<div class="card shadow-sm border-0">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead>
                    <tr>
                        <th>Cliente</th>
                        <th>Venta</th>
                        @if(auth()->user()->isAdmin())<th>Cobrador</th>@endif
                        <th>Total Venta</th>
                        <th>Cuota</th>
                        <th>Saldo</th>
                        <th>Frecuencia</th>
                        <th>Acción</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($ventas as $venta)
                    <tr>
                        <td>
                            <a href="{{ route('clientes.show', $venta->cliente) }}" class="fw-semibold text-decoration-none">
                                {{ $venta->cliente->nombre_completo }}
                            </a>
                            @if($venta->cliente->telefono)
                            <div class="text-muted" style="font-size:.75rem;"><i class="bi bi-telephone me-1"></i>{{ $venta->cliente->telefono }}</div>
                            @endif
                        </td>
                        <td><a href="{{ route('ventas.show', $venta) }}" class="text-decoration-none">{{ $venta->numero_venta }}</a></td>
                        @if(auth()->user()->isAdmin())
                        <td><span class="badge bg-secondary">{{ $venta->cobrador->name }}</span></td>
                        @endif
                        <td>${{ number_format($venta->total, 2) }}</td>
                        <td>${{ number_format($venta->monto_cuota, 2) }}</td>
                        <td>
                            <span class="fw-bold {{ $venta->saldo_pendiente > 0 ? 'text-danger' : 'text-success' }}">
                                ${{ number_format($venta->saldo_pendiente, 2) }}
                            </span>
                        </td>
                        <td>{{ ucfirst($venta->frecuencia_pago) }}</td>
                        <td>
                            <a href="{{ route('pagos.create', $venta) }}" class="btn btn-sm btn-success">
                                <i class="bi bi-cash me-1"></i> Cobrar
                            </a>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="8" class="text-center text-muted py-4">
                            <i class="bi bi-emoji-smile fs-2 d-block mb-2 text-success"></i>
                            ¡Sin pagos pendientes!
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="p-3">{{ $ventas->links() }}</div>
    </div>
</div>
@endsection
