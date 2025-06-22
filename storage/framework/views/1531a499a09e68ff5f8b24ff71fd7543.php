<?php
    use App\Models\Category;
    use App\Models\ProductSpecification;
    use App\Models\Product;
    use Illuminate\Support\Facades\DB;
    use Illuminate\Support\Facades\Vite;
    
    // Получаем все родительские категории
    $mainCategories = Category::whereNull('parent_id')->orderBy('name')->get();
    
    // Иконки для категорий (по умолчанию и для известных категорий)
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
    
    // Получаем базовый URL для изображений, обрабатываемых Vite
    // Используем фиктивное имя файла для получения базового пути
    $viteImageUrl = Vite::asset('resources/media/images/placeholder.webp');
    // Обрезаем фиктивное имя файла, чтобы получить только базовый путь к папке изображений после сборки Vite
    $baseImageUrl = rtrim(dirname($viteImageUrl), '/') . '/';
    
    // Функция для получения характеристик товаров по категории
    function getCategorySpecifications($categoryId) {
        // Сначала получаем все ID подкатегорий
        $categoryIds = [$categoryId];
        $subCategories = Category::where('parent_id', $categoryId)->pluck('id')->toArray();
        if (!empty($subCategories)) {
            $categoryIds = array_merge($categoryIds, $subCategories);
        }
        
        // Получаем товары из этой категории и подкатегорий
        $productIds = Product::whereIn('category_id', $categoryIds)->pluck('id')->toArray();
        
        if (empty($productIds)) {
            return [];
        }
        
        $specs = [];
        
        // --- Бренды: объединяем ARDOR и ARDOR GAMING и получаем только бренды --- //
        $brandValues = DB::table('product_specifications')
            ->whereIn('product_id', $productIds)
            ->where('spec_key', 'brand')
            ->where('is_filterable', true) // Убедимся, что бренд фильтруемый
            ->pluck('spec_value')
            ->map(function($v) {
                $v = trim(mb_strtoupper($v));
                if (strpos($v, 'ARDOR') !== false) return 'ARDOR GAMING'; // Объединяем ARDOR
                return $v;
            })
            ->unique()
            ->filter(fn($v) => $v !== 'КОРПУС' && $v !== 'ЕСТЬ') // Фильтруем некорректные значения, если есть
            ->values() // Сбрасываем ключи массива
            ->toArray();
        
        // Сортируем бренды по алфавиту
        sort($brandValues);

        // Добавляем только фильтр по бренду в результирующий массив
        if (!empty($brandValues)) {
            $specs['brand'] = [
                'name' => 'Бренд',
                'values' => $brandValues
            ];
        }
        
        return $specs; // Возвращаем только фильтр по бренду
    }
    
    // Характеристики для фильтрации по каждой категории
    $categorySpecs = [
        'Смартфоны' => [
            ['key' => 'brand', 'name' => 'Бренд'],
            ['key' => 'ram', 'name' => 'Оперативная память'],
            ['key' => 'storage', 'name' => 'Объем памяти'],
            ['key' => 'processor', 'name' => 'Процессор'],
            ['key' => 'camera', 'name' => 'Камера'],
            ['key' => 'screen_size', 'name' => 'Диагональ экрана']
        ],
        'Ноутбуки' => [
            ['key' => 'brand', 'name' => 'Бренд'],
            ['key' => 'ram', 'name' => 'Оперативная память'],
            ['key' => 'storage', 'name' => 'Объем SSD'],
            ['key' => 'processor', 'name' => 'Процессор'],
            ['key' => 'screen_size', 'name' => 'Диагональ экрана'],
            ['key' => 'video_card', 'name' => 'Видеокарта']
        ]
    ];
?>

