@extends('layouts.app')
@section('title', $producto->nombre)

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('productos.index') }}">Productos</a></li>
    <li class="breadcrumb-item active">{{ $producto->nombre }}</li>
@endsection

@section('content')
<div class="row g-4">
    <!-- Product detail card -->
    <div class="col-12 col-lg-4">
        <div class="card mb-0">
            <div class="card-body text-center p-4">
                @if($producto->imagen)
                <div class="mb-3 text-center">
                    <img src="{{ asset('storage/' . $producto->imagen) }}" alt="{{ $producto->nombre }}"
                         class="img-fluid rounded-3 border" style="max-height: 200px; object-fit: contain;">
                </div>
                @else
                <div class="rounded-3 d-inline-flex align-items-center justify-content-center mb-3"
                     style="width: 88px; height: 88px; background: #f1f5f9; color: #64748b; font-size: 2.25rem;">
                    <i class="bi bi-box-seam"></i>
                </div>
                @endif

                <h5 class="fw-bold text-dark mb-1">{{ $producto->nombre }}</h5>
                <div class="mb-3">
                    <code>{{ $producto->codigo }}</code>
                </div>

                <div class="d-flex justify-content-center gap-2 mb-3">
                    <span class="badge {{ $producto->activo ? 'badge-success-soft' : 'badge-secondary-soft' }}">
                        {{ $producto->activo ? 'Activo' : 'Inactivo' }}
                    </span>
                    @if($producto->bajo_stock)
                    <span class="badge badge-warning-soft">
                        <i class="bi bi-exclamation-triangle-fill me-1"></i> Bajo Stock
                    </span>
                    @endif
                </div>

                <!-- Price and Stock KPI summary -->
                <div class="row g-2 text-center p-3 rounded-3 bg-light border mb-3">
                    <div class="col-4 border-end">
                        <div class="text-muted small fw-semibold">Costo</div>
                        <div class="fw-bold text-secondary">${{ number_format($producto->precio_costo, 2) }}</div>
                    </div>
                    <div class="col-4 border-end">
                        <div class="text-muted small fw-semibold">Venta</div>
                        <div class="fw-bold text-success">${{ number_format($producto->precio_venta, 2) }}</div>
                    </div>
                    <div class="col-4">
                        <div class="text-muted small fw-semibold">Stock</div>
                        <div class="fw-bold {{ $producto->stock <= 0 ? 'text-danger' : ($producto->bajo_stock ? 'text-warning' : 'text-success') }}">
                            {{ $producto->stock }}
                        </div>
                    </div>
                </div>

                <div class="text-start border-top pt-3">
                    <div class="mb-2">
                        <span class="text-muted small fw-semibold d-block">Categoría</span>
                        <span class="fw-medium text-dark">{{ $producto->categoria->nombre ?? 'Sin categoría' }}</span>
                    </div>
                    <div class="mb-2">
                        <span class="text-muted small fw-semibold d-block">Stock Mínimo Configurado</span>
                        <span class="fw-medium text-dark">{{ $producto->stock_minimo }} unidades</span>
                    </div>
                    @if($producto->descripcion)
                    <div>
                        <span class="text-muted small fw-semibold d-block">Descripción</span>
                        <p class="text-secondary small mb-0">{{ $producto->descripcion }}</p>
                    </div>
                    @endif
                </div>
            </div>

            <div class="card-footer d-flex gap-2 bg-light">
                <a href="{{ route('productos.edit', $producto) }}" class="btn btn-sm btn-primary flex-fill">
                    <i class="bi bi-pencil me-1"></i> Editar
                </a>
                <form method="POST" action="{{ route('productos.destroy', $producto) }}" class="d-inline"
                      onsubmit="return confirm('¿Está seguro de eliminar este producto?')">
                    @csrf @method('DELETE')
                    <button type="submit" class="btn btn-sm btn-outline-danger" title="Eliminar">
                        <i class="bi bi-trash"></i>
                    </button>
                </form>
            </div>
        </div>
    </div>

    <!-- Sales history for this product -->
    <div class="col-12 col-lg-8">
        <div class="card mb-0">
            <div class="card-header">
                <h6><i class="bi bi-clock-history me-2 text-primary"></i> Últimas Ventas de este Producto</h6>
            </div>
            <div class="table-responsive">
                <table class="table align-middle">
                    <thead>
                        <tr>
                            <th>N° Venta</th>
                            <th>Cliente</th>
                            <th class="num">Cantidad</th>
                            <th class="num">Precio Unit.</th>
                            <th>Fecha</th>
                            <th class="text-end">Acción</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($producto->ventaDetalles->take(15) as $det)
                        <tr>
                            <td>
                                <a href="{{ route('ventas.show', $det->venta) }}" class="fw-bold text-primary text-decoration-none">
                                    {{ $det->venta->numero_venta }}
                                </a>
                            </td>
                            <td class="fw-medium text-dark">{{ $det->venta->cliente->nombre_completo }}</td>
                            <td class="num">{{ $det->cantidad }}</td>
                            <td class="num fw-bold text-dark">${{ number_format($det->precio_unitario, 2) }}</td>
                            <td class="text-secondary small">{{ $det->venta->fecha_venta->format('d/m/Y') }}</td>
                            <td class="text-end">
                                <a href="{{ route('ventas.show', $det->venta) }}" class="btn-action action-primary" title="Ver venta">
                                    <i class="bi bi-eye"></i>
                                </a>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="6" class="text-center py-4 text-muted">
                                No se registran ventas para este producto aún
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection
