@extends('layouts.app')
@section('title', 'Nuevo Cliente')

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('clientes.index') }}">Clientes</a></li>
    <li class="breadcrumb-item active">Nuevo Cliente</li>
@endsection

@section('content')
<div class="row justify-content-center">
    <div class="col-12 col-lg-8">
        <div class="card mb-0">
            <div class="card-header">
                <div>
                    <h6><i class="bi bi-person-plus-fill me-2 text-primary"></i> Registrar Nuevo Cliente</h6>
                    <p class="text-muted small mb-0">Complete la información personal y fiscal del cliente</p>
                </div>
            </div>
            <div class="card-body p-4">
                <form method="POST" action="{{ route('clientes.store') }}">
                    @csrf
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label">Nombre(s) <span class="text-danger">*</span></label>
                            <input type="text" name="nombre" class="form-control @error('nombre') is-invalid @enderror"
                                   placeholder="Ej. Juan Carlos" value="{{ old('nombre') }}" required>
                            @error('nombre')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Apellidos <span class="text-danger">*</span></label>
                            <input type="text" name="apellido" class="form-control @error('apellido') is-invalid @enderror"
                                   placeholder="Ej. Pérez García" value="{{ old('apellido') }}" required>
                            @error('apellido')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Teléfono Móvil</label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="bi bi-telephone"></i></span>
                                <input type="text" name="telefono" class="form-control" placeholder="Ej. 55 1234 5678" value="{{ old('telefono') }}">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Correo Electrónico</label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="bi bi-envelope"></i></span>
                                <input type="email" name="email" class="form-control" placeholder="cliente@correo.com" value="{{ old('email') }}">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">CURP <span class="text-muted small fw-normal">(18 caracteres)</span></label>
                            <input type="text" name="dui" class="form-control font-monospace text-uppercase"
                                   placeholder="AAAA000000AAAAAA00" value="{{ old('dui') }}" maxlength="18">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">RFC <span class="text-muted small fw-normal">(13 caracteres)</span></label>
                            <input type="text" name="nit" class="form-control font-monospace text-uppercase"
                                   placeholder="AAAA000000AAA" value="{{ old('nit') }}" maxlength="13">
                        </div>
                        <div class="col-12">
                            <label class="form-label">Dirección de Entrega / Domicilio</label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="bi bi-geo-alt"></i></span>
                                <input type="text" name="direccion" class="form-control"
                                       placeholder="Calle, número, colonia, municipio..." value="{{ old('direccion') }}">
                            </div>
                        </div>
                        <div class="col-12">
                            <label class="form-label">Puntos de Referencia para Cobranza</label>
                            <textarea name="referencias" class="form-control" rows="2"
                                      placeholder="Color de fachada, entre calles, frente a tienda...">{{ old('referencias') }}</textarea>
                        </div>
                        @if(auth()->user()->isAdmin())
                        <div class="col-md-6">
                            <label class="form-label">Cobrador Responsable <span class="text-danger">*</span></label>
                            <select name="cobrador_id" class="form-select @error('cobrador_id') is-invalid @enderror" required>
                                <option value="">-- Seleccionar cobrador --</option>
                                @foreach($cobradores as $cobrador)
                                    <option value="{{ $cobrador->id }}" {{ old('cobrador_id') == $cobrador->id ? 'selected' : '' }}>
                                        {{ $cobrador->name }}
                                    </option>
                                @endforeach
                            </select>
                            @error('cobrador_id')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        @else
                        <input type="hidden" name="cobrador_id" value="{{ auth()->id() }}">
                        @endif
                    </div>

                    <div class="d-flex align-items-center justify-content-end gap-2 mt-4 pt-3 border-top">
                        <a href="{{ route('clientes.index') }}" class="btn btn-outline-secondary">Cancelar</a>
                        <button type="submit" class="btn btn-primary px-4">
                            <i class="bi bi-check-lg me-1"></i> Guardar Cliente
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