<header class="sticky-top">
  <!-- Верхняя панель -->
  <div class="top-bar d-lg-block">
    <div class="container d-flex justify-content-between align-items-center" style="height: 46px">
      <?php if(auth()->guard()->guest()): ?>
      <div class="d-flex gap-4 text-white">
        <a href="<?php echo e(route('login')); ?>" class="text-white text-decoration-none">Войти</a>
        <a href="<?php echo e(route('register')); ?>" class="text-white text-decoration-none">Зарегистрироваться</a>
      </div>
      <?php endif; ?>

      <?php if(auth()->guard()->check()): ?>
      <div class="d-flex justify-content-between w-100 text-white">
        <!-- Профиль (слева) -->
        <div class="dropdown">
          <a class="text-white text-decoration-none dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown">
            <?php echo e(Auth::user()->name); ?>

          </a>
          <ul class="dropdown-menu">
            <li><a class="dropdown-item" href="<?php echo e(route('profile.index')); ?>">Профиль</a></li>
            <li><hr class="dropdown-divider"></li>
            <li>
              <form method="POST" action="<?php echo e(route('logout')); ?>">
                <?php echo csrf_field(); ?>
                <button type="submit" class="dropdown-item">Выйти</button>
              </form>
            </li>
          </ul>
        </div>

        <!-- Избранное (справа) -->
        <div class="d-flex align-items-center gap-2 ms-auto">
          <i class="bi bi-heart"></i>
          <a href="<?php echo e(route('favorites')); ?>" class="text-white text-decoration-none <?php echo e(Request::is('favorites') ? 'active' : ''); ?>">Избранное</a>
        </div>
      </div>
      <?php endif; ?>
    </div>
  </div>

  <!-- Основная навигация -->
  <nav class="navbar navbar-expand-lg">
    <div class="container">
      <!-- Логотип и бургер меню -->
      <div class="d-flex align-items-center w-100">
        <button class="navbar-toggler border-0 p-0 me-3 d-lg-none" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
          <i class="bi bi-list fs-1"></i>
        </button>
        <a class="navbar-brand brand" href="/">dualshop.</a>

        <!-- Основные элементы навигации -->
        <div class="collapse navbar-collapse flex-grow-1" id="navbarNav">
          <div class="d-flex flex-column flex-lg-row align-items-lg-center w-100">
            <!-- Кнопка каталога с выпадающим списком -->
            <div class="dropdown me-lg-4 mb-3 mb-lg-0">
              <a class="btn btn-black rounded-pill px-4 py-2 d-flex align-items-center btn-header <?php echo e(Request::is('catalog') ? 'active' : ''); ?>" href="#" id="catalogDropdown" role="button">
                Каталог
                <i class="bi bi-chevron-down ms-2"></i>
              </a>
              <ul class="dropdown-menu catalog-mega-menu" aria-labelledby="catalogDropdown">
                <button type="button" class="catalog-close-btn d-lg-none" aria-label="Закрыть меню" style="position:absolute;top:12px;right:16px;background:none;border:none;font-size:2rem;z-index:2100;cursor:pointer;">
                  &times;
                </button>
                <li class="mega-menu-header">
                  <a class="dropdown-item d-flex align-items-center mega-menu-all" href="<?php echo e(route('catalog.categories')); ?>">
                    <i class="bi bi-grid-3x3-gap-fill me-2"></i> Весь каталог
                  </a>
                </li>
                <li><hr class="dropdown-divider"></li>
                
                <div class="mega-menu-content">
                  <div class="row">
                    <?php
                      // Получаем основные категории с их фильтруемыми спецификациями
                      $mainCategoriesWithSpecs = \App\Models\Category::whereNull('parent_id')->orderBy('name')->get()->map(function($category) use ($categoryIcons) {
                          $category->filters = getCategorySpecifications($category->id);
                          return $category;
                      });

                      $count = $mainCategoriesWithSpecs->count();
                      $colClass = 'col-md-4'; // 3 колонки по умолчанию
                      if ($count === 1) {
                          $colClass = 'col-md-12';
                      } elseif ($count === 2) {
                          $colClass = 'col-md-6';
                      }
                    ?>
                    
                    <?php $__currentLoopData = $mainCategoriesWithSpecs; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $category): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                      <div class="<?php echo e($colClass); ?> mb-3">
                          <div class="mega-category">
                              <a class="mega-category-title" href="<?php echo e(route('catalog.category', $category->id)); ?>">
                                  <?php
                                      // Определяем иконку для категории
                                      $icon = 'bi-box'; // Default icon
                                      if ($category->icon) {
                                          $icon = $category->icon;
                                      } elseif (isset($categoryIcons[$category->name])) {
                                          $icon = $categoryIcons[$category->name];
                                      } else {
                                          // Fallback based on name keyword
                                          $nameLower = strtolower($category->name);
                                          if (strpos($nameLower, 'процессор') !== false) {
                                              $icon = 'bi-cpu';
                                          } elseif (strpos($nameLower, 'материнск') !== false) {
                                              $icon = 'bi-motherboard';
                                          } elseif (strpos($nameLower, 'видеокарт') !== false) {
                                              $icon = 'bi-gpu-card';
                                          } elseif (strpos($nameLower, 'памят') !== false) {
                                              $icon = 'bi-memory';
                                          } elseif (strpos($nameLower, 'накопит') !== false) {
                                              $icon = 'bi-hdd';
                                          } elseif (strpos($nameLower, 'питани') !== false) {
                                              $icon = 'bi-plug';
                                          } elseif (strpos($nameLower, 'корпус') !== false) {
                                              $icon = 'bi-pc';
                                          } elseif (strpos($nameLower, 'охлажден') !== false) {
                                              $icon = 'bi-snow';
                                          } elseif (strpos($nameLower, 'монитор') !== false) {
                                              $icon = 'bi-display';
                                          } elseif (strpos($nameLower, 'клавиатур') !== false) {
                                              $icon = 'bi-keyboard';
                                          } elseif (strpos($nameLower, 'мыш') !== false) {
                                              $icon = 'bi-mouse';
                                          } elseif (strpos($nameLower, 'наушник') !== false) {
                                              $icon = 'bi-headphones';
                                          }
                                      }
                                  ?>
                                  <i class="bi <?php echo e($icon); ?> me-2"></i> <?php echo e($category->name); ?>

                              </a>
                              <?php if(!empty($category->filters['brand'])): ?> 
                                  <ul class="mega-category-list list-unstyled">
                                      <?php $count = 0; ?>
                                      <?php
                                          // Теперь $category->filters['brand']['values'] содержит только бренды
                                          $displayValues = array_slice($category->filters['brand']['values'], 0, 3); // Берем до 3 значений для отображения
                                      ?>
                                      <?php $__currentLoopData = $displayValues; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $value): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                          <?php if($value === 'ARDOR'): ?>
                                              <?php continue; ?> 
                                          <?php endif; ?>
                                          <li>
                                              <a class="mega-subcategory-item" href="<?php echo e(route('catalog.category', $category->id)); ?>?filter[brand][]=<?php echo e(urlencode($value)); ?>">
                                                  <?php echo e($value); ?>

                                              </a>
                                          </li>
                                          <?php $count++; ?>
                                      <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                  </ul>
                              <?php endif; ?>
                          </div>
                      </div>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                  </div>
                  

                </div>
              </ul>
            </div>

            <!-- Поиск -->
            <div class="search-box me-lg-4 mb-3 mb-lg-0 flex-grow-1" style="position:relative;">
              <form action="<?php echo e(route('search')); ?>" method="GET">
                <div class="input-group">
                  <input 
                    type="text" 
                    name="query" 
                    class="form-control border-0" 
                    placeholder="Поиск по сайту"
                    autocomplete="off"
                  >
                  <button type="submit" class="btn bg-white">
                    <i class="bi bi-search"></i>
                  </button>
                </div>
                <div id="autocomplete-results" class="autocomplete-results" style="display:none;"></div>
              </form>
            </div>

            <!-- Ссылки -->
            <div class="d-flex flex-column flex-lg-row gap-4 me-lg-4 mb-3 mb-lg-0">
              <a href="<?php echo e(route('delivery')); ?>" class="text-dark text-decoration-none <?php echo e(Request::is('delivery') ? 'active' : ''); ?>">Доставка</a>
              <a href="<?php echo e(route('shops')); ?>" class="text-dark text-decoration-none <?php echo e(Request::is('shops') ? 'active' : ''); ?>">Новости</a>
              <a href="<?php echo e(route('promotions')); ?>" class="text-dark text-decoration-none <?php echo e(Request::is('promotions') ? 'active' : ''); ?>">О компании</a>
            </div>

            <div class="nav-divider me-lg-4 d-none d-lg-block"></div>

            <!-- Корзина -->
            <a href="<?php echo e(route('cart')); ?>" class="text-dark text-decoration-none position-relative <?php echo e(Request::is('cart') ? 'active' : ''); ?>">
              <i class="bi bi-cart3 fs-5 pe-3"></i>
              Корзина
              <?php if(auth()->guard()->check()): ?>
              <?php if(auth()->user()->cartItems()->count() > 0): ?>
              <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger">
                <?php echo e(auth()->user()->cartItems()->count()); ?>

              </span>
              <?php endif; ?>
              <?php endif; ?>
            </a>
          </div>
        </div>
      </div>
    </div>
  </nav>
