@extends('layouts.app')
@section('title', 'Compras')

@section('breadcrumb')
    <li class="breadcrumb-item active">Compras</li>
@endsection

@section('content')
<div class="page-header">
    <div>
        <h1 class="page-title">
            <i class="bi bi-truck text-primary"></i>
            Compras a Proveedores
        </h1>
        <p class="page-subtitle">Abastecimiento de inventario, facturas por pagar y recepción de mercancía</p>
    </div>
    <a href="{{ route('compras.create') }}" class="btn btn-primary">
        <i class="bi bi-plus-lg"></i>
        <span>Nueva Compra</span>
    </a>
</div>

<!-- Filter card -->
<div class="filter-card">
    <form method="GET" class="row g-2 align-items-center">
        <div class="col-12 col-md-5">
            <div class="input-group input-group-sm">
                <span class="input-group-text"><i class="bi bi-search"></i></span>
                <input type="text" name="search" class="form-control" placeholder="Buscar por proveedor o N° de compra..." value="{{ $search }}">
            </div>
        </div>
        <div class="col-12 col-sm-6 col-md-3">
            <select name="estado" class="form-select form-select-sm">
                <option value="">Todos los estados</option>
                <option value="pendiente" {{ $estado == 'pendiente' ? 'selected' : '' }}>Pendiente</option>
                <option value="parcial" {{ $estado == 'parcial' ? 'selected' : '' }}>Parcial</option>
                <option value="pagada" {{ $estado == 'pagada' ? 'selected' : '' }}>Pagada</option>
                <option value="cancelada" {{ $estado == 'cancelada' ? 'selected' : '' }}>Cancelada</option>
            </select>
        </div>
        <div class="col-auto ms-auto d-flex gap-2">
            <button type="submit" class="btn btn-sm btn-primary">
                <i class="bi bi-funnel me-1"></i> Filtrar
            </button>
            @if($search || $estado)
            <a href="{{ route('compras.index') }}" class="btn btn-sm btn-outline-secondary">
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
                    <th>N° Compra</th>
                    <th>Proveedor</th>
                    <th>Fecha</th>
                    <th class="num">Total</th>
                    <th class="num">Monto Pagado</th>
                    <th class="num">Saldo Pendiente</th>
                    <th>Estado</th>
                    <th class="text-end">Acciones</th>
                </tr>
            </thead>
            <tbody>
                @forelse($compras as $compra)
                <tr>
                    <td>
                        <a href="{{ route('compras.show', $compra) }}" class="fw-bold text-primary text-decoration-none">
                            {{ $compra->numero_compra }}
                        </a>
                    </td>
                    <td class="fw-medium text-dark">{{ $compra->proveedor->nombre }}</td>
                    <td class="text-secondary small">{{ $compra->fecha_compra->format('d/m/Y') }}</td>
                    <td class="num text-dark fw-bold">${{ number_format($compra->total, 2) }}</td>
                    <td class="num text-success">${{ number_format($compra->monto_pagado, 2) }}</td>
                    <td class="num {{ $compra->saldo_pendiente > 0 ? 'text-danger fw-bold' : 'text-success' }}">
                        ${{ number_format($compra->saldo_pendiente, 2) }}
                    </td>
                    <td>
                        <span class="badge badge-{{ $compra->estado }}">
                            {{ ucfirst($compra->estado) }}
                        </span>
                    </td>
                    <td class="text-end">
                        <a href="{{ route('compras.show', $compra) }}" class="btn-action action-primary" title="Ver detalle de compra">
                            <i class="bi bi-eye"></i>
                        </a>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="8">
                        <div class="empty-state">
                            <div class="empty-state-icon">
                                <i class="bi bi-truck"></i>
                            </div>
                            <div class="empty-state-title">No se encontraron compras</div>
                            <div class="empty-state-text">No hay órdenes de compra registradas con los filtros actuales.</div>
                            <a href="{{ route('compras.create') }}" class="btn btn-sm btn-primary">
                                <i class="bi bi-plus-lg me-1"></i> Registrar Compra
                            </a>
                        </div>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    @if($compras->hasPages())
    <div class="card-footer bg-white border-top">
        {{ $compras->appends(request()->query())->links() }}
    </div>
    @endif
</div>
@endsection
