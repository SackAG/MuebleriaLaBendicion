@extends('layouts.app')
@section('title', 'Nuevo Producto')

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('productos.index') }}">Productos</a></li>
    <li class="breadcrumb-item active">Nuevo Producto</li>
@endsection

@section('content')
<div class="row justify-content-center">
    <div class="col-12 col-lg-8">
        <div class="card mb-0">
            <div class="card-header">
                <div>
                    <h6><i class="bi bi-box-seam me-2 text-primary"></i> Registrar Nuevo Producto</h6>
                    <p class="text-muted small mb-0">Complete la ficha técnica y datos comerciales</p>
                </div>
            </div>
            <div class="card-body p-4">
                <form method="POST" action="{{ route('productos.store') }}" enctype="multipart/form-data">
                    @csrf
                    <div class="row g-3">
                        <div class="col-md-4">
                            <label class="form-label">Código / SKU <span class="text-danger">*</span></label>
                            <input type="text" name="codigo" class="form-control @error('codigo') is-invalid @enderror"
                                   placeholder="Ej. SAL-001" value="{{ old('codigo') }}" required>
                            @error('codigo')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-md-8">
                            <label class="form-label">Nombre del Producto <span class="text-danger">*</span></label>
                            <input type="text" name="nombre" class="form-control @error('nombre') is-invalid @enderror"
                                   placeholder="Ej. Sala Seccional Gris Oxford" value="{{ old('nombre') }}" required>
                            @error('nombre')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-12">
                            <label class="form-label">Descripción o Especificaciones</label>
                            <textarea name="descripcion" class="form-control" rows="3"
                                      placeholder="Materiales, dimensiones, acabados, colores...">{{ old('descripcion') }}</textarea>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Categoría</label>
                            <select name="categoria_id" class="form-select">
                                <option value="">-- Sin categoría --</option>
                                @foreach($categorias as $cat)
                                <option value="{{ $cat->id }}" {{ old('categoria_id') == $cat->id ? 'selected' : '' }}>{{ $cat->nombre }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-3">
                            <label class="form-label">Precio Costo <span class="text-danger">*</span></label>
                            <div class="input-group">
                                <span class="input-group-text">$</span>
                                <input type="number" name="precio_costo" class="form-control @error('precio_costo') is-invalid @enderror"
                                       value="{{ old('precio_costo', 0) }}" min="0" step="0.01" required>
                            </div>
                            @error('precio_costo')<div class="text-danger small mt-1">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-md-3">
                            <label class="form-label">Precio Venta <span class="text-danger">*</span></label>
                            <div class="input-group">
                                <span class="input-group-text">$</span>
                                <input type="number" name="precio_venta" class="form-control @error('precio_venta') is-invalid @enderror"
                                       value="{{ old('precio_venta', 0) }}" min="0" step="0.01" required>
                            </div>
                            @error('precio_venta')<div class="text-danger small mt-1">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Stock Inicial <span class="text-danger">*</span></label>
                            <input type="number" name="stock" class="form-control @error('stock') is-invalid @enderror"
                                   value="{{ old('stock', 0) }}" min="0" required>
                            @error('stock')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Stock Mínimo (Alerta) <span class="text-danger">*</span></label>
                            <input type="number" name="stock_minimo" class="form-control @error('stock_minimo') is-invalid @enderror"
                                   value="{{ old('stock_minimo', 2) }}" min="0" required>
                            @error('stock_minimo')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Imagen</label>
                            <input type="file" name="imagen" class="form-control" accept="image/*">
                        </div>
                    </div>

                    <div class="d-flex align-items-center justify-content-end gap-2 mt-4 pt-3 border-top">
                        <a href="{{ route('productos.index') }}" class="btn btn-outline-secondary">Cancelar</a>
                        <button type="submit" class="btn btn-primary px-4">
                            <i class="bi bi-check-lg me-1"></i> Guardar Producto
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
