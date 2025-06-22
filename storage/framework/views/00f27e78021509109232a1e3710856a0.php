<?php $__env->startSection('content'); ?>

<section class="product-banner position-relative overflow-hidden bg-light">
  <div class="container h-100">
    <div class="row h-100 align-items-center">
      <div class="col-md-6 content-column">
        <h1 class="product-title1">
          <a href="/catalog?filter=1" class="d-inline-block">
            <img src="<?php echo e(Vite::asset('resources/media/images/fire.png')); ?>" alt="Apple" class="apple-logo mb-4">
          </a>
          GeForce RTX 5080
        </h1>
        <p class="product-features lead mb-4">
          Современный дизайн,<br>
          момощное охлаждение и передовая<br>  архитектура
        </p>
        <p class="product-description mb-5">
        Видеокарта представлена в серии GameRock
        – с улучшенной системой охлаждения, подсветкой
        и разгонным потенциалом. Оснащена 16 ГБ памяти GDDR7,
        поддержкой трассировки лучей и DLSS 3.5, а также инновационными
        технологиями NVIDIA для максимальной производительности в играх и творческих задачах.
        </p>
        <a href="<?php echo e(route('product.show', 7)); ?>" class="btn btn-dark btn-lg px-5 rounded-1 btn-banner">Купить</a>
      </div>

      <div class="col-md-6 image-column">
        <div class="image-wrapper">
          <a href="/catalog/product/7">
            <img src="<?php echo e(Vite::asset('resources/media/images/phone1.png')); ?>" alt="iPhone 15" class="product-image1">
          </a>
        </div>
      </div>
    </div>
  </div>
</section>

