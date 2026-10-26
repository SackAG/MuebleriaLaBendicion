@extends('layouts.app')

@section('title', 'Iniciar Sesión - Mueblería La Bendición')

@section('content')
<div style="min-height: 100vh; display: flex; align-items: center; justify-content: center; background: radial-gradient(circle at 50% 20%, #1e293b 0%, #0f172a 100%); padding: 1.5rem;">

    <div style="width: 100%; max-width: 420px;">

        <!-- Brand header -->
        <div class="text-center mb-4">
            <div style="display: inline-flex; align-items: center; justify-content: center; width: 56px; height: 56px; background: linear-gradient(135deg, #6366f1, #4f46e5); border-radius: 14px; box-shadow: 0 4px 16px rgba(99, 102, 241, 0.4); margin-bottom: 1rem;">
                <i class="bi bi-house-heart-fill" style="color: #ffffff; font-size: 1.65rem;"></i>
            </div>
            <h1 style="font-size: 1.35rem; font-weight: 800; color: #ffffff; margin: 0; letter-spacing: -0.02em;">
                Mueblería La Bendición
            </h1>
            <p style="font-size: 0.8125rem; color: #94a3b8; margin: 0.35rem 0 0;">
                Sistema de Inventario, Ventas y Cobros
            </p>
        </div>

        <!-- Auth Card -->
        <div class="card shadow-lg border-0" style="border-radius: 16px; background: #ffffff;">
            <div class="card-body p-4 p-sm-5">
                <div class="mb-4 text-center">
                    <h2 style="font-size: 1.15rem; font-weight: 700; color: #0f172a; margin: 0;">Bienvenido de nuevo</h2>
                    <p style="font-size: 0.8rem; color: #64748b; margin: 0.25rem 0 0;">Ingrese sus credenciales para acceder</p>
                </div>

                <form method="POST" action="{{ route('login') }}">
                    @csrf

                    <!-- Email -->
                    <div class="mb-3">
                        <label for="email" class="form-label">Correo electrónico</label>
                        <div class="input-group">
                            <span class="input-group-text"><i class="bi bi-envelope"></i></span>
                            <input type="email"
                                   id="email"
                                   name="email"
                                   value="{{ old('email') }}"
                                   placeholder="ejemplo@muebleria.com"
                                   autofocus
                                   required
                                   class="form-control @error('email') is-invalid @enderror"
                                   autocomplete="email">
                        </div>
                        @error('email')
                            <div class="text-danger mt-1" style="font-size: 0.75rem;">
                                <i class="bi bi-exclamation-circle me-1"></i>{{ $message }}
                            </div>
                        @enderror
                    </div>

                    <!-- Password -->
                    <div class="mb-3">
                        <label for="password" class="form-label">Contraseña</label>
                        <div class="input-group">
                            <span class="input-group-text"><i class="bi bi-lock"></i></span>
                            <input type="password"
                                   id="password"
                                   name="password"
                                   placeholder="••••••••"
                                   required
                                   class="form-control @error('password') is-invalid @enderror"
                                   autocomplete="current-password">
                        </div>
                        @error('password')
                            <div class="text-danger mt-1" style="font-size: 0.75rem;">
                                <i class="bi bi-exclamation-circle me-1"></i>{{ $message }}
                            </div>
                        @enderror
                    </div>

                    <!-- Remember -->
                    <div class="mb-4 d-flex align-items-center">
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" id="remember" name="remember" {{ old('remember') ? 'checked' : '' }}>
                            <label class="form-check-label text-secondary small user-select-none" for="remember">
                                Mantener sesión iniciada
                            </label>
                        </div>
                    </div>

                    <!-- Submit -->
                    <button type="submit" class="btn btn-primary w-100 py-2" style="font-size: 0.9rem; font-weight: 700; border-radius: 10px;">
                        <i class="bi bi-box-arrow-in-right me-1"></i> Iniciar Sesión
                    </button>
                </form>

                <div class="text-center mt-4 pt-3 border-top">
                    <span style="font-size: 0.75rem; color: #94a3b8; display: inline-flex; align-items: center; gap: 0.35rem;">
                        <i class="bi bi-shield-check text-success"></i> Acceso restringido a personal autorizado
                    </span>
                </div>
            </div>
        </div>

    </div>
</div>
@endsection
