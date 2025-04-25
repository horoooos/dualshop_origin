<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Category extends Model
{
    protected $fillable = [
        'name',
        'description',
        'is_popular',
        'is_seasonal',
        'img',
        'product_type',
        'parent_id'
    ];

    protected $casts = [
        'is_popular' => 'boolean',
        'is_seasonal' => 'boolean'
    ];

    public function products(): HasMany
    {
        return $this->hasMany(Product::class, 'product_type', 'id');
    }
} 