</header>

<script>
// Pass the base image URL to JavaScript
const baseImageUrl = "<?php echo e($baseImageUrl); ?>";

document.addEventListener('DOMContentLoaded', function() {
    const searchInput = document.querySelector('.search-box input[name="query"]');
    const resultsBox = document.getElementById('autocomplete-results');
    if (!searchInput || !resultsBox) return;

    let timer;
    searchInput.addEventListener('input', function() {
        clearTimeout(timer);
        const query = this.value.trim();
        if (query.length < 2) {
            resultsBox.innerHTML = '';
            resultsBox.style.display = 'none';
            return;
        }
        timer = setTimeout(() => {
            fetch(`/catalog/search?query=${encodeURIComponent(query)}`)
                .then(res => res.json())
                .then(data => {
                    if (data.length === 0) {
                        resultsBox.innerHTML = '<div class="autocomplete-item">Ничего не найдено</div>';
                    } else {
                        // Add info line before results
                        const infoLine = `<div class="autocomplete-info">Найдено товаров (${query}): ${data.length}</div>`;
                        
                        resultsBox.innerHTML = infoLine + data.map(item =>
                            `<a href="/product/${item.id}" class="autocomplete-item">
                                <div class="autocomplete-item__image-container">
                                    ${item.img ? `<img src="${baseImageUrl}${item.img}" alt="${item.title}" class="autocomplete-item__image">` : `<i class="bi bi-image autocomplete-item__no-image"></i>`}
                                </div>
                                <div class="autocomplete-item__text-content">
                                    <div class="autocomplete-item__title">${highlightMatch(item.title, query)}</div>
                                    ${item.description ? `<div class="autocomplete-item__description">${highlightMatch(item.description, query)}</div>` : ''}
                                </div>
                            </a>`
                        ).join('');
                    }
                    resultsBox.style.display = 'block';
                })
                .catch(err => {
                    resultsBox.innerHTML = '<div class="autocomplete-item">Ошибка поиска</div>';
                    resultsBox.style.display = 'block';
                });
        }, 300);
    });

    document.addEventListener('click', function(e) {
        if (!resultsBox.contains(e.target) && e.target !== searchInput) {
            resultsBox.style.display = 'none';
        }
    });

    function highlightMatch(text, query) {
        if (!text) return '';
        const regex = new RegExp(`(${query})`, 'gi');
        return text.replace(regex, '<b>$1</b>');
    }
});

