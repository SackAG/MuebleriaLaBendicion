@extends('layouts.app')
@section('title', 'Editar Cliente')

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('clientes.index') }}">Clientes</a></li>
    <li class="breadcrumb-item"><a href="{{ route('clientes.show', $cliente) }}">{{ $cliente->nombre_completo }}</a></li>
    <li class="breadcrumb-item active">Editar</li>
@endsection

@section('content')
<div class="row justify-content-center">
    <div class="col-12 col-lg-8">
        <div class="card mb-0">
            <div class="card-header">
                <div>
                    <h6><i class="bi bi-pencil-square me-2 text-primary"></i> Editar Información de Cliente</h6>
                    <p class="text-muted small mb-0">{{ $cliente->nombre_completo }}</p>
                </div>
            </div>
            <div class="card-body p-4">
                <form method="POST" action="{{ route('clientes.update', $cliente) }}">
                    @csrf @method('PUT')
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label">Nombre(s) <span class="text-danger">*</span></label>
                            <input type="text" name="nombre" class="form-control @error('nombre') is-invalid @enderror"
                                   value="{{ old('nombre', $cliente->nombre) }}" required>
                            @error('nombre')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Apellidos <span class="text-danger">*</span></label>
                            <input type="text" name="apellido" class="form-control @error('apellido') is-invalid @enderror"
                                   value="{{ old('apellido', $cliente->apellido) }}" required>
                            @error('apellido')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Teléfono Móvil</label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="bi bi-telephone"></i></span>
                                <input type="text" name="telefono" class="form-control" value="{{ old('telefono', $cliente->telefono) }}">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Correo Electrónico</label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="bi bi-envelope"></i></span>
                                <input type="email" name="email" class="form-control" value="{{ old('email', $cliente->email) }}">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">CURP <span class="text-muted small fw-normal">(18 caracteres)</span></label>
                            <input type="text" name="dui" class="form-control font-monospace text-uppercase"
                                   value="{{ old('dui', $cliente->dui) }}" maxlength="18">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">RFC <span class="text-muted small fw-normal">(13 caracteres)</span></label>
                            <input type="text" name="nit" class="form-control font-monospace text-uppercase"
                                   value="{{ old('nit', $cliente->nit) }}" maxlength="13">
                        </div>
                        <div class="col-12">
                            <label class="form-label">Dirección de Domicilio</label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="bi bi-geo-alt"></i></span>
                                <input type="text" name="direccion" class="form-control" value="{{ old('direccion', $cliente->direccion) }}">
                            </div>
                        </div>
                        <div class="col-12">
                            <label class="form-label">Referencias para Cobranza</label>
                            <textarea name="referencias" class="form-control" rows="2">{{ old('referencias', $cliente->referencias) }}</textarea>
                        </div>
                        @if(auth()->user()->isAdmin())
                        <div class="col-md-6">
                            <label class="form-label">Cobrador Responsable <span class="text-danger">*</span></label>
                            <select name="cobrador_id" class="form-select" required>
                                @foreach($cobradores as $cobrador)
                                    <option value="{{ $cobrador->id }}" {{ old('cobrador_id', $cliente->cobrador_id) == $cobrador->id ? 'selected' : '' }}>
                                        {{ $cobrador->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Estado de la Cuenta</label>
                            <select name="activo" class="form-select">
                                <option value="1" {{ old('activo', $cliente->activo) ? 'selected' : '' }}>Activo</option>
                                <option value="0" {{ !old('activo', $cliente->activo) ? 'selected' : '' }}>Inactivo</option>
                            </select>
                        </div>
                        @else
                        <input type="hidden" name="cobrador_id" value="{{ auth()->id() }}">
                        @endif
                    </div>

                    <div class="d-flex align-items-center justify-content-end gap-2 mt-4 pt-3 border-top">
                        <a href="{{ route('clientes.show', $cliente) }}" class="btn btn-outline-secondary">Cancelar</a>
                        <button type="submit" class="btn btn-primary px-4">
                            <i class="bi bi-check-lg me-1"></i> Actualizar Cliente
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