<section class="categories-section py-5">
    <div class="container">
        <div class="row g-4">
            <!-- Популярные категории -->
            <div class="col-lg-4 col-md-6">
                <h2 class="section-title">Популярные категории</h2>
                <div class="category-card">
                    <div class="row g-3 h-100">
                        <div class="col-5">
                            <div class="image-container">
                                <?php
                                    $randomProduct = \App\Models\Product::whereNotNull('img')->where('img', '!=', '')->inRandomOrder()->first();
                                ?>
                                <?php if($randomProduct): ?>
                                    <?php
                                        $imageFileName = null;
                                        if (isset($randomProduct->images) && count($randomProduct->images) > 0) {
                                            $imageFileName = $randomProduct->images[0]->image_path;
                                        } elseif (!empty($randomProduct->img)) {
                                            $imageFileName = $randomProduct->img;
                                        }
                                    ?>
                                    <?php if($imageFileName): ?>
                                        <a href="<?php echo e(route('product.show', $randomProduct->id)); ?>">
                                            <?php
                                                $publicPath = public_path('media/images/' . $imageFileName);
                                            ?>
                                            <?php if($imageFileName && file_exists($publicPath)): ?>
                                                <img src="<?php echo e(asset('media/images/' . $imageFileName)); ?>" 
                                                     class="category-img" 
                                                     alt="<?php echo e($randomProduct->title); ?>">
                                            <?php elseif($imageFileName): ?>
                                                <img src="<?php echo e(Vite::asset('resources/media/images/' . $imageFileName)); ?>" 
                                                     class="category-img" 
                                                     alt="<?php echo e($randomProduct->title); ?>">
                                            <?php else: ?>
                                                <div class="no-image-placeholder d-flex flex-column align-items-center justify-content-center h-100">
                                                    <i class="bi bi-image" style="font-size: 3rem; color: #ccc;"></i>
                                                    <div style="margin-top: 10px; color: #999;">Нет изображения</div>
                                                </div>
                                            <?php endif; ?>
                                        </a>
                                    <?php else: ?>
                                        <div class="no-image-placeholder d-flex flex-column align-items-center justify-content-center h-100">
                                            <i class="bi bi-image" style="font-size: 3rem; color: #ccc;"></i>
                                            <div style="margin-top: 10px; color: #999;">Нет изображения</div>
                                        </div>
                                    <?php endif; ?>
                                <?php endif; ?>
                            </div>
                        </div>
                        <div class="col-7">
                            <ul class="category-list">
                                <?php
                                    $categories = \App\Models\Category::whereNull('parent_id')->inRandomOrder()->take(5)->get();
                                ?>
                                <?php if($categories->count() > 0): ?>
                                    <?php $__currentLoopData = $categories; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $category): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <li class="category-item">
                                            <a href="<?php echo e(route('catalog.category', $category->id)); ?>" 
                                               class="category-list <?php echo e(request()->query('filter') == $category->id ? 'active' : ''); ?>">
                                                <?php echo e($category->name); ?>

                                            </a>
                                        </li>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                <?php else: ?>
                                    <li class="category-item">Нет доступных категорий</li>
                                <?php endif; ?>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Сезонные товары -->
            <div class="col-lg-4 col-md-6">
                <h2 class="section-title">Сезонные товары</h2>
                <div class="category-card">
                    <div class="row g-3 h-100">
                        <div class="col-5">
                            <div class="image-container">
                                <?php
                                    $randomProduct = \App\Models\Product::whereNotNull('img')->where('img', '!=', '')->inRandomOrder()->first();
                                ?>
                                <?php if($randomProduct): ?>
                                    <?php
                                        $imageFileName = null;
                                        if (isset($randomProduct->images) && count($randomProduct->images) > 0) {
                                            $imageFileName = $randomProduct->images[0]->image_path;
                                        } elseif (!empty($randomProduct->img)) {
                                            $imageFileName = $randomProduct->img;
                                        }
                                    ?>
                                    <?php if($imageFileName): ?>
                                        <a href="<?php echo e(route('product.show', $randomProduct->id)); ?>">
                                            <?php
                                                $publicPath = public_path('media/images/' . $imageFileName);
                                            ?>
                                            <?php if($imageFileName && file_exists($publicPath)): ?>
                                                <img src="<?php echo e(asset('media/images/' . $imageFileName)); ?>" 
                                                     class="category-img" 
                                                     alt="<?php echo e($randomProduct->title); ?>">
                                            <?php elseif($imageFileName): ?>
                                                <img src="<?php echo e(Vite::asset('resources/media/images/' . $imageFileName)); ?>" 
                                                     class="category-img" 
                                                     alt="<?php echo e($randomProduct->title); ?>">
                                            <?php else: ?>
                                                <div class="no-image-placeholder d-flex flex-column align-items-center justify-content-center h-100">
                                                    <i class="bi bi-image" style="font-size: 3rem; color: #ccc;"></i>
                                                    <div style="margin-top: 10px; color: #999;">Нет изображения</div>
                                                </div>
                                            <?php endif; ?>
                                        </a>
                                    <?php else: ?>
                                        <div class="no-image-placeholder d-flex flex-column align-items-center justify-content-center h-100">
                                            <i class="bi bi-image" style="font-size: 3rem; color: #ccc;"></i>
                                            <div style="margin-top: 10px; color: #999;">Нет изображения</div>
                                        </div>
                                    <?php endif; ?>
                                <?php endif; ?>
                            </div>
                        </div>
                        <div class="col-7">
                            <ul class="category-list">
                                <?php
                                    $categories = \App\Models\Category::whereNull('parent_id')->inRandomOrder()->take(5)->get();
                                ?>
                                <?php if($categories->count() > 0): ?>
                                    <?php $__currentLoopData = $categories; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $category): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <li class="category-item">
                                            <a href="<?php echo e(route('catalog.category', $category->id)); ?>" 
                                               class="category-list <?php echo e(request()->query('filter') == $category->id ? 'active' : ''); ?>">
                                                <?php echo e($category->name); ?>

                                            </a>
                                        </li>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                <?php else: ?>
                                    <li class="category-item">Нет доступных категорий</li>
                                <?php endif; ?>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Акции -->
            <div class="col-lg-4 col-md-6">
                <h2 class="section-title">Акции и скидки</h2>
                <div class="category-card">
                    <div class="row g-3 h-100">
                        <div class="col-5">
                            <div class="image-container">
                                <?php
                                    $randomProduct = \App\Models\Product::whereNotNull('img')->where('img', '!=', '')->inRandomOrder()->first();
                                ?>
                                <?php if($randomProduct): ?>
                                    <?php
                                        $imageFileName = null;
                                        if (isset($randomProduct->images) && count($randomProduct->images) > 0) {
                                            $imageFileName = $randomProduct->images[0]->image_path;
                                        } elseif (!empty($randomProduct->img)) {
                                            $imageFileName = $randomProduct->img;
                                        }
                                    ?>
                                    <?php if($imageFileName): ?>
                                        <a href="<?php echo e(route('product.show', $randomProduct->id)); ?>">
                                            <?php
                                                $publicPath = public_path('media/images/' . $imageFileName);
                                            ?>
                                            <?php if($imageFileName && file_exists($publicPath)): ?>
                                                <img src="<?php echo e(asset('media/images/' . $imageFileName)); ?>" 
                                                     class="category-img" 
                                                     alt="<?php echo e($randomProduct->title); ?>">
                                            <?php elseif($imageFileName): ?>
                                                <img src="<?php echo e(Vite::asset('resources/media/images/' . $imageFileName)); ?>" 
                                                     class="category-img" 
                                                     alt="<?php echo e($randomProduct->title); ?>">
                                            <?php else: ?>
                                                <div class="no-image-placeholder d-flex flex-column align-items-center justify-content-center h-100">
                                                    <i class="bi bi-image" style="font-size: 3rem; color: #ccc;"></i>
                                                    <div style="margin-top: 10px; color: #999;">Нет изображения</div>
                                                </div>
                                            <?php endif; ?>
                                        </a>
                                    <?php else: ?>
                                        <div class="no-image-placeholder d-flex flex-column align-items-center justify-content-center h-100">
                                            <i class="bi bi-image" style="font-size: 3rem; color: #ccc;"></i>
                                            <div style="margin-top: 10px; color: #999;">Нет изображения</div>
                                        </div>
                                    <?php endif; ?>
                                <?php endif; ?>
                            </div>
                        </div>
                        <div class="col-7">
                            <ul class="category-list">
                                <?php
                                    $categories = \App\Models\Category::whereNull('parent_id')->inRandomOrder()->take(5)->get();
                                ?>
                                <?php if($categories->count() > 0): ?>
                                    <?php $__currentLoopData = $categories; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $category): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <li class="category-item">
                                            <a href="<?php echo e(route('catalog.category', $category->id)); ?>" 
                                               class="category-list <?php echo e(request()->query('filter') == $category->id ? 'active' : ''); ?>">
                                                <?php echo e($category->name); ?>

                                            </a>
                                        </li>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                <?php else: ?>
                                    <li class="category-item">Нет доступных категорий</li>
                                <?php endif; ?>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<section class="brands-section py-5 mt-5">
  <div class="container">
    <div class="row g-3 justify-content-center">
      <!-- Логотип 1 -->
      <div class="col-6 col-md-4 col-lg-2">
        <div class="brand-card">
          <a href="<?php echo e(route('catalog')); ?>?filter[brand][]=DEEPCOOL">
            <img src="<?php echo e(Vite::asset('resources/media/images/logo_brand (1).png')); ?>" alt="Brand 1" class="brand-logo">
          </a>
        </div>
      </div>

      <!-- Логотип 2 -->
      <div class="col-6 col-md-4 col-lg-2">
        <div class="brand-card">
          <a href="<?php echo e(route('catalog')); ?>?filter[brand][]=AEROCOOL">
            <img src="<?php echo e(Vite::asset('resources/media/images/logo_brand (2).png')); ?>" alt="Brand 2" class="brand-logo">
          </a>
        </div>
      </div>

      <!-- Логотип 3 -->
      <div class="col-6 col-md-4 col-lg-2">
        <div class="brand-card">
          <a href="<?php echo e(route('catalog')); ?>?filter[brand][]=COUGAR">
            <img src="<?php echo e(Vite::asset('resources/media/images/logo_brand (3).png')); ?>" alt="Brand 3" class="brand-logo">
          </a>
        </div>
      </div>

      <!-- Логотип 4 -->
      <div class="col-6 col-md-4 col-lg-2">
        <div class="brand-card">
          <a href="<?php echo e(route('catalog')); ?>?filter[brand][]=ASROCK">
            <img src="<?php echo e(Vite::asset('resources/media/images/logo_brand (4).png')); ?>" alt="Brand 4" class="brand-logo">
          </a>
        </div>
      </div>

      <!-- Логотип 5 -->
      <div class="col-6 col-md-4 col-lg-2">
        <div class="brand-card">
          <a href="<?php echo e(route('catalog')); ?>?filter[brand][]=KINGSTON">
            <img src="<?php echo e(Vite::asset('resources/media/images/logo_brand (5).png')); ?>" alt="Brand 5" class="brand-logo">
          </a>
        </div>
      </div>

      <!-- Логотип 6 -->
      <div class="col-6 col-md-4 col-lg-2">
        <div class="brand-card">
          <a href="<?php echo e(route('catalog')); ?>?filter[brand][]=ARDOR+GAMING">
            <img src="<?php echo e(Vite::asset('resources/media/images/logo_brand (6).png')); ?>" alt="Brand 6" class="brand-logo">
          </a>
        </div>
      </div>
    </div>
  </div>
