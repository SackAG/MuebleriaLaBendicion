@extends('layouts.app')
@section('title', 'Usuarios / Cobradores')
@section('breadcrumb')
    <li class="breadcrumb-item active">Usuarios</li>
@endsection

@section('content')
<div class="d-flex justify-content-between align-items-center mb-3">
    <h5 class="fw-bold mb-0"><i class="bi bi-person-badge-fill me-1"></i> Usuarios y Cobradores</h5>
    <a href="{{ route('usuarios.create') }}" class="btn btn-sm text-white" style="background:#4a1942;">
        <i class="bi bi-plus-lg me-1"></i> Nuevo Usuario
    </a>
</div>

<div class="card shadow-sm border-0">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead>
                    <tr><th>Nombre</th><th>Email</th><th>Rol</th><th>Teléfono</th><th>Clientes</th><th>Estado</th><th class="text-end">Acciones</th></tr>
                </thead>
                <tbody>
                    @forelse($usuarios as $user)
                    <tr>
                        <td class="fw-semibold">
                            {{ $user->name }}
                            @if($user->id === auth()->id())
                            <span class="badge bg-primary ms-1" style="font-size:.65rem;">Tú</span>
                            @endif
                        </td>
                        <td>{{ $user->email }}</td>
                        <td>
                            <span class="badge {{ $user->role->nombre === 'admin' ? 'bg-danger' : 'bg-info text-dark' }}">
                                {{ ucfirst($user->role->nombre) }}
                            </span>
                        </td>
                        <td>{{ $user->telefono ?? '-' }}</td>
                        <td>{{ $user->clientes_count }}</td>
                        <td><span class="badge {{ $user->activo ? 'bg-success' : 'bg-secondary' }}">{{ $user->activo ? 'Activo' : 'Inactivo' }}</span></td>
                        <td class="text-end">
                            <a href="{{ route('usuarios.edit', $user) }}" class="btn btn-sm btn-outline-warning"><i class="bi bi-pencil"></i></a>
                            @if($user->id !== auth()->id())
                            <form method="POST" action="{{ route('usuarios.destroy', $user) }}" class="d-inline" onsubmit="return confirm('¿Eliminar usuario?')">
                                @csrf @method('DELETE')
                                <button class="btn btn-sm btn-outline-danger"><i class="bi bi-trash"></i></button>
                            </form>
                            @endif
                        </td>
                    </tr>
                    @empty
                    <tr><td colspan="7" class="text-center text-muted py-4">Sin usuarios</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="p-3">{{ $usuarios->links() }}</div>
    </div>
</div>
@endsection
