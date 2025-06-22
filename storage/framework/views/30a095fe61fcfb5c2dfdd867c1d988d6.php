<?php $__env->startSection('content'); ?>
<div class="admin-content">
    <h1 style="font-family: 'Yeseva One', serif; font-size: 2rem; margin-bottom: 1.5rem;">Список товаров</h1>
     
    
    <table class="admin-table">
        <thead>
            <tr>
                <th>Изображения</th>
                <th>Названия</th>
                <th>Категория</th>
                <th>Количество</th>
                <th>Цена</th>
                <th>Действия</th>
            </tr>
        </thead>
        <tbody>
            <?php $__currentLoopData = $products; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $product): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <tr>
                    <td>
                        <?php
                            $imageFileName = null;
                            $debugImagePath = null; // Для отладочного вывода
                            // Проверяем отношение images first
                            if ($product->images && $product->images->count() > 0) {
                                $imageFileName = $product->images->first()->image_path;
                                $debugImagePath = $imageFileName ? 'images relationship: ' . $imageFileName : 'images relationship: (empty path)';
                            }
                            // Fallback to img field
                            elseif (!empty($product->img)) {
                                $imageFileName = $product->img;
                                 $debugImagePath = $imageFileName ? 'img field: ' . $imageFileName : 'img field: (empty path)';
                            }

                            $imageUrl = null;
                            if ($imageFileName) {
                                 // Используем Vite::asset для формирования URL
                                 $imageUrl = Vite::asset('resources/media/images/' . $imageFileName);
                            }
                        ?>

                        
                            
                            
                        

                        <?php if($imageUrl): ?>
                            <img src="<?php echo e(asset('media/images/' . $imageFileName)); ?>" alt="<?php echo e($product->title); ?>" width="100px">
                        <?php else: ?>
                            
                            <div style="width: 100px; height: 100px; border: 1px solid #ccc; display: flex; align-items: center; justify-content: center;">
                                Нет фото
                            </div>
                        <?php endif; ?>
                    </td>
                    <td><?php echo e($product->title); ?></td>
                    <td><?php echo e($product->category_name); ?></td>
                    <td><?php echo e($product->qty); ?></td>
                    <td><?php echo e($product->price); ?></td>
                    <td>
                        <a href="<?php echo e(route('admin.products.edit', $product->id)); ?>" class="admin-btn btn-sm">Редактировать</a>
                        <form action="<?php echo e(route('admin.products.delete', ['id' => $product->id])); ?>" method="POST" style="display:inline-block;">
                            <?php echo csrf_field(); ?>
                            <?php echo method_field('DELETE'); ?>
                            <button type="submit" class="admin-btn btn-sm" style="background:#dc3545;" onclick="return confirm('Вы уверены?');">Удалить</button>
                        </form>
                    </td>
                </tr>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </tbody>
    </table>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.admin', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\OpenServer\domains\dualshop1\laravel-main\resources\views/admin/products.blade.php ENDPATH**/ ?>