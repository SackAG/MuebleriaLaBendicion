<?php $__env->startSection('title', 'Clientes'); ?>

<?php $__env->startSection('breadcrumb'); ?>
    <li class="breadcrumb-item active">Clientes</li>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>
<div class="d-flex align-items-center justify-content-between mb-3">
    <h5 class="fw-bold mb-0"><i class="bi bi-people-fill me-1"></i> Clientes</h5>
    <a href="<?php echo e(route('clientes.create')); ?>" class="btn btn-sm text-white" style="background:#4a1942;">
        <i class="bi bi-plus-lg me-1"></i> Nuevo Cliente
    </a>
</div>

<div class="card shadow-sm border-0">
    <div class="card-body">
        <!-- Search -->
        <form method="GET" class="row g-2 mb-3">
            <div class="col-md-5">
                <div class="input-group input-group-sm">
                    <span class="input-group-text"><i class="bi bi-search"></i></span>
                    <input type="text" name="search" class="form-control" placeholder="Buscar por nombre, teléfono o DUI..." value="<?php echo e($search); ?>">
                </div>
            </div>
            <div class="col-auto">
                <button type="submit" class="btn btn-sm btn-primary">Buscar</button>
                <?php if($search): ?><a href="<?php echo e(route('clientes.index')); ?>" class="btn btn-sm btn-outline-secondary">Limpiar</a><?php endif; ?>
            </div>
        </form>

        <div class="table-responsive">
            <table class="table table-hover align-middle">
                <thead>
                    <tr>
                        <th>Nombre</th>
                        <th>Teléfono</th>
                        <th>DUI</th>
                        <?php if(auth()->user()->isAdmin()): ?><th>Cobrador</th><?php endif; ?>
                        <th>Saldo Pendiente</th>
                        <th>Estado</th>
                        <th class="text-end">Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    <?php $__empty_1 = true; $__currentLoopData = $clientes; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $cliente): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                    <tr>
                        <td>
                            <a href="<?php echo e(route('clientes.show', $cliente)); ?>" class="fw-semibold text-decoration-none">
                                <?php echo e($cliente->nombre_completo); ?>

                            </a>
                        </td>
                        <td><?php echo e($cliente->telefono ?? '-'); ?></td>
                        <td><?php echo e($cliente->dui ?? '-'); ?></td>
                        <?php if(auth()->user()->isAdmin()): ?>
                        <td><span class="badge bg-secondary"><?php echo e($cliente->cobrador->name); ?></span></td>
                        <?php endif; ?>
                        <td class="<?php echo e($cliente->saldo_total > 0 ? 'text-danger fw-semibold' : 'text-success'); ?>">
                            $<?php echo e(number_format($cliente->saldo_total, 2)); ?>

                        </td>
                        <td>
                            <?php if($cliente->activo): ?>
                                <span class="badge bg-success">Activo</span>
                            <?php else: ?>
                                <span class="badge bg-secondary">Inactivo</span>
                            <?php endif; ?>
                        </td>
                        <td class="text-end">
                            <a href="<?php echo e(route('clientes.show', $cliente)); ?>" class="btn btn-sm btn-outline-info" title="Ver"><i class="bi bi-eye"></i></a>
                            <a href="<?php echo e(route('clientes.edit', $cliente)); ?>" class="btn btn-sm btn-outline-warning" title="Editar"><i class="bi bi-pencil"></i></a>
                            <form method="POST" action="<?php echo e(route('clientes.destroy', $cliente)); ?>" class="d-inline"
                                  onsubmit="return confirm('¿Eliminar cliente?')">
                                <?php echo csrf_field(); ?> <?php echo method_field('DELETE'); ?>
                                <button class="btn btn-sm btn-outline-danger" title="Eliminar"><i class="bi bi-trash"></i></button>
                            </form>
                        </td>
                    </tr>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                    <tr>
                        <td colspan="8" class="text-center text-muted py-4">
                            <i class="bi bi-people fs-2 d-block mb-2"></i>
                            No se encontraron clientes
                        </td>
                    </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>

        <?php echo e($clientes->appends(request()->query())->links()); ?>

    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\isaac\Documents\MuebleriaBendicion\resources\views/clientes/index.blade.php ENDPATH**/ ?>