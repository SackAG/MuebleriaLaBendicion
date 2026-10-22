@extends('layouts.app')
@section('title', 'Recibo ' . $pago->numero_recibo)

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('pagos.index') }}">Cobros</a></li>
    <li class="breadcrumb-item active">{{ $pago->numero_recibo }}</li>
@endsection

@section('content')
<div class="row justify-content-center">
    <div class="col-md-7">
        <div class="card shadow-sm border-0">
            <div class="card-header bg-transparent border-0 pt-4 pb-2 d-flex justify-content-between align-items-center">
                <h5 class="fw-bold mb-0"><i class="bi bi-receipt me-1"></i> Recibo de Pago</h5>
                <span class="fw-bold text-muted">{{ $pago->numero_recibo }}</span>
            </div>
            <div class="card-body">
                <div class="row g-3">
                    <div class="col-md-6">
                        <p class="text-muted small mb-1">Cliente</p>
                        <p class="fw-semibold mb-0">{{ $pago->venta->cliente->nombre_completo }}</p>
                    </div>
                    <div class="col-md-6">
                        <p class="text-muted small mb-1">N° Venta</p>
                        <p class="fw-semibold mb-0">
                            <a href="{{ route('ventas.show', $pago->venta) }}" class="text-decoration-none">{{ $pago->venta->numero_venta }}</a>
                        </p>
                    </div>
                    <div class="col-md-6">
                        <p class="text-muted small mb-1">Cobrador</p>
                        <p class="fw-semibold mb-0">{{ $pago->cobrador->name }}</p>
                    </div>
                    <div class="col-md-6">
                        <p class="text-muted small mb-1">Fecha del Cobro</p>
                        <p class="fw-semibold mb-0">{{ $pago->fecha_pago->format('d/m/Y') }}</p>
                    </div>
                    <div class="col-md-4">
                        <p class="text-muted small mb-1">Monto</p>
                        <p class="fw-semibold mb-0 text-success">${{ number_format($pago->monto, 2) }}</p>
                    </div>
                    <div class="col-md-4">
                        <p class="text-muted small mb-1">Mora</p>
                        <p class="fw-semibold mb-0 text-warning">${{ number_format($pago->mora, 2) }}</p>
                    </div>
                    <div class="col-md-4">
                        <p class="text-muted small mb-1">Total Cobrado</p>
                        <p class="fw-bold mb-0 fs-5 text-success">${{ number_format($pago->total_cobrado, 2) }}</p>
                    </div>
                    <div class="col-md-6">
                        <p class="text-muted small mb-1">Método</p>
                        <p class="fw-semibold mb-0">{{ ucfirst($pago->metodo_pago) }}</p>
                    </div>
                    @if($pago->notas)
                    <div class="col-12">
                        <p class="text-muted small mb-1">Notas</p>
                        <p class="mb-0">{{ $pago->notas }}</p>
                    </div>
                    @endif
                </div>
            </div>
            <div class="card-footer bg-transparent d-flex justify-content-between align-items-center">
                <a href="{{ route('ventas.show', $pago->venta) }}" class="btn btn-outline-secondary btn-sm">
                    <i class="bi bi-arrow-left me-1"></i> Volver a Venta
                </a>
                @if(auth()->user()->isAdmin())
                <form method="POST" action="{{ route('pagos.destroy', $pago) }}" onsubmit="return confirm('¿Eliminar pago?')">
                    @csrf @method('DELETE')
                    <button class="btn btn-sm btn-outline-danger"><i class="bi bi-trash me-1"></i> Eliminar</button>
                </form>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection
