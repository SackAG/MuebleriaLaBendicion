@extends('layouts.app')
@section('title', 'Editar Cliente')

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('clientes.index') }}">Clientes</a></li>
    <li class="breadcrumb-item"><a href="{{ route('clientes.show', $cliente) }}">{{ $cliente->nombre_completo }}</a></li>
    <li class="breadcrumb-item active">Editar</li>
@endsection

@section('content')
<div class="row justify-content-center">
    <div class="col-md-8">
        <div class="card shadow-sm border-0">
            <div class="card-header bg-transparent border-0 pt-4 pb-0">
                <h5 class="fw-bold mb-0"><i class="bi bi-pencil-fill me-1"></i> Editar Cliente</h5>
            </div>
            <div class="card-body">
                <form method="POST" action="{{ route('clientes.update', $cliente) }}">
                    @csrf @method('PUT')
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label">Nombre *</label>
                            <input type="text" name="nombre" class="form-control @error('nombre') is-invalid @enderror"
                                   value="{{ old('nombre', $cliente->nombre) }}" required>
                            @error('nombre')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Apellido *</label>
                            <input type="text" name="apellido" class="form-control @error('apellido') is-invalid @enderror"
                                   value="{{ old('apellido', $cliente->apellido) }}" required>
                            @error('apellido')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Teléfono</label>
                            <input type="text" name="telefono" class="form-control" value="{{ old('telefono', $cliente->telefono) }}">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Email</label>
                            <input type="email" name="email" class="form-control" value="{{ old('email', $cliente->email) }}">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">DUI</label>
                            <input type="text" name="dui" class="form-control" value="{{ old('dui', $cliente->dui) }}">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">NIT</label>
                            <input type="text" name="nit" class="form-control" value="{{ old('nit', $cliente->nit) }}">
                        </div>
                        <div class="col-12">
                            <label class="form-label">Dirección</label>
                            <input type="text" name="direccion" class="form-control" value="{{ old('direccion', $cliente->direccion) }}">
                        </div>
                        <div class="col-12">
                            <label class="form-label">Referencias</label>
                            <textarea name="referencias" class="form-control" rows="2">{{ old('referencias', $cliente->referencias) }}</textarea>
                        </div>
                        @if(auth()->user()->isAdmin())
                        <div class="col-md-6">
                            <label class="form-label">Cobrador Asignado *</label>
                            <select name="cobrador_id" class="form-select" required>
                                @foreach($cobradores as $cobrador)
                                    <option value="{{ $cobrador->id }}" {{ old('cobrador_id', $cliente->cobrador_id) == $cobrador->id ? 'selected' : '' }}>
                                        {{ $cobrador->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Estado</label>
                            <select name="activo" class="form-select">
                                <option value="1" {{ old('activo', $cliente->activo) ? 'selected' : '' }}>Activo</option>
                                <option value="0" {{ !old('activo', $cliente->activo) ? 'selected' : '' }}>Inactivo</option>
                            </select>
                        </div>
                        @else
                        <input type="hidden" name="cobrador_id" value="{{ auth()->id() }}">
                        @endif
                    </div>

                    <div class="d-flex gap-2 mt-4">
                        <button type="submit" class="btn text-white" style="background:#4a1942;">
                            <i class="bi bi-check-lg me-1"></i> Actualizar
                        </button>
                        <a href="{{ route('clientes.show', $cliente) }}" class="btn btn-outline-secondary">Cancelar</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
