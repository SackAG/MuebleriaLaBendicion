@extends('layouts.app')
@section('title', 'Cliente - ' . $cliente->nombre_completo)

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('clientes.index') }}">Clientes</a></li>
    <li class="breadcrumb-item active">{{ $cliente->nombre_completo }}</li>
@endsection

@section('content')
<div class="row g-3">
    <!-- Info Cliente -->
    <div class="col-md-4">
        <div class="card shadow-sm border-0">
            <div class="card-body text-center py-4">
                <div class="rounded-circle d-inline-flex align-items-center justify-content-center mb-3"
                     style="width:72px;height:72px;background:#4a1942;font-size:1.8rem;color:#fff;">
                    {{ strtoupper(substr($cliente->nombre, 0, 1) . substr($cliente->apellido, 0, 1)) }}
                </div>
                <h5 class="fw-bold mb-1">{{ $cliente->nombre_completo }}</h5>
                <p class="text-muted small mb-2">{{ $cliente->cobrador->name }}</p>
                @if($cliente->activo)
                    <span class="badge bg-success">Activo</span>
                @else
                    <span class="badge bg-secondary">Inactivo</span>
                @endif
            </div>
            <div class="card-body border-top pt-3">
                <ul class="list-unstyled mb-0">
                    @if($cliente->telefono)
                    <li class="mb-2"><i class="bi bi-telephone-fill text-muted me-2"></i>{{ $cliente->telefono }}</li>
                    @endif
                    @if($cliente->email)
                    <li class="mb-2"><i class="bi bi-envelope-fill text-muted me-2"></i>{{ $cliente->email }}</li>
                    @endif
                    @if($cliente->dui)
                    <li class="mb-2"><i class="bi bi-card-text text-muted me-2"></i>DUI: {{ $cliente->dui }}</li>
                    @endif
                    @if($cliente->direccion)
                    <li class="mb-2"><i class="bi bi-geo-alt-fill text-muted me-2"></i>{{ $cliente->direccion }}</li>
                    @endif
                    @if($cliente->referencias)
                    <li class="mb-2 text-muted small"><i class="bi bi-info-circle text-muted me-2"></i>{{ $cliente->referencias }}</li>
                    @endif
                </ul>
            </div>
            <div class="card-body border-top pt-3">
                <div class="d-flex gap-2">
                    <a href="{{ route('clientes.edit', $cliente) }}" class="btn btn-sm btn-warning flex-fill">
                        <i class="bi bi-pencil me-1"></i> Editar
                    </a>
                    <a href="{{ route('ventas.create') }}?cliente_id={{ $cliente->id }}" class="btn btn-sm btn-success flex-fill">
                        <i class="bi bi-cart-plus me-1"></i> Nueva Venta
                    </a>
                </div>
            </div>
        </div>

        <!-- Resumen Financiero -->
        <div class="card shadow-sm border-0 mt-3">
            <div class="card-header bg-transparent border-0 pb-0">
                <h6 class="fw-bold mb-0">Resumen Financiero</h6>
            </div>
            <div class="card-body">
                <div class="d-flex justify-content-between mb-2">
                    <span class="text-muted small">Total Ventas</span>
                    <span class="fw-semibold">{{ $cliente->ventas->count() }}</span>
                </div>
                <div class="d-flex justify-content-between mb-2">
                    <span class="text-muted small">Ventas Activas</span>
                    <span class="fw-semibold text-warning">{{ $cliente->ventas->where('estado','activa')->count() }}</span>
                </div>
                <div class="d-flex justify-content-between mb-2">
                    <span class="text-muted small">Saldo Total</span>
                    <span class="fw-bold text-danger">${{ number_format($cliente->saldo_total, 2) }}</span>
                </div>
            </div>
        </div>
    </div>

    <!-- Ventas -->
    <div class="col-md-8">
        <div class="card shadow-sm border-0">
            <div class="card-header bg-transparent border-0 pt-3 pb-0 d-flex justify-content-between">
                <h6 class="fw-bold mb-0"><i class="bi bi-cart-fill me-1"></i> Historial de Ventas</h6>
                <a href="{{ route('ventas.create') }}" class="btn btn-sm text-white" style="background:#4a1942;">
                    <i class="bi bi-plus-lg me-1"></i> Nueva
                </a>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover mb-0">
                        <thead>
                            <tr>
                                <th>N° Venta</th>
                                <th>Fecha</th>
                                <th>Total</th>
                                <th>Pagado</th>
                                <th>Saldo</th>
                                <th>Estado</th>
                                <th></th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($cliente->ventas as $venta)
                            <tr>
                                <td><a href="{{ route('ventas.show', $venta) }}" class="fw-semibold text-decoration-none">{{ $venta->numero_venta }}</a></td>
                                <td>{{ $venta->fecha_venta->format('d/m/Y') }}</td>
                                <td>${{ number_format($venta->total, 2) }}</td>
                                <td class="text-success">${{ number_format($venta->total_pagado, 2) }}</td>
                                <td class="{{ $venta->saldo_pendiente > 0 ? 'text-danger fw-bold' : 'text-success' }}">${{ number_format($venta->saldo_pendiente, 2) }}</td>
                                <td><span class="badge badge-{{ $venta->estado }}">{{ ucfirst($venta->estado) }}</span></td>
                                <td>
                                    @if($venta->estado === 'activa')
                                        <a href="{{ route('pagos.create', $venta) }}" class="btn btn-sm btn-success">
                                            <i class="bi bi-cash"></i> Cobrar
                                        </a>
                                    @endif
                                </td>
                            </tr>
                            @empty
                            <tr><td colspan="7" class="text-center text-muted py-3">Sin ventas registradas</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