</section>

<section class="products-section">
  <div class="container">
    <div class="row g-4">
      <?php if(isset($products) && $products->isNotEmpty()): ?>
        <?php $__currentLoopData = $products; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $product): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
          <div class="col-xl-4 col-md-6">
            <div class="product-card position-relative">
              <img src="<?php echo e(Vite::asset('resources/media/images/bxs_hot.png')); ?>" class="product-badge">
              <div class="row h-100 g-0 pe-4">
                <div class="col-6">
                  <div class="product-content">
                    <span class="product-timer">Еще 4 часа скидка</span>
                    <h3 class="product-title-cart"><?php echo e($product->title); ?><br></h3>
                    <a href="<?php echo e(route('product.show', $product->id)); ?>" class="btn btn-outline-primary product-detail-btn mt-2">Подробнее</a>
                  </div>
                </div>
                <div class="col-6">
                  <div class="product-image-container">
                    <?php
                        $imageFileName = null;
                        if (isset($product->images) && count($product->images) > 0) {
                            $imageFileName = $product->images[0]->image_path;
                        } elseif (!empty($product->img)) {
                            $imageFileName = $product->img;
                        }
                        $publicPath = public_path('media/images/' . $imageFileName);
                    ?>
                    <?php if($imageFileName && file_exists($publicPath)): ?>
                        <a href="<?php echo e(route('product.show', $product->id)); ?>">
                            <img src="<?php echo e(asset('media/images/' . $imageFileName)); ?>" 
                                 class="product-image" 
                                 alt="<?php echo e($product->title); ?>">
                        </a>
                    <?php elseif($imageFileName): ?>
                        <a href="<?php echo e(route('product.show', $product->id)); ?>">
                            <img src="<?php echo e(Vite::asset('resources/media/images/' . $imageFileName)); ?>" 
                                 class="product-image" 
                                 alt="<?php echo e($product->title); ?>">
                        </a>
                    <?php else: ?>
                        <div class="no-image-placeholder d-flex flex-column align-items-center justify-content-center h-100">
                            <i class="bi bi-image" style="font-size: 3rem; color: #ccc;"></i>
                            <div style="margin-top: 10px; color: #999;">Нет изображения</div>
                        </div>
                    <?php endif; ?>
                  </div>
                </div>
              </div>
            </div>
          </div>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

        <!-- Дополнительная карточка "Показать больше" -->
        <div class="col-12">
            <div class="product-card position-relative d-flex align-items-center justify-content-center text-center h-100 show-more-card">
                <a href="<?php echo e(route('catalog.categories')); ?>" class="product-detail-btn1 show-more-btn w-100">
                    Показать больше
                    <i class="bi bi-arrow-right ms-2"></i>
                </a>
            </div>
        </div>

      <?php else: ?>
        <div class="col-12">
          <p class="text-center">Нет доступных товаров</p>
        </div>
      <?php endif; ?>
    </div>
  </div>
