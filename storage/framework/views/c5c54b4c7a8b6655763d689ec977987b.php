<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Админ-панель</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <?php echo app('Illuminate\Foundation\Vite')(['resources/css/app.css', 'resources/js/app.js']); ?>
</head>
<body>
    <div class="admin-sidebar">
        <h2>DUALSHOP/ADMIN</h2>
        <ul>
            <li><a href="<?php echo e(route('admin.index')); ?>">Главная</a></li>
            <li><a href="<?php echo e(route('admin.products')); ?>">Товары</a></li>
            <li><a href="<?php echo e(route('admin.products.create')); ?>">Добавить товар</a></li>
            <li><a href="<?php echo e(route('admin.categories')); ?>">Категории</a></li>
            <li><a href="<?php echo e(route('admin.categories.create')); ?>">Добавить категорию</a></li>
            <li><a href="<?php echo e(route('admin.orders')); ?>">Заказы</a></li>
            <li><a href="<?php echo e(route('admin.news.create')); ?>">Добавить новость</a></li>
            <li><a href="<?php echo e(route('admin.news')); ?>">Редактировать новости</a></li>
        </ul>
    </div>
    <main class="admin-main">
        <?php echo $__env->yieldContent('content'); ?>
    </main>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <?php echo $__env->yieldPushContent('scripts'); ?>
</body>
</html> <?php /**PATH C:\OpenServer\domains\dualshop1\laravel-main\resources\views/layouts/admin.blade.php ENDPATH**/ ?>