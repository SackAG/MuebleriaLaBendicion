@extends('layouts.app')
@section('title', 'Nuevo Proveedor')

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('proveedores.index') }}">Proveedores</a></li>
    <li class="breadcrumb-item active">Nuevo Proveedor</li>
@endsection

@section('content')
<div class="row justify-content-center">
    <div class="col-12 col-lg-7">
        <div class="card mb-0">
            <div class="card-header">
                <div>
                    <h6><i class="bi bi-building-add me-2 text-primary"></i> Registrar Nuevo Proveedor</h6>
                    <p class="text-muted small mb-0">Información comercial y de contacto para compras</p>
                </div>
            </div>
            <div class="card-body p-4">
                <form method="POST" action="{{ route('proveedores.store') }}">
                    @csrf
                    <div class="row g-3">
                        <div class="col-12">
                            <label class="form-label">Nombre de la Empresa / Razón Social <span class="text-danger">*</span></label>
                            <input type="text" name="nombre" class="form-control @error('nombre') is-invalid @enderror"
                                   placeholder="Ej. Muebles Finos de Occidente S.A." value="{{ old('nombre') }}" required>
                            @error('nombre')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Persona de Contacto</label>
                            <input type="text" name="contacto" class="form-control" placeholder="Ej. Lic. Roberto Gómez" value="{{ old('contacto') }}">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Teléfono de Oficina / Ventas</label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="bi bi-telephone"></i></span>
                                <input type="text" name="telefono" class="form-control" placeholder="Ej. 33 9876 5432" value="{{ old('telefono') }}">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Correo Electrónico</label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="bi bi-envelope"></i></span>
                                <input type="email" name="email" class="form-control" placeholder="ventas@proveedor.com" value="{{ old('email') }}">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Dirección / Almacén</label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="bi bi-geo-alt"></i></span>
                                <input type="text" name="direccion" class="form-control" placeholder="Dirección completa" value="{{ old('direccion') }}">
                            </div>
                        </div>
                        <div class="col-12">
                            <label class="form-label">Notas Adicionales</label>
                            <textarea name="notas" class="form-control" rows="2" placeholder="Condiciones de crédito con proveedor, días de entrega...">{{ old('notas') }}</textarea>
                        </div>
                    </div>

                    <div class="d-flex align-items-center justify-content-end gap-2 mt-4 pt-3 border-top">
                        <a href="{{ route('proveedores.index') }}" class="btn btn-outline-secondary">Cancelar</a>
                        <button type="submit" class="btn btn-primary px-4">
                            <i class="bi bi-check-lg me-1"></i> Guardar Proveedor
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
