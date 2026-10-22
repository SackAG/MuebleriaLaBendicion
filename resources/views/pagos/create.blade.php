@extends('layouts.app')
@section('title', 'Registrar Pago')

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('ventas.index') }}">Ventas</a></li>
    <li class="breadcrumb-item"><a href="{{ route('ventas.show', $venta) }}">{{ $venta->numero_venta }}</a></li>
    <li class="breadcrumb-item active">Registrar Pago</li>
@endsection

@section('content')
<div class="row justify-content-center">
    <div class="col-md-6">
        <div class="card shadow-sm border-0">
            <div class="card-header bg-transparent border-0 pt-4 pb-0">
                <h5 class="fw-bold mb-0"><i class="bi bi-cash-coin me-1 text-success"></i> Registrar Pago</h5>
            </div>
            <div class="card-body">
                <!-- Client / Venta summary -->
                <div class="alert alert-info d-flex align-items-center gap-3 py-2">
                    <i class="bi bi-person-circle fs-3"></i>
                    <div>
                        <div class="fw-bold">{{ $venta->cliente->nombre_completo }}</div>
                        <div class="small">Venta: {{ $venta->numero_venta }} &nbsp;|&nbsp;
                            Cuota sugerida: <strong>${{ number_format($venta->monto_cuota, 2) }}</strong>
                        </div>
                    </div>
                    <div class="ms-auto text-end">
                        <div class="text-danger fw-bold fs-5">${{ number_format($venta->saldo_pendiente, 2) }}</div>
                        <div class="small text-muted">Saldo pendiente</div>
                    </div>
                </div>

                <form method="POST" action="{{ route('pagos.store', $venta) }}">
                    @csrf
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label">Fecha del Cobro <span class="text-danger">*</span></label>
                            <input type="date" name="fecha_pago" class="form-control @error('fecha_pago') is-invalid @enderror"
                                   value="{{ old('fecha_pago', date('Y-m-d')) }}" required>
                            @error('fecha_pago')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Método de Pago <span class="text-danger">*</span></label>
                            <select name="metodo_pago" class="form-select" required>
                                <option value="efectivo" selected>Efectivo</option>
                                <option value="transferencia">Transferencia</option>
                                <option value="tarjeta">Tarjeta</option>
                                <option value="otro">Otro</option>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Monto a Cobrar <span class="text-danger">*</span></label>
                            <div class="input-group">
                                <span class="input-group-text">$</span>
                                <input type="number" name="monto" id="monto" class="form-control @error('monto') is-invalid @enderror"
                                       value="{{ old('monto', $venta->monto_cuota) }}"
                                       min="0.01" max="{{ $venta->saldo_pendiente }}" step="0.01" required>
                            </div>
                            @error('monto')<div class="invalid-feedback d-block">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Mora</label>
                            <div class="input-group">
                                <span class="input-group-text">$</span>
                                <input type="number" name="mora" id="mora" class="form-control"
                                       value="{{ old('mora', 0) }}" min="0" step="0.01">
                            </div>
                        </div>
                        <div class="col-12">
                            <div class="alert alert-success py-2 small">
                                <strong>Total a cobrar: <span id="total-cobrar">${{ number_format($venta->monto_cuota, 2) }}</span></strong>
                            </div>
                        </div>
                        <div class="col-12">
                            <label class="form-label">Notas</label>
                            <textarea name="notas" class="form-control" rows="2">{{ old('notas') }}</textarea>
                        </div>
                    </div>

                    <div class="d-flex gap-2 mt-4">
                        <button type="submit" class="btn btn-success">
                            <i class="bi bi-check-lg me-1"></i> Confirmar Cobro
                        </button>
                        <a href="{{ route('ventas.show', $venta) }}" class="btn btn-outline-secondary">Cancelar</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
function updateTotal() {
    const monto = parseFloat(document.getElementById('monto').value) || 0;
    const mora  = parseFloat(document.getElementById('mora').value) || 0;
    document.getElementById('total-cobrar').textContent = '$' + (monto + mora).toFixed(2);
}
document.getElementById('monto').addEventListener('input', updateTotal);
document.getElementById('mora').addEventListener('input', updateTotal);
</script>
@endpush
