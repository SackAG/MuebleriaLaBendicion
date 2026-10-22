<?php $__env->startSection('title', 'Dashboard - Admin'); ?>

<?php $__env->startSection('breadcrumb'); ?>
    <li class="breadcrumb-item active">Dashboard</li>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>

<div class="d-flex align-items-center justify-content-between mb-4">
    <div>
        <h4 class="fw-bold mb-0">Dashboard</h4>
        <p class="text-muted small mb-0">Resumen general de Mueblería La Bendición</p>
    </div>
    <span class="text-muted small"><i class="bi bi-calendar3 me-1"></i><?php echo e(now()->format('d/m/Y')); ?></span>
</div>

<!-- Stats Row 1 -->
<div class="row g-3 mb-4">
    <div class="col-6 col-md-3">
        <div class="card stat-card p-3">
            <div class="d-flex align-items-center gap-3">
                <div class="stat-icon" style="background:#e8f5e9;color:#198754;"><i class="bi bi-people-fill"></i></div>
                <div>
                    <div class="text-muted small">Total Clientes</div>
                    <div class="fw-bold fs-5"><?php echo e(number_format($stats['total_clientes'])); ?></div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-6 col-md-3">
        <div class="card stat-card p-3">
            <div class="d-flex align-items-center gap-3">
                <div class="stat-icon" style="background:#e3f2fd;color:#0d6efd;"><i class="bi bi-cart-fill"></i></div>
                <div>
                    <div class="text-muted small">Ventas Activas</div>
                    <div class="fw-bold fs-5"><?php echo e(number_format($stats['ventas_activas'])); ?></div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-6 col-md-3">
        <div class="card stat-card p-3">
            <div class="d-flex align-items-center gap-3">
                <div class="stat-icon" style="background:#fff3e0;color:#fd7e14;"><i class="bi bi-cash-stack"></i></div>
                <div>
                    <div class="text-muted small">Cobros del Mes</div>
                    <div class="fw-bold fs-5">$<?php echo e(number_format($stats['cobros_mes'], 2)); ?></div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-6 col-md-3">
        <div class="card stat-card p-3">
            <div class="d-flex align-items-center gap-3">
                <div class="stat-icon" style="background:#fce4ec;color:#dc3545;"><i class="bi bi-hourglass-split"></i></div>
                <div>
                    <div class="text-muted small">Saldo Pendiente</div>
                    <div class="fw-bold fs-5">$<?php echo e(number_format($stats['saldo_pendiente_total'], 2)); ?></div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Stats Row 2 -->
<div class="row g-3 mb-4">
    <div class="col-6 col-md-3">
        <div class="card stat-card p-3">
            <div class="d-flex align-items-center gap-3">
                <div class="stat-icon" style="background:#f3e5f5;color:#6d2c63;"><i class="bi bi-box-seam-fill"></i></div>
                <div>
                    <div class="text-muted small">Productos</div>
                    <div class="fw-bold fs-5"><?php echo e(number_format($stats['total_productos'])); ?></div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-6 col-md-3">
        <div class="card stat-card p-3">
            <div class="d-flex align-items-center gap-3">
                <div class="stat-icon" style="background:#fff8e1;color:#ffc107;"><i class="bi bi-exclamation-triangle-fill"></i></div>
                <div>
                    <div class="text-muted small">Bajo Stock</div>
                    <div class="fw-bold fs-5 <?php echo e($stats['productos_bajo_stock'] > 0 ? 'text-warning' : ''); ?>">
                        <?php echo e(number_format($stats['productos_bajo_stock'])); ?>

                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-6 col-md-3">
        <div class="card stat-card p-3">
            <div class="d-flex align-items-center gap-3">
                <div class="stat-icon" style="background:#e8f5e9;color:#198754;"><i class="bi bi-graph-up-arrow"></i></div>
                <div>
                    <div class="text-muted small">Ventas del Mes</div>
                    <div class="fw-bold fs-5">$<?php echo e(number_format($stats['ventas_mes'], 2)); ?></div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-6 col-md-3">
        <div class="card stat-card p-3">
            <div class="d-flex align-items-center gap-3">
                <div class="stat-icon" style="background:#e3f2fd;color:#0d6efd;"><i class="bi bi-truck"></i></div>
                <div>
                    <div class="text-muted small">Deuda Proveedores</div>
                    <div class="fw-bold fs-5">$<?php echo e(number_format($stats['compras_pendientes'], 2)); ?></div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row g-3">
    <!-- Cobradores Performance -->
    <div class="col-md-4">
        <div class="card stat-card h-100">
            <div class="card-header bg-transparent border-0 pt-3 pb-0">
                <h6 class="fw-bold mb-0"><i class="bi bi-person-badge-fill me-1 text-primary"></i> Cobradores del Mes</h6>
            </div>
            <div class="card-body p-3">
                <?php $__empty_1 = true; $__currentLoopData = $stats['cobradores']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $cobrador): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                <div class="d-flex align-items-center justify-content-between py-2 border-bottom">
                    <div>
                        <div class="fw-semibold small"><?php echo e($cobrador->name); ?></div>
                        <div class="text-muted" style="font-size:.75rem;"><?php echo e($cobrador->clientes_count); ?> clientes</div>
                    </div>
                    <div class="text-success fw-bold small">
                        $<?php echo e(number_format($cobrador->pagos_sum_total_cobrado ?? 0, 2)); ?>

                    </div>
                </div>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                    <p class="text-muted small text-center py-3">Sin cobradores registrados</p>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <!-- Ventas Recientes -->
    <div class="col-md-8">
        <div class="card stat-card h-100">
            <div class="card-header bg-transparent border-0 pt-3 pb-0 d-flex justify-content-between align-items-center">
                <h6 class="fw-bold mb-0"><i class="bi bi-cart-fill me-1 text-success"></i> Ventas Recientes</h6>
                <a href="<?php echo e(route('ventas.index')); ?>" class="btn btn-sm btn-outline-secondary">Ver todas</a>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover mb-0">
                        <thead>
                            <tr>
                                <th>N° Venta</th>
                                <th>Cliente</th>
                                <th>Cobrador</th>
                                <th>Total</th>
                                <th>Saldo</th>
                                <th>Estado</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php $__empty_1 = true; $__currentLoopData = $ventasRecientes; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $venta): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                            <tr>
                                <td><a href="<?php echo e(route('ventas.show', $venta)); ?>" class="text-decoration-none fw-semibold"><?php echo e($venta->numero_venta); ?></a></td>
                                <td><?php echo e($venta->cliente->nombre_completo); ?></td>
                                <td><span class="text-muted small"><?php echo e($venta->cobrador->name); ?></span></td>
                                <td>$<?php echo e(number_format($venta->total, 2)); ?></td>
                                <td class="<?php echo e($venta->saldo_pendiente > 0 ? 'text-danger' : 'text-success'); ?>">$<?php echo e(number_format($venta->saldo_pendiente, 2)); ?></td>
                                <td><span class="badge badge-<?php echo e($venta->estado); ?>"><?php echo e(ucfirst($venta->estado)); ?></span></td>
                            </tr>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                            <tr><td colspan="6" class="text-center text-muted py-3">Sin ventas registradas</td></tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <!-- Productos bajo stock -->
    <?php if($productosBajoStock->count() > 0): ?>
    <div class="col-12">
        <div class="card stat-card border-warning">
            <div class="card-header bg-warning bg-opacity-10 border-warning border-bottom-0 pt-3 pb-0 d-flex justify-content-between align-items-center">
                <h6 class="fw-bold mb-0 text-warning"><i class="bi bi-exclamation-triangle-fill me-1"></i> Productos con Bajo Stock</h6>
                <a href="<?php echo e(route('productos.index', ['bajo_stock' => 1])); ?>" class="btn btn-sm btn-outline-warning">Ver todos</a>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover mb-0">
                        <thead>
                            <tr>
                                <th>Código</th>
                                <th>Producto</th>
                                <th>Categoría</th>
                                <th>Stock Actual</th>
                                <th>Stock Mínimo</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php $__currentLoopData = $productosBajoStock; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $prod): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <tr>
                                <td><code><?php echo e($prod->codigo); ?></code></td>
                                <td><a href="<?php echo e(route('productos.show', $prod)); ?>" class="text-decoration-none"><?php echo e($prod->nombre); ?></a></td>
                                <td><?php echo e($prod->categoria->nombre ?? '-'); ?></td>
                                <td><span class="badge bg-danger"><?php echo e($prod->stock); ?></span></td>
                                <td><?php echo e($prod->stock_minimo); ?></td>
                            </tr>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    <?php endif; ?>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\isaac\Documents\MuebleriaBendicion\resources\views/dashboard/admin.blade.php ENDPATH**/ ?>