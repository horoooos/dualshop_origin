<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Seeder;

class CategorySeeder extends Seeder
{
    /**
     * Запуск сидера.
     */
    public function run(): void
    {
        Category::query()->delete();

        // 1. Процессоры
        $processors = Category::create(['name' => 'Процессоры', 'slug' => 'processors', 'product_type' => 'processor']);
        $intel = Category::create(['name' => 'Intel', 'slug' => 'intel', 'parent_id' => $processors->id, 'product_type' => 'processor']);
        $amd = Category::create(['name' => 'AMD', 'slug' => 'amd', 'parent_id' => $processors->id, 'product_type' => 'processor']);

        // 2. Видеокарты
        $gpus = Category::create(['name' => 'Видеокарты', 'slug' => 'gpus', 'product_type' => 'gpu']);
        $nvidia = Category::create(['name' => 'NVIDIA', 'slug' => 'nvidia', 'parent_id' => $gpus->id, 'product_type' => 'gpu']);
        $amd_gpu = Category::create(['name' => 'AMD Radeon', 'slug' => 'amd-gpu', 'parent_id' => $gpus->id, 'product_type' => 'gpu']);

        // 3. Материнские платы
        $motherboards = Category::create(['name' => 'Материнские платы', 'slug' => 'motherboards', 'product_type' => 'motherboard']);
        $intel_mb = Category::create(['name' => 'Для Intel', 'slug' => 'intel-mb', 'parent_id' => $motherboards->id, 'product_type' => 'motherboard']);
        $amd_mb = Category::create(['name' => 'Для AMD', 'slug' => 'amd-mb', 'parent_id' => $motherboards->id, 'product_type' => 'motherboard']);

        // 4. Оперативная память
        $ram = Category::create(['name' => 'Оперативная память', 'slug' => 'ram', 'product_type' => 'ram']);
        $ddr4 = Category::create(['name' => 'DDR4', 'slug' => 'ddr4', 'parent_id' => $ram->id, 'product_type' => 'ram']);
        $ddr5 = Category::create(['name' => 'DDR5', 'slug' => 'ddr5', 'parent_id' => $ram->id, 'product_type' => 'ram']);

        // 5. Накопители
        $storage = Category::create(['name' => 'Накопители', 'slug' => 'storage', 'product_type' => 'storage']);
        $ssd = Category::create(['name' => 'SSD', 'slug' => 'ssd', 'parent_id' => $storage->id, 'product_type' => 'storage']);
        $hdd = Category::create(['name' => 'HDD', 'slug' => 'hdd', 'parent_id' => $storage->id, 'product_type' => 'storage']);

        // 6. Блоки питания
        $psu = Category::create(['name' => 'Блоки питания', 'slug' => 'psu', 'product_type' => 'psu']);
        $atx = Category::create(['name' => 'ATX', 'slug' => 'atx', 'parent_id' => $psu->id, 'product_type' => 'psu']);
        $sfx = Category::create(['name' => 'SFX', 'slug' => 'sfx', 'parent_id' => $psu->id, 'product_type' => 'psu']);

        // 7. Корпуса
        $cases = Category::create(['name' => 'Корпуса', 'slug' => 'cases', 'product_type' => 'case']);
        $atx_case = Category::create(['name' => 'ATX', 'slug' => 'atx-case', 'parent_id' => $cases->id, 'product_type' => 'case']);
        $mini_itx = Category::create(['name' => 'Mini-ITX', 'slug' => 'mini-itx', 'parent_id' => $cases->id, 'product_type' => 'case']);

        // 8. Системы охлаждения
        $cooling = Category::create(['name' => 'Системы охлаждения', 'slug' => 'cooling', 'product_type' => 'cooling']);
        $air = Category::create(['name' => 'Воздушное охлаждение', 'slug' => 'air-cooling', 'parent_id' => $cooling->id, 'product_type' => 'cooling']);
        $liquid = Category::create(['name' => 'Жидкостное охлаждение', 'slug' => 'liquid-cooling', 'parent_id' => $cooling->id, 'product_type' => 'cooling']);
    }
}
