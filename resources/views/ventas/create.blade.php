@extends('layouts.app')
@section('title', 'Nueva Venta')

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('ventas.index') }}">Ventas</a></li>
    <li class="breadcrumb-item active">Nueva Venta</li>
@endsection

@push('styles')
<style>
    .product-row { transition: background .12s ease; }
    .product-row:hover { background: #f8fafc; }
</style>
@endpush

@section('content')
<div class="row justify-content-center">
    <div class="col-12 col-xl-10">
        <div class="card mb-0">
            <div class="card-header">
                <div>
                    <h6><i class="bi bi-cart-plus-fill me-2 text-primary"></i> Registrar Nueva Venta</h6>
                    <p class="text-muted small mb-0">Generación de contrato de crédito o venta de contado</p>
                </div>
            </div>
            <div class="card-body p-4">
                <form method="POST" action="{{ route('ventas.store') }}" id="ventaForm">
                    @csrf

                    <!-- Customer & Sale Terms -->
                    <div class="row g-3 mb-4">
                        <div class="col-12 col-md-5">
                            <label class="form-label">Cliente <span class="text-danger">*</span></label>
                            <select name="cliente_id" class="form-select @error('cliente_id') is-invalid @enderror" required>
                                <option value="">-- Seleccionar cliente --</option>
                                @foreach($clientes as $cliente)
                                    <option value="{{ $cliente->id }}" {{ request('cliente_id') == $cliente->id || old('cliente_id') == $cliente->id ? 'selected' : '' }}>
                                        {{ $cliente->nombre_completo }} {{ $cliente->dui ? '('.$cliente->dui.')' : '' }}
                                    </option>
                                @endforeach
                            </select>
                            @error('cliente_id')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-6 col-md-3">
                            <label class="form-label">Fecha de Venta <span class="text-danger">*</span></label>
                            <input type="date" name="fecha_venta" class="form-control" value="{{ old('fecha_venta', date('Y-m-d')) }}" required>
                        </div>
                        <div class="col-6 col-md-4">
                            <label class="form-label">Modalidad de Pago <span class="text-danger">*</span></label>
                            <select name="tipo_pago" id="tipo_pago" class="form-select fw-semibold" required>
                                <option value="credito" {{ old('tipo_pago') == 'credito' ? 'selected' : '' }}>Crédito (Abonos)</option>
                                <option value="contado" {{ old('tipo_pago') == 'contado' ? 'selected' : '' }}>Contado (Total)</option>
                            </select>
                        </div>
                    </div>

                    <!-- Crédito Options Panel -->
                    <div id="credito-options" class="p-3 mb-4 rounded-3 border bg-light">
                        <div class="d-flex align-items-center gap-2 mb-3">
                            <span class="badge badge-info-soft"><i class="bi bi-calendar-range me-1"></i> Condiciones de Financiamiento</span>
                        </div>
                        <div class="row g-3">
                            <div class="col-6 col-md-3">
                                <label class="form-label">Enganche / Anticipo ($)</label>
                                <div class="input-group input-group-sm">
                                    <span class="input-group-text">$</span>
                                    <input type="number" name="enganche" id="enganche" class="form-control" value="{{ old('enganche', 0) }}" min="0" step="0.01">
                                </div>
                            </div>
                            <div class="col-6 col-md-3">
                                <label class="form-label">N° de Cuotas</label>
                                <input type="number" name="num_pagos" class="form-control form-control-sm" value="{{ old('num_pagos', 12) }}" min="1">
                            </div>
                            <div class="col-6 col-md-3">
                                <label class="form-label">Frecuencia de Abono</label>
                                <select name="frecuencia_pago" class="form-select form-select-sm">
                                    <option value="semanal">Semanal</option>
                                    <option value="quincenal">Quincenal</option>
                                    <option value="mensual" selected>Mensual</option>
                                </select>
                            </div>
                            <div class="col-6 col-md-3">
                                <label class="form-label">Fecha 1er Pago</label>
                                <input type="date" name="fecha_primer_pago" class="form-control form-control-sm" value="{{ old('fecha_primer_pago') }}">
                            </div>
                        </div>
                    </div>

                    <!-- Products Grid -->
                    <div class="d-flex align-items-center justify-content-between mb-2">
                        <label class="form-label fw-bold mb-0">
                            <i class="bi bi-box-seam me-1 text-primary"></i> Artículos de la Venta
                        </label>
                        <button type="button" id="add-row" class="btn btn-sm btn-outline-secondary">
                            <i class="bi bi-plus-lg me-1"></i> Agregar Producto
                        </button>
                    </div>

                    <div class="table-responsive border rounded-3 mb-3">
                        <table class="table align-middle mb-0" id="productos-table">
                            <thead>
                                <tr>
                                    <th style="min-width: 240px;">Producto</th>
                                    <th style="width: 100px;">Cant.</th>
                                    <th style="width: 140px;">Precio Unit.</th>
                                    <th style="width: 120px;">Descuento</th>
                                    <th style="width: 130px;" class="num">Subtotal</th>
                                    <th style="width: 48px;"></th>
                                </tr>
                            </thead>
                            <tbody id="productos-body">
                                <tr class="product-row" data-index="0">
                                    <td>
                                        <select name="productos[0][id]" class="form-select form-select-sm product-select" required>
                                            <option value="">-- Seleccionar producto --</option>
                                            @foreach($productos as $prod)
                                            <option value="{{ $prod->id }}" data-precio="{{ $prod->precio_venta }}">
                                                {{ $prod->nombre }} (Stock: {{ $prod->stock }})
                                            </option>
                                            @endforeach
                                        </select>
                                    </td>
                                    <td>
                                        <input type="number" name="productos[0][cantidad]" class="form-control form-control-sm product-qty text-center" value="1" min="1" required>
                                    </td>
                                    <td>
                                        <div class="input-group input-group-sm">
                                            <span class="input-group-text">$</span>
                                            <input type="number" name="productos[0][precio]" class="form-control product-price" step="0.01" min="0" required>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="input-group input-group-sm">
                                            <span class="input-group-text">$</span>
                                            <input type="number" name="productos[0][descuento]" class="form-control product-discount" step="0.01" min="0" value="0">
                                        </div>
                                    </td>
                                    <td class="num">
                                        <input type="text" class="form-control form-control-sm product-subtotal text-end fw-bold font-monospace bg-light" readonly value="0.00">
                                    </td>
                                    <td class="text-center">
                                        <button type="button" class="btn-action action-danger remove-row" title="Quitar fila">
                                            <i class="bi bi-trash"></i>
                                        </button>
                                    </td>
                                </tr>
                            </tbody>
                            <tfoot>
                                <tr class="bg-light">
                                    <td colspan="4" class="text-end fw-bold py-3">Total a Pagar:</td>
                                    <td class="text-end py-3">
                                        <span class="fs-5 fw-bold text-success font-monospace" id="grand-total">$0.00</span>
                                    </td>
                                    <td></td>
                                </tr>
                            </tfoot>
                        </table>
                    </div>

                    <!-- Notes -->
                    <div class="mb-4">
                        <label class="form-label">Observaciones o Notas Especiales</label>
                        <textarea name="notas" class="form-control" rows="2" placeholder="Instrucciones de entrega, garantías pactadas...">{{ old('notas') }}</textarea>
                    </div>

                    <div class="d-flex align-items-center justify-content-end gap-2 pt-3 border-top">
                        <a href="{{ route('ventas.index') }}" class="btn btn-outline-secondary">Cancelar</a>
                        <button type="submit" class="btn btn-primary px-4">
                            <i class="bi bi-check-lg me-1"></i> Registrar Venta
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
const productosData = @json($productos->keyBy('id'));
let rowIndex = 1;

function calcRow(row) {
    const qty    = parseFloat(row.querySelector('.product-qty').value) || 0;
    const price  = parseFloat(row.querySelector('.product-price').value) || 0;
    const disc   = parseFloat(row.querySelector('.product-discount').value) || 0;
    const sub    = Math.max(0, (price - disc) * qty);
    row.querySelector('.product-subtotal').value = sub.toFixed(2);
    calcTotal();
}

function calcTotal() {
    let total = 0;
    document.querySelectorAll('.product-subtotal').forEach(el => {
        total += parseFloat(el.value) || 0;
    });
    document.getElementById('grand-total').textContent = '$' + total.toFixed(2);
}

document.getElementById('productos-body').addEventListener('change', function(e) {
    if (e.target.classList.contains('product-select')) {
        const row      = e.target.closest('tr');
        const selected = e.target.options[e.target.selectedIndex];
        const precio   = selected.dataset.precio || 0;
        row.querySelector('.product-price').value = precio;
        calcRow(row);
    }
    if (e.target.classList.contains('product-qty') ||
        e.target.classList.contains('product-price') ||
        e.target.classList.contains('product-discount')) {
        calcRow(e.target.closest('tr'));
    }
});

document.getElementById('productos-body').addEventListener('input', function(e) {
    if (e.target.classList.contains('product-qty') ||
        e.target.classList.contains('product-price') ||
        e.target.classList.contains('product-discount')) {
        calcRow(e.target.closest('tr'));
    }
});

document.getElementById('add-row').addEventListener('click', function() {
    const tbody    = document.getElementById('productos-body');
    const firstRow = tbody.querySelector('tr');
    const newRow   = firstRow.cloneNode(true);
    newRow.dataset.index = rowIndex;

    newRow.querySelectorAll('input, select').forEach(el => {
        el.name = el.name.replace(/\[\d+\]/, `[${rowIndex}]`);
        if (el.type !== 'text') el.value = el.type === 'number' ? (el.classList.contains('product-qty') ? 1 : 0) : '';
    });
    tbody.appendChild(newRow);
    rowIndex++;
});

document.getElementById('productos-body').addEventListener('click', function(e) {
    if (e.target.closest('.remove-row')) {
        const rows = document.querySelectorAll('#productos-body tr');
        if (rows.length > 1) {
            e.target.closest('tr').remove();
            calcTotal();
        }
    }
});

document.getElementById('tipo_pago').addEventListener('change', function() {
    document.getElementById('credito-options').style.display =
        this.value === 'credito' ? 'block' : 'none';
    if (this.value === 'contado') {
        document.querySelector('[name="enganche"]').value = 0;
    }
});

document.getElementById('credito-options').style.display =
    document.getElementById('tipo_pago').value === 'credito' ? 'block' : 'none';
</script>
@endpush
