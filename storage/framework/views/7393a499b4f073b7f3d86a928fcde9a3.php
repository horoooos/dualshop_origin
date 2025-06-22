<div class="top-categories-card h-100 d-flex flex-column justify-content-between">
    <div>
        <div class="text-center mb-3" style="height: 180px; display: flex; align-items: center; justify-content: center;">
            <?php
                $imageFileName = null;
                // Сначала проверяем новую таблицу images
                if ($product->images && $product->images->count() > 0) {
                    $imageFileName = $product->images->first()->image_path;
                }
                // Если в новой таблице нет, проверяем старое поле img
                elseif (!empty($product->img)) {
                    $imageFileName = $product->img;
                }

                $imageUrl = null;
                if ($imageFileName) {
                    $imageUrl = Vite::asset('resources/media/images/' . $imageFileName);
                }
            ?>
            <?php if($imageUrl): ?>
                <img src="<?php echo e($imageUrl); ?>" alt="<?php echo e($product->title); ?>" class="top-categories-image">
            <?php else: ?>
                <div class="no-image-placeholder d-flex flex-column align-items-center justify-content-center h-100">
                    <i class="bi bi-image" style="font-size: 3rem; color: #ccc;"></i>
                    <div style="margin-top: 10px; color: #999;">Нет изображения</div>
                </div>
            <?php endif; ?>
        </div>
        <h5 class="top-categories-product-title"><?php echo e($product->title); ?></h5>
        <div class="mb-2 text-muted small">Код товара: <?php echo e($product->id); ?></div>
        <div class="mb-2">Категория: <?php echo e($product->category ? $product->category->name : 'Нет'); ?></div>
        <div class="top-categories-price mb-2">
            <?php echo e(number_format($product->price, 0, ',', ' ')); ?> ₽
        </div>
        <div class="product-credit mb-2"><?php echo e(number_format($product->price / 12, 0, ',', ' ')); ?> ₽/мес</div>
    </div>
    <div class="mt-auto">
        <a href="<?php echo e(route('catalog.product', $product->id)); ?>" class="btn btn-outline-primary w-100 product-detail-btn">Подробнее</a>
        <div class="d-flex justify-content-between align-items-center mt-2">
            <div class="small text-muted"><?php echo e($product->country); ?></div>
            <div class="top-categories-rating">
                <?php for($i = 1; $i <= 5; $i++): ?>
                    <?php if($i <= round($product->rating)): ?>
                        <i class="bi bi-star-fill text-warning"></i>
                    <?php else: ?>
                        <i class="bi bi-star text-muted"></i>
                    <?php endif; ?>
                <?php endfor; ?>
            </div>
        </div>
    </div>
</div> <?php /**PATH C:\OpenServer\domains\dualshop1\laravel-main\resources\views/catalog/_product_card.blade.php ENDPATH**/ ?>