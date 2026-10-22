@extends('layouts.app')
@section('title', 'Proveedores')
@section('breadcrumb')
    <li class="breadcrumb-item active">Proveedores</li>
@endsection

@section('content')
<div class="d-flex justify-content-between align-items-center mb-3">
    <h5 class="fw-bold mb-0"><i class="bi bi-building me-1"></i> Proveedores</h5>
    <a href="{{ route('proveedores.create') }}" class="btn btn-sm text-white" style="background:#4a1942;">
        <i class="bi bi-plus-lg me-1"></i> Nuevo
    </a>
</div>
<div class="card shadow-sm border-0">
    <div class="card-body">
        <form method="GET" class="row g-2 mb-3">
            <div class="col-md-4">
                <div class="input-group input-group-sm">
                    <span class="input-group-text"><i class="bi bi-search"></i></span>
                    <input type="text" name="search" class="form-control" placeholder="Buscar..." value="{{ $search }}">
                </div>
            </div>
            <div class="col-auto">
                <button type="submit" class="btn btn-sm btn-primary">Buscar</button>
                @if($search)<a href="{{ route('proveedores.index') }}" class="btn btn-sm btn-outline-secondary">Limpiar</a>@endif
            </div>
        </form>
        <div class="table-responsive">
            <table class="table table-hover align-middle">
                <thead>
                    <tr><th>Nombre</th><th>Contacto</th><th>Teléfono</th><th>Email</th><th>Estado</th><th class="text-end">Acciones</th></tr>
                </thead>
                <tbody>
                    @forelse($proveedores as $prov)
                    <tr>
                        <td class="fw-semibold">{{ $prov->nombre }}</td>
                        <td>{{ $prov->contacto ?? '-' }}</td>
                        <td>{{ $prov->telefono ?? '-' }}</td>
                        <td>{{ $prov->email ?? '-' }}</td>
                        <td><span class="badge {{ $prov->activo ? 'bg-success' : 'bg-secondary' }}">{{ $prov->activo ? 'Activo' : 'Inactivo' }}</span></td>
                        <td class="text-end">
                            <a href="{{ route('proveedores.edit', $prov) }}" class="btn btn-sm btn-outline-warning"><i class="bi bi-pencil"></i></a>
                            <form method="POST" action="{{ route('proveedores.destroy', $prov) }}" class="d-inline" onsubmit="return confirm('¿Eliminar?')">
                                @csrf @method('DELETE')
                                <button class="btn btn-sm btn-outline-danger"><i class="bi bi-trash"></i></button>
                            </form>
                        </td>
                    </tr>
                    @empty
                    <tr><td colspan="6" class="text-center text-muted py-4">No se encontraron proveedores</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        {{ $proveedores->appends(request()->query())->links() }}
    </div>
</div>
@endsection
