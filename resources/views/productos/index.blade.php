@extends('layouts.app')
@section('title', 'Inventario de Productos')

@section('breadcrumb')
    <li class="breadcrumb-item active">Productos</li>
@endsection

@section('content')
<div class="page-header">
    <div>
        <h1 class="page-title">
            <i class="bi bi-box-seam-fill text-primary"></i>
            Inventario de Productos
        </h1>
        <p class="page-subtitle">Control de existencias, catálogo y precios de venta</p>
    </div>
    <a href="{{ route('productos.create') }}" class="btn btn-primary">
        <i class="bi bi-plus-lg"></i>
        <span>Nuevo Producto</span>
    </a>
</div>

<!-- Filter card -->
<div class="filter-card">
    <form method="GET" class="row g-2 align-items-center">
        <div class="col-12 col-md-4">
            <div class="input-group input-group-sm">
                <span class="input-group-text"><i class="bi bi-search"></i></span>
                <input type="text" name="search" class="form-control" placeholder="Buscar por código o nombre..." value="{{ $search }}">
            </div>
        </div>
        <div class="col-12 col-sm-6 col-md-3">
            <select name="categoria_id" class="form-select form-select-sm">
                <option value="">Todas las categorías</option>
                @foreach($categorias as $cat)
                <option value="{{ $cat->id }}" {{ $categoria == $cat->id ? 'selected' : '' }}>{{ $cat->nombre }}</option>
                @endforeach
            </select>
        </div>
        <div class="col-auto">
            <div class="form-check form-check-inline mb-0">
                <input class="form-check-input" type="checkbox" name="bajo_stock" id="bajo_stock" value="1" {{ $bajo_stock ? 'checked' : '' }}>
                <label class="form-check-label small user-select-none" for="bajo_stock">
                    <span class="badge badge-warning-soft">Solo bajo stock</span>
                </label>
            </div>
        </div>
        <div class="col-auto ms-auto d-flex gap-2">
            <button type="submit" class="btn btn-sm btn-primary">
                <i class="bi bi-funnel me-1"></i> Filtrar
            </button>
            @if($search || $categoria || $bajo_stock)
            <a href="{{ route('productos.index') }}" class="btn btn-sm btn-outline-secondary">
                <i class="bi bi-x-circle me-1"></i> Limpiar
            </a>
            @endif
        </div>
    </form>
</div>

<!-- Table Card -->
<div class="card mb-0">
    <div class="table-responsive">
        <table class="table align-middle">
            <thead>
                <tr>
                    <th>Código</th>
                    <th>Producto</th>
                    <th>Categoría</th>
                    <th class="num">Costo</th>
                    <th class="num">Precio Venta</th>
                    <th class="num">Stock</th>
                    <th>Estado</th>
                    <th class="text-end">Acciones</th>
                </tr>
            </thead>
            <tbody>
                @forelse($productos as $prod)
                <tr>
                    <td><code>{{ $prod->codigo }}</code></td>
                    <td>
                        <div class="d-flex align-items-center gap-2">
                            <a href="{{ route('productos.show', $prod) }}" class="fw-bold text-dark text-decoration-none hover-primary">
                                {{ $prod->nombre }}
                            </a>
                            @if($prod->bajo_stock)
                            <span class="badge badge-warning-soft" style="font-size:.65rem;">
                                <i class="bi bi-exclamation-triangle-fill me-1"></i>Bajo stock
                            </span>
                            @endif
                        </div>
                    </td>
                    <td class="text-secondary small">{{ $prod->categoria->nombre ?? '—' }}</td>
                    <td class="num text-muted">${{ number_format($prod->precio_costo, 2) }}</td>
                    <td class="num text-dark fw-bold">${{ number_format($prod->precio_venta, 2) }}</td>
                    <td class="num">
                        @if($prod->stock <= 0)
                            <span class="badge badge-danger-soft">{{ $prod->stock }}</span>
                        @elseif($prod->bajo_stock)
                            <span class="badge badge-warning-soft">{{ $prod->stock }}</span>
                        @else
                            <span class="badge badge-success-soft">{{ $prod->stock }}</span>
                        @endif
                    </td>
                    <td>
                        <span class="badge {{ $prod->activo ? 'badge-success-soft' : 'badge-secondary-soft' }}">
                            {{ $prod->activo ? 'Activo' : 'Inactivo' }}
                        </span>
                    </td>
                    <td class="text-end">
                        <div class="d-inline-flex gap-1">
                            <a href="{{ route('productos.show', $prod) }}" class="btn-action action-primary" title="Ver detalle">
                                <i class="bi bi-eye"></i>
                            </a>
                            <a href="{{ route('productos.edit', $prod) }}" class="btn-action action-warning" title="Editar producto">
                                <i class="bi bi-pencil"></i>
                            </a>
                            <form method="POST" action="{{ route('productos.destroy', $prod) }}" class="d-inline"
                                  onsubmit="return confirm('¿Está seguro de eliminar este producto?')">
                                @csrf @method('DELETE')
                                <button type="submit" class="btn-action action-danger" title="Eliminar">
                                    <i class="bi bi-trash"></i>
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="8">
                        <div class="empty-state">
                            <div class="empty-state-icon">
                                <i class="bi bi-box-seam"></i>
                            </div>
                            <div class="empty-state-title">No se encontraron productos</div>
                            <div class="empty-state-text">Intente modificar los filtros o registre un nuevo producto en el catálogo.</div>
                            <a href="{{ route('productos.create') }}" class="btn btn-sm btn-primary">
                                <i class="bi bi-plus-lg me-1"></i> Registrar Producto
                            </a>
                        </div>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    @if($productos->hasPages())
    <div class="card-footer bg-white border-top">
        {{ $productos->appends(request()->query())->links() }}
    </div>
    @endif
</div>
@endsection
