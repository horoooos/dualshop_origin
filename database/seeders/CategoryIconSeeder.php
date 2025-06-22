<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Category;
use Illuminate\Support\Facades\DB;

class CategoryIconSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Обновляем иконки для всех категорий
        $categories = [
            'Процессоры' => 'bi bi-cpu',
            'Материнские платы' => 'bi bi-motherboard',
            'Видеокарты' => 'bi bi-gpu-card',
            'Оперативная память' => 'bi bi-memory',
            'Накопители' => 'bi bi-hdd',
            'Блоки питания' => 'bi bi-plug',
            'Корпуса' => 'bi bi-pc',
            'Системы охлаждения' => 'bi bi-snow'
        ];
        
        foreach ($categories as $name => $icon) {
            DB::table('categories')
                ->where('name', 'like', "%$name%")
                ->update(['icon' => $icon]);
        }
    }
}
