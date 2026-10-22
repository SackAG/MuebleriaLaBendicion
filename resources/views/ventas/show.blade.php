@extends('layouts.app')
@section('title', 'Venta ' . $venta->numero_venta)

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('ventas.index') }}">Ventas</a></li>
    <li class="breadcrumb-item active">{{ $venta->numero_venta }}</li>
@endsection

@section('content')
<div class="row g-3">
    <!-- Venta Info -->
    <div class="col-md-4">
        <div class="card shadow-sm border-0">
            <div class="card-header bg-transparent border-0 pt-3 pb-0">
                <div class="d-flex justify-content-between align-items-start">
                    <h6 class="fw-bold mb-0">{{ $venta->numero_venta }}</h6>
                    <span class="badge badge-{{ $venta->estado }}">{{ ucfirst($venta->estado) }}</span>
                </div>
            </div>
            <div class="card-body">
                <ul class="list-unstyled mb-0 small">
                    <li class="mb-2 d-flex justify-content-between">
                        <span class="text-muted">Cliente</span>
                        <a href="{{ route('clientes.show', $venta->cliente) }}" class="fw-semibold text-decoration-none">
                            {{ $venta->cliente->nombre_completo }}
                        </a>
                    </li>
                    <li class="mb-2 d-flex justify-content-between">
                        <span class="text-muted">Cobrador</span>
                        <span>{{ $venta->cobrador->name }}</span>
                    </li>
                    <li class="mb-2 d-flex justify-content-between">
                        <span class="text-muted">Fecha</span>
                        <span>{{ $venta->fecha_venta->format('d/m/Y') }}</span>
                    </li>
                    <li class="mb-2 d-flex justify-content-between">
                        <span class="text-muted">Tipo de Pago</span>
                        <span class="badge {{ $venta->tipo_pago == 'credito' ? 'bg-info text-dark' : 'bg-success' }}">
                            {{ ucfirst($venta->tipo_pago) }}
                        </span>
                    </li>
                    @if($venta->tipo_pago == 'credito')
                    <li class="mb-2 d-flex justify-content-between">
                        <span class="text-muted">Cuotas</span>
                        <span>{{ $venta->num_pagos }} x ${{ number_format($venta->monto_cuota, 2) }}</span>
                    </li>
                    <li class="mb-2 d-flex justify-content-between">
                        <span class="text-muted">Frecuencia</span>
                        <span>{{ ucfirst($venta->frecuencia_pago) }}</span>
                    </li>
                    @if($venta->fecha_primer_pago)
                    <li class="mb-2 d-flex justify-content-between">
                        <span class="text-muted">1er Pago</span>
                        <span>{{ $venta->fecha_primer_pago->format('d/m/Y') }}</span>
                    </li>
                    @endif
                    @endif
                </ul>
            </div>
            <div class="card-body border-top">
                <div class="d-flex justify-content-between mb-1">
                    <span class="text-muted small">Subtotal</span>
                    <span>${{ number_format($venta->subtotal, 2) }}</span>
                </div>
                <div class="d-flex justify-content-between mb-1">
                    <span class="text-muted small">Descuento</span>
                    <span class="text-danger">-${{ number_format($venta->descuento, 2) }}</span>
                </div>
                <div class="d-flex justify-content-between mb-1 fw-bold border-top pt-2">
                    <span>Total</span>
                    <span>${{ number_format($venta->total, 2) }}</span>
                </div>
                <div class="d-flex justify-content-between mb-1">
                    <span class="text-muted small">Enganche</span>
                    <span class="text-success">${{ number_format($venta->enganche, 2) }}</span>
                </div>
                <div class="d-flex justify-content-between mb-1">
                    <span class="text-muted small">Total Cobrado</span>
                    <span class="text-success">${{ number_format($venta->total_pagado, 2) }}</span>
                </div>
                <div class="d-flex justify-content-between fw-bold text-danger border-top pt-2">
                    <span>Saldo Pendiente</span>
                    <span>${{ number_format($venta->saldo_pendiente, 2) }}</span>
                </div>
            </div>
            @if($venta->estado === 'activa')
            <div class="card-body border-top pt-3 d-flex gap-2">
                <a href="{{ route('pagos.create', $venta) }}" class="btn btn-success flex-fill">
                    <i class="bi bi-cash me-1"></i> Registrar Pago
                </a>
                <form method="POST" action="{{ route('ventas.destroy', $venta) }}" onsubmit="return confirm('¿Cancelar venta?')">
                    @csrf @method('DELETE')
                    <button class="btn btn-outline-danger"><i class="bi bi-x-circle"></i></button>
                </form>
            </div>
            @endif
        </div>
    </div>

    <!-- Detalles & Pagos -->
    <div class="col-md-8">
        <!-- Detalle de productos -->
        <div class="card shadow-sm border-0 mb-3">
            <div class="card-header bg-transparent border-0 pt-3 pb-0">
                <h6 class="fw-bold mb-0"><i class="bi bi-box-seam me-1"></i> Productos Vendidos</h6>
            </div>
            <div class="card-body p-0">
                <table class="table mb-0">
                    <thead>
                        <tr>
                            <th>Producto</th>
                            <th class="text-end">Cant.</th>
                            <th class="text-end">Precio</th>
                            <th class="text-end">Desc.</th>
                            <th class="text-end">Subtotal</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($venta->detalles as $det)
                        <tr>
                            <td>{{ $det->producto->nombre }}</td>
                            <td class="text-end">{{ $det->cantidad }}</td>
                            <td class="text-end">${{ number_format($det->precio_unitario, 2) }}</td>
                            <td class="text-end text-danger">${{ number_format($det->descuento, 2) }}</td>
                            <td class="text-end fw-semibold">${{ number_format($det->subtotal, 2) }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Pagos realizados -->
        <div class="card shadow-sm border-0">
            <div class="card-header bg-transparent border-0 pt-3 pb-0 d-flex justify-content-between align-items-center">
                <h6 class="fw-bold mb-0"><i class="bi bi-cash-stack me-1"></i> Pagos Realizados</h6>
                @if($venta->estado === 'activa')
                <a href="{{ route('pagos.create', $venta) }}" class="btn btn-sm btn-success">
                    <i class="bi bi-plus-lg me-1"></i> Nuevo Pago
                </a>
                @endif
            </div>
            <div class="card-body p-0">
                <table class="table mb-0">
                    <thead>
                        <tr>
                            <th>Recibo</th>
                            <th>Fecha</th>
                            <th>Cobrador</th>
                            <th>Monto</th>
                            <th>Mora</th>
                            <th>Total</th>
                            <th>Método</th>
                            @if(auth()->user()->isAdmin())<th></th>@endif
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($venta->pagos as $pago)
                        <tr>
                            <td><a href="{{ route('pagos.show', $pago) }}" class="text-decoration-none">{{ $pago->numero_recibo }}</a></td>
                            <td>{{ $pago->fecha_pago->format('d/m/Y') }}</td>
                            <td>{{ $pago->cobrador->name }}</td>
                            <td>${{ number_format($pago->monto, 2) }}</td>
                            <td class="text-warning">${{ number_format($pago->mora, 2) }}</td>
                            <td class="fw-semibold text-success">${{ number_format($pago->total_cobrado, 2) }}</td>
                            <td>{{ ucfirst($pago->metodo_pago) }}</td>
                            @if(auth()->user()->isAdmin())
                            <td>
                                <form method="POST" action="{{ route('pagos.destroy', $pago) }}" onsubmit="return confirm('¿Eliminar pago y restaurar saldo?')">
                                    @csrf @method('DELETE')
                                    <button class="btn btn-sm btn-outline-danger"><i class="bi bi-trash"></i></button>
                                </form>
                            </td>
                            @endif
                        </tr>
                        @empty
                        <tr><td colspan="8" class="text-center text-muted py-3">Sin pagos registrados</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection
