@extends('layouts.app')
@section('title', 'Nueva Compra')

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('compras.index') }}">Compras</a></li>
    <li class="breadcrumb-item active">Nueva</li>
@endsection

@section('content')
<div class="card shadow-sm border-0">
    <div class="card-header bg-transparent border-0 pt-4 pb-0">
        <h5 class="fw-bold mb-0"><i class="bi bi-truck me-1"></i> Nueva Compra</h5>
    </div>
    <div class="card-body">
        <form method="POST" action="{{ route('compras.store') }}" id="compraForm">
            @csrf
            <div class="row g-3 mb-4">
                <div class="col-md-4">
                    <label class="form-label">Proveedor <span class="text-danger">*</span></label>
                    <select name="proveedor_id" class="form-select @error('proveedor_id') is-invalid @enderror" required>
                        <option value="">-- Seleccionar --</option>
                        @foreach($proveedores as $prov)
                        <option value="{{ $prov->id }}" {{ old('proveedor_id') == $prov->id ? 'selected' : '' }}>
                            {{ $prov->nombre }}
                        </option>
                        @endforeach
                    </select>
                    @error('proveedor_id')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
                <div class="col-md-3">
                    <label class="form-label">Fecha <span class="text-danger">*</span></label>
                    <input type="date" name="fecha_compra" class="form-control" value="{{ old('fecha_compra', date('Y-m-d')) }}" required>
                </div>
                <div class="col-md-5">
                    <label class="form-label">Notas</label>
                    <input type="text" name="notas" class="form-control" value="{{ old('notas') }}">
                </div>
            </div>

            <!-- Productos -->
            <h6 class="fw-semibold mb-2"><i class="bi bi-box-seam me-1"></i> Productos a Comprar</h6>
            <div class="table-responsive mb-2">
                <table class="table border" id="productos-table">
                    <thead class="table-light">
                        <tr>
                            <th style="min-width:220px;">Producto</th>
                            <th style="min-width:90px;">Cantidad</th>
                            <th style="min-width:120px;">Precio Unit.</th>
                            <th style="min-width:110px;">Subtotal</th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody id="productos-body">
                        <tr data-index="0">
                            <td>
                                <select name="productos[0][id]" class="form-select form-select-sm" required>
                                    <option value="">-- Producto --</option>
                                    @foreach($productos as $prod)
                                    <option value="{{ $prod->id }}" data-costo="{{ $prod->precio_costo }}">
                                        {{ $prod->nombre }}
                                    </option>
                                    @endforeach
                                </select>
                            </td>
                            <td><input type="number" name="productos[0][cantidad]" class="form-control form-control-sm qty" value="1" min="1" required></td>
                            <td><input type="number" name="productos[0][precio]" class="form-control form-control-sm price" step="0.01" min="0" required></td>
                            <td><input type="text" class="form-control form-control-sm subtotal" readonly value="0.00"></td>
                            <td><button type="button" class="btn btn-sm btn-outline-danger remove-row"><i class="bi bi-trash"></i></button></td>
                        </tr>
                    </tbody>
                    <tfoot>
                        <tr class="table-light">
                            <td colspan="3" class="text-end fw-bold">Total:</td>
                            <td colspan="2" class="fw-bold text-primary" id="grand-total">$0.00</td>
                        </tr>
                    </tfoot>
                </table>
            </div>
            <button type="button" id="add-row" class="btn btn-sm btn-outline-primary mb-4">
                <i class="bi bi-plus-lg me-1"></i> Agregar Producto
            </button>

            <div class="d-flex gap-2">
                <button type="submit" class="btn text-white" style="background:#4a1942;">
                    <i class="bi bi-check-lg me-1"></i> Registrar Compra
                </button>
                <a href="{{ route('compras.index') }}" class="btn btn-outline-secondary">Cancelar</a>
            </div>
        </form>
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
