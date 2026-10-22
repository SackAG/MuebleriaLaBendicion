@extends('layouts.app')
@section('title', 'Nueva Venta')

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('ventas.index') }}">Ventas</a></li>
    <li class="breadcrumb-item active">Nueva</li>
@endsection

@push('styles')
<style>
    .product-row { transition: background .15s; }
    .product-row:hover { background: #f8f9fa; }
    #productos-table tbody tr:last-child td { border-bottom: none; }
</style>
@endpush

@section('content')
<div class="card shadow-sm border-0">
    <div class="card-header bg-transparent border-0 pt-4 pb-0">
        <h5 class="fw-bold mb-0"><i class="bi bi-cart-plus-fill me-1"></i> Nueva Venta</h5>
    </div>
    <div class="card-body">
        <form method="POST" action="{{ route('ventas.store') }}" id="ventaForm">
            @csrf

            <div class="row g-3 mb-4">
                <div class="col-md-4">
                    <label class="form-label">Cliente <span class="text-danger">*</span></label>
                    <select name="cliente_id" class="form-select @error('cliente_id') is-invalid @enderror" required>
                        <option value="">-- Seleccionar cliente --</option>
                        @foreach($clientes as $cliente)
                            <option value="{{ $cliente->id }}" {{ request('cliente_id') == $cliente->id || old('cliente_id') == $cliente->id ? 'selected' : '' }}>
                                {{ $cliente->nombre_completo }}
                            </option>
                        @endforeach
                    </select>
                    @error('cliente_id')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
                <div class="col-md-4">
                    <label class="form-label">Fecha de Venta <span class="text-danger">*</span></label>
                    <input type="date" name="fecha_venta" class="form-control" value="{{ old('fecha_venta', date('Y-m-d')) }}" required>
                </div>
                <div class="col-md-4">
                    <label class="form-label">Tipo de Pago <span class="text-danger">*</span></label>
                    <select name="tipo_pago" id="tipo_pago" class="form-select" required>
                        <option value="credito" {{ old('tipo_pago') == 'credito' ? 'selected' : '' }}>Crédito</option>
                        <option value="contado" {{ old('tipo_pago') == 'contado' ? 'selected' : '' }}>Contado</option>
                    </select>
                </div>
            </div>

            <!-- Crédito options -->
            <div id="credito-options" class="row g-3 mb-4 p-3 bg-light rounded">
                <div class="col-12"><h6 class="fw-semibold text-muted mb-0"><i class="bi bi-calendar-range me-1"></i>Opciones de Crédito</h6></div>
                <div class="col-md-3">
                    <label class="form-label">Enganche ($)</label>
                    <input type="number" name="enganche" id="enganche" class="form-control" value="{{ old('enganche', 0) }}" min="0" step="0.01">
                </div>
                <div class="col-md-3">
                    <label class="form-label">N° de Cuotas</label>
                    <input type="number" name="num_pagos" class="form-control" value="{{ old('num_pagos', 12) }}" min="1">
                </div>
                <div class="col-md-3">
                    <label class="form-label">Frecuencia</label>
                    <select name="frecuencia_pago" class="form-select">
                        <option value="semanal">Semanal</option>
                        <option value="quincenal">Quincenal</option>
                        <option value="mensual" selected>Mensual</option>
                    </select>
                </div>
                <div class="col-md-3">
                    <label class="form-label">Fecha 1er Pago</label>
                    <input type="date" name="fecha_primer_pago" class="form-control" value="{{ old('fecha_primer_pago') }}">
                </div>
            </div>

            <!-- Productos -->
            <h6 class="fw-semibold mb-2"><i class="bi bi-box-seam me-1"></i> Productos</h6>
            <div class="table-responsive mb-2">
                <table class="table border" id="productos-table">
                    <thead class="table-light">
                        <tr>
                            <th style="min-width:220px;">Producto</th>
                            <th style="min-width:90px;">Cant.</th>
                            <th style="min-width:120px;">Precio Unit.</th>
                            <th style="min-width:100px;">Descuento</th>
                            <th style="min-width:110px;">Subtotal</th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody id="productos-body">
                        <tr class="product-row" data-index="0">
                            <td>
                                <select name="productos[0][id]" class="form-select form-select-sm product-select" required>
                                    <option value="">-- Producto --</option>
                                    @foreach($productos as $prod)
                                    <option value="{{ $prod->id }}" data-precio="{{ $prod->precio_venta }}">
                                        {{ $prod->nombre }} (Stock: {{ $prod->stock }})
                                    </option>
                                    @endforeach
                                </select>
                            </td>
                            <td><input type="number" name="productos[0][cantidad]" class="form-control form-control-sm product-qty" value="1" min="1" required></td>
                            <td><input type="number" name="productos[0][precio]" class="form-control form-control-sm product-price" step="0.01" min="0" required></td>
                            <td><input type="number" name="productos[0][descuento]" class="form-control form-control-sm product-discount" step="0.01" min="0" value="0"></td>
                            <td><input type="text" class="form-control form-control-sm product-subtotal" readonly value="0.00"></td>
                            <td><button type="button" class="btn btn-sm btn-outline-danger remove-row"><i class="bi bi-trash"></i></button></td>
                        </tr>
                    </tbody>
                    <tfoot>
                        <tr class="table-light">
                            <td colspan="4" class="text-end fw-bold">Total:</td>
                            <td colspan="2" class="fw-bold text-success" id="grand-total">$0.00</td>
                        </tr>
                    </tfoot>
                </table>
            </div>
            <button type="button" id="add-row" class="btn btn-sm btn-outline-primary mb-4">
                <i class="bi bi-plus-lg me-1"></i> Agregar Producto
            </button>

            <div class="col-md-8">
                <label class="form-label">Notas (opcional)</label>
                <textarea name="notas" class="form-control" rows="2">{{ old('notas') }}</textarea>
            </div>

            <div class="d-flex gap-2 mt-4">
                <button type="submit" class="btn text-white" style="background:#4a1942;">
                    <i class="bi bi-check-lg me-1"></i> Registrar Venta
                </button>
                <a href="{{ route('ventas.index') }}" class="btn btn-outline-secondary">Cancelar</a>
            </div>
        </form>
    </div>
</div>
@endsection

@push('scripts')
<script>
// Productos data
const productosData = @json($productos->keyBy('id'));
let rowIndex = 1;

function calcRow(row) {
    const qty    = parseFloat(row.querySelector('.product-qty').value) || 0;
    const price  = parseFloat(row.querySelector('.product-price').value) || 0;
    const disc   = parseFloat(row.querySelector('.product-discount').value) || 0;
    const sub    = (price - disc) * qty;
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

// Auto fill price when product selected
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

// Add row
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

// Remove row
document.getElementById('productos-body').addEventListener('click', function(e) {
    if (e.target.closest('.remove-row')) {
        const rows = document.querySelectorAll('#productos-body tr');
        if (rows.length > 1) {
            e.target.closest('tr').remove();
            calcTotal();
        }
    }
});

// Toggle credit options
document.getElementById('tipo_pago').addEventListener('change', function() {
    document.getElementById('credito-options').style.display =
        this.value === 'credito' ? 'flex' : 'none';
    if (this.value === 'contado') {
        document.querySelector('[name="enganche"]').value = 0;
    }
});

// Init state
document.getElementById('credito-options').style.display =
    document.getElementById('tipo_pago').value === 'credito' ? 'flex' : 'none';
</script>
@endpush
