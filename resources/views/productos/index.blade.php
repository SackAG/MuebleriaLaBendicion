@extends('layouts.app')
@section('title', 'Inventario de Productos')

@section('breadcrumb')
    <li class="breadcrumb-item active">Productos</li>
@endsection

@section('content')
<div class="d-flex align-items-center justify-content-between mb-3">
    <h5 class="fw-bold mb-0"><i class="bi bi-box-seam-fill me-1"></i> Inventario de Productos</h5>
    <a href="{{ route('productos.create') }}" class="btn btn-sm text-white" style="background:#4a1942;">
        <i class="bi bi-plus-lg me-1"></i> Nuevo Producto
    </a>
</div>

<div class="card shadow-sm border-0">
    <div class="card-body">
        <form method="GET" class="row g-2 mb-3">
            <div class="col-md-4">
                <div class="input-group input-group-sm">
                    <span class="input-group-text"><i class="bi bi-search"></i></span>
                    <input type="text" name="search" class="form-control" placeholder="Código o nombre..." value="{{ $search }}">
                </div>
            </div>
            <div class="col-md-3">
                <select name="categoria_id" class="form-select form-select-sm">
                    <option value="">Todas las categorías</option>
                    @foreach($categorias as $cat)
                    <option value="{{ $cat->id }}" {{ $categoria == $cat->id ? 'selected' : '' }}>{{ $cat->nombre }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-auto">
                <div class="form-check form-check-inline mt-1">
                    <input class="form-check-input" type="checkbox" name="bajo_stock" id="bajo_stock" value="1" {{ $bajo_stock ? 'checked' : '' }}>
                    <label class="form-check-label small" for="bajo_stock">Solo bajo stock</label>
                </div>
            </div>
            <div class="col-auto">
                <button type="submit" class="btn btn-sm btn-primary">Filtrar</button>
                <a href="{{ route('productos.index') }}" class="btn btn-sm btn-outline-secondary">Limpiar</a>
            </div>
        </form>

        <div class="table-responsive">
            <table class="table table-hover align-middle">
                <thead>
                    <tr>
                        <th>Código</th>
                        <th>Nombre</th>
                        <th>Categoría</th>
                        <th>Precio Costo</th>
                        <th>Precio Venta</th>
                        <th>Stock</th>
                        <th>Estado</th>
                        <th class="text-end">Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($productos as $prod)
                    <tr class="{{ $prod->bajo_stock ? 'table-warning' : '' }}">
                        <td><code>{{ $prod->codigo }}</code></td>
                        <td>
                            <a href="{{ route('productos.show', $prod) }}" class="fw-semibold text-decoration-none">{{ $prod->nombre }}</a>
                            @if($prod->bajo_stock)
                            <span class="badge bg-warning text-dark ms-1" style="font-size:.65rem;">Bajo Stock</span>
                            @endif
                        </td>
                        <td>{{ $prod->categoria->nombre ?? '-' }}</td>
                        <td>${{ number_format($prod->precio_costo, 2) }}</td>
                        <td class="fw-semibold">${{ number_format($prod->precio_venta, 2) }}</td>
                        <td>
                            <span class="badge {{ $prod->stock <= 0 ? 'bg-danger' : ($prod->bajo_stock ? 'bg-warning text-dark' : 'bg-success') }}">
                                {{ $prod->stock }}
                            </span>
                        </td>
                        <td>
                            <span class="badge {{ $prod->activo ? 'bg-success' : 'bg-secondary' }}">
                                {{ $prod->activo ? 'Activo' : 'Inactivo' }}
                            </span>
                        </td>
                        <td class="text-end">
                            <a href="{{ route('productos.show', $prod) }}" class="btn btn-sm btn-outline-info" title="Ver"><i class="bi bi-eye"></i></a>
                            <a href="{{ route('productos.edit', $prod) }}" class="btn btn-sm btn-outline-warning" title="Editar"><i class="bi bi-pencil"></i></a>
                            <form method="POST" action="{{ route('productos.destroy', $prod) }}" class="d-inline"
                                  onsubmit="return confirm('¿Eliminar producto?')">
                                @csrf @method('DELETE')
                                <button class="btn btn-sm btn-outline-danger"><i class="bi bi-trash"></i></button>
                            </form>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="8" class="text-center text-muted py-4">
                            <i class="bi bi-box-seam fs-2 d-block mb-2"></i>
                            No se encontraron productos
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{ $productos->appends(request()->query())->links() }}
    </div>
</div>
@endsection
