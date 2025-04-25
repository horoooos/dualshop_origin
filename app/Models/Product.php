<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Product extends Model
{
    use HasFactory;

    // Указываем, какие поля могут быть массово присвоены
    protected $fillable = [
        'title', 'price', 'img', 'product_type', 'country', 'color', 'qty', 'description', 'discount'
    ];

    protected $casts = [
        'price' => 'float',
        'discount' => 'integer'
    ];

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class, 'product_type', 'id');
    }

    // Если необходимо, добавьте методы и связи
}
