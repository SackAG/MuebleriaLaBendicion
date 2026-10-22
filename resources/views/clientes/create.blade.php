@extends('layouts.app')
@section('title', 'Nuevo Cliente')

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('clientes.index') }}">Clientes</a></li>
    <li class="breadcrumb-item active">Nuevo</li>
@endsection

@section('content')
<div class="row justify-content-center">
    <div class="col-md-8">
        <div class="card shadow-sm border-0">
            <div class="card-header bg-transparent border-0 pt-4 pb-0">
                <h5 class="fw-bold mb-0"><i class="bi bi-person-plus-fill me-1"></i> Nuevo Cliente</h5>
            </div>
            <div class="card-body">
                <form method="POST" action="{{ route('clientes.store') }}">
                    @csrf
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label">Nombre <span class="text-danger">*</span></label>
                            <input type="text" name="nombre" class="form-control @error('nombre') is-invalid @enderror"
                                   value="{{ old('nombre') }}" required>
                            @error('nombre')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Apellido <span class="text-danger">*</span></label>
                            <input type="text" name="apellido" class="form-control @error('apellido') is-invalid @enderror"
                                   value="{{ old('apellido') }}" required>
                            @error('apellido')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Teléfono</label>
                            <input type="text" name="telefono" class="form-control" value="{{ old('telefono') }}">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Correo Electrónico</label>
                            <input type="email" name="email" class="form-control" value="{{ old('email') }}">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">DUI</label>
                            <input type="text" name="dui" class="form-control" placeholder="00000000-0" value="{{ old('dui') }}">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">NIT</label>
                            <input type="text" name="nit" class="form-control" value="{{ old('nit') }}">
                        </div>
                        <div class="col-12">
                            <label class="form-label">Dirección</label>
                            <input type="text" name="direccion" class="form-control" value="{{ old('direccion') }}">
                        </div>
                        <div class="col-12">
                            <label class="form-label">Referencias / Puntos de referencia</label>
                            <textarea name="referencias" class="form-control" rows="2">{{ old('referencias') }}</textarea>
                        </div>
                        @if(auth()->user()->isAdmin())
                        <div class="col-md-6">
                            <label class="form-label">Cobrador Asignado <span class="text-danger">*</span></label>
                            <select name="cobrador_id" class="form-select @error('cobrador_id') is-invalid @enderror" required>
                                <option value="">-- Seleccionar --</option>
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

                    <div class="d-flex gap-2 mt-4">
                        <button type="submit" class="btn text-white" style="background:#4a1942;">
                            <i class="bi bi-check-lg me-1"></i> Guardar Cliente
                        </button>
                        <a href="{{ route('clientes.index') }}" class="btn btn-outline-secondary">Cancelar</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
