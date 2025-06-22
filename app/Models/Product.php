<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use App\Models\ProductSpecification;
use App\Models\Category;
use App\Models\ProductImage;

class Product extends Model
{
    use HasFactory;

    // Указываем, какие поля могут быть массово присвоены
    protected $fillable = [
        'title',
        'price',
        'img',
        'country',
        'color',
        'qty',
        'description',
        'specs_data',
        'category_id',
        'rating',
        'is_seasonal',
        'old_price',
        'is_bestseller',
        'is_new',
    ];

    protected $casts = [
        'price' => 'float',
        'discount' => 'integer',
        'rating' => 'float',
        'is_seasonal' => 'boolean',
        'specs_data' => 'array'
    ];

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    /**
     * Получить характеристики товара
     */
    public function specifications(): HasMany
    {
        return $this->hasMany(ProductSpecification::class);
    }

    /**
     * Получить значение характеристики по ключу
     */
    public function getSpecificationValue($key)
    {
        $spec = $this->specifications()->where('spec_key', $key)->first();
        return $spec ? $spec->spec_value : null;
    }

    /**
     * Проверить соответствие товара заданным фильтрам
     */
    public function matchesFilters(array $filters)
    {
        foreach ($filters as $key => $value) {
            $spec = $this->specifications()->where('spec_key', $key)->first();
            
            if (!$spec || $spec->spec_value != $value) {
                return false;
            }
        }
        
        return true;
    }

    // Определяем отношение 'один ко многим' с изображениями продукта
    public function images()
    {
        return $this->hasMany(ProductImage::class)->orderBy('order'); // Упорядочиваем по полю 'order'
    }

    // Метод для получения основного изображения (первого по порядку)
    public function getPrimaryImageAttribute()
    {
        return $this->images()->orderBy('order')->first();
    }

    // Метод для получения всех изображений, включая основное
    public function getAllImagesAttribute()
    {
        return $this->images()->orderBy('order')->get();
    }

    // Старый метод для одного изображения (можно удалить после перехода на множественные)
    public function getImgAttribute($value)
    {
        return $value;
    }

    // Определяем аксессор для статуса "В наличии"
    public function getInStockAttribute(): bool
    {
        return $this->qty > 0;
    }

    // Определяем аксессор для статуса "На скидке"
    public function getOnSaleAttribute(): bool
    {
        // Товар на скидке, если есть старая цена и она больше текущей
        return ($this->old_price ?? 0) > $this->price;
    }

    // Определяем аксессор для вычисления процента скидки
    public function getDiscountPercentAttribute(): float
    {
        if (($this->old_price ?? 0) > $this->price && $this->old_price > 0) {
            return (($this->old_price - $this->price) / $this->old_price) * 100;
        }
        return 0.0;
    }

    // Если необходимо, добавьте методы и связи
}
