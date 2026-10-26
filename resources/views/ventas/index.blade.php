@extends('layouts.app')
@section('title', 'Ventas')

@section('breadcrumb')
    <li class="breadcrumb-item active">Ventas</li>
@endsection

@section('content')
<div class="page-header">
    <div>
        <h1 class="page-title">
            <i class="bi bi-cart3 text-primary"></i>
            Control de Ventas
        </h1>
        <p class="page-subtitle">Historial de operaciones, contratos de crédito y ventas de contado</p>
    </div>
    <a href="{{ route('ventas.create') }}" class="btn btn-primary">
        <i class="bi bi-plus-lg"></i>
        <span>Nueva Venta</span>
    </a>
</div>

<!-- Filter card -->
<div class="filter-card">
    <form method="GET" class="row g-2 align-items-center">
        <div class="col-12 col-md-5">
            <div class="input-group input-group-sm">
                <span class="input-group-text"><i class="bi bi-search"></i></span>
                <input type="text" name="search" class="form-control" placeholder="Buscar por cliente o N° de venta..." value="{{ $search }}">
            </div>
        </div>
        <div class="col-12 col-sm-6 col-md-3">
            <select name="estado" class="form-select form-select-sm">
                <option value="">Todos los estados</option>
                <option value="activa" {{ $estado == 'activa' ? 'selected' : '' }}>Activa</option>
                <option value="liquidada" {{ $estado == 'liquidada' ? 'selected' : '' }}>Liquidada</option>
                <option value="cancelada" {{ $estado == 'cancelada' ? 'selected' : '' }}>Cancelada</option>
            </select>
        </div>
        <div class="col-auto ms-auto d-flex gap-2">
            <button type="submit" class="btn btn-sm btn-primary">
                <i class="bi bi-funnel me-1"></i> Filtrar
            </button>
            @if($search || $estado)
            <a href="{{ route('ventas.index') }}" class="btn btn-sm btn-outline-secondary">
                <i class="bi bi-x-circle me-1"></i> Limpiar
            </a>
            @endif
        </div>
    </form>
</div>

<!-- Sales Table -->
<div class="card mb-0">
    <div class="table-responsive">
        <table class="table align-middle">
            <thead>
                <tr>
                    <th>N° Venta</th>
                    <th>Cliente</th>
                    @if(auth()->user()->isAdmin())
                    <th>Cobrador</th>
                    @endif
                    <th>Fecha</th>
                    <th class="num">Total</th>
                    <th class="num">Saldo Pendiente</th>
                    <th>Tipo</th>
                    <th>Estado</th>
                    <th class="text-end">Acciones</th>
                </tr>
            </thead>
            <tbody>
                @forelse($ventas as $venta)
                <tr>
                    <td>
                        <a href="{{ route('ventas.show', $venta) }}" class="fw-bold text-primary text-decoration-none">
                            {{ $venta->numero_venta }}
                        </a>
                    </td>
                    <td>
                        <a href="{{ route('clientes.show', $venta->cliente) }}" class="fw-semibold text-dark text-decoration-none hover-primary">
                            {{ $venta->cliente->nombre_completo }}
                        </a>
                    </td>
                    @if(auth()->user()->isAdmin())
                    <td>
                        <span class="badge badge-secondary-soft">{{ $venta->cobrador->name }}</span>
                    </td>
                    @endif
                    <td class="text-secondary small">{{ $venta->fecha_venta->format('d/m/Y') }}</td>
                    <td class="num">${{ number_format($venta->total, 2) }}</td>
                    <td class="num {{ $venta->saldo_pendiente > 0 ? 'text-danger fw-bold' : 'text-success' }}">
                        ${{ number_format($venta->saldo_pendiente, 2) }}
                    </td>
                    <td>
                        <span class="badge {{ $venta->tipo_pago == 'credito' ? 'badge-info-soft' : 'badge-success-soft' }}">
                            {{ ucfirst($venta->tipo_pago) }}
                        </span>
                    </td>
                    <td>
                        <span class="badge badge-{{ $venta->estado }}">
                            {{ ucfirst($venta->estado) }}
                        </span>
                    </td>
                    <td class="text-end">
                        <div class="d-inline-flex gap-1 align-items-center">
                            <a href="{{ route('ventas.show', $venta) }}" class="btn-action action-primary" title="Ver detalle">
                                <i class="bi bi-eye"></i>
                            </a>
                            @if($venta->estado === 'activa')
                            <a href="{{ route('pagos.create', $venta) }}" class="btn btn-sm btn-success px-2" title="Cobrar cuota">
                                <i class="bi bi-cash me-1"></i> Cobrar
                            </a>
                            @endif
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="9">
                        <div class="empty-state">
                            <div class="empty-state-icon">
                                <i class="bi bi-cart"></i>
                            </div>
                            <div class="empty-state-title">No se encontraron ventas</div>
                            <div class="empty-state-text">Intente con otros criterios o registre una nueva venta en el sistema.</div>
                            <a href="{{ route('ventas.create') }}" class="btn btn-sm btn-primary">
                                <i class="bi bi-plus-lg me-1"></i> Nueva Venta
                            </a>
                        </div>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    @if($ventas->hasPages())
    <div class="card-footer bg-white border-top">
        {{ $ventas->appends(request()->query())->links() }}
    </div>
    @endif
</div>
@endsection