</section>

<section class="top-categories-section">
    <div class="container">
        <div class="row align-items-center mb-4">
            <div class="col-md-8">
                <h2 class="top-categories-title">Новые товары</h2>
            </div>
            <div class="col-md-4 text-end">
                <a href="<?php echo e(route('catalog.categories')); ?>" class="top-categories-link">
                    Перейти в каталог
                    <i class="bi bi-chevron-right ms-2"></i>
                </a>
            </div>
        </div>

        <div class="top-categories-grid">
            <div class="row g-4">
                <?php if(isset($topCategoriesProducts) && $topCategoriesProducts->isNotEmpty()): ?>
                    <?php $__currentLoopData = $topCategoriesProducts; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $product): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <div class="col-6 col-md-4 col-lg-3">
                        <div class="top-categories-card">
                            <div class="top-categories-badge"><?php echo e($product->is_seasonal ? 'HOT' : 'NEW'); ?></div>
                            <a href="<?php echo e(route('product.show', $product->id)); ?>">
                                <?php
                                    $imageFileName = null;
                                    if (isset($product->images) && count($product->images) > 0) {
                                        $imageFileName = $product->images[0]->image_path;
                                    } elseif (!empty($product->img)) {
                                        $imageFileName = $product->img;
                                    }
                                    $publicPath = public_path('media/images/' . $imageFileName);
                                ?>
                                <?php if($imageFileName && file_exists($publicPath)): ?>
                                    <img src="<?php echo e(asset('media/images/' . $imageFileName)); ?>" 
                                         class="top-categories-image" 
                                         alt="<?php echo e($product->title); ?>">
                                <?php elseif($imageFileName): ?>
                                    <img src="<?php echo e(Vite::asset('resources/media/images/' . $imageFileName)); ?>" 
                                         class="top-categories-image" 
                                         alt="<?php echo e($product->title); ?>">
                                <?php else: ?>
                                    <div class="no-image-placeholder d-flex flex-column align-items-center justify-content-center h-100">
                                        <i class="bi bi-image" style="font-size: 3rem; color: #ccc;"></i>
                                        <div style="margin-top: 10px; color: #999;">Нет изображения</div>
                                    </div>
                                <?php endif; ?>
                            </a>
                            <h3 class="top-categories-product-title"><?php echo e($product->title); ?></h3>
                            <div class="top-categories-rating">
                                <?php for($i = 1; $i <= 5; $i++): ?>
                                    <?php if($i <= floor($product->rating ?? 0)): ?>
                                        <i class="bi bi-star-fill"></i>
                                    <?php elseif($i - 0.5 <= ($product->rating ?? 0)): ?>
                                        <i class="bi bi-star-half"></i>
                                    <?php else: ?>
                                        <i class="bi bi-star"></i>
                                    <?php endif; ?>
                                <?php endfor; ?>
                            </div>
                            <div class="top-categories-price"><?php echo e(number_format($product->price, 0, ',', ' ')); ?> ₽</div>
                            <a href="<?php echo e(route('product.show', $product->id)); ?>" class="btn btn-outline-primary product-detail-btn2">
                                Подробнее
                                <i class="bi bi-arrow-right-short"></i>
                            </a>
                        </div>
                    </div>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                <?php else: ?>
                    <div class="col-12">
                        <p class="text-center">Нет доступных товаров</p>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</section>
