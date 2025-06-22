
<?php $__env->startSection('content'); ?>
<div class="container py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 style="font-family: 'Yeseva One', serif; font-size: 2rem; margin-bottom: 1.5rem;">Редактирование новости</h1>
        <a href="<?php echo e(route('admin.news')); ?>" class="admin-btn admin-btn-outline mb-3">
            <i class="bi bi-arrow-left"></i> Назад к списку
        </a>
    </div>

    <div class="card">
        <div class="card-body">
            <form action="<?php echo e(route('admin.news.update', $news->id)); ?>" method="POST" enctype="multipart/form-data">
                <?php echo csrf_field(); ?>
                <?php echo method_field('PATCH'); ?>
                
                <div class="mb-3">
                    <label for="title" class="form-label">Заголовок <span class="text-danger">*</span></label>
                    <input type="text" class="form-control <?php $__errorArgs = ['title'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" id="title" name="title" value="<?php echo e(old('title', $news->title)); ?>" required>
                    <?php $__errorArgs = ['title'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                        <div class="invalid-feedback d-block"><?php echo e($message); ?></div>
                    <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                </div>
                
                <div class="mb-3">
                    <label for="short_description" class="form-label">Краткое описание <span class="text-danger">*</span></label>
                    <textarea class="form-control <?php $__errorArgs = ['short_description'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" id="short_description" name="short_description" rows="3" required><?php echo e(old('short_description', $news->short_description)); ?></textarea>
                    <?php $__errorArgs = ['short_description'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                        <div class="invalid-feedback d-block"><?php echo e($message); ?></div>
                    <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                </div>
                
                <div class="mb-3">
                    <label for="content" class="form-label">Полный текст новости <span class="text-danger">*</span></label>
                    <textarea class="form-control <?php $__errorArgs = ['content'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" id="content" name="content" rows="10" required><?php echo e(old('content', $news->content)); ?></textarea>
                    <?php $__errorArgs = ['content'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                        <div class="invalid-feedback d-block"><?php echo e($message); ?></div>
                    <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                </div>
                
                <div class="mb-3">
                    <label for="image" class="form-label">Изображение</label>
                    
                    <?php if($news->image): ?>
                        <div class="mb-2">
                            <img src="<?php echo e(asset('media/images/' . $news->image)); ?>" alt="<?php echo e($news->title); ?>" class="img-thumbnail" style="max-height: 200px;">
                            <div class="form-text">Текущее изображение. Загрузите новое, чтобы заменить.</div>
                        </div>
                    <?php endif; ?>
                    
                    <input type="file" class="form-control <?php $__errorArgs = ['image'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" id="image" name="image" accept="image/*">
                    <div class="form-text">Рекомендуемый размер: 800x400 пикселей. Максимальный размер: 2MB.</div>
                    <?php $__errorArgs = ['image'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                        <div class="invalid-feedback d-block"><?php echo e($message); ?></div>
                    <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                </div>
                
                <div class="mb-3 form-check">
                    <input type="hidden" name="is_published" value="0">
                    <input type="checkbox" class="form-check-input" id="is_published" name="is_published" value="1" <?php echo e(old('is_published', $news->is_published) ? 'checked' : ''); ?>>
                    <label class="form-check-label" for="is_published">Опубликовать</label>
                </div>
                
                <div class="d-flex justify-content-between">
                    <a href="<?php echo e(route('news.show', $news->id)); ?>" class="admin-btn admin-btn-outline btn-sm" target="_blank">
                        <i class="bi bi-eye"></i> Просмотр на сайте
                    </a>
                    <button type="submit" class="admin-btn btn-sm">
                        <i class="bi bi-check-lg"></i> Сохранить изменения
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    if (document.querySelector('#content')) {
        ClassicEditor
            .create(document.querySelector('#content'))
            .catch(error => {
                console.error(error);
            });
    }
});
</script>
<?php $__env->stopSection(); ?> 
<?php echo $__env->make('layouts.admin', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\OpenServer\domains\dualshop1\laravel-main\resources\views/admin/news/edit.blade.php ENDPATH**/ ?>