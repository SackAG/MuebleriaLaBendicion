@extends('layouts.app')
@section('title', 'Compra ' . $compra->numero_compra)

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('compras.index') }}">Compras</a></li>
    <li class="breadcrumb-item active">{{ $compra->numero_compra }}</li>
@endsection

@section('content')
<div class="row g-3">
    <div class="col-md-4">
        <div class="card shadow-sm border-0">
            <div class="card-header bg-transparent border-0 pt-3 pb-0 d-flex justify-content-between">
                <h6 class="fw-bold mb-0">{{ $compra->numero_compra }}</h6>
                <span class="badge badge-{{ $compra->estado }}">{{ ucfirst($compra->estado) }}</span>
            </div>
            <div class="card-body">
                <ul class="list-unstyled small mb-0">
                    <li class="mb-2 d-flex justify-content-between">
                        <span class="text-muted">Proveedor</span>
                        <span class="fw-semibold">{{ $compra->proveedor->nombre }}</span>
                    </li>
                    <li class="mb-2 d-flex justify-content-between">
                        <span class="text-muted">Fecha</span>
                        <span>{{ $compra->fecha_compra->format('d/m/Y') }}</span>
                    </li>
                    <li class="mb-2 d-flex justify-content-between">
                        <span class="text-muted">Registrado por</span>
                        <span>{{ $compra->creadoPor->name }}</span>
                    </li>
                    @if($compra->notas)
                    <li class="mt-2 text-muted">{{ $compra->notas }}</li>
                    @endif
                </ul>
            </div>
            <div class="card-body border-top">
                <div class="d-flex justify-content-between mb-1">
                    <span class="text-muted small">Subtotal</span>
                    <span>${{ number_format($compra->subtotal, 2) }}</span>
                </div>
                <div class="d-flex justify-content-between mb-1 fw-bold border-top pt-2">
                    <span>Total</span>
                    <span>${{ number_format($compra->total, 2) }}</span>
                </div>
                <div class="d-flex justify-content-between mb-1">
                    <span class="text-muted small">Pagado</span>
                    <span class="text-success">${{ number_format($compra->monto_pagado, 2) }}</span>
                </div>
                <div class="d-flex justify-content-between fw-bold text-danger border-top pt-2">
                    <span>Saldo Pendiente</span>
                    <span>${{ number_format($compra->saldo_pendiente, 2) }}</span>
                </div>
            </div>

            @if($compra->saldo_pendiente > 0)
            <div class="card-body border-top">
                <h6 class="fw-semibold mb-2">Registrar Pago al Proveedor</h6>
                <form method="POST" action="{{ route('compras.pagos.store', $compra) }}">
                    @csrf
                    <div class="mb-2">
                        <label class="form-label small">Fecha</label>
                        <input type="date" name="fecha_pago" class="form-control form-control-sm" value="{{ date('Y-m-d') }}" required>
                    </div>
                    <div class="mb-2">
                        <label class="form-label small">Monto ($)</label>
                        <input type="number" name="monto" class="form-control form-control-sm"
                               value="{{ $compra->saldo_pendiente }}" max="{{ $compra->saldo_pendiente }}" step="0.01" min="0.01" required>
                    </div>
                    <div class="mb-2">
                        <select name="metodo_pago" class="form-select form-select-sm">
                            <option value="efectivo">Efectivo</option>
                            <option value="transferencia">Transferencia</option>
                            <option value="tarjeta">Tarjeta</option>
                        </select>
                    </div>
                    <button type="submit" class="btn btn-sm btn-success w-100">
                        <i class="bi bi-check-lg me-1"></i> Confirmar Pago
                    </button>
                </form>
            </div>
            @endif
        </div>
    </div>

    <div class="col-md-8">
        <!-- Detalles -->
        <div class="card shadow-sm border-0 mb-3">
            <div class="card-header bg-transparent border-0 pt-3 pb-0">
                <h6 class="fw-bold mb-0"><i class="bi bi-box-seam me-1"></i> Productos Comprados</h6>
            </div>
            <div class="card-body p-0">
                <table class="table mb-0">
                    <thead><tr><th>Producto</th><th class="text-end">Cant.</th><th class="text-end">Precio</th><th class="text-end">Subtotal</th></tr></thead>
                    <tbody>
                        @foreach($compra->detalles as $det)
                        <tr>
                            <td>{{ $det->producto->nombre }}</td>
                            <td class="text-end">{{ $det->cantidad }}</td>
                            <td class="text-end">${{ number_format($det->precio_unitario, 2) }}</td>
                            <td class="text-end fw-semibold">${{ number_format($det->subtotal, 2) }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Pagos -->
        <div class="card shadow-sm border-0">
            <div class="card-header bg-transparent border-0 pt-3 pb-0">
                <h6 class="fw-bold mb-0"><i class="bi bi-cash-stack me-1"></i> Pagos Realizados al Proveedor</h6>
            </div>
            <div class="card-body p-0">
                <table class="table mb-0">
                    <thead><tr><th>Fecha</th><th>Monto</th><th>Método</th><th>Registrado por</th></tr></thead>
                    <tbody>
                        @forelse($compra->pagos as $pago)
                        <tr>
                            <td>{{ $pago->fecha_pago->format('d/m/Y') }}</td>
                            <td class="fw-semibold text-success">${{ number_format($pago->monto, 2) }}</td>
                            <td>{{ ucfirst($pago->metodo_pago) }}</td>
                            <td>{{ $pago->registradoPor->name }}</td>
                        </tr>
                        @empty
                        <tr><td colspan="4" class="text-center text-muted py-3">Sin pagos registrados</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection
