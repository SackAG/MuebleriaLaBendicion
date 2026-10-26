@extends('layouts.app')
@section('title', 'Proveedores')

@section('breadcrumb')
    <li class="breadcrumb-item active">Proveedores</li>
@endsection

@section('content')
<div class="page-header">
    <div>
        <h1 class="page-title">
            <i class="bi bi-building text-primary"></i>
            Proveedores
        </h1>
        <p class="page-subtitle">Fabricantes, distribuidores y suministros de inventario</p>
    </div>
    <a href="{{ route('proveedores.create') }}" class="btn btn-primary">
        <i class="bi bi-plus-lg"></i>
        <span>Nuevo Proveedor</span>
    </a>
</div>

<!-- Search card -->
<div class="filter-card">
    <form method="GET" class="row g-2 align-items-center">
        <div class="col-12 col-md-5">
            <div class="input-group input-group-sm">
                <span class="input-group-text"><i class="bi bi-search"></i></span>
                <input type="text" name="search" class="form-control" placeholder="Buscar por nombre, contacto o teléfono..." value="{{ $search }}">
            </div>
        </div>
        <div class="col-auto ms-auto d-flex gap-2">
            <button type="submit" class="btn btn-sm btn-primary">
                <i class="bi bi-search me-1"></i> Buscar
            </button>
            @if($search)
            <a href="{{ route('proveedores.index') }}" class="btn btn-sm btn-outline-secondary">
                <i class="bi bi-x-circle me-1"></i> Limpiar
            </a>
            @endif
        </div>
    </form>
</div>

<!-- Table Card -->
<div class="card mb-0">
    <div class="table-responsive">
        <table class="table align-middle">
            <thead>
                <tr>
                    <th>Empresa / Proveedor</th>
                    <th>Contacto</th>
                    <th>Teléfono</th>
                    <th>Correo</th>
                    <th>Estado</th>
                    <th class="text-end">Acciones</th>
                </tr>
            </thead>
            <tbody>
                @forelse($proveedores as $prov)
                <tr>
                    <td>
                        <div class="d-flex align-items-center gap-2">
                            <div class="user-avatar" style="width:34px;height:34px;font-size:.8rem;">
                                <i class="bi bi-building"></i>
                            </div>
                            <span class="fw-bold text-dark">{{ $prov->nombre }}</span>
                        </div>
                    </td>
                    <td class="text-secondary small">{{ $prov->contacto ?? '—' }}</td>
                    <td>
                        @if($prov->telefono)
                        <a href="tel:{{ $prov->telefono }}" class="text-secondary small text-decoration-none d-inline-flex align-items-center gap-1">
                            <i class="bi bi-telephone text-primary"></i> {{ $prov->telefono }}
                        </a>
                        @else
                        <span class="text-muted small">—</span>
                        @endif
                    </td>
                    <td class="text-secondary small">{{ $prov->email ?? '—' }}</td>
                    <td>
                        <span class="badge {{ $prov->activo ? 'badge-success-soft' : 'badge-secondary-soft' }}">
                            {{ $prov->activo ? 'Activo' : 'Inactivo' }}
                        </span>
                    </td>
                    <td class="text-end">
                        <div class="d-inline-flex gap-1">
                            <a href="{{ route('proveedores.edit', $prov) }}" class="btn-action action-warning" title="Editar">
                                <i class="bi bi-pencil"></i>
                            </a>
                            <form method="POST" action="{{ route('proveedores.destroy', $prov) }}" class="d-inline"
                                  onsubmit="return confirm('¿Está seguro de eliminar este proveedor?')">
                                @csrf @method('DELETE')
                                <button type="submit" class="btn-action action-danger" title="Eliminar">
                                    <i class="bi bi-trash"></i>
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="6">
                        <div class="empty-state">
                            <div class="empty-state-icon">
                                <i class="bi bi-building"></i>
                            </div>
                            <div class="empty-state-title">No se encontraron proveedores</div>
                            <div class="empty-state-text">No hay proveedores registrados con los términos ingresados.</div>
                            <a href="{{ route('proveedores.create') }}" class="btn btn-sm btn-primary">
                                <i class="bi bi-plus-lg me-1"></i> Registrar Proveedor
                            </a>
                        </div>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    @if($proveedores->hasPages())
    <div class="card-footer bg-white border-top">
        {{ $proveedores->appends(request()->query())->links() }}
    </div>
    @endif
</div>
@endsection
