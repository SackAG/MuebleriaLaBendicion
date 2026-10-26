<?php $__env->startSection('title', 'Registrar Pago'); ?>

<?php $__env->startSection('breadcrumb'); ?>
    <li class="breadcrumb-item"><a href="<?php echo e(route('ventas.index')); ?>">Ventas</a></li>
    <li class="breadcrumb-item"><a href="<?php echo e(route('ventas.show', $venta)); ?>"><?php echo e($venta->numero_venta); ?></a></li>
    <li class="breadcrumb-item active">Registrar Cobro</li>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>
<div class="row justify-content-center">
    <div class="col-12 col-lg-7 col-xl-6">
        <div class="card mb-0">
            <div class="card-header">
                <div>
                    <h6><i class="bi bi-cash-coin me-2 text-success"></i> Registrar Abono / Cobro</h6>
                    <p class="text-muted small mb-0">Venta: <?php echo e($venta->numero_venta); ?></p>
                </div>
            </div>
            <div class="card-body p-4">
                <!-- Customer summary card -->
                <div class="p-3 mb-4 rounded-3 border bg-light">
                    <div class="d-flex align-items-center justify-content-between mb-2">
                        <div class="d-flex align-items-center gap-2">
                            <div class="user-avatar" style="width:34px;height:34px;font-size:.8rem;">
                                <?php echo e(strtoupper(substr($venta->cliente->nombre, 0, 1) . substr($venta->cliente->apellido, 0, 1))); ?>

                            </div>
                            <div>
                                <div class="fw-bold text-dark"><?php echo e($venta->cliente->nombre_completo); ?></div>
                                <div class="text-muted small">Contrato: <?php echo e($venta->numero_venta); ?></div>
                            </div>
                        </div>
                        <div class="text-end">
                            <span class="text-muted small d-block">Saldo Deudor</span>
                            <span class="fs-5 fw-bold text-danger font-monospace">$<?php echo e(number_format($venta->saldo_pendiente, 2)); ?></span>
                        </div>
                    </div>

                    <div class="row g-2 pt-2 border-top text-secondary small">
                        <div class="col-6 col-sm-3">
                            <span class="text-muted d-block" style="font-size:.7rem;">CUOTA</span>
                            <span class="fw-bold text-dark"><?php echo e($numeroPago); ?> de <?php echo e($venta->num_pagos); ?></span>
                        </div>
                        <div class="col-6 col-sm-3">
                            <span class="text-muted d-block" style="font-size:.7rem;">SUGERIDO</span>
                            <span class="fw-bold text-dark" id="cuota-sugerida-valor">$<?php echo e(number_format($cuotaSugerida, 2)); ?></span>
                        </div>
                        <div class="col-6 col-sm-3">
                            <span class="text-muted d-block" style="font-size:.7rem;">RESTANTES</span>
                            <span class="fw-bold text-dark"><?php echo e($pagosRestantes); ?></span>
                        </div>
                        <div class="col-6 col-sm-3">
                            <span class="text-muted d-block" style="font-size:.7rem;">FRECUENCIA</span>
                            <span class="fw-bold text-dark"><?php echo e(ucfirst($venta->frecuencia_pago)); ?></span>
                        </div>
                    </div>
                </div>

                <!-- Payment Form -->
                <form method="POST" action="<?php echo e(route('pagos.store', $venta)); ?>">
                    <?php echo csrf_field(); ?>
                    <div class="row g-3">
                        <div class="col-12 col-sm-6">
                            <label class="form-label">Fecha del Abono <span class="text-danger">*</span></label>
                            <input type="date" name="fecha_pago" class="form-control <?php $__errorArgs = ['fecha_pago'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                                   value="<?php echo e(old('fecha_pago', date('Y-m-d'))); ?>" required>
                            <?php $__errorArgs = ['fecha_pago'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><div class="invalid-feedback"><?php echo e($message); ?></div><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                        </div>
                        <div class="col-12 col-sm-6">
                            <label class="form-label">Método de Cobro <span class="text-danger">*</span></label>
                            <select name="metodo_pago" class="form-select" required>
                                <option value="efectivo" selected>Efectivo</option>
                                <option value="transferencia">Transferencia bancaria</option>
                                <option value="tarjeta">Tarjeta de débito/crédito</option>
                                <option value="otro">Otro</option>
                            </select>
                        </div>
                        <div class="col-12 col-sm-6">
                            <label class="form-label">Monto Abonado ($) <span class="text-danger">*</span></label>
                            <div class="input-group">
                                <span class="input-group-text">$</span>
                                <input type="number" name="monto" id="monto" class="form-control <?php $__errorArgs = ['monto'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                                       value="<?php echo e(old('monto', number_format($cuotaSugerida, 2, '.', ''))); ?>"
                                       min="0.01" max="<?php echo e($venta->saldo_pendiente); ?>" step="0.01" required>
                            </div>
                            <?php $__errorArgs = ['monto'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><div class="text-danger small mt-1"><?php echo e($message); ?></div><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                        </div>
                        <div class="col-12 col-sm-6">
                            <label class="form-label">Recargo por Retraso / Mora ($)</label>
                            <div class="input-group">
                                <span class="input-group-text">$</span>
                                <input type="number" name="mora" id="mora" class="form-control"
                                       value="<?php echo e(old('mora', 0)); ?>" min="0" step="0.01">
                            </div>
                        </div>

                        <!-- Total to Collect Highlight -->
                        <div class="col-12">
                            <div class="p-3 rounded-3 bg-success-subtle border border-success-subtle d-flex align-items-center justify-content-between">
                                <div class="d-flex align-items-center gap-2">
                                    <i class="bi bi-wallet2 fs-4 text-success"></i>
                                    <div>
                                        <div class="fw-bold text-success-emphasis" style="font-size:.85rem;">Total a Cobrar en Recibo:</div>
                                        <div class="text-muted" style="font-size:.72rem;">Monto base + mora calculada</div>
                                    </div>
                                </div>
                                <span class="fs-4 fw-bold text-success font-monospace" id="total-cobrar">$<?php echo e(number_format(old('monto', $cuotaSugerida) + old('mora', 0), 2)); ?></span>
                            </div>
                        </div>

                        <div class="col-12">
                            <label class="form-label">Notas o Referencia del Recibo</label>
                            <textarea name="notas" class="form-control" rows="2" placeholder="Detalles de pago, comprobante de transferencia..."><?php echo e(old('notas')); ?></textarea>
                        </div>
                    </div>

                    <div class="d-flex align-items-center justify-content-end gap-2 mt-4 pt-3 border-top">
                        <a href="<?php echo e(route('ventas.show', $venta)); ?>" class="btn btn-outline-secondary">Cancelar</a>
                        <button type="submit" class="btn btn-success px-4">
                            <i class="bi bi-check2-circle me-1"></i> Confirmar y Generar Recibo
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php $__env->startPush('scripts'); ?>
<script>
function updateTotal() {
    const montoEl = document.getElementById('monto');
    const moraEl  = document.getElementById('mora');
    const totalEl = document.getElementById('total-cobrar');
    if (!montoEl || !totalEl) return;

    const monto = parseFloat(montoEl.value) || 0;
    const mora  = parseFloat(moraEl?.value) || 0;
    const total = monto + mora;
    totalEl.textContent = '$' + total.toFixed(2);
}

document.addEventListener('DOMContentLoaded', function () {
    const montoInput = document.getElementById('monto');
    const moraInput  = document.getElementById('mora');
    const sugeridoEl = document.getElementById('cuota-sugerida-valor');

    montoInput?.addEventListener('input', updateTotal);
    montoInput?.addEventListener('change', updateTotal);
    moraInput?.addEventListener('input', updateTotal);
    moraInput?.addEventListener('change', updateTotal);

    sugeridoEl?.addEventListener('click', function () {
        if (montoInput) {
            montoInput.value = '<?php echo e(number_format($cuotaSugerida, 2, ".", "")); ?>';
            updateTotal();
        }
    });

    updateTotal();
});
updateTotal();
</script>
<?php $__env->stopPush(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\isaac\Documents\MuebleriaBendicion\resources\views/pagos/create.blade.php ENDPATH**/ ?>