document.addEventListener('DOMContentLoaded', function() {
    // Универсальное открытие/закрытие меню Каталога по клику
    const catalogBtn = document.getElementById('catalogDropdown');
    const catalogMenu = document.querySelector('.catalog-mega-menu');
    if (catalogBtn && catalogMenu) {
        // Отключаем стандартное поведение Bootstrap dropdown
        catalogBtn.addEventListener('click', function(e) {
            e.preventDefault();
            e.stopPropagation();
            // Закрыть другие открытые dropdown'ы
            document.querySelectorAll('.catalog-mega-menu.show').forEach(el => {
                if (el !== catalogMenu) el.classList.remove('show');
            });
            catalogMenu.classList.toggle('show');
        });
        // Закрытие по клику вне меню
        document.addEventListener('click', function(e) {
            if (!catalogMenu.contains(e.target) && e.target !== catalogBtn) {
                catalogMenu.classList.remove('show');
            }
        });
        // Кнопка-крестик для закрытия меню
        const closeBtn = catalogMenu.querySelector('.catalog-close-btn');
        if (closeBtn) {
            closeBtn.addEventListener('click', function(e) {
                e.preventDefault();
                catalogMenu.classList.remove('show');
            });
        }
    }
});
</script>

<style>
/* Для корректного отображения подменю справа */
.dropdown-submenu > .dropdown-menu {
  top: 0;
  left: 100%;
  margin-top: -1px;
}
.dropdown-submenu:hover > .dropdown-menu {
  display: block;
}

