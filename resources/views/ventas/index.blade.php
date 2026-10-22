@extends('layouts.app')
@section('title', 'Ventas')

@section('breadcrumb')
    <li class="breadcrumb-item active">Ventas</li>
@endsection

@section('content')
<div class="d-flex align-items-center justify-content-between mb-3">
    <h5 class="fw-bold mb-0"><i class="bi bi-cart-fill me-1"></i> Ventas</h5>
    <a href="{{ route('ventas.create') }}" class="btn btn-sm text-white" style="background:#4a1942;">
        <i class="bi bi-plus-lg me-1"></i> Nueva Venta
    </a>
</div>

<div class="card shadow-sm border-0">
    <div class="card-body">
        <form method="GET" class="row g-2 mb-3">
            <div class="col-md-4">
                <div class="input-group input-group-sm">
                    <span class="input-group-text"><i class="bi bi-search"></i></span>
                    <input type="text" name="search" class="form-control" placeholder="Buscar cliente o N° venta..." value="{{ $search }}">
                </div>
            </div>
            <div class="col-md-3">
                <select name="estado" class="form-select form-select-sm">
                    <option value="">Todos los estados</option>
                    <option value="activa" {{ $estado == 'activa' ? 'selected' : '' }}>Activa</option>
                    <option value="liquidada" {{ $estado == 'liquidada' ? 'selected' : '' }}>Liquidada</option>
                    <option value="cancelada" {{ $estado == 'cancelada' ? 'selected' : '' }}>Cancelada</option>
                </select>
            </div>
            <div class="col-auto">
                <button type="submit" class="btn btn-sm btn-primary">Filtrar</button>
                <a href="{{ route('ventas.index') }}" class="btn btn-sm btn-outline-secondary">Limpiar</a>
            </div>
        </form>

        <div class="table-responsive">
            <table class="table table-hover align-middle">
                <thead>
                    <tr>
                        <th>N° Venta</th>
                        <th>Cliente</th>
                        @if(auth()->user()->isAdmin())<th>Cobrador</th>@endif
                        <th>Fecha</th>
                        <th>Total</th>
                        <th>Saldo</th>
                        <th>Tipo</th>
                        <th>Estado</th>
                        <th class="text-end">Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($ventas as $venta)
                    <tr>
                        <td><a href="{{ route('ventas.show', $venta) }}" class="fw-semibold text-decoration-none">{{ $venta->numero_venta }}</a></td>
                        <td>{{ $venta->cliente->nombre_completo }}</td>
                        @if(auth()->user()->isAdmin())
                        <td><span class="badge bg-secondary">{{ $venta->cobrador->name }}</span></td>
                        @endif
                        <td>{{ $venta->fecha_venta->format('d/m/Y') }}</td>
                        <td>${{ number_format($venta->total, 2) }}</td>
                        <td class="{{ $venta->saldo_pendiente > 0 ? 'text-danger fw-semibold' : 'text-success' }}">
                            ${{ number_format($venta->saldo_pendiente, 2) }}
                        </td>
                        <td>
                            @if($venta->tipo_pago == 'credito')
                                <span class="badge bg-info text-dark">Crédito</span>
                            @else
                                <span class="badge bg-success">Contado</span>
                            @endif
                        </td>
                        <td><span class="badge badge-{{ $venta->estado }}">{{ ucfirst($venta->estado) }}</span></td>
                        <td class="text-end">
                            <a href="{{ route('ventas.show', $venta) }}" class="btn btn-sm btn-outline-info" title="Ver"><i class="bi bi-eye"></i></a>
                            @if($venta->estado === 'activa')
                            <a href="{{ route('pagos.create', $venta) }}" class="btn btn-sm btn-success" title="Cobrar">
                                <i class="bi bi-cash"></i>
                            </a>
                            @endif
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="9" class="text-center text-muted py-4">
                            <i class="bi bi-cart fs-2 d-block mb-2"></i>
                            No se encontraron ventas
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{ $ventas->appends(request()->query())->links() }}
    </div>
</div>
@endsection
