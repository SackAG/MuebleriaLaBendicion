@extends('layouts.app')
@section('title', 'Historial de Cobros')

@section('breadcrumb')
    <li class="breadcrumb-item active">Historial de Cobros</li>
@endsection

@section('content')
<div class="page-header">
    <div>
        <h1 class="page-title">
            <i class="bi bi-cash-stack text-primary"></i>
            Historial de Cobros y Recibos
        </h1>
        <p class="page-subtitle">Registro cronológico de pagos, abonos y cuotas cobradas</p>
    </div>
</div>

<!-- Filter card -->
<div class="filter-card">
    <form method="GET" class="row g-2 align-items-center">
        <div class="col-12 col-md-5">
            <div class="input-group input-group-sm">
                <span class="input-group-text"><i class="bi bi-search"></i></span>
                <input type="text" name="search" class="form-control" placeholder="Buscar por cliente o N° de recibo..." value="{{ $search }}">
            </div>
        </div>
        <div class="col-12 col-sm-6 col-md-3">
            <input type="date" name="fecha" class="form-control form-control-sm" value="{{ $fecha }}">
        </div>
        <div class="col-auto ms-auto d-flex gap-2">
            <button type="submit" class="btn btn-sm btn-primary">
                <i class="bi bi-funnel me-1"></i> Filtrar
            </button>
            @if($search || $fecha)
            <a href="{{ route('pagos.index') }}" class="btn btn-sm btn-outline-secondary">
                <i class="bi bi-x-circle me-1"></i> Limpiar
            </a>
            @endif
        </div>
    </form>
</div>

<!-- Payments table -->
<div class="card mb-0">
    <div class="table-responsive">
        <table class="table align-middle">
            <thead>
                <tr>
                    <th>Recibo</th>
                    <th>Fecha</th>
                    <th>Cliente</th>
                    <th>N° Venta</th>
                    @if(auth()->user()->isAdmin())
                    <th>Cobrador</th>
                    @endif
                    <th class="num">Monto</th>
                    <th class="num">Mora</th>
                    <th class="num">Total Cobrado</th>
                    <th>Método</th>
                    <th class="text-end">Acciones</th>
                </tr>
            </thead>
            <tbody>
                @forelse($pagos as $pago)
                <tr>
                    <td>
                        <a href="{{ route('pagos.show', $pago) }}" class="fw-bold text-primary text-decoration-none">
                            {{ $pago->numero_recibo }}
                        </a>
                    </td>
                    <td class="text-secondary small">{{ $pago->fecha_pago->format('d/m/Y') }}</td>
                    <td>
                        <a href="{{ route('clientes.show', $pago->venta->cliente) }}" class="fw-semibold text-dark text-decoration-none hover-primary">
                            {{ $pago->venta->cliente->nombre_completo }}
                        </a>
                    </td>
                    <td>
                        <a href="{{ route('ventas.show', $pago->venta) }}" class="text-secondary small text-decoration-none font-monospace">
                            {{ $pago->venta->numero_venta }}
                        </a>
                    </td>
                    @if(auth()->user()->isAdmin())
                    <td>
                        <span class="badge badge-secondary-soft">{{ $pago->cobrador->name }}</span>
                    </td>
                    @endif
                    <td class="num">${{ number_format($pago->monto, 2) }}</td>
                    <td class="num {{ $pago->mora > 0 ? 'text-warning fw-bold' : 'text-muted' }}">
                        ${{ number_format($pago->mora, 2) }}
                    </td>
                    <td class="num text-success fw-bold">${{ number_format($pago->total_cobrado, 2) }}</td>
                    <td>
                        <span class="badge badge-secondary-soft">{{ ucfirst($pago->metodo_pago) }}</span>
                    </td>
                    <td class="text-end">
                        <div class="d-inline-flex gap-1">
                            <a href="{{ route('pagos.show', $pago) }}" class="btn-action action-primary" title="Ver recibo">
                                <i class="bi bi-eye"></i>
                            </a>
                            @if(auth()->user()->isAdmin())
                            <form method="POST" action="{{ route('pagos.destroy', $pago) }}" class="d-inline"
                                  onsubmit="return confirm('¿Está seguro de eliminar este pago y restaurar el saldo pendiente?')">
                                @csrf @method('DELETE')
                                <button type="submit" class="btn-action action-danger" title="Eliminar recibo">
                                    <i class="bi bi-trash"></i>
                                </button>
                            </form>
                            @endif
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="10">
                        <div class="empty-state">
                            <div class="empty-state-icon">
                                <i class="bi bi-cash"></i>
                            </div>
                            <div class="empty-state-title">No se encontraron cobros registrados</div>
                            <div class="empty-state-text">Intente ajustando la fecha o los términos de búsqueda.</div>
                        </div>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    @if($pagos->hasPages())
    <div class="card-footer bg-white border-top">
        {{ $pagos->appends(request()->query())->links() }}
    </div>
    @endif
</div>
@endsection
