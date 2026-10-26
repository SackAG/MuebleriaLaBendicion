@extends('layouts.app')
@section('title', 'Editar Usuario')
@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('usuarios.index') }}">Usuarios</a></li>
    <li class="breadcrumb-item active">Editar {{ $usuario->name }}</li>
@endsection

@section('content')
<div class="row justify-content-center">
    <div class="col-12 col-lg-7">
        <div class="card mb-0">
            <div class="card-header">
                <div>
                    <h6><i class="bi bi-pencil-square me-2 text-primary"></i> Editar Usuario</h6>
                    <p class="text-muted small mb-0">{{ $usuario->name }}</p>
                </div>
            </div>
            <div class="card-body p-4">
                <form method="POST" action="{{ route('usuarios.update', $usuario) }}">
                    @csrf @method('PUT')
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label">Nombre Completo <span class="text-danger">*</span></label>
                            <input type="text" name="name" class="form-control" value="{{ old('name', $usuario->name) }}" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Correo Electrónico <span class="text-danger">*</span></label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="bi bi-envelope"></i></span>
                                <input type="email" name="email" class="form-control @error('email') is-invalid @enderror"
                                       value="{{ old('email', $usuario->email) }}" required>
                            </div>
                            @error('email')<div class="text-danger small mt-1">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Nueva Contraseña <small class="text-muted fw-normal">(opcional)</small></label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="bi bi-lock"></i></span>
                                <input type="password" name="password" class="form-control @error('password') is-invalid @enderror"
                                       placeholder="Dejar en blanco para mantener actual" minlength="8">
                            </div>
                            @error('password')<div class="text-danger small mt-1">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Confirmar Nueva Contraseña</label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="bi bi-lock-fill"></i></span>
                                <input type="password" name="password_confirmation" class="form-control"
                                       placeholder="Confirmar solo si la cambia">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Rol en el Sistema <span class="text-danger">*</span></label>
                            <select name="role_id" class="form-select" required>
                                @foreach($roles as $role)
                                <option value="{{ $role->id }}" {{ old('role_id', $usuario->role_id) == $role->id ? 'selected' : '' }}>
                                    {{ ucfirst($role->nombre) }}
                                </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-3">
                            <label class="form-label">Teléfono</label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="bi bi-telephone"></i></span>
                                <input type="text" name="telefono" class="form-control" value="{{ old('telefono', $usuario->telefono) }}">
                            </div>
                        </div>
                        <div class="col-md-3">
                            <label class="form-label">Estado de la Cuenta</label>
                            <select name="activo" class="form-select">
                                <option value="1" {{ old('activo', $usuario->activo) ? 'selected' : '' }}>Activo</option>
                                <option value="0" {{ !old('activo', $usuario->activo) ? 'selected' : '' }}>Inactivo</option>
                            </select>
                        </div>
                    </div>

                    <div class="d-flex align-items-center justify-content-end gap-2 mt-4 pt-3 border-top">
                        <a href="{{ route('usuarios.index') }}" class="btn btn-outline-secondary">Cancelar</a>
                        <button type="submit" class="btn btn-primary px-4">
                            <i class="bi bi-check-lg me-1"></i> Actualizar Usuario
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
