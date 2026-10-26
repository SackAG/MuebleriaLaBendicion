@extends('layouts.app')
@section('title', 'Nueva Compra')

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('compras.index') }}">Compras</a></li>
    <li class="breadcrumb-item active">Nueva Compra</li>
@endsection

@section('content')
<div class="row justify-content-center">
    <div class="col-12 col-xl-9">
        <div class="card mb-0">
            <div class="card-header">
                <div>
                    <h6><i class="bi bi-truck me-2 text-primary"></i> Registrar Nueva Compra a Proveedor</h6>
                    <p class="text-muted small mb-0">Recepción de mercancía y aumento automático de existencias</p>
                </div>
            </div>
            <div class="card-body p-4">
                <form method="POST" action="{{ route('compras.store') }}" id="compraForm">
                    @csrf
                    <div class="row g-3 mb-4">
                        <div class="col-12 col-md-5">
                            <label class="form-label">Proveedor <span class="text-danger">*</span></label>
                            <select name="proveedor_id" class="form-select @error('proveedor_id') is-invalid @enderror" required>
                                <option value="">-- Seleccionar proveedor --</option>
                                @foreach($proveedores as $prov)
                                <option value="{{ $prov->id }}" {{ old('proveedor_id') == $prov->id ? 'selected' : '' }}>
                                    {{ $prov->nombre }}
                                </option>
                                @endforeach
                            </select>
                            @error('proveedor_id')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-6 col-md-3">
                            <label class="form-label">Fecha de Compra <span class="text-danger">*</span></label>
                            <input type="date" name="fecha_compra" class="form-control" value="{{ old('fecha_compra', date('Y-m-d')) }}" required>
                        </div>
                        <div class="col-12 col-md-4">
                            <label class="form-label">Factura / Remisión / Notas</label>
                            <input type="text" name="notas" class="form-control" placeholder="N° de factura o remisión del proveedor" value="{{ old('notas') }}">
                        </div>
                    </div>

                    <!-- Products Table -->
                    <div class="d-flex align-items-center justify-content-between mb-2">
                        <label class="form-label fw-bold mb-0">
                            <i class="bi bi-box-seam me-1 text-primary"></i> Productos a Ingresar al Inventario
                        </label>
                        <button type="button" id="add-row" class="btn btn-sm btn-outline-secondary">
                            <i class="bi bi-plus-lg me-1"></i> Agregar Producto
                        </button>
                    </div>

                    <div class="table-responsive border rounded-3 mb-3">
                        <table class="table align-middle mb-0" id="productos-table">
                            <thead>
                                <tr>
                                    <th style="min-width: 260px;">Producto</th>
                                    <th style="width: 120px;">Cantidad</th>
                                    <th style="width: 150px;">Precio Unit. Costo</th>
                                    <th style="width: 140px;" class="num">Subtotal</th>
                                    <th style="width: 48px;"></th>
                                </tr>
                            </thead>
                            <tbody id="productos-body">
                                <tr data-index="0">
                                    <td>
                                        <select name="productos[0][id]" class="form-select form-select-sm" required>
                                            <option value="">-- Seleccionar producto --</option>
                                            @foreach($productos as $prod)
                                            <option value="{{ $prod->id }}" data-costo="{{ $prod->precio_costo }}">
                                                {{ $prod->nombre }} (Stock actual: {{ $prod->stock }})
                                            </option>
                                            @endforeach
                                        </select>
                                    </td>
                                    <td>
                                        <input type="number" name="productos[0][cantidad]" class="form-control form-control-sm qty text-center" value="1" min="1" required>
                                    </td>
                                    <td>
                                        <div class="input-group input-group-sm">
                                            <span class="input-group-text">$</span>
                                            <input type="number" name="productos[0][precio]" class="form-control price" step="0.01" min="0" required>
                                        </div>
                                    </td>
                                    <td class="num">
                                        <input type="text" class="form-control form-control-sm subtotal text-end fw-bold font-monospace bg-light" readonly value="0.00">
                                    </td>
                                    <td class="text-center">
                                        <button type="button" class="btn-action action-danger remove-row" title="Quitar">
                                            <i class="bi bi-trash"></i>
                                        </button>
                                    </td>
                                </tr>
                            </tbody>
                            <tfoot>
                                <tr class="bg-light">
                                    <td colspan="3" class="text-end fw-bold py-3">Total de la Compra:</td>
                                    <td class="text-end py-3">
                                        <span class="fs-5 fw-bold text-primary font-monospace" id="grand-total">$0.00</span>
                                    </td>
                                    <td></td>
                                </tr>
                            </tfoot>
                        </table>
                    </div>

                    <div class="d-flex align-items-center justify-content-end gap-2 pt-3 border-top">
                        <a href="{{ route('compras.index') }}" class="btn btn-outline-secondary">Cancelar</a>
                        <button type="submit" class="btn btn-primary px-4">
                            <i class="bi bi-check-lg me-1"></i> Registrar Compra
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
let rowIndex = 1;

function calcRow(row) {
    const qty   = parseFloat(row.querySelector('.qty').value) || 0;
    const price = parseFloat(row.querySelector('.price').value) || 0;
    row.querySelector('.subtotal').value = (qty * price).toFixed(2);
    calcTotal();
}

function calcTotal() {
    let total = 0;
    document.querySelectorAll('.subtotal').forEach(el => total += parseFloat(el.value) || 0);
    document.getElementById('grand-total').textContent = '$' + total.toFixed(2);
}

document.getElementById('productos-body').addEventListener('change', function(e) {
    if (e.target.tagName === 'SELECT') {
        const row    = e.target.closest('tr');
        const opt    = e.target.options[e.target.selectedIndex];
        row.querySelector('.price').value = opt.dataset.costo || 0;
        calcRow(row);
    }
});

document.getElementById('productos-body').addEventListener('input', function(e) {
    if (e.target.classList.contains('qty') || e.target.classList.contains('price')) {
        calcRow(e.target.closest('tr'));
    }
});

document.getElementById('add-row').addEventListener('click', function() {
    const tbody  = document.getElementById('productos-body');
    const newRow = tbody.querySelector('tr').cloneNode(true);
    newRow.dataset.index = rowIndex;
    newRow.querySelectorAll('input, select').forEach(el => {
        el.name = el.name.replace(/\[\d+\]/, `[${rowIndex}]`);
        if (el.tagName === 'INPUT' && el.type !== 'text') el.value = el.classList.contains('qty') ? 1 : 0;
        if (el.tagName === 'SELECT') el.value = '';
    });
    tbody.appendChild(newRow);
    rowIndex++;
});

document.getElementById('productos-body').addEventListener('click', function(e) {
    if (e.target.closest('.remove-row')) {
        if (document.querySelectorAll('#productos-body tr').length > 1) {
            e.target.closest('tr').remove();
            calcTotal();
        }
    }
});
</script>
@endpush
