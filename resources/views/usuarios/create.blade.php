@extends('layouts.app')
@section('title', 'Nuevo Usuario')
@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('usuarios.index') }}">Usuarios</a></li>
    <li class="breadcrumb-item active">Nuevo Usuario</li>
@endsection

@section('content')
<div class="row justify-content-center">
    <div class="col-12 col-lg-7">
        <div class="card mb-0">
            <div class="card-header">
                <div>
                    <h6><i class="bi bi-person-plus-fill me-2 text-primary"></i> Registrar Nuevo Usuario o Cobrador</h6>
                    <p class="text-muted small mb-0">Asigne el rol y cree las credenciales de acceso</p>
                </div>
            </div>
            <div class="card-body p-4">
                <form method="POST" action="{{ route('usuarios.store') }}">
                    @csrf
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label">Nombre Completo <span class="text-danger">*</span></label>
                            <input type="text" name="name" class="form-control @error('name') is-invalid @enderror"
                                   placeholder="Ej. Carlos Mendoza" value="{{ old('name') }}" required>
                            @error('name')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Correo Electrónico (Login) <span class="text-danger">*</span></label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="bi bi-envelope"></i></span>
                                <input type="email" name="email" class="form-control @error('email') is-invalid @enderror"
                                       placeholder="carlos@muebleria.com" value="{{ old('email') }}" required>
                            </div>
                            @error('email')<div class="text-danger small mt-1">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Contraseña <span class="text-danger">*</span></label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="bi bi-lock"></i></span>
                                <input type="password" name="password" class="form-control @error('password') is-invalid @enderror"
                                       placeholder="Mínimo 8 caracteres" required minlength="8">
                            </div>
                            @error('password')<div class="text-danger small mt-1">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Confirmar Contraseña <span class="text-danger">*</span></label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="bi bi-lock-fill"></i></span>
                                <input type="password" name="password_confirmation" class="form-control"
                                       placeholder="Repita la contraseña" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Rol en el Sistema <span class="text-danger">*</span></label>
                            <select name="role_id" class="form-select @error('role_id') is-invalid @enderror" required>
                                <option value="">-- Seleccionar rol --</option>
                                @foreach($roles as $role)
                                <option value="{{ $role->id }}" {{ old('role_id') == $role->id ? 'selected' : '' }}>
                                    {{ ucfirst($role->nombre) }}
                                </option>
                                @endforeach
                            </select>
                            @error('role_id')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Teléfono de Contacto</label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="bi bi-telephone"></i></span>
                                <input type="text" name="telefono" class="form-control" placeholder="Ej. 55 9876 5432" value="{{ old('telefono') }}">
                            </div>
                        </div>
                    </div>

                    <div class="d-flex align-items-center justify-content-end gap-2 mt-4 pt-3 border-top">
                        <a href="{{ route('usuarios.index') }}" class="btn btn-outline-secondary">Cancelar</a>
                        <button type="submit" class="btn btn-primary px-4">
                            <i class="bi bi-check-lg me-1"></i> Guardar Usuario
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