/* Стили для мега-меню каталога */
.catalog-mega-menu {
  min-width: 700px;
  background: #ffffff; /* Чистый белый фон */
  border-radius: 12px; /* Менее скругленные углы */
  box-shadow: 0 8px 25px rgba(0, 0, 0, 0.15); /* Более выраженная тень */
  padding: 20px 25px; /* Единый отступ */
  border: none;
  margin-top: 15px; /* Небольшой отступ сверху */
}

/* Единые стили для хлебных крошек на всех страницах */
.breadcrumb {
  padding: 0.5rem 0;
  font-size: 0.875rem;
  margin-bottom: 1rem;
}

.breadcrumb-item a {
  color: #000;
  text-decoration: none;
}

.breadcrumb-item a:hover {
  text-decoration: none;
  color: #555;
}

.breadcrumb-item.active {
  color: #666;
}

.breadcrumb-item + .breadcrumb-item::before {
  color: #999;
}

.mega-menu-header {
  font-size: 1.1rem;
  font-weight: 700;
  color: #222;
  padding-bottom: 0.5rem;
}

.mega-menu-all {
  font-weight: 600;
  color: #222;
  padding: 0.5rem 0.7rem;
  border-radius: 8px;
  transition: background 0.18s;
}

.mega-menu-all:hover {
  background: #f5f5f5;
  color: #000;
}

.mega-menu-content {
  padding: 20px;
}

.mega-category {
  margin-bottom: 1.2rem;
}

.mega-category-title {
  display: flex;
  align-items: center;
  font-size: 1.08rem;
  font-weight: 700;
  color: #222;
  margin-bottom: 0.5rem;
  gap: 0.6rem;
  padding: 0.4rem 0.7rem;
  border-radius: 8px;
  transition: background 0.18s;
}

.mega-category-title:hover {
  background: #f5f5f5;
  color: #000;
  text-decoration: none;
}

.mega-category-icon {
  font-size: 1.3rem;
  margin-right: 0.5rem;
  color: #000 !important;
}

.mega-subcategory-list {
  list-style: none;
  padding-left: 1.7rem;
  margin-bottom: 0.2rem;
}

.mega-subcategory-item {
  display: block;
  color: #222;
  font-size: 1rem;
  padding: 0.25rem 0.5rem;
  border-radius: 6px;
  margin-bottom: 0.1rem;
  transition: background 0.18s;
}

.mega-subcategory-item:hover {
  background: #f5f5f5;
  color: #000;
  text-decoration: none;
}

.mega-subcategory-more {
  color: #007bff;
  font-size: 0.98rem;
  padding-left: 0.5rem;
}

@media (max-width: 900px) {
  .catalog-mega-menu {
    min-width: 320px;
    padding: 0.7rem 0.5rem 1rem 0.5rem;
  }
}

.catalog-mega-menu a,
.mega-category-title,
.mega-subcategory-item,
.mega-menu-all {
    text-decoration: none !important;
}
.mega-category-title:hover,
.mega-subcategory-item:hover,
.mega-menu-all:hover {
    text-decoration: none !important;
}