<?php if(auth()->guard()->check()): ?>
<div class="position-fixed" style="top: 20px; right: 20px; z-index: 9999;">
    <div id="successToast" class="toast" role="alert" aria-live="assertive" aria-atomic="true">
        <div class="toast-header bg-success text-white">
            <i class="fas fa-check-circle me-2"></i>
            <strong class="me-auto">Успешно</strong>
            <button type="button" class="btn-close btn-close-white" data-bs-dismiss="toast" aria-label="Close"></button>
        </div>
        <div class="toast-body bg-success text-white">
            Товар успешно добавлен в корзину
        </div>
    </div>
    
    <div id="errorToast" class="toast" role="alert" aria-live="assertive" aria-atomic="true">
        <div class="toast-header bg-danger text-white">
            <i class="fas fa-exclamation-circle me-2"></i>
            <strong class="me-auto">Ошибка</strong>
            <button type="button" class="btn-close btn-close-white" data-bs-dismiss="toast" aria-label="Close"></button>
        </div>
        <div class="toast-body bg-danger text-white">
            Товара нет в наличии
        </div>
    </div>
</div>

<script>
function addToCart(productId) {
    if(!productId) return;
    
    fetch(`/add-to-cart/${productId}`, {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': '<?php echo e(csrf_token()); ?>',
            'Content-Type': 'application/json',
            'Accept': 'application/json'
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            const toast = new bootstrap.Toast(document.getElementById('successToast'));
            toast.show();
        } else {
            const errorToast = document.getElementById('errorToast');
            errorToast.querySelector('.toast-body').textContent = data.error;
            const toast = new bootstrap.Toast(errorToast);
            toast.show();
        }
    })
    .catch(() => {
        const errorToast = document.getElementById('errorToast');
        errorToast.querySelector('.toast-body').textContent = 'Произошла ошибка при добавлении товара';
        const toast = new bootstrap.Toast(errorToast);
        toast.show();
    });
}

