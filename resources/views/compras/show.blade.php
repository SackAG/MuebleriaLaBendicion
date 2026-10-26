@extends('layouts.app')
@section('title', 'Compra ' . $compra->numero_compra)

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('compras.index') }}">Compras</a></li>
    <li class="breadcrumb-item active">{{ $compra->numero_compra }}</li>
@endsection

@section('content')
<div class="row g-4">
    <!-- Purchase summary -->
    <div class="col-12 col-lg-4">
        <div class="card mb-4">
            <div class="card-header">
                <div>
                    <h6 class="mb-1"><i class="bi bi-truck me-2 text-primary"></i> Compra {{ $compra->numero_compra }}</h6>
                    <span class="badge badge-{{ $compra->estado }}">
                        {{ ucfirst($compra->estado) }}
                    </span>
                </div>
            </div>
            <div class="card-body">
                <ul class="list-unstyled mb-0 small">
                    <li class="mb-2 d-flex justify-content-between">
                        <span class="text-muted">Proveedor</span>
                        <span class="fw-bold text-dark">{{ $compra->proveedor->nombre }}</span>
                    </li>
                    <li class="mb-2 d-flex justify-content-between">
                        <span class="text-muted">Fecha de Factura</span>
                        <span class="text-secondary">{{ $compra->fecha_compra->format('d/m/Y') }}</span>
                    </li>
                    <li class="mb-2 d-flex justify-content-between">
                        <span class="text-muted">Ingresado por</span>
                        <span class="text-secondary">{{ $compra->creadoPor->name }}</span>
                    </li>
                    @if($compra->notas)
                    <li class="mt-2 pt-2 border-top text-muted">
                        <span class="fw-semibold text-secondary d-block mb-1">Notas:</span>
                        {{ $compra->notas }}
                    </li>
                    @endif
                </ul>
            </div>

            <div class="card-body border-top bg-light">
                <div class="d-flex justify-content-between mb-1 small text-secondary">
                    <span>Subtotal</span>
                    <span>${{ number_format($compra->subtotal, 2) }}</span>
                </div>
                <div class="d-flex justify-content-between mb-2 fw-bold text-dark border-top pt-2">
                    <span>Total de la Compra</span>
                    <span class="fs-6">${{ number_format($compra->total, 2) }}</span>
                </div>
                <div class="d-flex justify-content-between mb-2 small text-secondary">
                    <span>Monto Pagado</span>
                    <span class="text-success fw-bold">${{ number_format($compra->monto_pagado, 2) }}</span>
                </div>
                <div class="d-flex justify-content-between fw-bold border-top pt-2">
                    <span class="text-danger">Saldo Pendiente</span>
                    <span class="fs-5 text-danger font-monospace">${{ number_format($compra->saldo_pendiente, 2) }}</span>
                </div>
            </div>

            @if($compra->saldo_pendiente > 0)
            <div class="card-body border-top">
                <h6 class="fw-bold mb-3" style="font-size:.85rem;">
                    <i class="bi bi-cash-coin me-1 text-success"></i> Abonar al Proveedor
                </h6>
                <form method="POST" action="{{ route('compras.pagos.store', $compra) }}">
                    @csrf
                    <div class="mb-2">
                        <label class="form-label small">Fecha de Abono</label>
                        <input type="date" name="fecha_pago" class="form-control form-control-sm" value="{{ date('Y-m-d') }}" required>
                    </div>
                    <div class="mb-2">
                        <label class="form-label small">Monto ($)</label>
                        <div class="input-group input-group-sm">
                            <span class="input-group-text">$</span>
                            <input type="number" name="monto" class="form-control"
                                   value="{{ $compra->saldo_pendiente }}" max="{{ $compra->saldo_pendiente }}" step="0.01" min="0.01" required>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label small">Método de Pago</label>
                        <select name="metodo_pago" class="form-select form-select-sm">
                            <option value="efectivo">Efectivo</option>
                            <option value="transferencia" selected>Transferencia bancaria</option>
                            <option value="tarjeta">Tarjeta</option>
                        </select>
                    </div>
                    <button type="submit" class="btn btn-sm btn-success w-100">
                        <i class="bi bi-check2-circle me-1"></i> Confirmar Pago
                    </button>
                </form>
            </div>
            @endif
        </div>
    </div>

    <!-- Items & Payments list -->
    <div class="col-12 col-lg-8">
        <!-- Products purchased -->
        <div class="card mb-4">
            <div class="card-header">
                <h6><i class="bi bi-box-seam me-2 text-primary"></i> Mercancía Recibida</h6>
            </div>
            <div class="table-responsive">
                <table class="table align-middle">
                    <thead>
                        <tr>
                            <th>Producto</th>
                            <th class="num">Cantidad</th>
                            <th class="num">Precio Costo</th>
                            <th class="num">Subtotal</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($compra->detalles as $det)
                        <tr>
                            <td>
                                <a href="{{ route('productos.show', $det->producto) }}" class="fw-bold text-dark text-decoration-none hover-primary">
                                    {{ $det->producto->nombre }}
                                </a>
                                <div class="small text-muted font-monospace">{{ $det->producto->codigo }}</div>
                            </td>
                            <td class="num">{{ $det->cantidad }}</td>
                            <td class="num">${{ number_format($det->precio_unitario, 2) }}</td>
                            <td class="num text-dark fw-bold">${{ number_format($det->subtotal, 2) }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Payments made to provider -->
        <div class="card mb-0">
            <div class="card-header">
                <h6><i class="bi bi-cash-stack me-2 text-primary"></i> Pagos Realizados al Proveedor</h6>
            </div>
            <div class="table-responsive">
                <table class="table align-middle">
                    <thead>
                        <tr>
                            <th>Fecha</th>
                            <th class="num">Monto Pagado</th>
                            <th>Método</th>
                            <th>Registrado Por</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($compra->pagos as $pago)
                        <tr>
                            <td class="text-secondary small">{{ $pago->fecha_pago->format('d/m/Y') }}</td>
                            <td class="num text-success fw-bold">${{ number_format($pago->monto, 2) }}</td>
                            <td>
                                <span class="badge badge-secondary-soft">{{ ucfirst($pago->metodo_pago) }}</span>
                            </td>
                            <td class="text-secondary small">{{ $pago->registradoPor->name }}</td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="4" class="text-center py-4 text-muted">
                                No se registran pagos hacia este proveedor aún
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
