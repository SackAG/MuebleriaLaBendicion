@extends('layouts.app')
@section('title', 'Cliente - ' . $cliente->nombre_completo)

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('clientes.index') }}">Clientes</a></li>
    <li class="breadcrumb-item active">{{ $cliente->nombre_completo }}</li>
@endsection

@section('content')
<div class="row g-4">
    <!-- Customer Profile -->
    <div class="col-12 col-lg-4">
        <div class="card mb-4">
            <div class="card-body text-center p-4">
                <div class="user-avatar mx-auto mb-3" style="width: 72px; height: 72px; font-size: 1.75rem; background: var(--primary-subtle); color: var(--primary);">
                    {{ strtoupper(substr($cliente->nombre, 0, 1) . substr($cliente->apellido, 0, 1)) }}
                </div>
                <h5 class="fw-bold text-dark mb-1">{{ $cliente->nombre_completo }}</h5>
                <p class="text-muted small mb-2">
                    Cobrador: <strong class="text-secondary">{{ $cliente->cobrador->name }}</strong>
                </p>
                <div class="d-flex justify-content-center gap-2 mb-3">
                    <span class="badge {{ $cliente->activo ? 'badge-success-soft' : 'badge-secondary-soft' }}">
                        {{ $cliente->activo ? 'Activo' : 'Inactivo' }}
                    </span>
                    @if($cliente->saldo_total > 0)
                    <span class="badge badge-danger-soft">
                        <i class="bi bi-clock-history me-1"></i> Con Saldo Pendiente
                    </span>
                    @endif
                </div>

                <div class="border-top text-start pt-3">
                    <ul class="list-unstyled mb-0 small">
                        @if($cliente->telefono)
                        <li class="mb-2 d-flex align-items-center gap-2">
                            <i class="bi bi-telephone text-primary"></i>
                            <a href="tel:{{ $cliente->telefono }}" class="text-dark fw-medium text-decoration-none">
                                {{ $cliente->telefono }}
                            </a>
                        </li>
                        @endif
                        @if($cliente->email)
                        <li class="mb-2 d-flex align-items-center gap-2">
                            <i class="bi bi-envelope text-primary"></i>
                            <a href="mailto:{{ $cliente->email }}" class="text-secondary text-decoration-none">
                                {{ $cliente->email }}
                            </a>
                        </li>
                        @endif
                        @if($cliente->dui)
                        <li class="mb-2 d-flex align-items-center gap-2">
                            <i class="bi bi-person-vcard text-primary"></i>
                            <span class="text-muted">CURP:</span>
                            <span class="font-monospace fw-semibold">{{ $cliente->dui }}</span>
                        </li>
                        @endif
                        @if($cliente->nit)
                        <li class="mb-2 d-flex align-items-center gap-2">
                            <i class="bi bi-receipt text-primary"></i>
                            <span class="text-muted">RFC:</span>
                            <span class="font-monospace fw-semibold">{{ $cliente->nit }}</span>
                        </li>
                        @endif
                        @if($cliente->direccion)
                        <li class="mb-2 d-flex align-items-start gap-2">
                            <i class="bi bi-geo-alt text-primary mt-1"></i>
                            <span class="text-secondary">{{ $cliente->direccion }}</span>
                        </li>
                        @endif
                        @if($cliente->referencias)
                        <li class="mb-2 d-flex align-items-start gap-2">
                            <i class="bi bi-info-circle text-muted mt-1"></i>
                            <span class="text-muted">{{ $cliente->referencias }}</span>
                        </li>
                        @endif
                    </ul>
                </div>
            </div>

            <div class="card-footer bg-light d-flex gap-2">
                <a href="{{ route('clientes.edit', $cliente) }}" class="btn btn-sm btn-outline-secondary flex-fill">
                    <i class="bi bi-pencil me-1"></i> Editar
                </a>
                <a href="{{ route('ventas.create') }}?cliente_id={{ $cliente->id }}" class="btn btn-sm btn-primary flex-fill">
                    <i class="bi bi-cart-plus me-1"></i> Nueva Venta
                </a>
            </div>
        </div>

        <!-- Financial Summary Card -->
        <div class="card mb-0">
            <div class="card-header">
                <h6><i class="bi bi-wallet2 me-2 text-primary"></i> Resumen de Crédito</h6>
            </div>
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center mb-2 pb-2 border-bottom">
                    <span class="text-muted small">Total Ventas Registradas</span>
                    <span class="fw-bold text-dark">{{ $cliente->ventas->count() }}</span>
                </div>
                <div class="d-flex justify-content-between align-items-center mb-2 pb-2 border-bottom">
                    <span class="text-muted small">Ventas Activas con Saldo</span>
                    <span class="badge badge-warning-soft">{{ $cliente->ventas->where('estado','activa')->count() }}</span>
                </div>
                <div class="d-flex justify-content-between align-items-center">
                    <span class="text-muted small">Saldo Total Deudor</span>
                    <span class="fs-5 fw-bold {{ $cliente->saldo_total > 0 ? 'text-danger' : 'text-success' }}">
                        ${{ number_format($cliente->saldo_total, 2) }}
                    </span>
                </div>
            </div>
        </div>
    </div>

    <!-- Sales History -->
    <div class="col-12 col-lg-8">
        <div class="card mb-0">
            <div class="card-header">
                <h6><i class="bi bi-clock-history me-2 text-primary"></i> Historial de Ventas del Cliente</h6>
                <a href="{{ route('ventas.create') }}?cliente_id={{ $cliente->id }}" class="btn btn-sm btn-primary">
                    <i class="bi bi-plus-lg me-1"></i> Registrar Venta
                </a>
            </div>
            <div class="table-responsive">
                <table class="table align-middle">
                    <thead>
                        <tr>
                            <th>N° Venta</th>
                            <th>Fecha</th>
                            <th class="num">Total</th>
                            <th class="num">Pagado</th>
                            <th class="num">Saldo</th>
                            <th>Estado</th>
                            <th class="text-end">Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($cliente->ventas as $venta)
                        <tr>
                            <td>
                                <a href="{{ route('ventas.show', $venta) }}" class="fw-bold text-primary text-decoration-none">
                                    {{ $venta->numero_venta }}
                                </a>
                            </td>
                            <td class="text-secondary small">{{ $venta->fecha_venta->format('d/m/Y') }}</td>
                            <td class="num">${{ number_format($venta->total, 2) }}</td>
                            <td class="num text-success">${{ number_format($venta->total_pagado, 2) }}</td>
                            <td class="num {{ $venta->saldo_pendiente > 0 ? 'text-danger fw-bold' : 'text-success' }}">
                                ${{ number_format($venta->saldo_pendiente, 2) }}
                            </td>
                            <td>
                                <span class="badge badge-{{ $venta->estado }}">
                                    {{ ucfirst($venta->estado) }}
                                </span>
                            </td>
                            <td class="text-end">
                                <div class="d-inline-flex gap-1">
                                    <a href="{{ route('ventas.show', $venta) }}" class="btn-action action-primary" title="Ver detalle">
                                        <i class="bi bi-eye"></i>
                                    </a>
                                    @if($venta->estado === 'activa')
                                    <a href="{{ route('pagos.create', $venta) }}" class="btn btn-sm btn-success px-2" title="Cobrar cuota">
                                        <i class="bi bi-cash me-1"></i> Cobrar
                                    </a>
                                    @endif
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="7" class="text-center py-5 text-muted">
                                <i class="bi bi-cart-x fs-2 d-block mb-2 text-secondary"></i>
                                Este cliente no tiene ventas registradas aún
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection
