
<?php $__env->startSection('content'); ?>

<section class="catalog-categories-page container py-5">
    <div class="row">
        <div class="col-12">
            <nav aria-label="breadcrumb" class="mb-4">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="<?php echo e(route('welcome')); ?>">Главная</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Весь каталог</li>
                </ol>
            </nav>
            
            <h1 class="h2 mb-4">Категории товаров</h1>
            
            <div class="category-grid-container">
                <!-- Категории из базы данных -->
                <?php $__currentLoopData = $categories; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $category): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <a href="<?php echo e(route('catalog.category', $category->id)); ?>" class="category-link-item">
                        <div class="category-grid-icon">
                            <?php
                                // Иконки для категорий (по умолчанию и для известных категорий) - Скопировано из navigation.blade.php
                                $categoryIcons = [
                                    'default' => 'bi-box',
                                    'Процессоры' => 'bi-cpu',
                                    'Материнские платы' => 'bi-motherboard',
                                    'Видеокарты' => 'bi-gpu-card',
                                    'Оперативная память' => 'bi-memory',
                                    'Накопители' => 'bi-hdd',
                                    'Блоки питания' => 'bi-plug',
                                    'Корпуса' => 'bi-pc',
                                    'Системы охлаждения' => 'bi-snow',
                                    'Мониторы' => 'bi-display',
                                    'Клавиатуры' => 'bi-keyboard',
                                    'Мыши' => 'bi-mouse',
                                    'Наушники' => 'bi-headphones'
                                ];

                                // Определяем иконку для категории - Логика из navigation.blade.php
                                $icon = $categoryIcons['default']; // Иконка по умолчанию
                                if ($category->icon) { // Используем пользовательскую иконку из БД, если задана
                                    $icon = $category->icon;
                                } elseif (isset($categoryIcons[$category->name])) { // Ищем в предопределенном массиве по точному имени
                                    $icon = $categoryIcons[$category->name];
                                } else {
                                    // Fallback по ключевому слову в названии (дополнительная проверка)
                                    $nameLower = strtolower($category->name);
                                    if (strpos($nameLower, 'процессор') !== false) { $icon = 'bi-cpu'; }
                                    elseif (strpos($nameLower, 'материнск') !== false) { $icon = 'bi-motherboard'; }
                                    elseif (strpos($nameLower, 'видеокарт') !== false) { $icon = 'bi-gpu-card'; }
                                    elseif (strpos($nameLower, 'памят') !== false) { $icon = 'bi-memory'; }
                                    elseif (strpos($nameLower, 'накопит') !== false) { $icon = 'bi-hdd'; }
                                    elseif (strpos($nameLower, 'питани') !== false) { $icon = 'bi-plug'; }
                                    elseif (strpos($nameLower, 'корпус') !== false) { $icon = 'bi-pc'; }
                                    elseif (strpos($nameLower, 'охлажден') !== false) { $icon = 'bi-snow'; }
                                    elseif (strpos($nameLower, 'монитор') !== false) { $icon = 'bi-display'; }
                                    elseif (strpos($nameLower, 'клавиатур') !== false) { $icon = 'bi-keyboard'; }
                                    elseif (strpos($nameLower, 'мыш') !== false) { $icon = 'bi-mouse'; }
                                    elseif (strpos($nameLower, 'наушник') !== false) { $icon = 'bi-headphones'; }
                                    else {
                                        // Если ничего не подошло, оставляем иконку по умолчанию bi-box
                                        $icon = $categoryIcons['default'];
                                    }
                                }
                            ?>
                            <i class="bi <?php echo e($icon); ?>"></i>
                        </div>
                        <div class="category-grid-title"><?php echo e($category->name); ?></div>
                    </a>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </div>
        </div>
    </div>
</section>

<?php $__env->stopSection(); ?>

<?php $__env->startSection('styles'); ?>
<style>
/* Стили для страницы категорий */
.catalog-categories-page {
    background-color: #f9f9f9; /* Светлый фон из app.css */
    border-radius: 12px;
    /* Убираем box-shadow, если он есть, или делаем его нейтральным */
}

/* Сетка категорий */
.category-grid-container {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(200px, 1fr));
    gap: 1.5rem;
    margin-bottom: 2rem;
}

.category-link-item {
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    text-align: center;
    padding: 2rem 1.5rem;
    border-radius: 12px;
    text-decoration: none;
    color: #02111b; /* Цвет текста из app.css */
    transition: all 0.3s ease;
    background-color: #fff; /* Белый фон из app.css */
    border: 1px solid #dee2e6; /* Светлая граница из app.css */
    min-height: 180px;
    box-shadow: 0 3px 3px -1px rgba(0,0,0,0.1); /* Нейтральная тень */
}

.category-link-item:hover {
    box-shadow: 0 8px 15px rgba(0,0,0,0.1); /* Нейтральная тень при наведении */
    transform: translateY(-4px);
    background-color: #f8f9fa; /* Чуть темнее белый при наведении */
    border-color: #ced4da; /* Нейтральная граница при наведении */
}

.category-grid-icon {
    font-size: 3rem;
    margin-bottom: 1.25rem;
    color: #000; /* Черный цвет для иконок */
    transition: all 0.3s ease;
}

.category-link-item:hover .category-grid-icon {
    transform: scale(1.1);
    color: #333; /* Темно-серый при наведении */
}

.category-grid-title {
    font-size: 1.1rem;
    font-weight: 600;
    line-height: 1.4;
    color: #02111b; /* Цвет заголовка из app.css */
}

/* Адаптивность */
@media (max-width: 992px) {
    .category-grid-container {
        grid-template-columns: repeat(3, 1fr);
    }
}

@media (max-width: 768px) {
    .category-grid-container {
        grid-template-columns: repeat(2, 1fr);
    }
    
    .category-grid-icon {
        font-size: 2.5rem;
    }
    
    .category-grid-title {
        font-size: 1rem;
    }
}

@media (max-width: 480px) {
    .category-grid-container {
        grid-template-columns: 1fr;
    }
    
    .category-link-item {
        padding: 1.5rem 1rem;
        min-height: 160px;
    }
}
</style>
<?php $__env->stopSection(); ?> 
<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\OpenServer\domains\dualshop1\laravel-main\resources\views/catalog/categories.blade.php ENDPATH**/ ?>