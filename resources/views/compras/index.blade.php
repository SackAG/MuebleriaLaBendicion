@extends('layouts.app')
@section('title', 'Compras')

@section('breadcrumb')
    <li class="breadcrumb-item active">Compras</li>
@endsection

@section('content')
<div class="d-flex align-items-center justify-content-between mb-3">
    <h5 class="fw-bold mb-0"><i class="bi bi-truck me-1"></i> Compras a Proveedores</h5>
    <a href="{{ route('compras.create') }}" class="btn btn-sm text-white" style="background:#4a1942;">
        <i class="bi bi-plus-lg me-1"></i> Nueva Compra
    </a>
</div>

<div class="card shadow-sm border-0">
    <div class="card-body">
        <form method="GET" class="row g-2 mb-3">
            <div class="col-md-4">
                <div class="input-group input-group-sm">
                    <span class="input-group-text"><i class="bi bi-search"></i></span>
                    <input type="text" name="search" class="form-control" placeholder="Proveedor o N° compra..." value="{{ $search }}">
                </div>
            </div>
            <div class="col-md-3">
                <select name="estado" class="form-select form-select-sm">
                    <option value="">Todos</option>
                    <option value="pendiente" {{ $estado == 'pendiente' ? 'selected' : '' }}>Pendiente</option>
                    <option value="parcial" {{ $estado == 'parcial' ? 'selected' : '' }}>Parcial</option>
                    <option value="pagada" {{ $estado == 'pagada' ? 'selected' : '' }}>Pagada</option>
                    <option value="cancelada" {{ $estado == 'cancelada' ? 'selected' : '' }}>Cancelada</option>
                </select>
            </div>
            <div class="col-auto">
                <button type="submit" class="btn btn-sm btn-primary">Filtrar</button>
                <a href="{{ route('compras.index') }}" class="btn btn-sm btn-outline-secondary">Limpiar</a>
            </div>
        </form>

        <div class="table-responsive">
            <table class="table table-hover align-middle">
                <thead>
                    <tr>
                        <th>N° Compra</th>
                        <th>Proveedor</th>
                        <th>Fecha</th>
                        <th>Total</th>
                        <th>Pagado</th>
                        <th>Saldo</th>
                        <th>Estado</th>
                        <th class="text-end">Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($compras as $compra)
                    <tr>
                        <td><a href="{{ route('compras.show', $compra) }}" class="fw-semibold text-decoration-none">{{ $compra->numero_compra }}</a></td>
                        <td>{{ $compra->proveedor->nombre }}</td>
                        <td>{{ $compra->fecha_compra->format('d/m/Y') }}</td>
                        <td>${{ number_format($compra->total, 2) }}</td>
                        <td class="text-success">${{ number_format($compra->monto_pagado, 2) }}</td>
                        <td class="{{ $compra->saldo_pendiente > 0 ? 'text-danger fw-semibold' : 'text-success' }}">
                            ${{ number_format($compra->saldo_pendiente, 2) }}
                        </td>
                        <td><span class="badge badge-{{ $compra->estado }}">{{ ucfirst($compra->estado) }}</span></td>
                        <td class="text-end">
                            <a href="{{ route('compras.show', $compra) }}" class="btn btn-sm btn-outline-info"><i class="bi bi-eye"></i></a>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="8" class="text-center text-muted py-4">No se encontraron compras</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{ $compras->appends(request()->query())->links() }}
    </div>
</div>
@endsection
