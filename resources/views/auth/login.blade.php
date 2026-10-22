@extends('layouts.app')

@section('title', 'Iniciar Sesión - Mueblería La Bendición')

@section('content')
<div class="min-vh-100 d-flex align-items-center justify-content-center" style="background: linear-gradient(135deg, #4a1942 0%, #2d0f29 100%);">
    <div class="card shadow-lg" style="width: 100%; max-width: 420px; border-radius: 16px; border: none;">
        <div class="card-body p-5">
            <!-- Logo/Brand -->
            <div class="text-center mb-4">
                <div class="d-inline-flex align-items-center justify-content-center rounded-3 mb-3"
                     style="width:64px;height:64px;background:#4a1942;">
                    <i class="bi bi-house-heart-fill text-white fs-2"></i>
                </div>
                <h4 class="fw-bold text-dark mb-0">Mueblería La Bendición</h4>
                <p class="text-muted small">Sistema de Inventario y Cobros</p>
            </div>

            <!-- Form -->
            <form method="POST" action="{{ route('login') }}">
                @csrf
                <div class="mb-3">
                    <label for="email" class="form-label fw-semibold">Correo Electrónico</label>
                    <div class="input-group">
                        <span class="input-group-text"><i class="bi bi-envelope-fill text-muted"></i></span>
                        <input type="email"
                               id="email"
                               name="email"
                               class="form-control @error('email') is-invalid @enderror"
                               value="{{ old('email') }}"
                               placeholder="correo@ejemplo.com"
                               autofocus
                               required>
                        @error('email')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <div class="mb-3">
                    <label for="password" class="form-label fw-semibold">Contraseña</label>
                    <div class="input-group">
                        <span class="input-group-text"><i class="bi bi-lock-fill text-muted"></i></span>
                        <input type="password"
                               id="password"
                               name="password"
                               class="form-control @error('password') is-invalid @enderror"
                               placeholder="••••••••"
                               required>
                        @error('password')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <div class="mb-4 d-flex align-items-center justify-content-between">
                    <div class="form-check mb-0">
                        <input type="checkbox" class="form-check-input" id="remember" name="remember">
                        <label class="form-check-label small" for="remember">Recordarme</label>
                    </div>
                </div>

                <button type="submit" class="btn w-100 text-white fw-semibold py-2"
                        style="background:#4a1942; border-color:#4a1942;">
                    <i class="bi bi-box-arrow-in-right me-1"></i> Iniciar Sesión
                </button>
            </form>

            <div class="text-center mt-4">
                <small class="text-muted">
                    <i class="bi bi-shield-check me-1"></i>
                    Acceso solo para personal autorizado
                </small>
            </div>
        </div>
    </div>
</div>
@endsection
