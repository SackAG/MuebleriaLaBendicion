@extends('layouts.app')
@section('title', 'Historial de Cobros')

@section('breadcrumb')
    <li class="breadcrumb-item active">Historial de Cobros</li>
@endsection

@section('content')
<div class="d-flex align-items-center justify-content-between mb-3">
    <h5 class="fw-bold mb-0"><i class="bi bi-cash-stack me-1"></i> Historial de Cobros</h5>
</div>

<div class="card shadow-sm border-0">
    <div class="card-body">
        <form method="GET" class="row g-2 mb-3">
            <div class="col-md-4">
                <div class="input-group input-group-sm">
                    <span class="input-group-text"><i class="bi bi-search"></i></span>
                    <input type="text" name="search" class="form-control" placeholder="Cliente o N° recibo..." value="{{ $search }}">
                </div>
            </div>
            <div class="col-md-3">
                <input type="date" name="fecha" class="form-control form-control-sm" value="{{ $fecha }}">
            </div>
            <div class="col-auto">
                <button type="submit" class="btn btn-sm btn-primary">Filtrar</button>
                <a href="{{ route('pagos.index') }}" class="btn btn-sm btn-outline-secondary">Limpiar</a>
            </div>
        </form>

        <div class="table-responsive">
            <table class="table table-hover align-middle">
                <thead>
                    <tr>
                        <th>Recibo</th>
                        <th>Fecha</th>
                        <th>Cliente</th>
                        <th>Venta</th>
                        @if(auth()->user()->isAdmin())<th>Cobrador</th>@endif
                        <th>Monto</th>
                        <th>Mora</th>
                        <th>Total</th>
                        <th>Método</th>
                        @if(auth()->user()->isAdmin())<th></th>@endif
                    </tr>
                </thead>
                <tbody>
                    @forelse($pagos as $pago)
                    <tr>
                        <td><a href="{{ route('pagos.show', $pago) }}" class="text-decoration-none fw-semibold">{{ $pago->numero_recibo }}</a></td>
                        <td>{{ $pago->fecha_pago->format('d/m/Y') }}</td>
                        <td>{{ $pago->venta->cliente->nombre_completo }}</td>
                        <td><a href="{{ route('ventas.show', $pago->venta) }}" class="text-decoration-none">{{ $pago->venta->numero_venta }}</a></td>
                        @if(auth()->user()->isAdmin())
                        <td><span class="badge bg-secondary">{{ $pago->cobrador->name }}</span></td>
                        @endif
                        <td>${{ number_format($pago->monto, 2) }}</td>
                        <td class="{{ $pago->mora > 0 ? 'text-warning' : 'text-muted' }}">${{ number_format($pago->mora, 2) }}</td>
                        <td class="text-success fw-bold">${{ number_format($pago->total_cobrado, 2) }}</td>
                        <td>{{ ucfirst($pago->metodo_pago) }}</td>
                        @if(auth()->user()->isAdmin())
                        <td>
                            <form method="POST" action="{{ route('pagos.destroy', $pago) }}" onsubmit="return confirm('¿Eliminar pago?')">
                                @csrf @method('DELETE')
                                <button class="btn btn-sm btn-outline-danger"><i class="bi bi-trash"></i></button>
                            </form>
                        </td>
                        @endif
                    </tr>
                    @empty
                    <tr>
                        <td colspan="10" class="text-center text-muted py-4">
                            <i class="bi bi-cash fs-2 d-block mb-2"></i>
                            No se encontraron cobros
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{ $pagos->appends(request()->query())->links() }}
    </div>
</div>
@endsection