function changeQuantity(cartItemId, action) {
    fetch(`/cart/${cartItemId}/change-quantity`, {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': '<?php echo e(csrf_token()); ?>',
            'Content-Type': 'application/json',
            'Accept': 'application/json'
        },
        body: JSON.stringify({ action })
    })
    .then(response => {
        if (response.ok) {
            window.location.reload();
        }
    });
}
</script>
<?php endif; ?>

<style>
.show-more-card {
    min-height: 100px;
    border-radius: 10px;
    transition: all 0.3s ease;
    position: relative;
    overflow: hidden;
    background: black;
}

.show-more-card::before {
    content: '';
    position: absolute;
    top: 0;
    left: -100%;
    width: 100%;
    height: 100%;
    background: linear-gradient(
        90deg,
        transparent,
        rgba(255, 255, 255, 0.2),
        transparent
    );
    animation: shine 3s infinite;
    z-index: 32;
}

@keyframes shine {
    0% {
        left: -100%;
    }
    20% {
        left: 100%;
    }
    100% {
        left: 100%;
    }
}

.show-more-card:hover {
    transform: translateY(-2px);
    box-shadow: 0 5px 15px rgba(0,0,0,0.1);
}

.show-more-btn {
    font-size: 1.2rem;
    color: white;
    text-decoration: none;
    padding: 1rem;
    display: flex;
    align-items: center;
    justify-content: center;
    width: 100%;
    transition: all 0.3s ease;
    position: relative;
    z-index: 1;
}

.show-more-btn:hover {
    color: white;
}

</style>

<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\OpenServer\domains\dualshop1\laravel-main\resources\views/welcome.blade.php ENDPATH**/ ?>