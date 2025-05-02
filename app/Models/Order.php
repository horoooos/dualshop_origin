<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'product_id',
        'quantity',
        'number',
        'status',
    ];

    /**
     * Получить пользователя, сделавшего заказ
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Получить товар в заказе
     */
    public function product()
    {
        return $this->belongsTo(Product::class);
    }
} 