@extends('layouts.app')
@section('title', 'Recibo ' . $pago->numero_recibo)

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('pagos.index') }}">Cobros</a></li>
    <li class="breadcrumb-item active">{{ $pago->numero_recibo }}</li>
@endsection

@section('content')
<div class="row justify-content-center">
    <div class="col-12 col-md-8 col-lg-6">
        <div class="card mb-0">
            <!-- Receipt Header -->
            <div class="card-header bg-light py-3">
                <div class="d-flex align-items-center gap-2">
                    <div class="user-avatar" style="width:36px;height:36px;background:var(--success-subtle);color:var(--success);">
                        <i class="bi bi-receipt"></i>
                    </div>
                    <div>
                        <h6 class="mb-0">Comprobante de Pago</h6>
                        <span class="text-muted small">Mueblería La Bendición</span>
                    </div>
                </div>
                <div class="text-end">
                    <span class="font-monospace fw-bold text-dark fs-6">{{ $pago->numero_recibo }}</span>
                    <span class="badge badge-success-soft d-block mt-1">Registrado</span>
                </div>
            </div>

            <div class="card-body p-4">
                <div class="row g-3 mb-4">
                    <div class="col-12 col-sm-6">
                        <span class="text-muted small fw-semibold d-block">Cliente</span>
                        <a href="{{ route('clientes.show', $pago->venta->cliente) }}" class="fw-bold text-dark text-decoration-none hover-primary">
                            {{ $pago->venta->cliente->nombre_completo }}
                        </a>
                    </div>
                    <div class="col-12 col-sm-6">
                        <span class="text-muted small fw-semibold d-block">N° de Venta / Contrato</span>
                        <a href="{{ route('ventas.show', $pago->venta) }}" class="fw-semibold text-primary text-decoration-none">
                            {{ $pago->venta->numero_venta }}
                        </a>
                    </div>
                    <div class="col-12 col-sm-6">
                        <span class="text-muted small fw-semibold d-block">Cobrador Responsable</span>
                        <span class="text-secondary fw-medium">{{ $pago->cobrador->name }}</span>
                    </div>
                    <div class="col-12 col-sm-6">
                        <span class="text-muted small fw-semibold d-block">Fecha de Cobro</span>
                        <span class="text-secondary">{{ $pago->fecha_pago->format('d/m/Y') }}</span>
                    </div>
                    <div class="col-12 col-sm-6">
                        <span class="text-muted small fw-semibold d-block">Método de Pago</span>
                        <span class="badge badge-secondary-soft">{{ ucfirst($pago->metodo_pago) }}</span>
                    </div>
                </div>

                <!-- Financial Highlight Box -->
                <div class="p-3 rounded-3 bg-light border mb-3">
                    <div class="d-flex justify-content-between mb-2 small text-secondary">
                        <span>Monto del Abono</span>
                        <span class="fw-semibold font-monospace">${{ number_format($pago->monto, 2) }}</span>
                    </div>
                    <div class="d-flex justify-content-between mb-2 small text-secondary">
                        <span>Recargo por Retraso (Mora)</span>
                        <span class="fw-semibold font-monospace {{ $pago->mora > 0 ? 'text-warning' : 'text-muted' }}">
                            ${{ number_format($pago->mora, 2) }}
                        </span>
                    </div>
                    <div class="d-flex justify-content-between align-items-center pt-2 border-top">
                        <span class="fw-bold text-dark">Total Recibido</span>
                        <span class="fs-4 fw-bold text-success font-monospace">${{ number_format($pago->total_cobrado, 2) }}</span>
                    </div>
                </div>

                @if($pago->notas)
                <div class="p-3 rounded-3 bg-light border text-secondary small">
                    <span class="text-muted fw-semibold d-block mb-1">Notas / Observaciones:</span>
                    {{ $pago->notas }}
                </div>
                @endif
            </div>

            <div class="card-footer bg-white d-flex justify-content-between align-items-center">
                <a href="{{ route('ventas.show', $pago->venta) }}" class="btn btn-sm btn-outline-secondary">
                    <i class="bi bi-arrow-left me-1"></i> Volver a la Venta
                </a>
                <div class="d-flex gap-2">
                    <button type="button" onclick="window.print()" class="btn btn-sm btn-outline-secondary">
                        <i class="bi bi-printer me-1"></i> Imprimir
                    </button>
                    @if(auth()->user()->isAdmin())
                    <form method="POST" action="{{ route('pagos.destroy', $pago) }}" class="d-inline"
                          onsubmit="return confirm('¿Está seguro de eliminar este pago y restaurar el saldo pendiente de la venta?')">
                        @csrf @method('DELETE')
                        <button type="submit" class="btn btn-sm btn-outline-danger">
                            <i class="bi bi-trash me-1"></i> Eliminar
                        </button>
                    </form>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
