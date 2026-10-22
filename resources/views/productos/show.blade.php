@extends('layouts.app')
@section('title', $producto->nombre)

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('productos.index') }}">Productos</a></li>
    <li class="breadcrumb-item active">{{ $producto->nombre }}</li>
@endsection

@section('content')
<div class="row g-3">
    <div class="col-md-4">
        <div class="card shadow-sm border-0">
            <div class="card-body text-center py-4">
                @if($producto->imagen)
                <img src="{{ asset('storage/' . $producto->imagen) }}" class="img-fluid rounded mb-3" style="max-height:180px;">
                @else
                <div class="rounded-3 d-inline-flex align-items-center justify-content-center mb-3"
                     style="width:80px;height:80px;background:#f3e5f5;font-size:2rem;color:#4a1942;">
                    <i class="bi bi-box-seam"></i>
                </div>
                @endif
                <h5 class="fw-bold">{{ $producto->nombre }}</h5>
                <code class="text-muted">{{ $producto->codigo }}</code>
                <div class="mt-2">
                    <span class="badge {{ $producto->activo ? 'bg-success' : 'bg-secondary' }}">{{ $producto->activo ? 'Activo' : 'Inactivo' }}</span>
                    @if($producto->bajo_stock)
                    <span class="badge bg-warning text-dark">Bajo Stock</span>
                    @endif
                </div>
            </div>
            <div class="card-body border-top">
                <div class="row text-center g-2">
                    <div class="col-4">
                        <div class="text-muted small">Costo</div>
                        <div class="fw-bold">${{ number_format($producto->precio_costo, 2) }}</div>
                    </div>
                    <div class="col-4">
                        <div class="text-muted small">Precio</div>
                        <div class="fw-bold text-success">${{ number_format($producto->precio_venta, 2) }}</div>
                    </div>
                    <div class="col-4">
                        <div class="text-muted small">Stock</div>
                        <div class="fw-bold {{ $producto->bajo_stock ? 'text-danger' : 'text-success' }}">{{ $producto->stock }}</div>
                    </div>
                </div>
            </div>
            <div class="card-body border-top">
                <p class="text-muted small mb-1">Categoría</p>
                <p class="fw-semibold mb-0">{{ $producto->categoria->nombre ?? 'Sin categoría' }}</p>
                @if($producto->descripcion)
                <p class="text-muted small mt-2 mb-0">{{ $producto->descripcion }}</p>
                @endif
            </div>
            <div class="card-footer bg-transparent d-flex gap-2">
                <a href="{{ route('productos.edit', $producto) }}" class="btn btn-warning btn-sm flex-fill">
                    <i class="bi bi-pencil me-1"></i> Editar
                </a>
                <form method="POST" action="{{ route('productos.destroy', $producto) }}" onsubmit="return confirm('¿Eliminar?')">
                    @csrf @method('DELETE')
                    <button class="btn btn-outline-danger btn-sm"><i class="bi bi-trash"></i></button>
                </form>
            </div>
        </div>
    </div>

    <div class="col-md-8">
        <div class="card shadow-sm border-0">
            <div class="card-header bg-transparent border-0 pt-3 pb-0">
                <h6 class="fw-bold mb-0"><i class="bi bi-clock-history me-1"></i> Últimas Ventas de este Producto</h6>
            </div>
            <div class="card-body p-0">
                <table class="table mb-0">
                    <thead>
                        <tr>
                            <th>Venta</th>
                            <th>Cliente</th>
                            <th>Cantidad</th>
                            <th>Precio</th>
                            <th>Fecha</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($producto->ventaDetalles->take(15) as $det)
                        <tr>
                            <td><a href="{{ route('ventas.show', $det->venta) }}" class="text-decoration-none">{{ $det->venta->numero_venta }}</a></td>
                            <td>{{ $det->venta->cliente->nombre_completo }}</td>
                            <td>{{ $det->cantidad }}</td>
                            <td>${{ number_format($det->precio_unitario, 2) }}</td>
                            <td>{{ $det->venta->fecha_venta->format('d/m/Y') }}</td>
                        </tr>
                        @empty
                        <tr><td colspan="5" class="text-center text-muted py-3">Sin ventas aún</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection
