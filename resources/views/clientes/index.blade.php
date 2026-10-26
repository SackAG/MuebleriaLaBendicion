@extends('layouts.app')
@section('title', 'Clientes')

@section('breadcrumb')
    <li class="breadcrumb-item active">Clientes</li>
@endsection

@section('content')
<div class="page-header">
    <div>
        <h1 class="page-title">
            <i class="bi bi-people-fill text-primary"></i>
            Directorio de Clientes
        </h1>
        <p class="page-subtitle">Gestión de cartera, contactos y saldos de crédito</p>
    </div>
    <a href="{{ route('clientes.create') }}" class="btn btn-primary">
        <i class="bi bi-person-plus-fill"></i>
        <span>Nuevo Cliente</span>
    </a>
</div>

<!-- Search card -->
<div class="filter-card">
    <form method="GET" class="row g-2 align-items-center">
        <div class="col-12 col-md-6 col-lg-5">
            <div class="input-group input-group-sm">
                <span class="input-group-text"><i class="bi bi-search"></i></span>
                <input type="text" name="search" class="form-control" placeholder="Buscar por nombre, teléfono o CURP..." value="{{ $search }}">
            </div>
        </div>
        <div class="col-auto ms-auto d-flex gap-2">
            <button type="submit" class="btn btn-sm btn-primary">
                <i class="bi bi-search me-1"></i> Buscar
            </button>
            @if($search)
            <a href="{{ route('clientes.index') }}" class="btn btn-sm btn-outline-secondary">
                <i class="bi bi-x-circle me-1"></i> Limpiar
            </a>
            @endif
        </div>
    </form>
</div>

<!-- Customers table -->
<div class="card mb-0">
    <div class="table-responsive">
        <table class="table align-middle">
            <thead>
                <tr>
                    <th>Cliente</th>
                    <th>Teléfono</th>
                    <th>CURP / Documento</th>
                    @if(auth()->user()->isAdmin())
                    <th>Cobrador Asignado</th>
                    @endif
                    <th class="num">Saldo Pendiente</th>
                    <th>Estado</th>
                    <th class="text-end">Acciones</th>
                </tr>
            </thead>
            <tbody>
                @forelse($clientes as $cliente)
                <tr>
                    <td>
                        <div class="d-flex align-items-center gap-2">
                            <div class="user-avatar" style="width:34px;height:34px;font-size:.8rem;">
                                {{ strtoupper(substr($cliente->nombre, 0, 1) . substr($cliente->apellido, 0, 1)) }}
                            </div>
                            <div>
                                <a href="{{ route('clientes.show', $cliente) }}" class="fw-bold text-dark text-decoration-none hover-primary">
                                    {{ $cliente->nombre_completo }}
                                </a>
                                @if($cliente->email)
                                <div class="text-muted" style="font-size:.72rem;">{{ $cliente->email }}</div>
                                @endif
                            </div>
                        </div>
                    </td>
                    <td>
                        @if($cliente->telefono)
                        <a href="tel:{{ $cliente->telefono }}" class="text-secondary small text-decoration-none d-inline-flex align-items-center gap-1">
                            <i class="bi bi-telephone text-primary"></i> {{ $cliente->telefono }}
                        </a>
                        @else
                        <span class="text-muted small">—</span>
                        @endif
                    </td>
                    <td>
                        <span class="font-monospace small text-secondary">{{ $cliente->dui ?? '—' }}</span>
                    </td>
                    @if(auth()->user()->isAdmin())
                    <td>
                        <span class="badge badge-secondary-soft">
                            <i class="bi bi-person me-1"></i>{{ $cliente->cobrador->name }}
                        </span>
                    </td>
                    @endif
                    <td class="num {{ $cliente->saldo_total > 0 ? 'text-danger fw-bold' : 'text-success' }}">
                        ${{ number_format($cliente->saldo_total, 2) }}
                    </td>
                    <td>
                        <span class="badge {{ $cliente->activo ? 'badge-success-soft' : 'badge-secondary-soft' }}">
                            {{ $cliente->activo ? 'Activo' : 'Inactivo' }}
                        </span>
                    </td>
                    <td class="text-end">
                        <div class="d-inline-flex gap-1">
                            <a href="{{ route('clientes.show', $cliente) }}" class="btn-action action-primary" title="Ver perfil">
                                <i class="bi bi-eye"></i>
                            </a>
                            <a href="{{ route('clientes.edit', $cliente) }}" class="btn-action action-warning" title="Editar cliente">
                                <i class="bi bi-pencil"></i>
                            </a>
                            <form method="POST" action="{{ route('clientes.destroy', $cliente) }}" class="d-inline"
                                  onsubmit="return confirm('¿Está seguro de eliminar este cliente?')">
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
                    <td colspan="7">
                        <div class="empty-state">
                            <div class="empty-state-icon">
                                <i class="bi bi-people"></i>
                            </div>
                            <div class="empty-state-title">No se encontraron clientes</div>
                            <div class="empty-state-text">No hay registros que coincidan con la búsqueda ingresada.</div>
                            <a href="{{ route('clientes.create') }}" class="btn btn-sm btn-primary">
                                <i class="bi bi-person-plus-fill me-1"></i> Registrar Cliente
                            </a>
                        </div>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    @if($clientes->hasPages())
    <div class="card-footer bg-white border-top">
        {{ $clientes->appends(request()->query())->links() }}
    </div>
    @endif
</div>
@endsection
