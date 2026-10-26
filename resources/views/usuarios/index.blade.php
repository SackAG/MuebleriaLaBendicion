@extends('layouts.app')
@section('title', 'Usuarios / Cobradores')
@section('breadcrumb')
    <li class="breadcrumb-item active">Usuarios</li>
@endsection

@section('content')
<div class="page-header">
    <div>
        <h1 class="page-title">
            <i class="bi bi-person-badge-fill text-primary"></i>
            Equipo y Cobradores
        </h1>
        <p class="page-subtitle">Administración de accesos al sistema, roles y carteras de cobradores</p>
    </div>
    <a href="{{ route('usuarios.create') }}" class="btn btn-primary">
        <i class="bi bi-person-plus-fill"></i>
        <span>Nuevo Usuario</span>
    </a>
</div>

<!-- Table Card -->
<div class="card mb-0">
    <div class="table-responsive">
        <table class="table align-middle">
            <thead>
                <tr>
                    <th>Usuario</th>
                    <th>Email</th>
                    <th>Rol</th>
                    <th>Teléfono</th>
                    <th class="num">Clientes Asignados</th>
                    <th>Estado</th>
                    <th class="text-end">Acciones</th>
                </tr>
            </thead>
            <tbody>
                @forelse($usuarios as $user)
                <tr>
                    <td>
                        <div class="d-flex align-items-center gap-2">
                            <div class="user-avatar" style="width:34px;height:34px;font-size:.8rem;">
                                {{ strtoupper(substr($user->name, 0, 1)) }}
                            </div>
                            <div>
                                <span class="fw-bold text-dark">{{ $user->name }}</span>
                                @if($user->id === auth()->id())
                                <span class="badge badge-info-soft ms-1" style="font-size:.65rem;">Sesión actual</span>
                                @endif
                            </div>
                        </div>
                    </td>
                    <td class="text-secondary small">{{ $user->email }}</td>
                    <td>
                        <span class="badge {{ $user->role->nombre === 'admin' ? 'badge-danger-soft' : 'badge-info-soft' }}">
                            {{ ucfirst($user->role->nombre) }}
                        </span>
                    </td>
                    <td>
                        @if($user->telefono)
                        <a href="tel:{{ $user->telefono }}" class="text-secondary small text-decoration-none d-inline-flex align-items-center gap-1">
                            <i class="bi bi-telephone text-primary"></i> {{ $user->telefono }}
                        </a>
                        @else
                        <span class="text-muted small">—</span>
                        @endif
                    </td>
                    <td class="num text-dark fw-bold">{{ $user->clientes_count }}</td>
                    <td>
                        <span class="badge {{ $user->activo ? 'badge-success-soft' : 'badge-secondary-soft' }}">
                            {{ $user->activo ? 'Activo' : 'Inactivo' }}
                        </span>
                    </td>
                    <td class="text-end">
                        <div class="d-inline-flex gap-1">
                            <a href="{{ route('usuarios.edit', $user) }}" class="btn-action action-warning" title="Editar">
                                <i class="bi bi-pencil"></i>
                            </a>
                            @if($user->id !== auth()->id())
                            <form method="POST" action="{{ route('usuarios.destroy', $user) }}" class="d-inline"
                                  onsubmit="return confirm('¿Está seguro de eliminar este usuario?')">
                                @csrf @method('DELETE')
                                <button type="submit" class="btn-action action-danger" title="Eliminar">
                                    <i class="bi bi-trash"></i>
                                </button>
                            </form>
                            @endif
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="7">
                        <div class="empty-state">
                            <div class="empty-state-icon">
                                <i class="bi bi-people"></i>
                            </div>
                            <div class="empty-state-title">No hay usuarios registrados</div>
                            <div class="empty-state-text">Comience registrando un nuevo miembro del equipo.</div>
                            <a href="{{ route('usuarios.create') }}" class="btn btn-sm btn-primary">
                                <i class="bi bi-plus-lg me-1"></i> Registrar Usuario
                            </a>
                        </div>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    @if($usuarios->hasPages())
    <div class="card-footer bg-white border-top">
        {{ $usuarios->links() }}
    </div>
    @endif
</div>
@endsection
