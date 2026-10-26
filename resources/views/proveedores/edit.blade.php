@extends('layouts.app')
@section('title', 'Editar Proveedor')

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('proveedores.index') }}">Proveedores</a></li>
    <li class="breadcrumb-item active">Editar {{ $proveedor->nombre }}</li>
@endsection

@section('content')
<div class="row justify-content-center">
    <div class="col-12 col-lg-7">
        <div class="card mb-0">
            <div class="card-header">
                <div>
                    <h6><i class="bi bi-pencil-square me-2 text-primary"></i> Editar Proveedor</h6>
                    <p class="text-muted small mb-0">{{ $proveedor->nombre }}</p>
                </div>
            </div>
            <div class="card-body p-4">
                <form method="POST" action="{{ route('proveedores.update', $proveedor) }}">
                    @csrf @method('PUT')
                    <div class="row g-3">
                        <div class="col-12">
                            <label class="form-label">Nombre de la Empresa / Razón Social <span class="text-danger">*</span></label>
                            <input type="text" name="nombre" class="form-control @error('nombre') is-invalid @enderror"
                                   value="{{ old('nombre', $proveedor->nombre) }}" required>
                            @error('nombre')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Persona de Contacto</label>
                            <input type="text" name="contacto" class="form-control" value="{{ old('contacto', $proveedor->contacto) }}">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Teléfono de Oficina</label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="bi bi-telephone"></i></span>
                                <input type="text" name="telefono" class="form-control" value="{{ old('telefono', $proveedor->telefono) }}">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Correo Electrónico</label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="bi bi-envelope"></i></span>
                                <input type="email" name="email" class="form-control" value="{{ old('email', $proveedor->email) }}">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Dirección</label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="bi bi-geo-alt"></i></span>
                                <input type="text" name="direccion" class="form-control" value="{{ old('direccion', $proveedor->direccion) }}">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Estado</label>
                            <select name="activo" class="form-select">
                                <option value="1" {{ old('activo', $proveedor->activo) ? 'selected' : '' }}>Activo</option>
                                <option value="0" {{ !old('activo', $proveedor->activo) ? 'selected' : '' }}>Inactivo</option>
                            </select>
                        </div>
                        <div class="col-12">
                            <label class="form-label">Notas Adicionales</label>
                            <textarea name="notas" class="form-control" rows="2">{{ old('notas', $proveedor->notas) }}</textarea>
                        </div>
                    </div>

                    <div class="d-flex align-items-center justify-content-end gap-2 mt-4 pt-3 border-top">
                        <a href="{{ route('proveedores.index') }}" class="btn btn-outline-secondary">Cancelar</a>
                        <button type="submit" class="btn btn-primary px-4">
                            <i class="bi bi-check-lg me-1"></i> Actualizar Proveedor
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