/* Styles for autocomplete results */
.autocomplete-results {
    position: absolute;
    top: 100%; /* Position below the search input */
    left: 0;
    right: 0;
    background-color: #fff;
    border: 1px solid #ccc;
    border-top: none;
    max-height: 300px;
    overflow-y: auto;
    z-index: 1000; /* Ensure it's above other content */
    border-radius: 0 0 8px 8px; /* Rounded corners at the bottom */
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
}

.autocomplete-item {
    display: flex;
    align-items: center;
    padding: 10px;
    text-decoration: none;
    color: #333;
    border-bottom: 1px solid #eee;
    transition: background-color 0.2s ease;
}

.autocomplete-item:last-child {
    border-bottom: none;
}

.autocomplete-item:hover {
    background-color: #f5f5f5;
    color: #000;
}

.autocomplete-item__image-container {
    width: 50px;
    height: 50px;
    min-width: 50px; /* Prevent shrinking */
    min-height: 50px; /* Prevent shrinking */
    overflow: hidden;
    border-radius: 4px;
    margin-right: 15px;
    display: flex;
    align-items: center;
    justify-content: center;
    background-color: #f0f0f0; /* Placeholder background */
}

.autocomplete-item__image {
    display: block;
    width: 100%;
    height: 100%;
    object-fit: cover; /* Cover the container */
}

.autocomplete-item__no-image {
    font-size: 24px;
    color: #888;
}

.autocomplete-item__text-content {
    flex-grow: 1;
    overflow: hidden; /* Prevent text overflow */
}

.autocomplete-item__title {
    font-weight: bold;
    margin-bottom: 3px;
    white-space: nowrap; /* Prevent wrapping */
    overflow: hidden;
    text-overflow: ellipsis; /* Add ellipsis if text is too long */
}

.autocomplete-item__description {
    font-size: 0.9em;
    color: #666;
    white-space: nowrap; /* Prevent wrapping */
    overflow: hidden;
    text-overflow: ellipsis; /* Add ellipsis if text is too long */
}

.autocomplete-item b {
    font-weight: bold;
    color: #000;
}

/* Style for the info line */
.autocomplete-info {
    padding: 10px;
    font-size: 0.9em;
    color: #555;
    border-bottom: 1px solid #eee;
    background-color: #f9f9f9; /* Slightly different background */
}

@media (max-width: 991.98px) {
  .catalog-mega-menu {
    position: fixed !important;
    left: 0 !important;
    right: 0 !important;
    top: 60px !important; /* высота шапки, при необходимости скорректируйте */
    min-width: 100vw !important;
    width: 100vw !important;
    border-radius: 0 0 16px 16px !important;
    box-shadow: 0 8px 25px rgba(0,0,0,0.15);
    z-index: 2000 !important;
    margin-top: 0 !important;
    padding: 16px 8px !important;
    max-height: 80vh;
    overflow-y: auto;
  }
  .dropdown-menu.catalog-mega-menu {
    display: none;
  }
  .dropdown-menu.catalog-mega-menu.show {
    display: block;
  }
  .catalog-close-btn {
    display: block !important;
  }
}
@media (min-width: 992px) {
  .catalog-close-btn {
    display: none !important;
  }
}

@media (max-width: 1399px) {
  .navbar .search-box {
    max-width: 260px !important;
    min-width: 180px !important;
  }
  .navbar .d-flex.flex-column.flex-lg-row.gap-4 {
    gap: 1.2rem !important;
  }
  .navbar .me-lg-4 {
    margin-right: 1rem !important;
  }
  .navbar .btn-header {
    padding-left: 1rem !important;
    padding-right: 1rem !important;
  }
}

@media (max-width: 1199px) {
  .navbar .search-box {
    max-width: 200px !important;
    min-width: 140px !important;
  }
  .navbar .d-flex.flex-column.flex-lg-row.gap-4 {
    gap: 0.8rem !important;
  }
  .navbar .me-lg-4 {
    margin-right: 0.5rem !important;
  }
  .navbar .btn-header {
    padding-left: 1rem !important;
    padding-right: 1rem !important;
  }
}
</style>

<?php /**PATH C:\OpenServer\domains\dualshop1\laravel-main\resources\views/layouts/navigation.blade.php ENDPATH**/ ?>