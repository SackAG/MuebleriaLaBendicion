@extends('layouts.app')
@section('title', 'Clientes')

@section('breadcrumb')
    <li class="breadcrumb-item active">Clientes</li>
@endsection

@section('content')
<div class="d-flex align-items-center justify-content-between mb-3">
    <h5 class="fw-bold mb-0"><i class="bi bi-people-fill me-1"></i> Clientes</h5>
    <a href="{{ route('clientes.create') }}" class="btn btn-sm text-white" style="background:#4a1942;">
        <i class="bi bi-plus-lg me-1"></i> Nuevo Cliente
    </a>
</div>

<div class="card shadow-sm border-0">
    <div class="card-body">
        <!-- Search -->
        <form method="GET" class="row g-2 mb-3">
            <div class="col-md-5">
                <div class="input-group input-group-sm">
                    <span class="input-group-text"><i class="bi bi-search"></i></span>
                    <input type="text" name="search" class="form-control" placeholder="Buscar por nombre, teléfono o DUI..." value="{{ $search }}">
                </div>
            </div>
            <div class="col-auto">
                <button type="submit" class="btn btn-sm btn-primary">Buscar</button>
                @if($search)<a href="{{ route('clientes.index') }}" class="btn btn-sm btn-outline-secondary">Limpiar</a>@endif
            </div>
        </form>

        <div class="table-responsive">
            <table class="table table-hover align-middle">
                <thead>
                    <tr>
                        <th>Nombre</th>
                        <th>Teléfono</th>
                        <th>DUI</th>
                        @if(auth()->user()->isAdmin())<th>Cobrador</th>@endif
                        <th>Saldo Pendiente</th>
                        <th>Estado</th>
                        <th class="text-end">Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($clientes as $cliente)
                    <tr>
                        <td>
                            <a href="{{ route('clientes.show', $cliente) }}" class="fw-semibold text-decoration-none">
                                {{ $cliente->nombre_completo }}
                            </a>
                        </td>
                        <td>{{ $cliente->telefono ?? '-' }}</td>
                        <td>{{ $cliente->dui ?? '-' }}</td>
                        @if(auth()->user()->isAdmin())
                        <td><span class="badge bg-secondary">{{ $cliente->cobrador->name }}</span></td>
                        @endif
                        <td class="{{ $cliente->saldo_total > 0 ? 'text-danger fw-semibold' : 'text-success' }}">
                            ${{ number_format($cliente->saldo_total, 2) }}
                        </td>
                        <td>
                            @if($cliente->activo)
                                <span class="badge bg-success">Activo</span>
                            @else
                                <span class="badge bg-secondary">Inactivo</span>
                            @endif
                        </td>
                        <td class="text-end">
                            <a href="{{ route('clientes.show', $cliente) }}" class="btn btn-sm btn-outline-info" title="Ver"><i class="bi bi-eye"></i></a>
                            <a href="{{ route('clientes.edit', $cliente) }}" class="btn btn-sm btn-outline-warning" title="Editar"><i class="bi bi-pencil"></i></a>
                            <form method="POST" action="{{ route('clientes.destroy', $cliente) }}" class="d-inline"
                                  onsubmit="return confirm('¿Eliminar cliente?')">
                                @csrf @method('DELETE')
                                <button class="btn btn-sm btn-outline-danger" title="Eliminar"><i class="bi bi-trash"></i></button>
                            </form>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="8" class="text-center text-muted py-4">
                            <i class="bi bi-people fs-2 d-block mb-2"></i>
                            No se encontraron clientes
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{ $clientes->appends(request()->query())->links() }}
    </div>
</div>
@endsection
