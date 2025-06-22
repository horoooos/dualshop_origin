<?php $__env->startSection('content'); ?>

    

 


    
    <?php $__env->startPush('scripts'); ?>

        <script>
            document.addEventListener('DOMContentLoaded', function () {


                var priceMinInput = document.getElementById('price_min');


                var priceMaxInput = document.getElementById('price_max');


                // Явно определяем переменные minPrice для JavaScript
                var minPrice = <?php echo e($minPrice ?? 0); ?>;
                // Максимальное значение будет определено из $maxProductPrice с бэкенда.

                // *** Определяем реальную максимальную цену для слайдера ***
                 // Используем $maxProductPrice с бэкенда, если он установлен, не null, является числом и больше minPrice.
                 // Иначе используем надежное максимальное значение по умолчанию (1000000).
                var realMaxPriceForSlider;
                var maxPriceDefault = 1000000; // Надежное максимальное значение по умолчанию для JS fallback

                 <?php if(isset($maxProductPrice) && is_numeric($maxProductPrice) && $maxProductPrice !== null && $maxProductPrice > $minPrice): ?>
                     realMaxPriceForSlider = parseFloat(<?php echo e($maxProductPrice); ?>);
                     console.log('[DEBUG] Использую максимальную цену с бэкенда для слайдера:', realMaxPriceForSlider);
                 <?php else: ?>
                     realMaxPriceForSlider = maxPriceDefault; // Используем значение по умолчанию, если $maxProductPrice некорректен
                      console.log('[DEBUG] $maxProductPrice не установлен, не является числом, null или не больше minPrice. Использую стандартное максимальное значение:', realMaxPriceForSlider);
                 <?php endif; ?>

                // Проверяем, есть ли примененные фильтры по цене в запросе
                var requestPriceMin = parseFloat('<?php echo e(request('price_min', '')); ?>');
                var requestPriceMax = parseFloat('<?php echo e(request('price_max', '')); ?>');

                // Определяем начальные значения для слайдера, используя значения из запроса или min/realMax
                var startMin = requestPriceMin || minPrice;
                // Если requestPriceMax есть, используем его для начального положения ползунка "до".
                // Иначе используем realMaxPriceForSlider как значение по умолчанию.
                var startMax = requestPriceMax || realMaxPriceForSlider;

                // Корректируем начальные значения, убедившись, что они в пределах реального диапазона (minPrice - realMaxPriceForSlider)
                if (startMin < minPrice) startMin = minPrice;
                if (startMin > realMaxPriceForSlider) startMin = realMaxPriceForSlider;
                if (startMax < minPrice) startMax = minPrice;
                if (startMax > realMaxPriceForSlider) startMax = realMaxPriceForSlider;

                // Добавляем проверку на существование необходимых элементов
                // Теперь проверяем скрытый input для слайдера
                var priceRangeSlider = $('#price_range_slider'); // Ion.RangeSlider использует jQuery

                if (priceRangeSlider.length && priceMinInput && priceMaxInput && realMaxPriceForSlider > minPrice) {

                    // Функция для обновления плейсхолдеров
                    function updatePlaceholders() {
                        if (priceMinInput.value === '') {
                            priceMinInput.placeholder = minPrice;
                        }
                        if (priceMaxInput.value === '') {
                            priceMaxInput.placeholder = realMaxPriceForSlider;
                        }
                    }

                    // *** Инициализация Ion.RangeSlider ***
                     console.log('[DEBUG] Инициализация Ion.RangeSlider с параметрами:', {
                        min: minPrice,
                        max: realMaxPriceForSlider,
                        from: startMin,
                        to: startMax
                     });

                     priceRangeSlider.ionRangeSlider({
                        type: 'double', // Диапазонный слайдер
                        min: minPrice,
                        max: realMaxPriceForSlider, // Используем реальную максимальную цену для слайдера
                        from: startMin, // Начальное значение ОТ
                        to: startMax,   // Начальное значение ДО
                        step: 1,
                        prettify_enabled: true,
                        prettify_separator: ' ', // Разделитель тысяч
                        postfix: ' ₽', // Суффикс валюты
                        hide_min_max: true, // Скрываем подписи min/max под слайдером
                        hide_from_to: false, // Показываем текущие значения над слайдером
                        grid: false, // Отключаем сетку
                        onChange: function (data) {
                            // Обновляем поля ввода при движении ползунка
                            priceMinInput.value = data.from;
                            priceMaxInput.value = data.to;
                            // Убираем плейсхолдеры при обновлении полей
                            priceMinInput.placeholder = '';
                            priceMaxInput.placeholder = '';
                        },
                    });

                    // Получаем экземпляр слайдера для синхронизации
                    var sliderInstance = priceRangeSlider.data('ionRangeSlider');

                    // Синхронизация полей ввода со слайдером (для ручного ввода)
                    priceMinInput.addEventListener('input', function() {
                        var value = parseFloat(this.value);
                        if (!isNaN(value)) {
                            sliderInstance.update({ from: value });
                            this.placeholder = ''; // Убираем плейсхолдер при вводе
                        } else if (this.value === '') {
                            updatePlaceholders(); // Возвращаем плейсхолдер если поле очищено
                        }
                    });

                    priceMaxInput.addEventListener('input', function() {
                        var value = parseFloat(this.value);
                        if (!isNaN(value)) {
                            sliderInstance.update({ to: value });
                            this.placeholder = ''; // Убираем плейсхолдер при вводе
                        } else if (this.value === '') {
                            updatePlaceholders(); // Возвращаем плейсхолдер если поле очищено
                        }
                    });

                    // Изначально устанавливаем плейсхолдеры при загрузке страницы
                    updatePlaceholders();

                } else {
                     console.warn('[WARN] Инициализация Ion.RangeSlider пропущена.', 'Слайдер найден?', priceRangeSlider.length > 0, 'Input min найден?', !!priceMinInput, 'Input max найден?', !!priceMaxInput, 'Диапазон корректен (max > min)?', realMaxPriceForSlider > minPrice, 'Min:', minPrice, 'Max:', realMaxPriceForSlider);
                 }

                 // Кнопка сброса фильтров
                 var resetButton = document.getElementById('reset-filters');
                 if (resetButton) {

                     resetButton.addEventListener('click', function(e) {
                         e.preventDefault();
                         // Переходим на URL без параметров фильтрации
                         var currentUrl = new URL(window.location.href);
                         var baseUrl = currentUrl.origin + currentUrl.pathname;
                         // Оставляем только параметр категории, если он есть
                         var categoryParam = currentUrl.searchParams.get('category');
                         if (categoryParam) {
                              window.location.href = baseUrl + '?category=' + categoryParam;
                         } else {
                              window.location.href = baseUrl;
                         }
                     });
                 }
            });
        </script>
    <?php $__env->stopPush(); ?>

    <section class="catalog container pt-4 pb-5">
        <div class="row">
            <!-- Левая боковая панель с фильтрами -->
            <div class="col-lg-3 mb-4">
                <div class="border rounded bg-white p-3 mb-4 catalog-filter-panel">
                    <h5 class="mb-3">Фильтры</h5>
                    <?php if(isset($currentCategory) && $currentCategory): ?>
                        <div class="current-category mb-3">
                            <span class="badge bg-dark"><?php echo e($currentCategory->name); ?></span>
                        </div>
                    <?php endif; ?>
                    <form method="GET" action="<?php echo e(route('catalog')); ?>">
                        <?php if(isset($currentCategory) && $currentCategory): ?>
                            <input type="hidden" name="category" value="<?php echo e($currentCategory->id); ?>">
                        <?php endif; ?>
                        
                        <!-- Фильтрация по цене -->
                        <div class="filter-block mb-3">
                            <h6 class="filter-title">Цена</h6>
                            <div class="row align-items-center mb-2">
                                <div class="col-5">
                                    <div class="mb-2">
                                        
                                        <input type="number" class="form-control form-control-sm" id="price_min" name="price_min" 
                                            value="<?php echo e(request('price_min')); ?>" min="0">
                                    </div>
                                </div>
                                <div class="col-2 text-center">—</div> 
                                <div class="col-5">
                                    <div class="mb-2">
                                        <input type="number" class="form-control form-control-sm" id="price_max" name="price_max" 
                                            value="<?php echo e(request('price_max')); ?>" min="0">
                                    </div>
                                </div>
                            </div>
                            
 

                            
                            <input type="text" id="price_range_slider" class="price-range-slider" value="" />

                        </div>
                        
                        <!-- Фильтрация по характеристикам -->
                        <?php if(isset($filters) && is_array($filters) && count($filters) > 0): ?>
                            <div class="accordion accordion-flush" id="filterAccordion">
                                <?php $__currentLoopData = $filters; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $group => $groupFilters): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <?php
                                        // Проверяем, есть ли в этой группе фильтров хотя бы один фильтр с непустыми значениями (кроме model)
                                        $hasVisibleFilters = false;
                                        $isGroupActive = false; // Флаг для определения, активна ли группа (есть ли примененные фильтры в ней)
                                        foreach ($groupFilters as $key => $filter) {
                                            if ($key !== 'model' && !empty($filter['values'])) {
                                                $hasVisibleFilters = true;

                                                // Проверяем, применен ли какой-либо фильтр из этой группы
                                                if (isset($appliedFilters[$key]) && !empty($appliedFilters[$key])) {
                                                    $isGroupActive = true;
                                                }
                                            }
                                        }
                                    ?>
                                    <?php if($hasVisibleFilters): ?>
                                        <div class="accordion-item">
                                            <h2 class="accordion-header" id="heading-<?php echo e(Str::slug($group)); ?>">
                                                
                                                <button class="accordion-button p-2 <?php echo e($isGroupActive ? '' : 'collapsed'); ?>" type="button" data-bs-toggle="collapse" data-bs-target="#collapse-<?php echo e(Str::slug($group)); ?>" aria-expanded="<?php echo e($isGroupActive ? 'true' : 'false'); ?>" aria-controls="collapse-<?php echo e(Str::slug($group)); ?>">
                                                    <?php echo e($group); ?>

                                                </button>
                                            </h2>
                                            
                                            
                                            <div id="collapse-<?php echo e(Str::slug($group)); ?>" class="accordion-collapse collapse <?php echo e($isGroupActive ? 'show' : ''); ?>" aria-labelledby="heading-<?php echo e(Str::slug($group)); ?>">
                                                <div class="accordion-body p-2">
                                                    <?php $__currentLoopData = $groupFilters; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $filter): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                        <?php if($key !== 'model'): ?>
                                                            <?php if(!empty($filter['values'])): ?>
                                                                <div class="mb-2">
                                                                    <div class="fw-bold small mb-1"><?php echo e($filter['name']); ?></div>
                                                                    <?php $__currentLoopData = $filter['values']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $value): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                                        <?php
                                                                            $isChecked = false;
                                                                            $requestFilters = request()->query('filter', []);
                                                                            if (isset($requestFilters[$key])) {
                                                                                $valueToCompare = strtolower(trim((string)$value));
                                                                                if (!is_array($requestFilters[$key])) {
                                                                                    $isChecked = (strtolower(trim((string)$requestFilters[$key])) === $valueToCompare);
                                                                                } else {
                                                                                    foreach ($requestFilters[$key] as $requestedValue) {
                                                                                        $requestedValueCompare = strtolower(trim((string)$requestedValue));
                                                                                        // Учитываем нормализацию для материалов при сравнении
                                                                                         if ($key === 'material') {
                                                                                            $normalizedValue = strtolower(str_replace([' ,', ', '], ',', trim((string)$value)));
                                                                                            $normalizedRequestedValue = strtolower(str_replace([' ,', ', '], ',', trim((string)$requestedValue)));
                                                                                            if ($normalizedRequestedValue === $normalizedValue) {
                                                                                                $isChecked = true;
                                                                                                break;
                                                                                            }
                                                                                         } else {
                                                                                            if ($requestedValueCompare === $valueToCompare || ($requestedValueCompare === '' && in_array($valueToCompare, ['ddr4', 'ddr5', 'atx', 'micro-atx', 'mini-itx', 'intel', 'amd']))) {
                                                                                                $isChecked = true;
                                                                                                break;
                                                                                            }
                                                                                         }
                                                                                    }
                                                                                }
                                                                            }
                                                                        ?>
                                                                        <div class="form-check filter-value-item" data-value="<?php echo e(strtolower(trim((string)$value))); ?>">
                                                                            <input class="form-check-input" type="checkbox" name="filter[<?php echo e($key); ?>][]"
                                                                                   value="<?php echo e(($key === 'brand' && $value === 'ARDOR') ? 'ARDOR GAMING' : $value); ?>"
                                                                                   id="<?php echo e($key); ?>_<?php echo e($value); ?>" <?php echo e($isChecked ? 'checked' : ''); ?>>
                                                                            <label class="form-check-label" for="<?php echo e($key); ?>_<?php echo e($value); ?>"><?php echo e($value); ?></label>
                                                                        </div>
                                                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                                                </div>
                                                            <?php endif; ?>
                                                        <?php endif; ?>
                                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                                </div>
                                            </div>
                                        </div>
                                    <?php endif; ?>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </div>
                        <?php endif; ?>

                        
                        <button class="btn-banner text-white w-100 mt-3" type="submit">Применить</button>
                        <?php if(!empty(request()->query())): ?> 
                            
                            <a href="<?php if(isset($currentCategory) && $currentCategory): ?><?php echo e(route('catalog.category', $currentCategory->id)); ?><?php else: ?><?php echo e(route('catalog')); ?><?php endif; ?>" 
                               id="reset-filters" class="btn btn-link text-muted text-decoration-none w-100 mt-2 p-0">Сбросить фильтр</a>
                        <?php endif; ?>
                    </form>
                </div>
            </div>

            <!-- Основная часть каталога -->
            <div class="col-lg-9">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <?php if(isset($searchResults) && $searchResults): ?>
                        <h1 class="catalog-title">Результаты поиска (<?php echo e($searchQuery); ?>): <?php echo e($products->total()); ?></h1>
                    <?php else: ?>
                        <h1 class="catalog-title"><?php echo e($pageTitle ?? 'Каталог товаров'); ?></h1>
                    <?php endif; ?>
                    <div class="catalog__sort">
                        <a href="<?php echo e(request()->fullUrlWithQuery(['sort' => 'price_asc'])); ?>" class="catalog__sort-item<?php echo e(request('sort') == 'price_asc' ? ' active' : ''); ?>">Цена <i class="bi bi-arrow-up"></i></a>
                        <a href="<?php echo e(request()->fullUrlWithQuery(['sort' => 'price_desc'])); ?>" class="catalog__sort-item<?php echo e(request('sort') == 'price_desc' ? ' active' : ''); ?>">Цена <i class="bi bi-arrow-down"></i></a>
                        <a href="<?php echo e(request()->fullUrlWithQuery(['sort' => 'title_asc'])); ?>" class="catalog__sort-item<?php echo e(request('sort') == 'title_asc' ? ' active' : ''); ?>">Название</a>
                        <a href="<?php echo e(request()->fullUrlWithQuery(['sort' => 'country_asc'])); ?>" class="catalog__sort-item<?php echo e(request('sort') == 'country_asc' ? ' active' : ''); ?>">Страна</a>
                         
                         <?php if(request('sort')): ?>
                             <a href="<?php if(isset($currentCategory) && $currentCategory): ?><?php echo e(route('catalog.category', array_merge(['category' => $currentCategory->id], request()->except(['id', 'sort', 'page'])))); ?><?php else: ?><?php echo e(route('catalog', request()->except(['sort', 'page']))); ?><?php endif; ?>" class="catalog__sort-item catalog__sort-item--default">Сбросить сортировку</a>
                         <?php endif; ?>
                    </div>
                </div>
                <div class="row g-4">
                    <?php $__empty_1 = true; $__currentLoopData = $products; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $product): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                        <div class="col-md-6 col-lg-4">
                            <?php echo $__env->make('catalog._product_card', ['product' => $product], \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
                        </div>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                        <div class="col-12">
                            <div class="alert alert-info">Товары не найдены.</div>
                        </div>
                    <?php endif; ?>
                </div>
                <div class="mt-4">
                    
                    
                </div>
            </div>
        </div>
    </section>

    <style>
    .filter-title {
        font-weight: 600;
        margin-bottom: 0.75rem;
        font-size: 0.9rem;
    }
    .filter-block {
        padding-bottom: 1rem;
        margin-bottom: 1rem;
        border-bottom: 1px solid #eee;
    }
    .filter-block:last-child {
        border-bottom: none;
        margin-bottom: 0;
        padding-bottom: 0;
    }
    @media (max-width: 991px) {
        .product-image-container {
            height: 350px;
        }
    }
    @media (max-width: 767px) {
        .product-image-container {
            height: 300px;
        }
    }
    /* Стилизация блока фильтров под дизайн сайта */
    .catalog-filter-panel {
        background: #fff;
        border-radius: 12px;
        box-shadow: 0 3px 12px rgba(0,0,0,0.04);
        padding: 1.5rem 1.2rem 1.2rem 1.2rem;
        margin-bottom: 2rem;
        min-width: 250px;
        max-width: 340px;
    }
    .catalog-filter-panel h4, .catalog-filter-panel .filter-group-title {
        font-size: 1.15rem;
        font-weight: 700;
        margin-bottom: 1rem;
        color: #222;
    }
    .catalog-filter-panel .filter-group-title {
        margin-top: 1.2rem;
        margin-bottom: 0.7rem;
        font-size: 1rem;
        font-weight: 600;
        color: #222;
    }
    .catalog-filter-panel label {
        font-size: 1rem;
        font-weight: 400;
        color: #222;
        margin-bottom: 0.3rem;
        margin-left: 0.4rem;
        cursor: pointer;
    }
    .catalog-filter-panel .form-check {
        margin-bottom: 0.3rem;
        display: flex;
        align-items: center;
    }
    .catalog-filter-panel .form-check-input {
        margin-right: 0.5rem;
        accent-color: #000;
        width: 1.1em;
        height: 1.1em;
        border-radius: 4px;
    }
    .catalog-filter-panel hr {
        margin: 1.2rem 0 1rem 0;
        border-top: 1px solid #eee;
    }
    .catalog-filter-panel .btn-apply {
        width: 100%;
        background: #000;
        color: #fff;
        border: none;
        border-radius: 7px;
        padding: 0.7rem 0;
        font-size: 1rem;
        font-weight: 600;
        margin-top: 1.2rem;
        transition: background 0.2s;
    }
    .catalog-filter-panel .btn-apply:hover {
        background: #222;
    }

    /* Стили для аккордеона фильтров */
    .catalog-filter-panel .accordion-item {
        border: none;
        border-bottom: 1px solid #eee;
    }
    .catalog-filter-panel .accordion-item:last-child {
        border-bottom: none;
    }
    .catalog-filter-panel .accordion-button {
        background-color: transparent;
        color: #222;
        font-weight: 600;
        padding: 0.75rem 0.5rem;
        box-shadow: none;
    }
    .catalog-filter-panel .accordion-button:not(.collapsed) {
        background-color: #f0f0f0; /* Серый фон при открытии */
        color: #000;
    }
    .catalog-filter-panel .accordion-button:focus {
        box-shadow: none;
        border-color: transparent;
    }
     .catalog-filter-panel .accordion-body {
        padding: 0.5rem;
     }

     /* Стили для noUiSlider */
    .catalog-filter-panel .noUi-target {
        background: #ddd; /* Цвет неактивной части */
        border-radius: 4px;
        border: none;
        box-shadow: none;
    }
    .catalog-filter-panel .noUi-connects {
         border-radius: 4px;
    }
    .catalog-filter-panel .noUi-connect {
        background: #ff8c00; /* Оранжевый цвет для активной части */
    }
    .catalog-filter-panel .noUi-handle {
        border-radius: 50%;
        background: #ff8c00; /* Оранжевый фон ползунка */
        border: 2px solid #ff8c00; /* Оранжевая обводка */
        cursor: pointer;
        box-shadow: none;
        width: 18px; /* Размер ползунка */
        height: 18px;
        top: -6px; /* Корректировка положения */
        right: -9px; /* Корректировка положения */
    }
     .catalog-filter-panel .noUi-handle::before,
     .catalog-filter-panel .noUi-handle::after {
         display: none; /* Убираем стандартные элементы */
     }
     .catalog-filter-panel .noUi-tooltip {
         background: #ff8c00; /* Оранжевый фон подсказки */
         color: #fff; /* Белый текст подсказки */
         border-radius: 4px;
         border: none;
         padding: 4px 8px;
         font-size: 0.8rem;
         bottom: 140%; /* Положение над ползунком */
         transform: translate(-50%, 0); /* Центрирование */
     }
     .catalog-filter-panel .noUi-horizontal .noUi-tooltip {
          left: 50%;
          margin-left: 0;
     }

    @media (max-width: 768px) {
        .catalog-filter-panel {
            max-width: 100%;
            min-width: unset;
            padding: 1rem 0.7rem;
        }
    }

    /* Добавляем стили для полей ввода цены */
    .catalog-filter-panel input[type="number"].form-control-sm {
        padding: 0.25rem 0.5rem;
        height: calc(1.5em + 0.5rem + 2px); /* Примерная высота Bootstrap sm input */
        border-radius: 5px; /* Скругляем углы */
        border: 1px solid #ced4da;
        text-align: center; /* Центрируем текст */
        font-size: 0.875rem;
    }

    /* Стилизация Ion.RangeSlider */
    .catalog-filter-panel .irs--flat .irs-bar {
         background: #000; /* Черный цвет активной части */
         height: 6px; /* Толщина полосы */
         top: 25px; /* Положение полосы */
    }
    .catalog-filter-panel .irs--flat .irs-line {
        top: 25px; /* Положение полосы */
        height: 6px; /* Толщина полосы */
        background: #f0f0f0; /* Светло-серый цвет неактивной части */
         border: none; /* Убираем границу */
    }
    .catalog-filter-panel .irs--flat .irs-handle {
        top: 17px; /* Положение ползунка */
        width: 22px; /* Размер ползунка */
        height: 22px;
        background: #fff; /* Белый фон ползунка */
        border: 2px solid #000; /* Черная обводка */
        box-shadow: 0px 1px 3px rgba(0,0,0,0.2); /* Тень */
        cursor: pointer;
        border-radius: 50%; /* Круглая форма */
         /* Добавляем hover эффект */
         transition: all 0.2s ease;
    }
     .catalog-filter-panel .irs--flat .irs-handle:hover {
         transform: scale(1.1);
         box-shadow: 0px 2px 6px rgba(0,0,0,0.3);
     }
     .irs--flat .irs-handle>i:first-child {
        position: absolute;
        display: block;
        top: 0;
        left: 50%;
        width: 2px;
        height: 100%;
        margin-left: -1px;
        background-color: white !important;
     }

     /* Стилизация текста над ползунками */
     .catalog-filter-panel .irs--flat .irs-from,
     .catalog-filter-panel .irs--flat .irs-to,
     .catalog-filter-panel .irs--flat .irs-single {
         background: #000; /* Черный фон подсказки */
         color: #fff; /* Белый текст подсказки */
         padding: 4px 8px;
         border-radius: 4px; /* Скругление углов */
         font-size: 0.8rem;
         font-weight: 600; /* Делаем текст жирнее */
         margin-bottom: 20px;
     }
     .catalog-filter-panel .irs--flat .irs-from:before,
     .catalog-filter-panel .irs--flat .irs-to:before,
     .catalog-filter-panel .irs--flat .irs-single:before {
         border-top-color: #000; /* Черный цвет стрелки, соответствует фону подсказки */
     }
      /* Стилизация min/max подписей (скрыты, но на всякий случай) */
     .catalog-filter-panel .irs--flat .irs-min,
     .catalog-filter-panel .irs--flat .irs-max {
          display: none; /* Скрываем */
          color: #666;
          font-size: 0.8rem;
     }

    /* Styles for the catalog/search results title */
    .catalog-title {
        font-family: 'Roboto', sans-serif;
        font-weight: 700;
        font-size: 24px;
        color: #02111b;
    }
    </style>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\OpenServer\domains\dualshop1\laravel-main\resources\views/catalog.blade.php ENDPATH**/ ?>