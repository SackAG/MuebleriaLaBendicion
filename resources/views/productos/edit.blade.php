@extends('layouts.app')
@section('title', 'Editar Producto')

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('productos.index') }}">Productos</a></li>
    <li class="breadcrumb-item active">Editar</li>
@endsection

@section('content')
<div class="row justify-content-center">
    <div class="col-md-8">
        <div class="card shadow-sm border-0">
            <div class="card-header bg-transparent border-0 pt-4 pb-0">
                <h5 class="fw-bold mb-0"><i class="bi bi-pencil-fill me-1"></i> Editar Producto</h5>
            </div>
            <div class="card-body">
                <form method="POST" action="{{ route('productos.update', $producto) }}" enctype="multipart/form-data">
                    @csrf @method('PUT')
                    <div class="row g-3">
                        <div class="col-md-4">
                            <label class="form-label">Código *</label>
                            <input type="text" name="codigo" class="form-control" value="{{ old('codigo', $producto->codigo) }}" required>
                        </div>
                        <div class="col-md-8">
                            <label class="form-label">Nombre *</label>
                            <input type="text" name="nombre" class="form-control" value="{{ old('nombre', $producto->nombre) }}" required>
                        </div>
                        <div class="col-12">
                            <label class="form-label">Descripción</label>
                            <textarea name="descripcion" class="form-control" rows="2">{{ old('descripcion', $producto->descripcion) }}</textarea>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Categoría</label>
                            <select name="categoria_id" class="form-select">
                                <option value="">-- Sin categoría --</option>
                                @foreach($categorias as $cat)
                                <option value="{{ $cat->id }}" {{ old('categoria_id', $producto->categoria_id) == $cat->id ? 'selected' : '' }}>{{ $cat->nombre }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-3">
                            <label class="form-label">Precio Costo *</label>
                            <div class="input-group">
                                <span class="input-group-text">$</span>
                                <input type="number" name="precio_costo" class="form-control" value="{{ old('precio_costo', $producto->precio_costo) }}" min="0" step="0.01" required>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <label class="form-label">Precio Venta *</label>
                            <div class="input-group">
                                <span class="input-group-text">$</span>
                                <input type="number" name="precio_venta" class="form-control" value="{{ old('precio_venta', $producto->precio_venta) }}" min="0" step="0.01" required>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <label class="form-label">Stock</label>
                            <input type="number" name="stock" class="form-control" value="{{ old('stock', $producto->stock) }}" min="0" required>
                        </div>
                        <div class="col-md-3">
                            <label class="form-label">Stock Mínimo</label>
                            <input type="number" name="stock_minimo" class="form-control" value="{{ old('stock_minimo', $producto->stock_minimo) }}" min="0" required>
                        </div>
                        <div class="col-md-3">
                            <label class="form-label">Estado</label>
                            <select name="activo" class="form-select">
                                <option value="1" {{ old('activo', $producto->activo) ? 'selected' : '' }}>Activo</option>
                                <option value="0" {{ !old('activo', $producto->activo) ? 'selected' : '' }}>Inactivo</option>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Imagen</label>
                            @if($producto->imagen)
                            <div class="mb-1"><img src="{{ asset('storage/' . $producto->imagen) }}" height="60" class="rounded"></div>
                            @endif
                            <input type="file" name="imagen" class="form-control" accept="image/*">
                        </div>
                    </div>

                    <div class="d-flex gap-2 mt-4">
                        <button type="submit" class="btn text-white" style="background:#4a1942;">
                            <i class="bi bi-check-lg me-1"></i> Actualizar
                        </button>
                        <a href="{{ route('productos.show', $producto) }}" class="btn btn-outline-secondary">Cancelar</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
