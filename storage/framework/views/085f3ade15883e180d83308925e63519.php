
<?php $__env->startSection('content'); ?>
<div class="admin-content">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 style="font-family: 'Yeseva One', serif; font-size: 2rem;">Управление новостями</h1>
        <a href="<?php echo e(route('admin.news.create')); ?>" class="admin-btn">
            <i class="bi bi-plus-circle"></i> Добавить новость
        </a>
    </div>

    <?php if(session('success')): ?>
        <div class="alert alert-success">
            <?php echo e(session('success')); ?>

        </div>
    <?php endif; ?>

    <div class="card">
        <div class="card-body">
            <div class="table-responsive">
                <table class="admin-table">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Изображение</th>
                            <th>Заголовок</th>
                            <th>Дата публикации</th>
                            <th>Статус</th>
                            <th>Просмотры</th>
                            <th>Действия</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $__empty_1 = true; $__currentLoopData = $news; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                            <tr>
                                <td><?php echo e($item->id); ?></td>
                                <td>
                                    <?php if($item->image): ?>
                                        <img src="<?php echo e(asset('media/images/' . $item->image)); ?>" alt="<?php echo e($item->title); ?>" width="50" height="50" style="object-fit: cover;">
                                    <?php else: ?>
                                        <span class="text-muted">Нет изображения</span>
                                    <?php endif; ?>
                                </td>
                                <td><?php echo e($item->title); ?></td>
                                <td><?php echo e($item->published_at ? $item->published_at->format('d.m.Y H:i') : 'Не опубликована'); ?></td>
                                <td>
                                    <?php if($item->is_published): ?>
                                        <span class="badge bg-success">Опубликована</span>
                                    <?php else: ?>
                                        <span class="badge bg-warning text-dark">Черновик</span>
                                    <?php endif; ?>
                                </td>
                                <td><?php echo e($item->views); ?></td>
                                <td>
                                    <div class="admin-btn-group">
                                        <a href="<?php echo e(route('news.show', $item->id)); ?>" class="admin-btn admin-btn-outline" target="_blank" title="Просмотр">👁</a>
                                        <a href="<?php echo e(route('admin.news.edit', $item->id)); ?>" class="admin-btn" title="Редактировать">✏️</a>
                                        <form action="<?php echo e(route('admin.news.destroy', $item->id)); ?>" method="POST" onsubmit="return confirm('Вы уверены, что хотите удалить эту новость?')" style="display:inline-block;">
                                            <?php echo csrf_field(); ?>
                                            <?php echo method_field('DELETE'); ?>
                                            <button type="submit" class="admin-btn admin-btn-danger" title="Удалить">🗑</button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                            <tr>
                                <td colspan="7" class="text-center">Новости не найдены</td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?> 
<?php echo $__env->make('layouts.admin', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\OpenServer\domains\dualshop1\laravel-main\resources\views/admin/news/index.blade.php ENDPATH**/ ?>