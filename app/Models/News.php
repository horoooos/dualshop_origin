<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class News extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'short_description',
        'content',
        'image',
        'views',
        'is_published',
        'published_at'
    ];

    protected $casts = [
        'published_at' => 'datetime',
        'is_published' => 'boolean'
    ];

    // Увеличение счетчика просмотров
    public function incrementViews()
    {
        $this->increment('views');
    }

    // Получение только опубликованных новостей
    public function scopePublished($query)
    {
        return $query->where('is_published', true);
    }

    // Сортировка по дате публикации (новые сначала)
    public function scopeNewest($query)
    {
        return $query->orderBy('published_at', 'desc');
    }

    // Сортировка по дате публикации (старые сначала)
    public function scopeOldest($query)
    {
        return $query->orderBy('published_at', 'asc');
    }
}
