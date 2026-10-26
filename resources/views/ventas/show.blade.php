@extends('layouts.app')
@section('title', 'Venta ' . $venta->numero_venta)

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('ventas.index') }}">Ventas</a></li>
    <li class="breadcrumb-item active">{{ $venta->numero_venta }}</li>
@endsection

@section('content')
<div class="row g-4">
    <!-- Sale Overview & Financial Breakdown -->
    <div class="col-12 col-lg-4">
        <div class="card mb-4">
            <div class="card-header">
                <div>
                    <h6 class="mb-1"><i class="bi bi-receipt me-2 text-primary"></i> Venta {{ $venta->numero_venta }}</h6>
                    <span class="badge badge-{{ $venta->estado }}">
                        {{ ucfirst($venta->estado) }}
                    </span>
                </div>
                <span class="badge {{ $venta->tipo_pago == 'credito' ? 'badge-info-soft' : 'badge-success-soft' }}">
                    {{ ucfirst($venta->tipo_pago) }}
                </span>
            </div>
            <div class="card-body">
                <ul class="list-unstyled mb-0 small">
                    <li class="mb-2 d-flex justify-content-between align-items-center">
                        <span class="text-muted">Cliente</span>
                        <a href="{{ route('clientes.show', $venta->cliente) }}" class="fw-bold text-dark text-decoration-none hover-primary">
                            {{ $venta->cliente->nombre_completo }}
                        </a>
                    </li>
                    <li class="mb-2 d-flex justify-content-between">
                        <span class="text-muted">Cobrador Asignado</span>
                        <span class="text-secondary fw-medium">{{ $venta->cobrador->name }}</span>
                    </li>
                    <li class="mb-2 d-flex justify-content-between">
                        <span class="text-muted">Fecha de Venta</span>
                        <span class="text-secondary">{{ $venta->fecha_venta->format('d/m/Y') }}</span>
                    </li>
                    @if($venta->tipo_pago == 'credito')
                    <li class="mb-2 d-flex justify-content-between">
                        <span class="text-muted">Plan de Pagos</span>
                        <span class="fw-semibold text-dark">{{ $venta->num_pagos }} cuotas x ${{ number_format($venta->monto_cuota, 2) }}</span>
                    </li>
                    <li class="mb-2 d-flex justify-content-between">
                        <span class="text-muted">Frecuencia</span>
                        <span class="text-secondary">{{ ucfirst($venta->frecuencia_pago) }}</span>
                    </li>
                    @if($venta->fecha_primer_pago)
                    <li class="mb-2 d-flex justify-content-between">
                        <span class="text-muted">Fecha 1er Pago</span>
                        <span class="text-secondary">{{ $venta->fecha_primer_pago->format('d/m/Y') }}</span>
                    </li>
                    @endif
                    @endif
                </ul>
            </div>

            <div class="card-body border-top pt-3 bg-light">
                <div class="d-flex justify-content-between mb-1 small text-secondary">
                    <span>Subtotal</span>
                    <span>${{ number_format($venta->subtotal, 2) }}</span>
                </div>
                <div class="d-flex justify-content-between mb-1 small text-secondary">
                    <span>Descuento Aplicado</span>
                    <span class="text-danger">-${{ number_format($venta->descuento, 2) }}</span>
                </div>
                <div class="d-flex justify-content-between mb-2 fw-bold text-dark border-top pt-2">
                    <span>Total del Contrato</span>
                    <span class="fs-6">${{ number_format($venta->total, 2) }}</span>
                </div>
                <div class="d-flex justify-content-between mb-1 small text-secondary">
                    <span>Enganche</span>
                    <span class="text-success">${{ number_format($venta->enganche, 2) }}</span>
                </div>
                <div class="d-flex justify-content-between mb-2 small text-secondary">
                    <span>Total Cobrado Acumulado</span>
                    <span class="text-success fw-bold">${{ number_format($venta->total_pagado, 2) }}</span>
                </div>
                <div class="d-flex justify-content-between fw-bold border-top pt-2">
                    <span class="text-danger">Saldo Pendiente</span>
                    <span class="fs-5 text-danger font-monospace">${{ number_format($venta->saldo_pendiente, 2) }}</span>
                </div>
            </div>

            @if($venta->estado === 'activa')
            <div class="card-footer d-flex gap-2 bg-white">
                <a href="{{ route('pagos.create', $venta) }}" class="btn btn-sm btn-success flex-fill">
                    <i class="bi bi-cash me-1"></i> Registrar Pago
                </a>
                <form method="POST" action="{{ route('ventas.destroy', $venta) }}" class="d-inline"
                      onsubmit="return confirm('¿Está seguro de cancelar esta venta? Los movimientos serán revertidos.')">
                    @csrf @method('DELETE')
                    <button type="submit" class="btn btn-sm btn-outline-danger" title="Cancelar venta">
                        <i class="bi bi-x-circle"></i>
                    </button>
                </form>
            </div>
            @endif
        </div>
    </div>

    <!-- Items and Payment History -->
    <div class="col-12 col-lg-8">
        <!-- Products Table -->
        <div class="card mb-4">
            <div class="card-header">
                <h6><i class="bi bi-box-seam me-2 text-primary"></i> Artículos Incluidos</h6>
            </div>
            <div class="table-responsive">
                <table class="table align-middle">
                    <thead>
                        <tr>
                            <th>Producto</th>
                            <th class="num">Cant.</th>
                            <th class="num">Precio Unit.</th>
                            <th class="num">Descuento</th>
                            <th class="num">Subtotal</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($venta->detalles as $det)
                        <tr>
                            <td>
                                <a href="{{ route('productos.show', $det->producto) }}" class="fw-bold text-dark text-decoration-none hover-primary">
                                    {{ $det->producto->nombre }}
                                </a>
                                <div class="small text-muted font-monospace">{{ $det->producto->codigo }}</div>
                            </td>
                            <td class="num">{{ $det->cantidad }}</td>
                            <td class="num">${{ number_format($det->precio_unitario, 2) }}</td>
                            <td class="num text-danger">-${{ number_format($det->descuento, 2) }}</td>
                            <td class="num text-dark fw-bold">${{ number_format($det->subtotal, 2) }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Payments History Table -->
        <div class="card mb-0">
            <div class="card-header">
                <h6><i class="bi bi-cash-stack me-2 text-primary"></i> Historial de Abonos / Recibos</h6>
                @if($venta->estado === 'activa')
                <a href="{{ route('pagos.create', $venta) }}" class="btn btn-sm btn-success">
                    <i class="bi bi-plus-lg me-1"></i> Registrar Abono
                </a>
                @endif
            </div>
            <div class="table-responsive">
                <table class="table align-middle">
                    <thead>
                        <tr>
                            <th>Recibo</th>
                            <th>Fecha</th>
                            <th>Cobrador</th>
                            <th class="num">Abono</th>
                            <th class="num">Mora</th>
                            <th class="num">Total Cobrado</th>
                            <th>Método</th>
                            @if(auth()->user()->isAdmin())
                            <th class="text-end">Acción</th>
                            @endif
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($venta->pagos as $pago)
                        <tr>
                            <td>
                                <a href="{{ route('pagos.show', $pago) }}" class="fw-bold text-primary text-decoration-none">
                                    {{ $pago->numero_recibo }}
                                </a>
                            </td>
                            <td class="text-secondary small">{{ $pago->fecha_pago->format('d/m/Y') }}</td>
                            <td class="text-secondary small">{{ $pago->cobrador->name }}</td>
                            <td class="num">${{ number_format($pago->monto, 2) }}</td>
                            <td class="num {{ $pago->mora > 0 ? 'text-warning fw-bold' : 'text-muted' }}">
                                ${{ number_format($pago->mora, 2) }}
                            </td>
                            <td class="num text-success fw-bold">${{ number_format($pago->total_cobrado, 2) }}</td>
                            <td>
                                <span class="badge badge-secondary-soft">{{ ucfirst($pago->metodo_pago) }}</span>
                            </td>
                            @if(auth()->user()->isAdmin())
                            <td class="text-end">
                                <form method="POST" action="{{ route('pagos.destroy', $pago) }}" class="d-inline"
                                      onsubmit="return confirm('¿Eliminar este pago y restaurar el saldo pendiente?')">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="btn-action action-danger" title="Eliminar recibo">
                                        <i class="bi bi-trash"></i>
                                    </button>
                                </form>
                            </td>
                            @endif
                        </tr>
                        @empty
                        <tr>
                            <td colspan="{{ auth()->user()->isAdmin() ? 8 : 7 }}" class="text-center py-4 text-muted">
                                No se registran abonos en esta venta aún
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
