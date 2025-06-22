<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Product;
use App\Models\Category;
use Illuminate\Support\Facades\DB;
use App\Models\ProductSpecification;
use App\Models\User;
use App\Models\ProductImage; // Импортируем новую модель

class TestProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Отключаем проверки внешних ключей
        \DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        \DB::table('product_specifications')->truncate();
        \DB::table('products')->truncate();
        \DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        echo "Старые данные о товарах и спецификациях удалены\n"; // Обновлено сообщение
        
        // Получаем все категории
        $processorCategory = Category::where('name', 'Процессоры')->first();
        $videoCardCategory = Category::where('name', 'Видеокарты')->first();
        $motherboardCategory = Category::where('name', 'Материнские платы')->first();
        $powerSupplyCategory = Category::where('name', 'Блоки питания')->first();
        $caseCategory = Category::where('name', 'Корпуса')->first();
        $storageCategory = Category::where('name', 'Накопители')->first();
        $ramCategory = Category::where('name', 'Оперативная память')->first();
        $coolerCategory = Category::where('name', 'Системы охлаждения')->first();

        // Проверка наличия категорий
        $categories = [$processorCategory, $videoCardCategory, $motherboardCategory,
                       $powerSupplyCategory, $caseCategory, $storageCategory, $ramCategory, $coolerCategory];

        foreach ($categories as $category) {
            if (!$category) {
                // Если какая-то категория не найдена, выводим сообщение об ошибке и прекращаем выполнение
                echo "Не найдены все необходимые категории! Убедитесь, что категории созданы.\n";
                return;
            }
        }
        
        // Удаляем все старые материнские платы
        Product::where('product_type', 'motherboard')->delete();

        // Создаем несколько тестовых товаров для категории процессоров
        if($processorCategory) {
            $product = Product::create([
                'title' => 'Intel Core i7-13700K',
                'price' => 36990,
                'old_price' => 39990,
                'discount_percent' => 7.5,
                'in_stock' => true,
                'on_sale' => true,
                'is_bestseller' => true,
                'credit_available' => true,
                'img' => 'Intel Core i7-13700K OEM.webp', // Добавлен путь к изображению
                'product_type' => 'processor',
                'country' => 'Малайзия',
                'color' => 'Серебристый',
                'qty' => 15,
                'description' => 'Процессор Intel Core i7-13700K, LGA 1700, 16 ядер (8P+8E), частота 3.4 ГГц (до 5.4 ГГц), кэш 30 МБ, TDP 125 Вт',
                'category_id' => $processorCategory->id,
                'rating' => 4.8,
            ]);

            $product->images()->createMany([
                ['image_path' => 'Intel Core i7-13700K OEM.webp'],
                ['image_path' => 'Intel Core i7-13700K OEM1.webp'], // Assuming this file exists
                
            ]);
            
            // Добавляем спецификации для процессора Intel Core i7-13700K
            ProductSpecification::create(['product_id'=>$product->id,'spec_key'=>'brand','spec_name'=>'Бренд','spec_value'=>'Intel','group'=>'Основные','is_filterable'=>true]);
            ProductSpecification::create(['product_id'=>$product->id,'spec_key'=>'socket','spec_name'=>'Сокет','spec_value'=>'LGA 1700','group'=>'Основные','is_filterable'=>true]);
            ProductSpecification::create(['product_id'=>$product->id,'spec_key'=>'series','spec_name'=>'Серия','spec_value'=>'Core i7','group'=>'Основные','is_filterable'=>true]);
            ProductSpecification::create(['product_id'=>$product->id,'spec_key'=>'cores','spec_name'=>'Количество ядер','spec_value'=>'16 (8P+8E)','group'=>'Характеристики','is_filterable'=>true]);
            ProductSpecification::create(['product_id'=>$product->id,'spec_key'=>'threads','spec_name'=>'Потоков','spec_value'=>'24','group'=>'Характеристики','is_filterable'=>false]);
            ProductSpecification::create(['product_id'=>$product->id,'spec_key'=>'base_freq','spec_name'=>'Базовая частота','spec_value'=>'3.4 ГГц','group'=>'Характеристики','is_filterable'=>false]);
            ProductSpecification::create(['product_id'=>$product->id,'spec_key'=>'max_freq','spec_name'=>'Максимальная частота','spec_value'=>'5.4 ГГц','group'=>'Характеристики','is_filterable'=>false]);
            ProductSpecification::create(['product_id'=>$product->id,'spec_key'=>'cache','spec_name'=>'Кэш','spec_value'=>'30 МБ','group'=>'Характеристики','is_filterable'=>false]);
            ProductSpecification::create(['product_id'=>$product->id,'spec_key'=>'tdp','spec_name'=>'TDP','spec_value'=>'125 Вт','group'=>'Энергопотребление','is_filterable'=>false]);
            ProductSpecification::create(['product_id'=>$product->id,'spec_key'=>'unlocked','spec_name'=>'Разблокированный множитель','spec_value'=>'Да','group'=>'Особенности','is_filterable'=>true]);
            // Add color specification
            ProductSpecification::create(['product_id'=>$product->id,'spec_key'=>'color','spec_name'=>'Цвет','spec_value'=>'Серебристый','group'=>'Внешний вид','is_filterable'=>false]);
            
            $product = Product::create([
                'title' => 'AMD Ryzen 9 7900X',
                'price' => 42990,
                'old_price' => 45990,
                'discount_percent' => 6.5,
                'in_stock' => true,
                'on_sale' => false,
                'is_new' => true,
                'credit_available' => true,
                'img' => 'AMD Ryzen 9 7900X OEM.webp', // Добавлен путь к изображению
                'product_type' => 'processor',
                'country' => 'Тайвань',
                'color' => 'Черный',
                'qty' => 10,
                'description' => 'Процессор AMD Ryzen 9 7900X, Socket AM5, 12 ядер, частота 4.7 ГГц (до 5.6 ГГц), кэш 76 МБ, TDP 170 Вт',
                'category_id' => $processorCategory->id,
                'rating' => 4.9,
            ]);

            
            $product->images()->createMany([
                ['image_path' => 'AMD Ryzen 9 7900X OEM.webp'],
                ['image_path' => 'AMD Ryzen 9 7900X OEM1.webp'], // Assuming this file exists
                
            ]);
            
            // Добавляем спецификации для процессора AMD
            ProductSpecification::create(['product_id'=>$product->id,'spec_key'=>'brand','spec_name'=>'Бренд','spec_value'=>'AMD','group'=>'Основные','is_filterable'=>true]);
            ProductSpecification::create(['product_id'=>$product->id,'spec_key'=>'socket','spec_name'=>'Сокет','spec_value'=>'AM5','group'=>'Основные','is_filterable'=>true]);
            ProductSpecification::create(['product_id'=>$product->id,'spec_key'=>'series','spec_name'=>'Серия','spec_value'=>'Ryzen 9','group'=>'Основные','is_filterable'=>true]);
            ProductSpecification::create(['product_id'=>$product->id,'spec_key'=>'cores','spec_name'=>'Количество ядер','spec_value'=>'12','group'=>'Характеристики','is_filterable'=>true]);
            ProductSpecification::create(['product_id'=>$product->id,'spec_key'=>'threads','spec_name'=>'Потоков','spec_value'=>'24','group'=>'Характеристики','is_filterable'=>false]);
            ProductSpecification::create(['product_id'=>$product->id,'spec_key'=>'base_freq','spec_name'=>'Базовая частота','spec_value'=>'4.7 ГГц','group'=>'Характеристики','is_filterable'=>false]);
            ProductSpecification::create(['product_id'=>$product->id,'spec_key'=>'max_freq','spec_name'=>'Максимальная частота','spec_value'=>'5.6 ГГц','group'=>'Характеристики','is_filterable'=>false]);
            ProductSpecification::create(['product_id'=>$product->id,'spec_key'=>'cache','spec_name'=>'Кэш','spec_value'=>'76 МБ','group'=>'Характеристики','is_filterable'=>false]);
            ProductSpecification::create(['product_id'=>$product->id,'spec_key'=>'tdp','spec_name'=>'TDP','spec_value'=>'170 Вт','group'=>'Энергопотребление','is_filterable'=>false]);
            ProductSpecification::create(['product_id'=>$product->id,'spec_key'=>'unlocked','spec_name'=>'Разблокированный множитель','spec_value'=>'Да','group'=>'Особенности','is_filterable'=>true]);
            
            $product = Product::create([
                'title' => 'Intel Core i5-13600K',
                'price' => 28990,
                'old_price' => 31990,
                'discount_percent' => 9.3,
                'in_stock' => true,
                'on_sale' => true,
                'is_bestseller' => false,
                'credit_available' => true,
                'img' => 'Intel Core i5-13600K OEM.webp', // Добавлен путь к изображению
                'product_type' => 'processor',
                'country' => 'Малайзия',
                'color' => 'Серый',
                'qty' => 18,
                'description' => 'Процессор Intel Core i5-13600K, LGA 1700, 14 ядер (6P+8E), частота 3.5 ГГц (до 5.1 ГГц), кэш 24 МБ, TDP 125 Вт',
                'category_id' => $processorCategory->id,
                'rating' => 4.7,
            ]);

            $product->images()->createMany([
                ['image_path' => 'Intel Core i5-13600K OEM.webp'],
                ['image_path' => 'Intel Core i5-13600K OEM1.webp'], // Assuming this file exists
                
            ]);
            
            // Добавляем спецификации для процессора Intel i5
            ProductSpecification::create(['product_id'=>$product->id,'spec_key'=>'brand','spec_name'=>'Бренд','spec_value'=>'Intel','group'=>'Основные','is_filterable'=>true]);
            ProductSpecification::create(['product_id'=>$product->id,'spec_key'=>'socket','spec_name'=>'Сокет','spec_value'=>'LGA 1700','group'=>'Основные','is_filterable'=>true]);
            ProductSpecification::create(['product_id'=>$product->id,'spec_key'=>'series','spec_name'=>'Серия','spec_value'=>'Core i5','group'=>'Основные','is_filterable'=>true]);
            ProductSpecification::create(['product_id'=>$product->id,'spec_key'=>'cores','spec_name'=>'Количество ядер','spec_value'=>'14 (6P+8E)','group'=>'Характеристики','is_filterable'=>true]);
            ProductSpecification::create(['product_id'=>$product->id,'spec_key'=>'threads','spec_name'=>'Потоков','spec_value'=>'20','group'=>'Характеристики','is_filterable'=>false]);
            ProductSpecification::create(['product_id'=>$product->id,'spec_key'=>'base_freq','spec_name'=>'Базовая частота','spec_value'=>'3.5 ГГц','group'=>'Характеристики','is_filterable'=>false]);
            ProductSpecification::create(['product_id'=>$product->id,'spec_key'=>'max_freq','spec_name'=>'Максимальная частота','spec_value'=>'5.1 ГГц','group'=>'Характеристики','is_filterable'=>false]);
            ProductSpecification::create(['product_id'=>$product->id,'spec_key'=>'cache','spec_name'=>'Кэш','spec_value'=>'24 МБ','group'=>'Характеристики','is_filterable'=>false]);
            ProductSpecification::create(['product_id'=>$product->id,'spec_key'=>'tdp','spec_name'=>'TDP','spec_value'=>'125 Вт','group'=>'Энергопотребление','is_filterable'=>false]);
            ProductSpecification::create(['product_id'=>$product->id,'spec_key'=>'unlocked','spec_name'=>'Разблокированный множитель','spec_value'=>'Да','group'=>'Особенности','is_filterable'=>true]);
            
            $product = Product::create([
                'title' => 'AMD Ryzen 7 7700X',
                'price' => 32990,
                'old_price' => 35990,
                'discount_percent' => 8.3,
                'in_stock' => true,
                'on_sale' => true,
                'is_bestseller' => true,
                'credit_available' => true,
                'img' => 'Процессор AMD Ryzen 7 7700X OEM.webp', // Добавлен путь к изображению
                'product_type' => 'processor',
                'country' => 'Китай',
                'color' => 'Черный',
                'qty' => 20,
                'description' => 'Процессор AMD Ryzen 7 7700X, Socket AM5, 8 ядер, 16 потоков, частота 4.5-5.4 ГГц, кэш 40 МБ, TDP 170 Вт',
                'category_id' => $processorCategory->id,
                'rating' => 4.8,
            ]);

            $product->images()->createMany([
                ['image_path' => 'Процессор AMD Ryzen 7 7700X OEM.webp'],
                ['image_path' => 'Процессор AMD Ryzen 7 7700X OEM1.webp'], // Assuming this file exists
                
            ]);
            
            // Добавляем спецификации для процессора AMD Ryzen 7
            ProductSpecification::create(['product_id'=>$product->id,'spec_key'=>'brand','spec_name'=>'Бренд','spec_value'=>'AMD','group'=>'Основные','is_filterable'=>true]);
            ProductSpecification::create(['product_id'=>$product->id,'spec_key'=>'socket','spec_name'=>'Сокет','spec_value'=>'AM5','group'=>'Основные','is_filterable'=>true]);
            ProductSpecification::create(['product_id'=>$product->id,'spec_key'=>'series','spec_name'=>'Серия','spec_value'=>'Ryzen 7','group'=>'Основные','is_filterable'=>true]);
            ProductSpecification::create(['product_id'=>$product->id,'spec_key'=>'cores','spec_name'=>'Количество ядер','spec_value'=>'8','group'=>'Характеристики','is_filterable'=>true]);
            ProductSpecification::create(['product_id'=>$product->id,'spec_key'=>'threads','spec_name'=>'Потоков','spec_value'=>'16','group'=>'Характеристики','is_filterable'=>false]);
            ProductSpecification::create(['product_id'=>$product->id,'spec_key'=>'base_freq','spec_name'=>'Базовая частота','spec_value'=>'4.5 ГГц','group'=>'Характеристики','is_filterable'=>false]);
            ProductSpecification::create(['product_id'=>$product->id,'spec_key'=>'max_freq','spec_name'=>'Максимальная частота','spec_value'=>'5.6 ГГц','group'=>'Характеристики','is_filterable'=>false]);
            ProductSpecification::create(['product_id'=>$product->id,'spec_key'=>'cache','spec_name'=>'Кэш','spec_value'=>'40 МБ','group'=>'Характеристики','is_filterable'=>false]);
            ProductSpecification::create(['product_id'=>$product->id,'spec_key'=>'tdp','spec_name'=>'TDP','spec_value'=>'170 Вт','group'=>'Энергопотребление','is_filterable'=>false]);
            ProductSpecification::create(['product_id'=>$product->id,'spec_key'=>'unlocked','spec_name'=>'Разблокированный множитель','spec_value'=>'Да','group'=>'Особенности','is_filterable'=>true]);
            
            $product = Product::create([
                'title' => 'AMD Radeon RX 7900 XTX',
                'price' => 92990,
                'old_price' => 104990,
                'discount_percent' => 11,
                'in_stock' => true,
                'on_sale' => true,
                'is_new' => true,
                'credit_available' => true,
                'img' => null,
                'product_type' => 'video_card',
                'country' => 'Тайвань',
                'color' => 'Черный',
                'qty' => 5,
                'description' => 'Видеокарта AMD Radeon RX 7900 XTX, 24 ГБ GDDR6, 384 бит, PCI-E 4.0, DisplayPort x2, HDMI x2, Ray Tracing, FSR 3.0',
                'category_id' => $videoCardCategory->id,
                'rating' => 4.7
            ]);
            
            // Добавляем спецификации для видеокарты AMD RX 7900 XTX
            ProductSpecification::create(['product_id'=>$product->id,'spec_key'=>'brand','spec_name'=>'Бренд','spec_value'=>'AMD','group'=>'Основные','is_filterable'=>true]);
            ProductSpecification::create(['product_id'=>$product->id,'spec_key'=>'series','spec_name'=>'Серия','spec_value'=>'RX 7900 XTX','group'=>'Основные','is_filterable'=>true]);
            ProductSpecification::create(['product_id'=>$product->id,'spec_key'=>'memory','spec_name'=>'Объем памяти','spec_value'=>'24 ГБ','group'=>'Память','is_filterable'=>true]);
            ProductSpecification::create(['product_id'=>$product->id,'spec_key'=>'memory_type','spec_name'=>'Тип памяти','spec_value'=>'GDDR6','group'=>'Память','is_filterable'=>true]);
            ProductSpecification::create(['product_id'=>$product->id,'spec_key'=>'bus_width','spec_name'=>'Разрядность шины памяти','spec_value'=>'384 бит','group'=>'Память','is_filterable'=>false]);
            ProductSpecification::create(['product_id'=>$product->id,'spec_key'=>'ray_tracing','spec_name'=>'Ray Tracing','spec_value'=>'Да','group'=>'Технологии','is_filterable'=>true]);
            ProductSpecification::create(['product_id'=>$product->id,'spec_key'=>'fsr','spec_name'=>'FSR','spec_value'=>'FSR 3.0','group'=>'Технологии','is_filterable'=>true]);
            ProductSpecification::create(['product_id'=>$product->id,'spec_key'=>'ports','spec_name'=>'Порты','spec_value'=>'DisplayPort x2, HDMI x2','group'=>'Интерфейсы','is_filterable'=>false]);
            ProductSpecification::create(['product_id'=>$product->id,'spec_key'=>'tdp','spec_name'=>'TDP','spec_value'=>'355 Вт','group'=>'Энергопотребление','is_filterable'=>false]);
            
            $product = Product::create([
                'title' => 'NVIDIA GeForce RTX 4060 Ti',
                'price' => 45990,
                'old_price' => 49990,
                'discount_percent' => 8,
                'in_stock' => true,
                'on_sale' => false,
                'is_new' => true,
                'credit_available' => true,
                'img' => null,
                'product_type' => 'video_card',
                'country' => 'Китай',
                'color' => 'Черный',
                'qty' => 15,
                'description' => 'Видеокарта NVIDIA GeForce RTX 4060 Ti, 8 ГБ GDDR6, 128 бит, PCI-E 4.0, DisplayPort x3, HDMI x1, Ray Tracing, DLSS 3',
                'category_id' => $videoCardCategory->id,
                'rating' => 4.6
            ]);

            // Удаляем все старые видеокарты
            Product::where('product_type', 'video_card')->delete();

            if($videoCardCategory) {

                // 1 - Palit NVIDIA GeForce RTX 5080 PA-RTX5080 GAMEROCK OC 16ГБ GameRock
                $product = Product::create([
                    'title' => 'Palit NVIDIA GeForce RTX 5080 PA-RTX5080 GAMEROCK OC',
                    'price' => 140000,
                    'old_price' => null,
                    'discount_percent' => 0,
                    'in_stock' => true,
                    'on_sale' => false,
                    'is_bestseller' => false,
                    'credit_available' => true,
                    'img' => 'Palit NVIDIA GeForce RTX 5080 PA-RTX5080 GAMEROCK OC.webp',
                    'product_type' => 'video_card',
                    'country' => 'Китай',
                    'color' => 'черный',
                    'qty' => 10,
                    'description' => 'Серия Palit GameRock сочетает потрясающую эстетику и высокую производительность. Panel Chameleon на кожухе видеокарты предлагает динамичные, постоянно меняющиеся цвета с поддержкой ARGB Sync Evo для синхронизации подсветки без использования дополнительного программного обеспечения. Усовершенствованные технологии охлаждения, такие как TurboFan 4.0, воздушный дефлектор, композитные тепловые трубки и испарительная камера, обеспечивают оптимальные тепловые характеристики. Разработанная, чтобы вызывать лучшие впечатления, серия GameRock идеально подходит для геймеров, создателей контента и энтузиастов искусственного интеллекта.',
                    'category_id' => $videoCardCategory->id,
                    'rating' => 4.8
                ]);
                $product->images()->createMany([
                    ['image_path' => 'Palit NVIDIA GeForce RTX 5080 PA-RTX5080 GAMEROCK OC.webp'],
                    ['image_path' => 'Palit NVIDIA GeForce RTX 5080 PA-RTX5080 GAMEROCK OC1.webp'], // Assuming this file exists
                    ['image_path' => 'Palit NVIDIA GeForce RTX 5080 PA-RTX5080 GAMEROCK OC2.webp'], // Assuming this file exists
                ]);
                ProductSpecification::create(['product_id'=>$product->id,'spec_key'=>'brand','spec_name'=>'Бренд','spec_value'=>'Palit','group'=>'Общие параметры','is_filterable'=>true]);
                ProductSpecification::create(['product_id'=>$product->id,'spec_key'=>'model','spec_name'=>'Модель','spec_value'=>'GeForce RTX 5080 GameRock OC','group'=>'Общие параметры','is_filterable'=>true]);
                ProductSpecification::create(['product_id'=>$product->id,'spec_key'=>'gpu','spec_name'=>'Графический процессор','spec_value'=>'GeForce RTX 5080','group'=>'Основные параметры','is_filterable'=>true]);
                ProductSpecification::create(['product_id'=>$product->id,'spec_key'=>'architecture','spec_name'=>'Микроархитектура','spec_value'=>'NVIDIA Blackwell','group'=>'Основные параметры','is_filterable'=>false]);
                ProductSpecification::create(['product_id'=>$product->id,'spec_key'=>'process','spec_name'=>'Техпроцесс','spec_value'=>'5 нм','group'=>'Основные параметры','is_filterable'=>false]);
                ProductSpecification::create(['product_id'=>$product->id,'spec_key'=>'memory','spec_name'=>'Объем видеопамяти','spec_value'=>'16 ГБ','group'=>'Спецификации видеопамяти','is_filterable'=>true]);
                ProductSpecification::create(['product_id'=>$product->id,'spec_key'=>'memory_type','spec_name'=>'Тип памяти','spec_value'=>'GDDR7','group'=>'Спецификации видеопамяти','is_filterable'=>true]);
                ProductSpecification::create(['product_id'=>$product->id,'spec_key'=>'bus_width','spec_name'=>'Разрядность шины памяти','spec_value'=>'256 бит','group'=>'Спецификации видеопамяти','is_filterable'=>false]);
                ProductSpecification::create(['product_id'=>$product->id,'spec_key'=>'bandwidth','spec_name'=>'Максимальная пропускная способность памяти','spec_value'=>'960 Гбайт/сек','group'=>'Спецификации видеопамяти','is_filterable'=>false]);
                ProductSpecification::create(['product_id'=>$product->id,'spec_key'=>'cooling','spec_name'=>'Тип охлаждения','spec_value'=>'активное воздушное','group'=>'Система охлаждения','is_filterable'=>true]);
                ProductSpecification::create(['product_id'=>$product->id,'spec_key'=>'fan_count','spec_name'=>'Количество вентиляторов','spec_value'=>'3 осевых','group'=>'Система охлаждения','is_filterable'=>false]);
                ProductSpecification::create(['product_id'=>$product->id,'spec_key'=>'power','spec_name'=>'Рекомендуемый блок питания','spec_value'=>'850 Вт','group'=>'Подключение','is_filterable'=>false]);
                ProductSpecification::create(['product_id'=>$product->id,'spec_key'=>'power_consumption','spec_name'=>'Потребляемая мощность','spec_value'=>'360 Вт','group'=>'Подключение','is_filterable'=>false]);
                ProductSpecification::create(['product_id'=>$product->id,'spec_key'=>'warranty','spec_name'=>'Гарантия','spec_value'=>'36 мес.','group'=>'Заводские данные','is_filterable'=>false]);
                ProductSpecification::create(['product_id'=>$product->id,'spec_key'=>'country','spec_name'=>'Страна-производитель','spec_value'=>'Китай','group'=>'Заводские данные','is_filterable'=>false]);
                ProductSpecification::create(['product_id'=>$product->id,'spec_key'=>'color','spec_name'=>'Цвет','spec_value'=>'черный','group'=>'Общие параметры','is_filterable'=>true]);

                // 2 - AFOX GeForce GT 710 [AF710-2048D3L5]
                $product = Product::create([
                    'title' => 'AFOX GeForce GT 710 [AF710-2048D3L5]',
                    'price' => 3500,
                    'old_price' => null,
                    'discount_percent' => 0,
                    'in_stock' => true,
                    'on_sale' => false,
                    'is_bestseller' => false,
                    'credit_available' => true,
                    'img' => 'AFOX GeForce GT 710 [AF710-2048D3L5].webp',
                    'product_type' => 'video_card',
                    'country' => 'Китай',
                    'color' => 'серый',
                    'qty' => 10,
                    'description' => 'Видеокарта AFOX GeForce GT 710 [AF710-2048D3L5] обеспечивает продуктивность запуска требовательных программ и мультимедийной графики. Она основана на архитектуре NVIDIA Kepler и оснащена процессором с тактовой частотой 954 МГц. Благодаря длине 146 мм и низкопрофильной конструкции видеокарта подходит для установки в системный блок с ограниченным пространством. Технологии Adaptive V-Sync и PhysX обеспечивают реалистичную графику и отсутствие задержек динамичных кадров. На боковой панели расположены по 1 разъему DVI-D, HDMI и VGA, которые позволяют подключать до 3 мониторов для вывода изображения. Система охлаждения с ребристым радиатором и 1 осевым вентилятором отводит тепло от видеокарты AFOX GeForce GT 710 [AF710-2048D3L5] при разной вычислительной нагрузке.',
                    'category_id' => $videoCardCategory->id,
                    'rating' => 4.5
                ]);
                $product->images()->createMany([
                    ['image_path' => 'AFOX GeForce GT 710 [AF710-2048D3L5].webp'],
                    ['image_path' => 'AFOX GeForce GT 710 [AF710-2048D3L5]1.webp'], // Assuming this file exists
                    ['image_path' => 'AFOX GeForce GT 710 [AF710-2048D3L5]2.webp'], // Assuming this file exists
                ]);
                ProductSpecification::create(['product_id'=>$product->id,'spec_key'=>'brand','spec_name'=>'Бренд','spec_value'=>'AFOX','group'=>'Общие параметры','is_filterable'=>true]);
                ProductSpecification::create(['product_id'=>$product->id,'spec_key'=>'model','spec_name'=>'Модель','spec_value'=>'GeForce GT 710','group'=>'Общие параметры','is_filterable'=>true]);
                ProductSpecification::create(['product_id'=>$product->id,'spec_key'=>'gpu','spec_name'=>'Графический процессор','spec_value'=>'GeForce GT 710','group'=>'Основные параметры','is_filterable'=>true]);
                ProductSpecification::create(['product_id'=>$product->id,'spec_key'=>'architecture','spec_name'=>'Микроархитектура','spec_value'=>'NVIDIA Kepler','group'=>'Основные параметры','is_filterable'=>false]);
                ProductSpecification::create(['product_id'=>$product->id,'spec_key'=>'memory_type','spec_name'=>'Тип памяти','spec_value'=>'DDR3','group'=>'Спецификации видеопамяти','is_filterable'=>true]);
                ProductSpecification::create(['product_id'=>$product->id,'spec_key'=>'cooling','spec_name'=>'Тип охлаждения','spec_value'=>'пассивное','group'=>'Система охлаждения','is_filterable'=>true]);
                ProductSpecification::create(['product_id'=>$product->id,'spec_key'=>'fan_count','spec_name'=>'Количество вентиляторов','spec_value'=>'нет','group'=>'Система охлаждения','is_filterable'=>false]);
                ProductSpecification::create(['product_id'=>$product->id,'spec_key'=>'warranty','spec_name'=>'Гарантия','spec_value'=>'12 мес.','group'=>'Заводские данные','is_filterable'=>false]);
                ProductSpecification::create(['product_id'=>$product->id,'spec_key'=>'country','spec_name'=>'Страна-производитель','spec_value'=>'Китай','group'=>'Заводские данные','is_filterable'=>false]);
                ProductSpecification::create(['product_id'=>$product->id,'spec_key'=>'color','spec_name'=>'Цвет','spec_value'=>'серый','group'=>'Общие параметры','is_filterable'=>true]);

                // 3 - Palit GeForce RTX 4060 Infinity 2 [NE64060019P1-1070L]
                $product = Product::create([
                    'title' => 'Palit GeForce RTX 4060 Infinity 2 [NE64060019P1-1070L]',
                    'price' => 31000,
                    'old_price' => null,
                    'discount_percent' => 0,
                    'in_stock' => true,
                    'on_sale' => false,
                    'is_bestseller' => false,
                    'credit_available' => true,
                    'img' => 'Palit GeForce RTX 4060 Infinity 2.webp',
                    'product_type' => 'video_card',
                    'country' => 'Китай',
                    'color' => 'черный',
                    'qty' => 10,
                    'description' => 'Видеокарта Palit GeForce RTX 4060 Infinity 2 [NE64060019P1-1070L] ориентирована на сборку и модернизацию игровых системных блоков среднего уровня. Устройство охлаждается парой 95-миллиметровых вентиляторов, которые при невысокой нагрузке останавливаются. Длина видеоадаптера составляет 250 мм. Показатель гарантирует модели широкую совместимость с корпусами разных форм-факторов. Видеокарта оснащена 4 видеоразъемами – HDMI и 3 DisplayPort. Допускается одновременное подключение до 4 мониторов. Поддерживается разрешение до 7680x4320 (8K Ultra HD). Основной элемент конструкции видеокарты Palit GeForce RTX 4060 Infinity 2 [NE64060019P1-1070L] – графический процессор GeForce RTX 4060 на основе микроархитектуры NVIDIA Ada Lovelace. Потенциал видеочипа реализует 8-гигабайтная память GDDR6 с пропускной способностью 272 ГБ/с и эффективной частотой 17000 МГц. 24-мегабайтный кэш L2 увеличивает скорость обмена данными между VRAM и GPU. В модели реализованы 96 тензорных ядер и 24 RT-ядра. Первые увеличивают быстродействие рендеринга, а вторые производят аппаратное ускорение трассировки лучей.',
                    'category_id' => $videoCardCategory->id,
                    'rating' => 4.7
                ]);
                $product->images()->createMany([
                    ['image_path' => 'Palit GeForce RTX 4060 Infinity 2.webp'],
                    ['image_path' => 'Palit GeForce RTX 4060 Infinity 21.webp'], // Assuming this file exists
                    ['image_path' => 'Palit GeForce RTX 4060 Infinity 22.webp'], // Assuming this file exists
                ]);
                ProductSpecification::create(['product_id'=>$product->id,'spec_key'=>'brand','spec_name'=>'Бренд','spec_value'=>'Palit','group'=>'Общие параметры','is_filterable'=>true]);
                ProductSpecification::create(['product_id'=>$product->id,'spec_key'=>'model','spec_name'=>'Модель','spec_value'=>'GeForce RTX 4060 Infinity 2','group'=>'Общие параметры','is_filterable'=>true]);
                ProductSpecification::create(['product_id'=>$product->id,'spec_key'=>'gpu','spec_name'=>'Графический процессор','spec_value'=>'GeForce RTX 4060','group'=>'Основные параметры','is_filterable'=>true]);
                ProductSpecification::create(['product_id'=>$product->id,'spec_key'=>'architecture','spec_name'=>'Микроархитектура','spec_value'=>'NVIDIA Ada Lovelace','group'=>'Основные параметры','is_filterable'=>false]);
                ProductSpecification::create(['product_id'=>$product->id,'spec_key'=>'process','spec_name'=>'Техпроцесс','spec_value'=>'5 нм','group'=>'Основные параметры','is_filterable'=>false]);
                ProductSpecification::create(['product_id'=>$product->id,'spec_key'=>'memory','spec_name'=>'Объем видеопамяти','spec_value'=>'8 ГБ','group'=>'Спецификации видеопамяти','is_filterable'=>true]);
                ProductSpecification::create(['product_id'=>$product->id,'spec_key'=>'memory_type','spec_name'=>'Тип памяти','spec_value'=>'GDDR6','group'=>'Спецификации видеопамяти','is_filterable'=>true]);
                ProductSpecification::create(['product_id'=>$product->id,'spec_key'=>'bus_width','spec_name'=>'Разрядность шины памяти','spec_value'=>'128 бит','group'=>'Спецификации видеопамяти','is_filterable'=>false]);
                ProductSpecification::create(['product_id'=>$product->id,'spec_key'=>'bandwidth','spec_name'=>'Максимальная пропускная способность памяти','spec_value'=>'272 Гбайт/сек','group'=>'Спецификации видеопамяти','is_filterable'=>false]);
                ProductSpecification::create(['product_id'=>$product->id,'spec_key'=>'cooling','spec_name'=>'Тип охлаждения','spec_value'=>'активное воздушное','group'=>'Система охлаждения','is_filterable'=>true]);
                ProductSpecification::create(['product_id'=>$product->id,'spec_key'=>'fan_count','spec_name'=>'Количество вентиляторов','spec_value'=>'2 осевых','group'=>'Система охлаждения','is_filterable'=>false]);
                ProductSpecification::create(['product_id'=>$product->id,'spec_key'=>'power','spec_name'=>'Рекомендуемый блок питания','spec_value'=>'600 Вт','group'=>'Подключение','is_filterable'=>false]);
                ProductSpecification::create(['product_id'=>$product->id,'spec_key'=>'warranty','spec_name'=>'Гарантия','spec_value'=>'36 мес.','group'=>'Заводские данные','is_filterable'=>false]);
                ProductSpecification::create(['product_id'=>$product->id,'spec_key'=>'country','spec_name'=>'Страна-производитель','spec_value'=>'Китай','group'=>'Заводские данные','is_filterable'=>false]);
                ProductSpecification::create(['product_id'=>$product->id,'spec_key'=>'color','spec_name'=>'Цвет','spec_value'=>'черный','group'=>'Общие параметры','is_filterable'=>true]);
                

                // 4 - KFA2 GeForce RTX 5080 CORE OC 3FAN RGB Black [58NZN6MDBBOK]
                $product = Product::create([
                    'title' => 'KFA2 GeForce RTX 5080 CORE OC 3FAN RGB Black [58NZN6MDBBOK]',
                    'price' => 130000,
                    'old_price' => null,
                    'discount_percent' => 0,
                    'in_stock' => true,
                    'on_sale' => false,
                    'is_bestseller' => false,
                    'credit_available' => true,
                    'img' => 'KFA2 GeForce RTX 5080 CORE OC 3FAN RGB Black.webp',
                    'product_type' => 'video_card',
                    'country' => 'Китай',
                    'color' => 'черный',
                    'qty' => 10,
                    'description' => 'Игровая видеокарта KFA2 GeForce RTX 5080 CORE OC 3FAN RGB Black с поддержкой трассировки лучей делает графику более реалистичной благодаря естественной прорисовке света и теней. Устройство с 16 ГБ памяти GDDR7 поддерживает видео в 8K-формате и одновременную работу с четырьмя мониторами. Видеочип со штатной частотой 2300 МГц ускоряет обработку изображения и сохраняет плавность видеоигр. Видеокарта с тремя кулерами для защиты от перегрева занимает 2.5 слота расширения.',
                    'category_id' => $videoCardCategory->id,
                    'rating' => 4.7
                ]);
                $product->images()->createMany([
                    ['image_path' => 'KFA2 GeForce RTX 5080 CORE OC 3FAN RGB Black.webp'],
                    ['image_path' => 'KFA2 GeForce RTX 5080 CORE OC 3FAN RGB Black1.webp'], // Assuming this file exists
                    ['image_path' => 'KFA2 GeForce RTX 5080 CORE OC 3FAN RGB Black2.webp'], // Assuming this file exists
                ]);
                ProductSpecification::create(['product_id'=>$product->id,'spec_key'=>'brand','spec_name'=>'Бренд','spec_value'=>'KFA2','group'=>'Общие параметры','is_filterable'=>true]);
                ProductSpecification::create(['product_id'=>$product->id,'spec_key'=>'model','spec_name'=>'Модель','spec_value'=>'GeForce RTX 5080 CORE OC 3FAN RGB Black','group'=>'Общие параметры','is_filterable'=>true]);
                ProductSpecification::create(['product_id'=>$product->id,'spec_key'=>'gpu','spec_name'=>'Графический процессор','spec_value'=>'GeForce RTX 5080','group'=>'Основные параметры','is_filterable'=>true]);
                ProductSpecification::create(['product_id'=>$product->id,'spec_key'=>'architecture','spec_name'=>'Микроархитектура','spec_value'=>'NVIDIA Blackwell','group'=>'Основные параметры','is_filterable'=>false]);
                ProductSpecification::create(['product_id'=>$product->id,'spec_key'=>'memory','spec_name'=>'Объем видеопамяти','spec_value'=>'16 ГБ','group'=>'Спецификации видеопамяти','is_filterable'=>true]);
                ProductSpecification::create(['product_id'=>$product->id,'spec_key'=>'memory_type','spec_name'=>'Тип памяти','spec_value'=>'GDDR7','group'=>'Спецификации видеопамяти','is_filterable'=>true]);
                ProductSpecification::create(['product_id'=>$product->id,'spec_key'=>'bus_width','spec_name'=>'Разрядность шины памяти','spec_value'=>'256 бит','group'=>'Спецификации видеопамяти','is_filterable'=>false]);
                ProductSpecification::create(['product_id'=>$product->id,'spec_key'=>'bandwidth','spec_name'=>'Максимальная пропускная способность памяти','spec_value'=>'960 Гбайт/сек','group'=>'Спецификации видеопамяти','is_filterable'=>false]);
                ProductSpecification::create(['product_id'=>$product->id,'spec_key'=>'cooling','spec_name'=>'Тип охлаждения','spec_value'=>'активное воздушное','group'=>'Система охлаждения','is_filterable'=>true]);
                ProductSpecification::create(['product_id'=>$product->id,'spec_key'=>'fan_count','spec_name'=>'Количество вентиляторов','spec_value'=>'3 осевых','group'=>'Система охлаждения','is_filterable'=>false]);
                ProductSpecification::create(['product_id'=>$product->id,'spec_key'=>'power','spec_name'=>'Рекомендуемый блок питания','spec_value'=>'850 Вт','group'=>'Подключение','is_filterable'=>false]);
                ProductSpecification::create(['product_id'=>$product->id,'spec_key'=>'power_consumption','spec_name'=>'Потребляемая мощность','spec_value'=>'400 Вт','group'=>'Подключение','is_filterable'=>false]);
                ProductSpecification::create(['product_id'=>$product->id,'spec_key'=>'warranty','spec_name'=>'Гарантия','spec_value'=>'36 мес.','group'=>'Заводские данные','is_filterable'=>false]);
                ProductSpecification::create(['product_id'=>$product->id,'spec_key'=>'country','spec_name'=>'Страна-производитель','spec_value'=>'Китай','group'=>'Заводские данные','is_filterable'=>false]);
                ProductSpecification::create(['product_id'=>$product->id,'spec_key'=>'color','spec_name'=>'Цвет','spec_value'=>'черный','group'=>'Общие параметры','is_filterable'=>true]);
                 // 5 - Palit GeForce RTX 3060 Dual (LHR) [NE63060019K9-190AD]
                 $product = Product::create([
                    'title' => 'Palit GeForce RTX 3060 Dual (LHR) [NE63060019K9-190AD]',
                    'price' => 25000,
                    'old_price' => null,
                    'discount_percent' => 0,
                    'in_stock' => true,
                    'on_sale' => false,
                    'is_bestseller' => false,
                    'credit_available' => true,
                    'img' => 'Palit GeForce RTX 3060 Dual (LHR).webp',
                    'product_type' => 'video_card',
                    'country' => 'Китай',
                    'color' => 'черный',
                    'qty' => 10,
                    'description' => 'Видеокарта Palit GeForce RTX 3060 DUAL (LHR) отличается высокой производительностью и станет отличным выбором для профессиональных рабочих станций и игровых систем. Благодаря технологиям Ampere, мощному видеопроцессору и 12 ГБ встроенной памяти данная модель позволяет с легкостью решать любые задачи – будь то работа с графикой, моделирование или запуск игр. Кроме того, графический ускоритель поддерживает трассировку лучей, что может обеспечить более реалистичное изображение в играх. Для отвода тепла предусмотрено 2 осевых вентилятора с особой формой лопастей, за счет которых рабочая температура не превышает 93°C. Кроме широких возможностей для работы и развлечений Palit GeForce RTX 3060 DUAL (LHR) также отличается лаконичным дизайном с яркой подсветкой, которая добавит красок компьютерной сборке.',
                    'category_id' => $videoCardCategory->id,
                    'rating' => 4.7
                ]);
                $product->images()->createMany([
                    ['image_path' => 'Palit GeForce RTX 3060 Dual (LHR).webp'],
                    ['image_path' => 'Palit GeForce RTX 3060 Dual (LHR)1.webp'], // Assuming this file exists
                    ['image_path' => 'Palit GeForce RTX 3060 Dual (LHR)2.webp'], // Assuming this file exists
                ]);
                ProductSpecification::create(['product_id'=>$product->id,'spec_key'=>'brand','spec_name'=>'Бренд','spec_value'=>'Palit','group'=>'Общие параметры','is_filterable'=>true]);
                ProductSpecification::create(['product_id'=>$product->id,'spec_key'=>'model','spec_name'=>'Модель','spec_value'=>'GeForce RTX 3060 Dual (LHR)','group'=>'Общие параметры','is_filterable'=>true]);
                ProductSpecification::create(['product_id'=>$product->id,'spec_key'=>'gpu','spec_name'=>'Графический процессор','spec_value'=>'GeForce RTX 3060','group'=>'Основные параметры','is_filterable'=>true]);
                ProductSpecification::create(['product_id'=>$product->id,'spec_key'=>'architecture','spec_name'=>'Микроархитектура','spec_value'=>'NVIDIA Ampere','group'=>'Основные параметры','is_filterable'=>false]);
                ProductSpecification::create(['product_id'=>$product->id,'spec_key'=>'process','spec_name'=>'Техпроцесс','spec_value'=>'8 нм','group'=>'Основные параметры','is_filterable'=>false]);
                ProductSpecification::create(['product_id'=>$product->id,'spec_key'=>'memory','spec_name'=>'Объем видеопамяти','spec_value'=>'12 ГБ','group'=>'Спецификации видеопамяти','is_filterable'=>true]);
                ProductSpecification::create(['product_id'=>$product->id,'spec_key'=>'memory_type','spec_name'=>'Тип памяти','spec_value'=>'GDDR6','group'=>'Спецификации видеопамяти','is_filterable'=>true]);
                ProductSpecification::create(['product_id'=>$product->id,'spec_key'=>'bus_width','spec_name'=>'Разрядность шины памяти','spec_value'=>'192 бит','group'=>'Спецификации видеопамяти','is_filterable'=>false]);
                ProductSpecification::create(['product_id'=>$product->id,'spec_key'=>'bandwidth','spec_name'=>'Максимальная пропускная способность памяти','spec_value'=>'360 Гбайт/сек','group'=>'Спецификации видеопамяти','is_filterable'=>false]);
                ProductSpecification::create(['product_id'=>$product->id,'spec_key'=>'cooling','spec_name'=>'Тип охлаждения','spec_value'=>'активное воздушное','group'=>'Система охлаждения','is_filterable'=>true]);
                ProductSpecification::create(['product_id'=>$product->id,'spec_key'=>'fan_count','spec_name'=>'Количество вентиляторов','spec_value'=>'2 осевых','group'=>'Система охлаждения','is_filterable'=>false]);
                ProductSpecification::create(['product_id'=>$product->id,'spec_key'=>'power','spec_name'=>'Рекомендуемый блок питания','spec_value'=>'550 Вт','group'=>'Подключение','is_filterable'=>false]);
                ProductSpecification::create(['product_id'=>$product->id,'spec_key'=>'power_consumption','spec_name'=>'Потребляемая мощность','spec_value'=>'170 Вт','group'=>'Подключение','is_filterable'=>false]);
                ProductSpecification::create(['product_id'=>$product->id,'spec_key'=>'warranty','spec_name'=>'Гарантия','spec_value'=>'36 мес.','group'=>'Заводские данные','is_filterable'=>false]);
                ProductSpecification::create(['product_id'=>$product->id,'spec_key'=>'country','spec_name'=>'Страна-производитель','spec_value'=>'Китай','group'=>'Заводские данные','is_filterable'=>false]);
                ProductSpecification::create(['product_id'=>$product->id,'spec_key'=>'color','spec_name'=>'Цвет','spec_value'=>'черный','group'=>'Общие параметры','is_filterable'=>true]);

                // 6 - Sapphire AMD Radeon RX 7800 XT PURE OC [11330-03]
                $product = Product::create([
                    'title' => 'Sapphire AMD Radeon RX 7800 XT PURE OC [11330-03]',
                    'price' => 66000,
                    'old_price' => null,
                    'discount_percent' => 0,
                    'in_stock' => true,
                    'on_sale' => false,
                    'is_bestseller' => false,
                    'credit_available' => true,
                    'img' => 'Sapphire AMD Radeon RX 7800 XT PURE OC.webp',
                    'product_type' => 'video_card',
                    'country' => 'Китай',
                    'color' => 'белый',
                    'qty' => 10,
                    'description' => 'Видеокарта Sapphire AMD Radeon RX 7800 XT PURE OC [11330-03] предлагает компьютерным энтузиастам производительность, плавность и стиль. Модель выделяется оформлением в белом цвете. Благодаря архитектуре AMD RDNA 3, процессору с частотой 2475 МГц и 16 ГБ памяти GDDR6 обеспечивается мощность графики в программах и играх. За реалистичность деталей и теней отвечает технология трассировки лучей. Видеокарта Sapphire AMD Radeon RX 7800 XT PURE OC [11330-03] охлаждается кулером с 3 вентиляторами. Композитные тепловые трубки равномерно рассеивают тепло. Вентиляторы с двойными подшипниками и особой конструкцией лопастей формируют интенсивный воздушный поток. Логотип на верхней планке подсвечивается красным светодиодом. Цифровая система точно управляет питанием. Печатная плата с 2 унциями меди устойчива к нагреву, что гарантирует стабильность работы под разной нагрузкой. Металлическая тыловая панель защищает графический ускоритель от повреждений и улучшает рассеивание тепла от компонентов.',
                    'category_id' => $videoCardCategory->id,
                    'rating' => 4.8
                ]);
                $product->images()->createMany([
                    ['image_path' => 'Sapphire AMD Radeon RX 7800 XT PURE OC.webp'],
                    ['image_path' => 'Sapphire AMD Radeon RX 7800 XT PURE OC1.webp'], // Assuming this file exists
                    ['image_path' => 'Sapphire AMD Radeon RX 7800 XT PURE OC2.webp'], // Assuming this file exists
                ]);
                ProductSpecification::create(['product_id'=>$product->id,'spec_key'=>'brand','spec_name'=>'Бренд','spec_value'=>'Sapphire','group'=>'Общие параметры','is_filterable'=>true]);
                ProductSpecification::create(['product_id'=>$product->id,'spec_key'=>'model','spec_name'=>'Модель','spec_value'=>'AMD Radeon RX 7800 XT PURE OC','group'=>'Общие параметры','is_filterable'=>true]);
                ProductSpecification::create(['product_id'=>$product->id,'spec_key'=>'gpu','spec_name'=>'Графический процессор','spec_value'=>'Radeon RX 7800 XT','group'=>'Основные параметры','is_filterable'=>true]);
                ProductSpecification::create(['product_id'=>$product->id,'spec_key'=>'architecture','spec_name'=>'Микроархитектура','spec_value'=>'AMD RDNA 3','group'=>'Основные параметры','is_filterable'=>false]);
                ProductSpecification::create(['product_id'=>$product->id,'spec_key'=>'process','spec_name'=>'Техпроцесс','spec_value'=>'5 нм','group'=>'Основные параметры','is_filterable'=>false]);
                ProductSpecification::create(['product_id'=>$product->id,'spec_key'=>'memory','spec_name'=>'Объем видеопамяти','spec_value'=>'16 ГБ','group'=>'Спецификации видеопамяти','is_filterable'=>true]);
                ProductSpecification::create(['product_id'=>$product->id,'spec_key'=>'memory_type','spec_name'=>'Тип памяти','spec_value'=>'GDDR6','group'=>'Спецификации видеопамяти','is_filterable'=>true]);
                ProductSpecification::create(['product_id'=>$product->id,'spec_key'=>'bus_width','spec_name'=>'Разрядность шины памяти','spec_value'=>'256 бит','group'=>'Спецификации видеопамяти','is_filterable'=>false]);
                ProductSpecification::create(['product_id'=>$product->id,'spec_key'=>'bandwidth','spec_name'=>'Максимальная пропускная способность памяти','spec_value'=>'624 Гбайт/сек','group'=>'Спецификации видеопамяти','is_filterable'=>false]);
                ProductSpecification::create(['product_id'=>$product->id,'spec_key'=>'cooling','spec_name'=>'Тип охлаждения','spec_value'=>'активное воздушное','group'=>'Система охлаждения','is_filterable'=>true]);
                ProductSpecification::create(['product_id'=>$product->id,'spec_key'=>'fan_count','spec_name'=>'Количество вентиляторов','spec_value'=>'3 осевых','group'=>'Система охлаждения','is_filterable'=>false]);
                ProductSpecification::create(['product_id'=>$product->id,'spec_key'=>'power','spec_name'=>'Рекомендуемый блок питания','spec_value'=>'700 Вт','group'=>'Подключение','is_filterable'=>false]);
                ProductSpecification::create(['product_id'=>$product->id,'spec_key'=>'power_consumption','spec_name'=>'Потребляемая мощность','spec_value'=>'270 Вт','group'=>'Подключение','is_filterable'=>false]);
                ProductSpecification::create(['product_id'=>$product->id,'spec_key'=>'warranty','spec_name'=>'Гарантия','spec_value'=>'36 мес.','group'=>'Заводские данные','is_filterable'=>false]);
                ProductSpecification::create(['product_id'=>$product->id,'spec_key'=>'country','spec_name'=>'Страна-производитель','spec_value'=>'Китай','group'=>'Заводские данные','is_filterable'=>false]);
                ProductSpecification::create(['product_id'=>$product->id,'spec_key'=>'color','spec_name'=>'Цвет','spec_value'=>'белый','group'=>'Общие параметры','is_filterable'=>true]);
                 // 7 - MSI Geforce RTX 3050 VENTUS 2X XS WHITE OC [RTX 3050 VENTUS 2X XS WHITE 8G OC]
                 $product = Product::create([
                    'title' => 'MSI Geforce RTX 3050 VENTUS 2X XS WHITE OC [RTX 3050 VENTUS 2X XS WHITE 8G OC]',
                    'price' => 32000,
                    'old_price' => null,
                    'discount_percent' => 0,
                    'in_stock' => true,
                    'on_sale' => false,
                    'is_bestseller' => false,
                    'credit_available' => true,
                    'img' => 'MSI Geforce RTX 3050 VENTUS 2X XS WHITE OC.webp',
                    'product_type' => 'video_card',
                    'country' => 'Китай',
                    'color' => 'белый',
                    'qty' => 10,
                    'description' => 'Видеокарта MSI Geforce RTX 3050 VENTUS 2X XS WHITE OC выполнена в белом цвете с двумя осевыми вентиляторами. Технология автоматической регулировки скорости движения лопастей в зависимости от температуры нагрева платы делает тихой работу всей системы охлаждения. Видеокарта с улучшенными вычислительными блоками и скоростной памятью поддерживает разрешение 8K. Мощности устройства хватает для игр и работы с продвинутыми графическими редакторами.',
                    'category_id' => $videoCardCategory->id,
                    'rating' => 4.7
                ]);
                $product->images()->createMany([
                    ['image_path' => 'MSI Geforce RTX 3050 VENTUS 2X XS WHITE OC.webp'],
                    ['image_path' => 'MSI Geforce RTX 3050 VENTUS 2X XS WHITE OC1.webp'], // Assuming this file exists
                    ['image_path' => 'MSI Geforce RTX 3050 VENTUS 2X XS WHITE OC2.webp'], // Assuming this file exists
                ]);
                ProductSpecification::create(['product_id'=>$product->id,'spec_key'=>'brand','spec_name'=>'Бренд','spec_value'=>'MSI','group'=>'Общие параметры','is_filterable'=>true]);
                ProductSpecification::create(['product_id'=>$product->id,'spec_key'=>'model','spec_name'=>'Модель','spec_value'=>'Geforce RTX 3050 VENTUS 2X XS WHITE OC','group'=>'Общие параметры','is_filterable'=>true]);
                ProductSpecification::create(['product_id'=>$product->id,'spec_key'=>'gpu','spec_name'=>'Графический процессор','spec_value'=>'GeForce RTX 3050','group'=>'Основные параметры','is_filterable'=>true]);
                ProductSpecification::create(['product_id'=>$product->id,'spec_key'=>'architecture','spec_name'=>'Микроархитектура','spec_value'=>'NVIDIA Ampere','group'=>'Основные параметры','is_filterable'=>false]);
                ProductSpecification::create(['product_id'=>$product->id,'spec_key'=>'process','spec_name'=>'Техпроцесс','spec_value'=>'8 нм','group'=>'Основные параметры','is_filterable'=>false]);
                ProductSpecification::create(['product_id'=>$product->id,'spec_key'=>'memory','spec_name'=>'Объем видеопамяти','spec_value'=>'8 ГБ','group'=>'Спецификации видеопамяти','is_filterable'=>true]);
                ProductSpecification::create(['product_id'=>$product->id,'spec_key'=>'memory_type','spec_name'=>'Тип памяти','spec_value'=>'GDDR6','group'=>'Спецификации видеопамяти','is_filterable'=>true]);
                ProductSpecification::create(['product_id'=>$product->id,'spec_key'=>'bus_width','spec_name'=>'Разрядность шины памяти','spec_value'=>'128 бит','group'=>'Спецификации видеопамяти','is_filterable'=>false]);
                ProductSpecification::create(['product_id'=>$product->id,'spec_key'=>'cooling','spec_name'=>'Тип охлаждения','spec_value'=>'активное воздушное','group'=>'Система охлаждения','is_filterable'=>true]);
                ProductSpecification::create(['product_id'=>$product->id,'spec_key'=>'fan_count','spec_name'=>'Количество вентиляторов','spec_value'=>'2 осевых','group'=>'Система охлаждения','is_filterable'=>false]);
                ProductSpecification::create(['product_id'=>$product->id,'spec_key'=>'power','spec_name'=>'Рекомендуемый блок питания','spec_value'=>'500 Вт','group'=>'Подключение','is_filterable'=>false]);
                ProductSpecification::create(['product_id'=>$product->id,'spec_key'=>'warranty','spec_name'=>'Гарантия','spec_value'=>'36 мес.','group'=>'Заводские данные','is_filterable'=>false]);
                ProductSpecification::create(['product_id'=>$product->id,'spec_key'=>'country','spec_name'=>'Страна-производитель','spec_value'=>'Китай','group'=>'Заводские данные','is_filterable'=>false]);
                ProductSpecification::create(['product_id'=>$product->id,'spec_key'=>'color','spec_name'=>'Цвет','spec_value'=>'белый','group'=>'Общие параметры','is_filterable'=>true]);

                // 8 - GIGABYTE AMD Radeon 9070 XT AORUS ELITE [GV-R9070XTAORUS E-16GD]
                $product = Product::create([
                    'title' => 'GIGABYTE AMD Radeon 9070 XT AORUS ELITE [GV-R9070XTAORUS E-16GD]',
                    'price' => 80100,
                    'old_price' => null,
                    'discount_percent' => 0,
                    'in_stock' => true,
                    'on_sale' => false,
                    'is_bestseller' => false,
                    'credit_available' => true,
                    'img' => 'GIGABYTE AMD Radeon 9070 XT AORUS ELITE.webp',
                    'product_type' => 'video_card',
                    'country' => 'Китай',
                    'color' => 'черный',
                    'qty' => 10,
                    'description' => 'Игровая видеокарта GIGABYTE AMD Radeon 9070 XT AORUS ELITE с 16 ГБ памяти GDDR6 поддерживает запуск видео в формате 8K Ultra HD и одновременную работу с четырьмя мониторами. Технология трассировки лучей делает графику более реалистичной благодаря естественной прорисовке света и теней. Модель с тремя кулерами дополнена металлическим бэкплейтом для ускоренного охлаждения поверхности. Есть подсветка элементов видеокарты и переключатель BIOS.',
                    'category_id' => $videoCardCategory->id,
                    'rating' => 4.8
                ]);

                $product->images()->createMany([
                    ['image_path' => 'GIGABYTE AMD Radeon 9070 XT AORUS ELITE.webp'],
                    ['image_path' => 'GIGABYTE AMD Radeon 9070 XT AORUS ELITE1.webp'], // Assuming this file exists
                    ['image_path' => 'GIGABYTE AMD Radeon 9070 XT AORUS ELITE2.webp'], // Assuming this file exists
                ]);
                ProductSpecification::create(['product_id'=>$product->id,'spec_key'=>'brand','spec_name'=>'Бренд','spec_value'=>'GIGABYTE','group'=>'Общие параметры','is_filterable'=>true]);
                ProductSpecification::create(['product_id'=>$product->id,'spec_key'=>'model','spec_name'=>'Модель','spec_value'=>'AMD Radeon 9070 XT AORUS ELITE','group'=>'Общие параметры','is_filterable'=>true]);
                ProductSpecification::create(['product_id'=>$product->id,'spec_key'=>'gpu','spec_name'=>'Графический процессор','spec_value'=>'Radeon RX 9070 XT','group'=>'Основные параметры','is_filterable'=>true]);
                ProductSpecification::create(['product_id'=>$product->id,'spec_key'=>'architecture','spec_name'=>'Микроархитектура','spec_value'=>'AMD RDNA 4','group'=>'Основные параметры','is_filterable'=>false]);
                ProductSpecification::create(['product_id'=>$product->id,'spec_key'=>'memory','spec_name'=>'Объем видеопамяти','spec_value'=>'16 ГБ','group'=>'Спецификации видеопамяти','is_filterable'=>true]);
                ProductSpecification::create(['product_id'=>$product->id,'spec_key'=>'memory_type','spec_name'=>'Тип памяти','spec_value'=>'GDDR6','group'=>'Спецификации видеопамяти','is_filterable'=>true]);
                ProductSpecification::create(['product_id'=>$product->id,'spec_key'=>'bus_width','spec_name'=>'Разрядность шины памяти','spec_value'=>'256 бит','group'=>'Спецификации видеопамяти','is_filterable'=>false]);
                ProductSpecification::create(['product_id'=>$product->id,'spec_key'=>'bandwidth','spec_name'=>'Максимальная пропускная способность памяти','spec_value'=>'640 Гбайт/сек','group'=>'Спецификации видеопамяти','is_filterable'=>false]);
                ProductSpecification::create(['product_id'=>$product->id,'spec_key'=>'cooling','spec_name'=>'Тип охлаждения','spec_value'=>'активное воздушное','group'=>'Система охлаждения','is_filterable'=>true]);
                ProductSpecification::create(['product_id'=>$product->id,'spec_key'=>'fan_count','spec_name'=>'Количество вентиляторов','spec_value'=>'3 осевых','group'=>'Система охлаждения','is_filterable'=>false]);
                ProductSpecification::create(['product_id'=>$product->id,'spec_key'=>'power','spec_name'=>'Рекомендуемый блок питания','spec_value'=>'850 Вт','group'=>'Подключение','is_filterable'=>false]);
                ProductSpecification::create(['product_id'=>$product->id,'spec_key'=>'power_consumption','spec_name'=>'Потребляемая мощность','spec_value'=>'-','group'=>'Подключение','is_filterable'=>false]);
                ProductSpecification::create(['product_id'=>$product->id,'spec_key'=>'warranty','spec_name'=>'Гарантия','spec_value'=>'36 мес.','group'=>'Заводские данные','is_filterable'=>false]);
                ProductSpecification::create(['product_id'=>$product->id,'spec_key'=>'country','spec_name'=>'Страна-производитель','spec_value'=>'Китай','group'=>'Заводские данные','is_filterable'=>false]);
                ProductSpecification::create(['product_id'=>$product->id,'spec_key'=>'color','spec_name'=>'Цвет','spec_value'=>'черный','group'=>'Общие параметры','is_filterable'=>true]);
                // ... далее видеокарты 9 и 10 ...
            }
        }

        // Добавляем спецификации для видеокарты NVIDIA RTX 4060 Ti
        ProductSpecification::create(['product_id'=>$product->id,'spec_key'=>'brand','spec_name'=>'Бренд','spec_value'=>'NVIDIA','group'=>'Основные','is_filterable'=>true]);
        ProductSpecification::create(['product_id'=>$product->id,'spec_key'=>'series','spec_name'=>'Серия','spec_value'=>'RTX 4060 Ti','group'=>'Основные','is_filterable'=>true]);
        ProductSpecification::create(['product_id'=>$product->id,'spec_key'=>'memory','spec_name'=>'Объем памяти','spec_value'=>'8 ГБ','group'=>'Память','is_filterable'=>true]);
        ProductSpecification::create(['product_id'=>$product->id,'spec_key'=>'memory_type','spec_name'=>'Тип памяти','spec_value'=>'GDDR6','group'=>'Память','is_filterable'=>true]);
        ProductSpecification::create(['product_id'=>$product->id,'spec_key'=>'bus_width','spec_name'=>'Разрядность шины памяти','spec_value'=>'128 бит','group'=>'Память','is_filterable'=>false]);
        ProductSpecification::create(['product_id'=>$product->id,'spec_key'=>'ray_tracing','spec_name'=>'Ray Tracing','spec_value'=>'Да','group'=>'Технологии','is_filterable'=>true]);
        ProductSpecification::create(['product_id'=>$product->id,'spec_key'=>'dlss','spec_name'=>'DLSS','spec_value'=>'DLSS 3','group'=>'Технологии','is_filterable'=>true]);
        ProductSpecification::create(['product_id'=>$product->id,'spec_key'=>'ports','spec_name'=>'Порты','spec_value'=>'DisplayPort x3, HDMI x1','group'=>'Интерфейсы','is_filterable'=>false]);
        ProductSpecification::create(['product_id'=>$product->id,'spec_key'=>'tdp','spec_name'=>'TDP','spec_value'=>'160 Вт','group'=>'Энергопотребление','is_filterable'=>false]);
        
        $product = Product::create([
            'title' => 'AMD Radeon RX 6700 XT',
            'price' => 38990,
            'old_price' => 45990,
            'discount_percent' => 15,
            'in_stock' => true,
            'on_sale' => true,
            'is_bestseller' => false,
            'credit_available' => true,
            'img' => 'AMD Radeon RX 6700 XT.webp',
            'product_type' => 'video_card',
            'country' => 'Китай',
            'color' => 'Черный',
            'qty' => 12,
            'description' => 'Видеокарта AMD Radeon RX 6700 XT, 12 ГБ GDDR6, 192 бит, PCI-E 4.0, DisplayPort x2, HDMI x1, Ray Tracing, FSR 2.0',
            'category_id' => $videoCardCategory->id,
            'rating' => 4.5
        ]);

        $product->images()->createMany([
            ['image_path' => 'AMD Radeon RX 6700 XT.webp'],
            ['image_path' => 'AMD Radeon RX 6700 XT1.webp'], // Assuming this file exists
            ['image_path' => 'AMD Radeon RX 6700 XT2.webp'], // Assuming this file exists
        ]);
        
        // Добавляем спецификации для видеокарты AMD RX 6700 XT
        ProductSpecification::create(['product_id'=>$product->id,'spec_key'=>'brand','spec_name'=>'Бренд','spec_value'=>'AMD','group'=>'Основные','is_filterable'=>true]);
        ProductSpecification::create(['product_id'=>$product->id,'spec_key'=>'series','spec_name'=>'Серия','spec_value'=>'RX 6700 XT','group'=>'Основные','is_filterable'=>true]);
        ProductSpecification::create(['product_id'=>$product->id,'spec_key'=>'memory','spec_name'=>'Объем памяти','spec_value'=>'12 ГБ','group'=>'Память','is_filterable'=>true]);
        ProductSpecification::create(['product_id'=>$product->id,'spec_key'=>'memory_type','spec_name'=>'Тип памяти','spec_value'=>'GDDR6','group'=>'Память','is_filterable'=>true]);
        ProductSpecification::create(['product_id'=>$product->id,'spec_key'=>'bus_width','spec_name'=>'Разрядность шины памяти','spec_value'=>'192 бит','group'=>'Память','is_filterable'=>false]);
        ProductSpecification::create(['product_id'=>$product->id,'spec_key'=>'ray_tracing','spec_name'=>'Ray Tracing','spec_value'=>'Да','group'=>'Технологии','is_filterable'=>true]);
        ProductSpecification::create(['product_id'=>$product->id,'spec_key'=>'fsr','spec_name'=>'FSR','spec_value'=>'FSR 2.0','group'=>'Технологии','is_filterable'=>true]);
        ProductSpecification::create(['product_id'=>$product->id,'spec_key'=>'ports','spec_name'=>'Порты','spec_value'=>'DisplayPort x2, HDMI x1','group'=>'Интерфейсы','is_filterable'=>false]);
        ProductSpecification::create(['product_id'=>$product->id,'spec_key'=>'tdp','spec_name'=>'TDP','spec_value'=>'230 Вт','group'=>'Энергопотребление','is_filterable'=>false]);

        // Создаем несколько тестовых товаров для категории материнских плат
        if($motherboardCategory) {
            $product = Product::create([
                'title' => 'ASUS ROG Strix Z690-E Gaming',
                'price' => 35990,
                'old_price' => 39990,
                'discount_percent' => 10,
                'in_stock' => true,
                'on_sale' => true,
                'is_bestseller' => true,
                'credit_available' => true,
                'img' => 'Материнская плата ASUS ROG STRIX Z690-E GAMING WIFI.webp',
                'product_type' => 'motherboard',
                'country' => 'Китай',
                'color' => 'Черный',
                'qty' => 12,
                'description' => 'Материнская плата ASUS ROG Strix Z690-E Gaming, Socket LGA1700, Intel Z690, 4xDDR5 DIMM, 5xPCI-E, HDMI, DisplayPort, 6xSATA, 4xM.2, USB 3.2 Gen 2x2 Type-C, ATX',
                'category_id' => $motherboardCategory->id,
                'rating' => 4.9
            ]);
            
            $product->images()->createMany([
                ['image_path' => 'Материнская плата ASUS ROG STRIX Z690-E GAMING WIFI.webp'],
                ['image_path' => 'Материнская плата ASUS ROG STRIX Z690-E GAMING WIFI1.webp'], // Assuming this file exists
                ['image_path' => 'Материнская плата ASUS ROG STRIX Z690-E GAMING WIFI2.webp'], // Assuming this file exists
            ]);

            // Добавляем спецификации для материнской платы
            ProductSpecification::create(['product_id'=>$product->id,'spec_key'=>'brand','spec_name'=>'Бренд','spec_value'=>'ASUS','group'=>'Основные','is_filterable'=>true]);
            ProductSpecification::create(['product_id'=>$product->id,'spec_key'=>'socket','spec_name'=>'Сокет','spec_value'=>'LGA1700','group'=>'Основные','is_filterable'=>true]);
            ProductSpecification::create(['product_id'=>$product->id,'spec_key'=>'chipset','spec_name'=>'Чипсет','spec_value'=>'Intel Z690','group'=>'Основные','is_filterable'=>true]);
            ProductSpecification::create(['product_id'=>$product->id,'spec_key'=>'form_factor','spec_name'=>'Форм-фактор','spec_value'=>'ATX','group'=>'Основные','is_filterable'=>true]);
            ProductSpecification::create(['product_id'=>$product->id,'spec_key'=>'memory_type','spec_name'=>'Тип памяти','spec_value'=>'DDR5','group'=>'Память','is_filterable'=>true]);
            ProductSpecification::create(['product_id'=>$product->id,'spec_key'=>'memory_slots','spec_name'=>'Слотов памяти','spec_value'=>'4','group'=>'Память','is_filterable'=>true]);
            ProductSpecification::create(['product_id'=>$product->id,'spec_key'=>'pcie_slots','spec_name'=>'Слотов PCI-E','spec_value'=>'5','group'=>'Разъемы','is_filterable'=>false]);
            ProductSpecification::create(['product_id'=>$product->id,'spec_key'=>'sata_ports','spec_name'=>'Портов SATA','spec_value'=>'6','group'=>'Разъемы','is_filterable'=>false]);
            ProductSpecification::create(['product_id'=>$product->id,'spec_key'=>'m2_slots','spec_name'=>'Слотов M.2','spec_value'=>'4','group'=>'Разъемы','is_filterable'=>true]);
            
            $product = Product::create([
                'title' => 'MSI MAG B550 TOMAHAWK',
                'price' => 14990,
                'old_price' => null,
                'discount_percent' => 0,
                'in_stock' => true,
                'on_sale' => false,
                'is_new' => true,
                'credit_available' => true,
                'img' => 'Материнская плата MSI MAG B550 TOMAHAWK.webp',
                'product_type' => 'motherboard',
                'country' => 'Китай',
                'color' => 'Черный',
                'qty' => 18,
                'description' => 'Материнская плата MSI MAG B550 TOMAHAWK, Socket AM4, AMD B550, 4xDDR4 DIMM, 3xPCI-E, HDMI, DisplayPort, 6xSATA, 2xM.2, USB 3.2 Gen 2 Type-C, ATX',
                'category_id' => $motherboardCategory->id,
                'rating' => 4.7
            ]);
            $product->images()->createMany([
                ['image_path' => 'Материнская плата MSI MAG B550 TOMAHAWK.webp'],
                ['image_path' => 'Материнская плата MSI MAG B550 TOMAHAWK1.webp'], // Assuming this file exists
                ['image_path' => 'Материнская плата MSI MAG B550 TOMAHAWK2.webp'], // Assuming this file exists
            ]);
            ProductSpecification::create(['product_id'=>$product->id,'spec_key'=>'brand','spec_name'=>'Бренд','spec_value'=>'MSI','group'=>'Основные','is_filterable'=>true]);
            ProductSpecification::create(['product_id'=>$product->id,'spec_key'=>'chipset','spec_name'=>'Чипсет','spec_value'=>'AMD B550','group'=>'Основные','is_filterable'=>true]);
ProductSpecification::create(['product_id'=>$product->id,'spec_key'=>'socket','spec_name'=>'Сокет','spec_value'=>'AM4','group'=>'Основные','is_filterable'=>true]);
ProductSpecification::create(['product_id'=>$product->id,'spec_key'=>'form_factor','spec_name'=>'Форм-фактор','spec_value'=>'Standard-ATX','group'=>'Основные','is_filterable'=>true]);
ProductSpecification::create(['product_id'=>$product->id,'spec_key'=>'memory_type','spec_name'=>'Тип памяти','spec_value'=>'DDR4','group'=>'Память','is_filterable'=>true]);
ProductSpecification::create(['product_id'=>$product->id,'spec_key'=>'memory_slots','spec_name'=>'Слотов памяти','spec_value'=>'4','group'=>'Память','is_filterable'=>true]);
ProductSpecification::create(['product_id'=>$product->id,'spec_key'=>'max_memory','spec_name'=>'Максимальный объем памяти','spec_value'=>'128 ГБ','group'=>'Память','is_filterable'=>false]);
ProductSpecification::create(['product_id'=>$product->id,'spec_key'=>'pcie_x16_slots','spec_name'=>'Слоты PCI-E x16','spec_value'=>'2 (PCI-E 3.0)','group'=>'Разъемы','is_filterable'=>false]);
ProductSpecification::create(['product_id'=>$product->id,'spec_key'=>'crossfire','spec_name'=>'CrossFire X','spec_value'=>'Да','group'=>'Разъемы','is_filterable'=>true]);
ProductSpecification::create(['product_id'=>$product->id,'spec_key'=>'sata_ports','spec_name'=>'Портов SATA','spec_value'=>'6','group'=>'Разъемы','is_filterable'=>true]);
ProductSpecification::create(['product_id'=>$product->id,'spec_key'=>'m2_slots','spec_name'=>'Слотов M.2','spec_value'=>'2','group'=>'Разъемы','is_filterable'=>true]);
ProductSpecification::create(['product_id'=>$product->id,'spec_key'=>'lan','spec_name'=>'Сетевой адаптер','spec_value'=>'2.5 Гбит/с','group'=>'Интерфейсы','is_filterable'=>true]);
ProductSpecification::create(['product_id'=>$product->id,'spec_key'=>'usb_ports','spec_name'=>'Порты USB','spec_value'=>'USB-C, USB 3.2 Gen 2','group'=>'Интерфейсы','is_filterable'=>false]);
ProductSpecification::create(['product_id'=>$product->id,'spec_key'=>'display_outputs','spec_name'=>'Видеовыходы','spec_value'=>'HDMI, DisplayPort','group'=>'Интерфейсы','is_filterable'=>false]);
ProductSpecification::create(['product_id'=>$product->id,'spec_key'=>'audio_codec','spec_name'=>'Аудио кодек','spec_value'=>'Realtek HD Audio','group'=>'Интерфейсы','is_filterable'=>false]);
ProductSpecification::create(['product_id'=>$product->id,'spec_key'=>'rgb','spec_name'=>'RGB-подсветка','spec_value'=>'Да','group'=>'Особенности','is_filterable'=>false]);
            
// Материнская плата ASRock H610M-HVS/M.2 R2.0
$product = Product::create([
    'title' => 'ASRock H610M-HVS/M.2 R2.0',
    'price' => 6500,
    'old_price' => null,
    'discount_percent' => 0,
    'in_stock' => true,
    'on_sale' => false,
    'is_new' => true,
    'credit_available' => true,
    'img' => 'Материнская плата ASRock H610M-HVSM.2 R2.0.webp',
    'product_type' => 'motherboard',
    'country' => 'Китай',
    'color' => 'Черный',
    'qty' => 10,
    'description' => 'Материнская плата ASRock H610M-HVS/M.2 R2.0 выполнена в форм-факторе Micro-ATX, что делает ее подходящим решением для интеграции в системный блок с ограниченным пространством. Она основана на чипсете Intel H610, который позволяет установить процессор Intel 12/13/14-го поколения. В плате предусмотрены 2 слота под размещение модулей ОЗУ поколения DDR4, слот расширения PCI-E x16 для графического ускорителя, 1 разъем M.2 и 4 разъема SATA под накопители HDD/SSD. Для организации стабильного соединения с Интернетом реализован контроллер LAN 1 Гбит/с. Система Digi Power обеспечивает стабильное и равномерное питание процессора. Технология ASRock Full Spike Protection защищает компоненты платы ASRock H610M-HVS/M.2 R2.0 от перепадов напряжения. Два видеовыхода D-Sub и HDMI позволяют выводить изображение на мониторы.',
    'category_id' => $motherboardCategory->id,
    'rating' => 4.6
]);

$product->images()->createMany([
    ['image_path' => 'Материнская плата ASRock H610M-HVSM.2 R2.0.webp'],
    ['image_path' => 'Материнская плата ASRock H610M-HVSM.2 R2.01.webp'], // Assuming this file exists
    ['image_path' => 'Материнская плата ASRock H610M-HVSM.2 R2.02.webp'], // Assuming this file exists
]);

ProductSpecification::create(['product_id'=>$product->id,'spec_key'=>'brand','spec_name'=>'Бренд','spec_value'=>'ASRock','group'=>'Основные','is_filterable'=>true]);
ProductSpecification::create(['product_id'=>$product->id,'spec_key'=>'chipset','spec_name'=>'Чипсет','spec_value'=>'Intel H610','group'=>'Основные','is_filterable'=>true]);
ProductSpecification::create(['product_id'=>$product->id,'spec_key'=>'socket','spec_name'=>'Сокет','spec_value'=>'LGA1700','group'=>'Основные','is_filterable'=>true]);
ProductSpecification::create(['product_id'=>$product->id,'spec_key'=>'form_factor','spec_name'=>'Форм-фактор','spec_value'=>'Micro-ATX','group'=>'Основные','is_filterable'=>true]);
ProductSpecification::create(['product_id'=>$product->id,'spec_key'=>'memory_type','spec_name'=>'Тип памяти','spec_value'=>'DDR4','group'=>'Память','is_filterable'=>true]);
ProductSpecification::create(['product_id'=>$product->id,'spec_key'=>'memory_slots','spec_name'=>'Слотов памяти','spec_value'=>'2','group'=>'Память','is_filterable'=>true]);
ProductSpecification::create(['product_id'=>$product->id,'spec_key'=>'max_memory','spec_name'=>'Максимальный объем памяти','spec_value'=>'64 ГБ','group'=>'Память','is_filterable'=>false]);
ProductSpecification::create(['product_id'=>$product->id,'spec_key'=>'pcie_x16_slots','spec_name'=>'Слоты PCI-E x16','spec_value'=>'1','group'=>'Разъемы','is_filterable'=>false]);
ProductSpecification::create(['product_id'=>$product->id,'spec_key'=>'m2_slots','spec_name'=>'Слотов M.2','spec_value'=>'1','group'=>'Разъемы','is_filterable'=>true]);
ProductSpecification::create(['product_id'=>$product->id,'spec_key'=>'sata_ports','spec_name'=>'Портов SATA','spec_value'=>'4','group'=>'Разъемы','is_filterable'=>true]);
ProductSpecification::create(['product_id'=>$product->id,'spec_key'=>'lan','spec_name'=>'Сетевой адаптер','spec_value'=>'1 Гбит/с','group'=>'Интерфейсы','is_filterable'=>true]);
ProductSpecification::create(['product_id'=>$product->id,'spec_key'=>'video_outputs','spec_name'=>'Видеовыходы','spec_value'=>'D-Sub, HDMI','group'=>'Интерфейсы','is_filterable'=>false]);
ProductSpecification::create(['product_id'=>$product->id,'spec_key'=>'power_system','spec_name'=>'Система питания','spec_value'=>'Digi Power','group'=>'Питание','is_filterable'=>false]);
ProductSpecification::create(['product_id'=>$product->id,'spec_key'=>'protection','spec_name'=>'Защита','spec_value'=>'ASRock Full Spike Protection','group'=>'Особенности','is_filterable'=>false]);

// Материнская плата ASUS PRIME B650M-R
$product = Product::create([
    'title' => 'ASUS PRIME B650M-R',
    'price' => 14000,
    'old_price' => null,
    'discount_percent' => 0,
    'in_stock' => true,
    'on_sale' => false,
    'is_new' => true,
    'credit_available' => true,
    'img' => 'Материнская плата ASUS PRIME B650M-R.webp',
    'product_type' => 'motherboard',
    'country' => 'Китай',
    'color' => 'Черный',
    'qty' => 10,
    'description' => 'Материнская плата ASUS PRIME B650M-R – надежная основа для построения офисного или домашнего ПК. Она создана на чипсете AMD B650 с разъемом AM5 под процессор AMD и компактна благодаря типоразмеру Micro-ATX. Для подключения комплектующих реализованы 2 слота DIMM DDR5, слоты расширения PCIe x16 и PCIe x1, 4 разъема SATA и 2 разъема M.2. ASUS PRIME B650M-R оборудована сетевым адаптером 2.5 Гбит/с и 7.1-канальным звуковым кодеком. На панели ввода/вывода расположены порты и разъемы: USB Type-A, HDMI, аудио. Плата разработана с применением надежных компонентов и фирменных технологий для достижения стабильности в процессе эксплуатации.',
    'category_id' => $motherboardCategory->id,
    'rating' => 4.7
]);

$product->images()->createMany([
    ['image_path' => 'Материнская плата ASUS PRIME B650M-R.webp'],
    ['image_path' => 'Материнская плата ASUS PRIME B650M-R1.webp'], // Assuming this file exists
    ['image_path' => 'Материнская плата ASUS PRIME B650M-R2.webp'], // Assuming this file exists
]);

ProductSpecification::create(['product_id'=>$product->id,'spec_key'=>'brand','spec_name'=>'Бренд','spec_value'=>'ASUS','group'=>'Основные','is_filterable'=>true]);
ProductSpecification::create(['product_id'=>$product->id,'spec_key'=>'chipset','spec_name'=>'Чипсет','spec_value'=>'AMD B650','group'=>'Основные','is_filterable'=>true]);
ProductSpecification::create(['product_id'=>$product->id,'spec_key'=>'socket','spec_name'=>'Сокет','spec_value'=>'AM5','group'=>'Основные','is_filterable'=>true]);
ProductSpecification::create(['product_id'=>$product->id,'spec_key'=>'form_factor','spec_name'=>'Форм-фактор','spec_value'=>'Micro-ATX','group'=>'Основные','is_filterable'=>true]);
ProductSpecification::create(['product_id'=>$product->id,'spec_key'=>'memory_type','spec_name'=>'Тип памяти','spec_value'=>'DDR5','group'=>'Память','is_filterable'=>true]);
ProductSpecification::create(['product_id'=>$product->id,'spec_key'=>'memory_slots','spec_name'=>'Слотов памяти','spec_value'=>'2','group'=>'Память','is_filterable'=>true]);
ProductSpecification::create(['product_id'=>$product->id,'spec_key'=>'pcie_x16_slots','spec_name'=>'Слоты PCI-E x16','spec_value'=>'1','group'=>'Разъемы','is_filterable'=>false]);
ProductSpecification::create(['product_id'=>$product->id,'spec_key'=>'pcie_x1_slots','spec_name'=>'Слоты PCI-E x1','spec_value'=>'1','group'=>'Разъемы','is_filterable'=>false]);
ProductSpecification::create(['product_id'=>$product->id,'spec_key'=>'m2_slots','spec_name'=>'Слотов M.2','spec_value'=>'2','group'=>'Разъемы','is_filterable'=>true]);
ProductSpecification::create(['product_id'=>$product->id,'spec_key'=>'sata_ports','spec_name'=>'Портов SATA','spec_value'=>'4','group'=>'Разъемы','is_filterable'=>true]);
ProductSpecification::create(['product_id'=>$product->id,'spec_key'=>'lan','spec_name'=>'Сетевой адаптер','spec_value'=>'2.5 Гбит/с','group'=>'Интерфейсы','is_filterable'=>true]);
ProductSpecification::create(['product_id'=>$product->id,'spec_key'=>'audio_codec','spec_name'=>'Аудио кодек','spec_value'=>'7.1-канальный','group'=>'Интерфейсы','is_filterable'=>false]);
ProductSpecification::create(['product_id'=>$product->id,'spec_key'=>'usb_ports','spec_name'=>'Порты USB','spec_value'=>'USB Type-A','group'=>'Интерфейсы','is_filterable'=>false]);
ProductSpecification::create(['product_id'=>$product->id,'spec_key'=>'video_outputs','spec_name'=>'Видеовыходы','spec_value'=>'HDMI','group'=>'Интерфейсы','is_filterable'=>false]);

            // Добавляем спецификации для материнской платы
            ProductSpecification::create(['product_id'=>$product->id,'spec_key'=>'socket','spec_name'=>'Сокет','spec_value'=>'AM4','group'=>'Основные','is_filterable'=>true]);
            ProductSpecification::create(['product_id'=>$product->id,'spec_key'=>'chipset','spec_name'=>'Чипсет','spec_value'=>'AMD B550','group'=>'Основные','is_filterable'=>true]);
            ProductSpecification::create(['product_id'=>$product->id,'spec_key'=>'form_factor','spec_name'=>'Форм-фактор','spec_value'=>'ATX','group'=>'Основные','is_filterable'=>true]);
            ProductSpecification::create(['product_id'=>$product->id,'spec_key'=>'memory_type','spec_name'=>'Тип памяти','spec_value'=>'DDR4','group'=>'Память','is_filterable'=>true]);
            ProductSpecification::create(['product_id'=>$product->id,'spec_key'=>'memory_slots','spec_name'=>'Слотов памяти','spec_value'=>'4','group'=>'Память','is_filterable'=>true]);
            ProductSpecification::create(['product_id'=>$product->id,'spec_key'=>'pcie_slots','spec_name'=>'Слотов PCI-E','spec_value'=>'3','group'=>'Разъемы','is_filterable'=>false]);
            ProductSpecification::create(['product_id'=>$product->id,'spec_key'=>'sata_ports','spec_name'=>'Портов SATA','spec_value'=>'6','group'=>'Разъемы','is_filterable'=>false]);
            ProductSpecification::create(['product_id'=>$product->id,'spec_key'=>'m2_slots','spec_name'=>'Слотов M.2','spec_value'=>'2','group'=>'Разъемы','is_filterable'=>true]);

            // Материнская плата MSI B650 GAMING PLUS WIFI
            $product = Product::create([
                'title' => 'Материнская плата MSI B650 GAMING PLUS WIFI',
                'price' => 15000, // Условная цена, нужно будет скорректировать
                'old_price' => null,
                'discount_percent' => 0,
                'in_stock' => true,
                'on_sale' => false,
                'is_bestseller' => false,
                'is_new' => true,
                'credit_available' => true,
                'img' => 'Материнская плата MSI B650 GAMING PLUS WIFI.webp', // Предполагаемое имя файла изображения
                'product_type' => 'motherboard',
                'country' => 'Китай', // Условная страна
                'color' => 'Черный', // Условный цвет
                'qty' => 10,
                'description' => 'Материнская плата MSI B650 GAMING PLUS WIFI нацелена на геймеров, которые стремятся к достижению высокой производительности и стабильности компьютера. Она ориентирована на совместное функционирование с процессором AMD в исполнении AM5. Под установку модулей оперативной памяти стандарта DDR5 предлагается 4 слота. Дискретную графическую систем можно задействовать при помощи слотов расширения PCI-E x16 и PCI-E x1. Для создания вместительного хранилища есть 2 разъема M.2 и 4 порта SATA.\nКоннектор LAN 2.5 Гбит/с и модуль беспроводной связи (поддержка стандартов WI-FI 6E и Bluetooth 5.2) гарантируют стабильное соединение с Интернетом и совместимыми устройствами. Охлаждающие радиаторы увеличенного размера отводят тепло от чипсета, системы питания, твердотельного накопителя. Аудиосистема Audio Boost обеспечивает чистый и насыщенный звук для погружения в игровую атмосферу. Протестированные компоненты и 6-слойная печатная плата с повышенным содержанием меди гарантируют стабильную работу MSI B650 GAMING PLUS WIFI.',
                'category_id' => $motherboardCategory->id,
                'rating' => 0.0, // Условный рейтинг
            ]);

            $product->images()->createMany([
                ['image_path' => 'Материнская плата MSI B650 GAMING PLUS WIFI.webp'],
                ['image_path' => 'Материнская плата MSI B650 GAMING PLUS WIFI1.webp'], // Assuming this file exists
                ['image_path' => 'Материнская плата MSI B650 GAMING PLUS WIFI2.webp'], // Assuming this file exists
            ]);

            // Добавляем спецификации для MSI B650 GAMING PLUS WIFI
            ProductSpecification::create(['product_id'=>$product->id,'spec_key'=>'brand','spec_name'=>'Бренд','spec_value'=>'MSI','group'=>'Основные','is_filterable'=>true]);
            ProductSpecification::create(['product_id'=>$product->id,'spec_key'=>'socket','spec_name'=>'Сокет','spec_value'=>'AM5','group'=>'Основные','is_filterable'=>true]);
            ProductSpecification::create(['product_id'=>$product->id,'spec_key'=>'memory_type','spec_name'=>'Тип памяти','spec_value'=>'DDR5','group'=>'Память','is_filterable'=>true]);
            ProductSpecification::create(['product_id'=>$product->id,'spec_key'=>'memory_slots','spec_name'=>'Слотов памяти','spec_value'=>'4','group'=>'Память','is_filterable'=>true]);
            ProductSpecification::create(['product_id'=>$product->id,'spec_key'=>'pcie_x16_slots','spec_name'=>'Слоты PCI-E x16','spec_value'=>'1','group'=>'Разъемы','is_filterable'=>false]);
            ProductSpecification::create(['product_id'=>$product->id,'spec_key'=>'pcie_x1_slots','spec_name'=>'Слоты PCI-E x1','spec_value'=>'1','group'=>'Разъемы','is_filterable'=>false]);
            ProductSpecification::create(['product_id'=>$product->id,'spec_key'=>'m2_slots','spec_name'=>'Слотов M.2','spec_value'=>'2','group'=>'Разъемы','is_filterable'=>true]);
            ProductSpecification::create(['product_id'=>$product->id,'spec_key'=>'sata_ports','spec_name'=>'Портов SATA','spec_value'=>'4','group'=>'Разъемы','is_filterable'=>false]);
            ProductSpecification::create(['product_id'=>$product->id,'spec_key'=>'lan','spec_name'=>'Сетевой адаптер','spec_value'=>'2.5 Гбит/с','group'=>'Интерфейсы','is_filterable'=>false]);
            ProductSpecification::create(['product_id'=>$product->id,'spec_key'=>'wifi','spec_name'=>'Wi-Fi','spec_value'=>'WI-FI 6E','group'=>'Интерфейсы','is_filterable'=>true]);
            ProductSpecification::create(['product_id'=>$product->id,'spec_key'=>'bluetooth','spec_name'=>'Bluetooth','spec_value'=>'5.2','group'=>'Интерфейсы','is_filterable'=>true]);
            ProductSpecification::create(['product_id'=>$product->id,'spec_key'=>'audio','spec_name'=>'Аудио','spec_value'=>'Audio Boost','group'=>'Интерфейсы','is_filterable'=>false]);
            ProductSpecification::create(['product_id'=>$product->id,'spec_key'=>'form_factor','spec_name'=>'Форм-фактор','spec_value'=>'ATX', 'group'=>'Основные','is_filterable'=>true]); // Предполагаемый форм-фактор

            // Материнская плата MSI MPG B550 GAMING PLUS
            $product = Product::create([
                'title' => 'Материнская плата MSI MPG B550 GAMING PLUS',
                'price' => 12000, // Условная цена
                'old_price' => null,
                'discount_percent' => 0,
                'in_stock' => true,
                'on_sale' => false,
                'is_bestseller' => false,
                'is_new' => true,
                'credit_available' => true,
                'img' => 'Материнская плата MSI MPG B550 GAMING PLUS.webp', // Предполагаемое имя файла изображения
                'product_type' => 'motherboard',
                'country' => 'Китай', // Условная страна
                'color' => 'Черный', // Условный цвет
                'qty' => 10,
                'description' => 'Материнская плата MSI MPG B550 GAMING PLUS – отличный выбор для любителей игр, предпочитающих использовать процессоры AMD. Устройство совместимо с процессорами, для установки которых используется сокет AM4. Плата, располагающая возможностью монтажа до 128 ГБ памяти, подходит не только для развлечений, но и для профессиональных задач. В этой связи вам наверняка будет полезна поддержка NVMe, благодаря которой можно задействовать скоростные SSD.\nПлата MSI MPG B550 GAMING PLUS поддерживает технологию CrossFire X. Одновременно можно использовать 2 видеокарты. Всего слотов расширения 4: устройство располагает парой слотов PCI-E x1, которые обеспечивают возможность монтажа контроллеров и плат расширения разного назначения, в том числе – адаптера Wi-Fi и звуковой карты. Штатный звук платы соответствует формату 7.1. Скорость встроенного сетевого адаптера составляет 1 Гбит/с. Форм-фактор платы – Standard-ATX. Устройство имеет типовые (305x244 мм) размеры.',
                'category_id' => $motherboardCategory->id,
                'rating' => 0.0, // Условный рейтинг
            ]);

            $product->images()->createMany([
                ['image_path' => 'Материнская плата MSI MPG B550 GAMING PLUS.webp'],
                ['image_path' => 'Материнская плата MSI MPG B550 GAMING PLUS1.webp'], // Assuming this file exists
                ['image_path' => 'Материнская плата MSI MPG B550 GAMING PLUS2.webp'], // Assuming this file exists
            ]);

            // Добавляем спецификации для MSI MPG B550 GAMING PLUS
            ProductSpecification::create(['product_id'=>$product->id,'spec_key'=>'brand','spec_name'=>'Бренд','spec_value'=>'MSI','group'=>'Основные','is_filterable'=>true]);
            ProductSpecification::create(['product_id'=>$product->id,'spec_key'=>'chipset','spec_name'=>'Чипсет','spec_value'=>'AMD B550','group'=>'Основные','is_filterable'=>true]);
            ProductSpecification::create(['product_id'=>$product->id,'spec_key'=>'socket','spec_name'=>'Сокет','spec_value'=>'AM4','group'=>'Основные','is_filterable'=>true]);
            ProductSpecification::create(['product_id'=>$product->id,'spec_key'=>'form_factor','spec_name'=>'Форм-фактор','spec_value'=>'Standard-ATX','group'=>'Основные','is_filterable'=>true]);
            ProductSpecification::create(['product_id'=>$product->id,'spec_key'=>'memory_type','spec_name'=>'Тип памяти','spec_value'=>'DDR4','group'=>'Память','is_filterable'=>true]);
            ProductSpecification::create(['product_id'=>$product->id,'spec_key'=>'memory_slots','spec_name'=>'Слотов памяти','spec_value'=>'4','group'=>'Память','is_filterable'=>true]);
            ProductSpecification::create(['product_id'=>$product->id,'spec_key'=>'max_memory','spec_name'=>'Максимальный объем памяти','spec_value'=>'128 ГБ','group'=>'Память','is_filterable'=>false]);
            ProductSpecification::create(['product_id'=>$product->id,'spec_key'=>'nvme_support','spec_name'=>'Поддержка NVMe','spec_value'=>'Да','group'=>'Память','is_filterable'=>true]);
            ProductSpecification::create(['product_id'=>$product->id,'spec_key'=>'pcie_x16_slots','spec_name'=>'Слоты PCI-E x16','spec_value'=>'2','group'=>'Разъемы','is_filterable'=>false]);
            ProductSpecification::create(['product_id'=>$product->id,'spec_key'=>'pcie_x1_slots','spec_name'=>'Слоты PCI-E x1','spec_value'=>'2','group'=>'Разъемы','is_filterable'=>false]);
            ProductSpecification::create(['product_id'=>$product->id,'spec_key'=>'crossfire','spec_name'=>'CrossFire X','spec_value'=>'Да','group'=>'Разъемы','is_filterable'=>true]);
            ProductSpecification::create(['product_id'=>$product->id,'spec_key'=>'sata_ports','spec_name'=>'Портов SATA','spec_value'=>'4','group'=>'Разъемы','is_filterable'=>true]);
            ProductSpecification::create(['product_id'=>$product->id,'spec_key'=>'m2_slots','spec_name'=>'Слотов M.2','spec_value'=>'1','group'=>'Разъемы','is_filterable'=>true]);
            ProductSpecification::create(['product_id'=>$product->id,'spec_key'=>'lan','spec_name'=>'Сетевой адаптер','spec_value'=>'1 Гбит/с','group'=>'Интерфейсы','is_filterable'=>true]);
            ProductSpecification::create(['product_id'=>$product->id,'spec_key'=>'audio_codec','spec_name'=>'Аудио кодек','spec_value'=>'7.1-канальный звук','group'=>'Интерфейсы','is_filterable'=>false]);

            // Материнская плата MSI B760 GAMING PLUS WIFI
            $product = Product::create([
                'title' => 'Материнская плата MSI B760 GAMING PLUS WIFI',
                'price' => 17000, // Условная цена
                'old_price' => null,
                'discount_percent' => 0,
                'in_stock' => true,
                'on_sale' => false,
                'is_bestseller' => false,
                'is_new' => true,
                'credit_available' => true,
                'img' => 'Материнская плата MSI B760 GAMING PLUS WIFI.webp', // Предполагаемое имя файла изображения
                'product_type' => 'motherboard',
                'country' => 'Китай', // Условная страна
                'color' => 'Черный', // Условный цвет
                'qty' => 10,
                'description' => 'Материнская плата MSI B760 GAMING PLUS WIFI создана для геймеров и настроена на достижение высокого вычислительного потенциала. Она совместима с процессорами Alder Lake и Raptor Lake от производителя Intel. Плата оборудована 4 слотами DIMM, которые позволяют задействовать до 256 ГБ оперативной памяти поколения DDR5. Наличие 5 слотов расширения PCI-E x16 предусматривает создание графической системы из нескольких видеокарт. При помощи 2 разъемов M.2 и 6 портов SATA можно создать вместительное хранилище для игр, мультимедийных файлов, программ и документов.\nВ плате реализован коннектор 2.5 Гбит/с для стабильного доступа к Интернет по проводному соединению. Интегрированный контроллер с поддержкой Wi-Fi и Bluetooth предоставляет возможность беспроводной синхронизации с сетевым оборудованием, техникой и мобильными устройствами. Отборные протестированные компоненты и система питания 14 фаз означают, что MSI B760 GAMING PLUS WIFI способна обеспечить стабильную работу при разной нагрузке.',
                'category_id' => $motherboardCategory->id,
                'rating' => 0.0, // Условный рейтинг
            ]);

            $product->images()->createMany([
                ['image_path' => 'Материнская плата MSI B760 GAMING PLUS WIFI.webp'],
                ['image_path' => 'Материнская плата MSI B760 GAMING PLUS WIFI1.webp'], // Assuming this file exists
                ['image_path' => 'Материнская плата MSI B760 GAMING PLUS WIFI2.webp'], // Assuming this file exists
            ]);

            // Добавляем спецификации для MSI B760 GAMING PLUS WIFI
            ProductSpecification::create(['product_id'=>$product->id,'spec_key'=>'brand','spec_name'=>'Бренд','spec_value'=>'MSI','group'=>'Основные','is_filterable'=>true]);
            ProductSpecification::create(['product_id'=>$product->id,'spec_key'=>'socket','spec_name'=>'Сокет','spec_value'=>'LGA1700','group'=>'Основные','is_filterable'=>true]);
            ProductSpecification::create(['product_id'=>$product->id,'spec_key'=>'compatible_processors','spec_name'=>'Совместимые процессоры','spec_value'=>'Alder Lake, Raptor Lake','group'=>'Основные','is_filterable'=>false]);
            ProductSpecification::create(['product_id'=>$product->id,'spec_key'=>'memory_type','spec_name'=>'Тип памяти','spec_value'=>'DDR5','group'=>'Память','is_filterable'=>true]);
            ProductSpecification::create(['product_id'=>$product->id,'spec_key'=>'memory_slots','spec_name'=>'Слотов памяти','spec_value'=>'4','group'=>'Память','is_filterable'=>true]);
            ProductSpecification::create(['product_id'=>$product->id,'spec_key'=>'memory_capacity_max','spec_name'=>'Макс. объем памяти','spec_value'=>'256 ГБ','group'=>'Память','is_filterable'=>false]);
            ProductSpecification::create(['product_id'=>$product->id,'spec_key'=>'pcie_x16_slots','spec_name'=>'Слоты PCI-E x16','spec_value'=>'5','group'=>'Разъемы','is_filterable'=>false]);
            ProductSpecification::create(['product_id'=>$product->id,'spec_key'=>'m2_slots','spec_name'=>'Слотов M.2','spec_value'=>'2','group'=>'Разъемы','is_filterable'=>true]);
            ProductSpecification::create(['product_id'=>$product->id,'spec_key'=>'sata_ports','spec_name'=>'Портов SATA','spec_value'=>'6','group'=>'Разъемы','is_filterable'=>false]);
            ProductSpecification::create(['product_id'=>$product->id,'spec_key'=>'lan','spec_name'=>'Сетевой адаптер','spec_value'=>'2.5 Гбит/с','group'=>'Интерфейсы','is_filterable'=>false]);
            ProductSpecification::create(['product_id'=>$product->id,'spec_key'=>'wifi','spec_name'=>'Wi-Fi','spec_value'=>'есть','group'=>'Интерфейсы','is_filterable'=>true]);
            ProductSpecification::create(['product_id'=>$product->id,'spec_key'=>'bluetooth','spec_name'=>'Bluetooth','spec_value'=>'есть','group'=>'Интерфейсы','is_filterable'=>true]);
            ProductSpecification::create(['product_id'=>$product->id,'spec_key'=>'power_phase','spec_name'=>'Система питания','spec_value'=>'14 фаз','group'=>'Питание','is_filterable'=>false]);
            ProductSpecification::create(['product_id'=>$product->id,'spec_key'=>'form_factor','spec_name'=>'Форм-фактор','spec_value'=>'ATX', 'group'=>'Основные','is_filterable'=>true]); // Предполагаемый форм-фактор

$product = Product::create([
    'title' => 'Материнская плата ASUS TUF GAMING B650-PLUS',
    'price' => 21000, // Условная цена
    'old_price' => null,
    'discount_percent' => 0,
    'in_stock' => true,
    'on_sale' => false,
    'is_bestseller' => false,
    'is_new' => true,
    'credit_available' => true,
    'img' => 'Материнская плата ASUS TUF GAMING B650-PLUS.webp', // Имя файла изображения
    'product_type' => 'motherboard',
    'country' => 'Китай', // Условная страна
    'color' => 'Черно-желтый', // Цвет по описанию
    'qty' => 8,
    'description' => 'Материнская плата ASUS TUF GAMING B650-PLUS из семейства TUF предназначена для требовательных геймеров. Она выполнена в полноразмерном формате и предлагает мощные характеристики для игровой сборки. Модель отличается совместимостью с процессором AMD AM5. Из характеристик платы – 4 слота под установку до 128 ГБ оперативной памяти стандарта DDR5, 2 слота расширения PCI-E x16 для графического адаптера, 3 разъема M.2 и 4 слота SATA для накопителей. ASUS TUF GAMING B650-PLUS оснащена сетевым адаптером со скоростью 2.5 Гбит/с. С боковой стороны предусмотрено множество разъемов: USB Type-A и Type-C, HDMI, DisplayPort, LAN, аудио. Плата выделяется крупными охлаждающими радиаторами, черно-желтой расцветкой и светодиодной подсветкой с программируемой палитрой RGB.',
    'category_id' => $motherboardCategory->id,
    'rating' => 0.0, // Условный рейтинг
]);

$product->images()->createMany([
    ['image_path' => 'Материнская плата ASUS TUF GAMING B650-PLUS.webp'],
    ['image_path' => 'Материнская плата ASUS TUF GAMING B650-PLUS1.webp'], // Assuming this file exists
    ['image_path' => 'Материнская плата ASUS TUF GAMING B650-PLUS2.webp'], // Assuming this file exists
]);

// Добавляем спецификации для ASUS TUF GAMING B650-PLUS
ProductSpecification::create(['product_id'=>$product->id,'spec_key'=>'brand','spec_name'=>'Бренд','spec_value'=>'ASUS','group'=>'Основные','is_filterable'=>true]);
ProductSpecification::create(['product_id'=>$product->id,'spec_key'=>'chipset','spec_name'=>'Чипсет','spec_value'=>'AMD B650','group'=>'Основные','is_filterable'=>true]);
ProductSpecification::create(['product_id'=>$product->id,'spec_key'=>'socket','spec_name'=>'Сокет','spec_value'=>'AM5','group'=>'Основные','is_filterable'=>true]);
ProductSpecification::create(['product_id'=>$product->id,'spec_key'=>'form_factor','spec_name'=>'Форм-фактор','spec_value'=>'ATX (305x244 мм)','group'=>'Основные','is_filterable'=>true]);
ProductSpecification::create(['product_id'=>$product->id,'spec_key'=>'dimensions','spec_name'=>'Размеры','spec_value'=>'305 x 244 мм','group'=>'Основные','is_filterable'=>false]);
ProductSpecification::create(['product_id'=>$product->id,'spec_key'=>'memory_type','spec_name'=>'Тип памяти','spec_value'=>'DDR5','group'=>'Память','is_filterable'=>true]);
ProductSpecification::create(['product_id'=>$product->id,'spec_key'=>'memory_slots','spec_name'=>'Слотов памяти','spec_value'=>'4 x DIMM','group'=>'Память','is_filterable'=>true]);
ProductSpecification::create(['product_id'=>$product->id,'spec_key'=>'max_memory','spec_name'=>'Максимальный объем памяти','spec_value'=>'128 ГБ','group'=>'Память','is_filterable'=>false]);
ProductSpecification::create(['product_id'=>$product->id,'spec_key'=>'memory_frequency','spec_name'=>'Частота памяти','spec_value'=>'до 6400 МГц (OC)','group'=>'Память','is_filterable'=>false]);
ProductSpecification::create(['product_id'=>$product->id,'spec_key'=>'pcie_x16_slots','spec_name'=>'Слоты PCI-E x16','spec_value'=>'2','group'=>'Разъемы','is_filterable'=>true]);
ProductSpecification::create(['product_id'=>$product->id,'spec_key'=>'pcie_x1_slots','spec_name'=>'Слоты PCI-E x1','spec_value'=>'1','group'=>'Разъемы','is_filterable'=>false]);
ProductSpecification::create(['product_id'=>$product->id,'spec_key'=>'m2_slots','spec_name'=>'Слотов M.2','spec_value'=>'3','group'=>'Разъемы','is_filterable'=>true]);
ProductSpecification::create(['product_id'=>$product->id,'spec_key'=>'sata_ports','spec_name'=>'Портов SATA','spec_value'=>'4','group'=>'Разъемы','is_filterable'=>true]);
ProductSpecification::create(['product_id'=>$product->id,'spec_key'=>'multi_gpu','spec_name'=>'Поддержка Multi-GPU','spec_value'=>'AMD CrossFireX','group'=>'Разъемы','is_filterable'=>false]);
ProductSpecification::create(['product_id'=>$product->id,'spec_key'=>'lan','spec_name'=>'Сетевой адаптер','spec_value'=>'2.5 Гбит/с','group'=>'Интерфейсы','is_filterable'=>true]);
ProductSpecification::create(['product_id'=>$product->id,'spec_key'=>'lan_chip','spec_name'=>'Сетевой контроллер','spec_value'=>'Realtek 2.5Gb Ethernet','group'=>'Интерфейсы','is_filterable'=>false]);
ProductSpecification::create(['product_id'=>$product->id,'spec_key'=>'usb_ports','spec_name'=>'Порты USB','spec_value'=>'USB 3.2 Gen2x2 Type-C, USB 3.2 Gen2 Type-A, USB 2.0','group'=>'Интерфейсы','is_filterable'=>false]);
ProductSpecification::create(['product_id'=>$product->id,'spec_key'=>'display_outputs','spec_name'=>'Видеовыходы','spec_value'=>'HDMI, DisplayPort','group'=>'Интерфейсы','is_filterable'=>false]);
ProductSpecification::create(['product_id'=>$product->id,'spec_key'=>'audio_codec','spec_name'=>'Аудио кодек','spec_value'=>'Realtek S1200A, 7.1-канальный звук','group'=>'Интерфейсы','is_filterable'=>false]);
ProductSpecification::create(['product_id'=>$product->id,'spec_key'=>'audio_ports','spec_name'=>'Аудио разъемы','spec_value'=>'5 x 3.5 мм, 1 x S/PDIF','group'=>'Интерфейсы','is_filterable'=>false]);
ProductSpecification::create(['product_id'=>$product->id,'spec_key'=>'cooling','spec_name'=>'Охлаждение','spec_value'=>'Крупные радиаторы','group'=>'Особенности','is_filterable'=>false]);
ProductSpecification::create(['product_id'=>$product->id,'spec_key'=>'color','spec_name'=>'Цвет','spec_value'=>'Черно-желтый','group'=>'Особенности','is_filterable'=>false]);
ProductSpecification::create(['product_id'=>$product->id,'spec_key'=>'rgb','spec_name'=>'RGB-подсветка','spec_value'=>'Да, программируемая','group'=>'Особенности','is_filterable'=>false]);
ProductSpecification::create(['product_id'=>$product->id,'spec_key'=>'features','spec_name'=>'Особенности','spec_value'=>'TUF Protection, поддержка Aura Sync, усиленные слоты PCIe','group'=>'Особенности','is_filterable'=>false]);

            // Материнская плата MSI PRO B650-A WIFI
            $product = Product::create([
                'title' => 'Материнская плата MSI PRO B650-A WIFI',
                'price' => 13000, // Условная цена
                'old_price' => null,
                'discount_percent' => 0,
                'in_stock' => true,
                'on_sale' => false,
                'is_bestseller' => false,
                'is_new' => true,
                'credit_available' => true,
                'img' => 'Материнская плата MSI PRO B650-A WIFI.webp', // Предполагаемое имя файла изображения
                'product_type' => 'motherboard',
                'country' => 'Китай', // Условная страна
                'color' => 'Черный', // Условный цвет
                'qty' => 10,
                'description' => 'Материнская плата MSI PRO B650-A WIFI поддерживает установку процессора AMD Ryzen серий 9000/8000/7000. Благодаря системе охлаждения с увеличенным радиатором и усиленной конструкции питания она подходит для сборки высокопроизводительной рабочей станции. Под размещение модулей оперативной памяти предлагается 4 слота DIMM стандарта DDR5. Для устройств хранения предусмотрены 6 разъемов SATA и 3 порта M.2.\nМатеринская плата MSI PRO B650-A выполнена в форм-факторе Standard-ATX. Стабильное соединение с Интернетом проводным и беспроводным способами обеспечивается адаптером 2.5G Ethernet и модулем Wi-Fi 6E. Печатная 6-слойная плата с увеличенным содержанием меди устойчива к нагреву, окислению, коррозии. Радиаторы и тепловые подкладки поддерживают низкую температуру комплектующих под разной вычислительной нагрузкой.',
                'category_id' => $motherboardCategory->id,
                'rating' => 0.0, // Условный рейтинг
            ]);

            $product->images()->createMany([
                ['image_path' => 'Материнская плата MSI PRO B650-A WIFI.webp'],
                ['image_path' => 'Материнская плата MSI PRO B650-A WIFI1.webp'], // Assuming this file exists
                ['image_path' => 'Материнская плата MSI PRO B650-A WIFI2.webp'], // Assuming this file exists
            ]);

            // Добавляем спецификации для MSI PRO B650-A WIFI
            ProductSpecification::create(['product_id'=>$product->id,'spec_key'=>'brand','spec_name'=>'Бренд','spec_value'=>'MSI','group'=>'Основные','is_filterable'=>true]);
            ProductSpecification::create(['product_id'=>$product->id,'spec_key'=>'socket','spec_name'=>'Сокет','spec_value'=>'AM5','group'=>'Основные','is_filterable'=>true]);
            ProductSpecification::create(['product_id'=>$product->id,'spec_key'=>'compatible_processors','spec_name'=>'Совместимые процессоры','spec_value'=>'AMD Ryzen 9000/8000/7000','group'=>'Основные','is_filterable'=>false]);
            ProductSpecification::create(['product_id'=>$product->id,'spec_key'=>'memory_type','spec_name'=>'Тип памяти','spec_value'=>'DDR5','group'=>'Память','is_filterable'=>true]);
            ProductSpecification::create(['product_id'=>$product->id,'spec_key'=>'memory_slots','spec_name'=>'Слотов памяти','spec_value'=>'4','group'=>'Память','is_filterable'=>true]);
            ProductSpecification::create(['product_id'=>$product->id,'spec_key'=>'sata_ports','spec_name'=>'Портов SATA','spec_value'=>'6','group'=>'Разъемы','is_filterable'=>false]);
            ProductSpecification::create(['product_id'=>$product->id,'spec_key'=>'m2_slots','spec_name'=>'Слотов M.2','spec_value'=>'3','group'=>'Разъемы','is_filterable'=>true]);
            ProductSpecification::create(['product_id'=>$product->id,'spec_key'=>'form_factor','spec_name'=>'Форм-фактор','spec_value'=>'Standard-ATX','group'=>'Основные','is_filterable'=>true]);
            ProductSpecification::create(['product_id'=>$product->id,'spec_key'=>'lan','spec_name'=>'Сетевой адаптер','spec_value'=>'2.5G Ethernet','group'=>'Интерфейсы','is_filterable'=>false]);
            ProductSpecification::create(['product_id'=>$product->id,'spec_key'=>'wifi','spec_name'=>'Wi-Fi','spec_value'=>'Wi-Fi 6E','group'=>'Интерфейсы','is_filterable'=>true]);
            ProductSpecification::create(['product_id'=>$product->id,'spec_key'=>'pcb_layers','spec_name'=>'Слои PCB','spec_value'=>'6','group'=>'Основные','is_filterable'=>false]);


        }
        
        // Создаем несколько тестовых товаров для категории блоков питания
        if($powerSupplyCategory) {
            $product = Product::create([
                'title' => 'ExeGate AAA400',
                'price' => 999,
                'old_price' => null,
                'discount_percent' => 0,
                'in_stock' => true,
                'on_sale' => false,
                'is_bestseller' => false,
                'credit_available' => true,
                'img' => 'ExeGate AAA400.webp',
                'product_type' => 'power_supply',
                'country' => 'Китай',
                'color' => 'Серый',
                'qty' => 15,
                'description' => 'Блок питания ExeGate AAA предназначен для электропитания систем начального уровня. Благодаря длине силового кабеля устройство подходит для использования в корпусах с нижним расположением БП. Модель собрана из компонентов высокого качества и отличаются стабильной и надежной работой.',
                'category_id' => $powerSupplyCategory->id,
                'rating' => 4.5
            ]);
            $product->images()->createMany([
                ['image_path' => 'ExeGate AAA400.webp'],
                ['image_path' => 'ExeGate AAA4001.webp'],
            ]);
            ProductSpecification::create(['product_id'=>$product->id,'spec_key'=>'brand','spec_name'=>'Бренд','spec_value'=>'ExeGate','group'=>'Основные','is_filterable'=>true]);
            ProductSpecification::create(['product_id'=>$product->id,'spec_key'=>'wattage','spec_name'=>'Мощность','spec_value'=>'400 Вт','group'=>'Основные','is_filterable'=>true]);
            ProductSpecification::create(['product_id'=>$product->id,'spec_key'=>'form_factor','spec_name'=>'Форм-фактор','spec_value'=>'ATX','group'=>'Основные','is_filterable'=>true]);
            ProductSpecification::create(['product_id'=>$product->id,'spec_key'=>'certificate','spec_name'=>'Сертификат','spec_value'=>'Нет','group'=>'Основные','is_filterable'=>true]);
            ProductSpecification::create(['product_id'=>$product->id,'spec_key'=>'pfc','spec_name'=>'PFC','spec_value'=>'Нет','group'=>'Характеристики','is_filterable'=>false]);
            ProductSpecification::create(['product_id'=>$product->id,'spec_key'=>'fan_size','spec_name'=>'Размер вентилятора','spec_value'=>'80 мм','group'=>'Охлаждение','is_filterable'=>false]);
            ProductSpecification::create(['product_id'=>$product->id,'spec_key'=>'noise_level','spec_name'=>'Уровень шума','spec_value'=>'26 дБ','group'=>'Характеристики','is_filterable'=>false]);
            ProductSpecification::create(['product_id'=>$product->id,'spec_key'=>'warranty','spec_name'=>'Гарантия','spec_value'=>'12 мес.','group'=>'Особенности','is_filterable'=>false]);
            ProductSpecification::create(['product_id'=>$product->id,'spec_key'=>'color','spec_name'=>'Цвет','spec_value'=>'Серый','group'=>'Внешний вид','is_filterable'=>true]);
            
            $product = Product::create([
                'title' => 'Accord ACC-W400P',
                'price' => 2150,
                'old_price' => null,
                'discount_percent' => 0,
                'in_stock' => true,
                'on_sale' => false,
                'is_bestseller' => false,
                'credit_available' => true,
                'img' => 'Accord ACC-W400P.webp',
                'product_type' => 'power_supply',
                'country' => 'Китай',
                'color' => 'Черный',
                'qty' => 20,
                'description' => 'Блок питания Accord ACC-W400P представляет собой надежный источник питания для персонального компьютера. Этот блок питания имеет сертификацию 80 PLUS WHITE, что гарантирует его энергоэффективность. Он оснащен стандартным 20+4-пиновым разъемом для подключения к материнской плате, обеспечивая совместимость с широким спектром систем.',
                'category_id' => $powerSupplyCategory->id,
                'rating' => 4.6
            ]);
            $product->images()->createMany([
                ['image_path' => 'Accord ACC-W400P.webp'],
                ['image_path' => 'Accord ACC-W400P1.webp'],
                ['image_path' => 'Accord ACC-W400P2.webp'],
            ]);
            ProductSpecification::create(['product_id'=>$product->id,'spec_key'=>'brand','spec_name'=>'Бренд','spec_value'=>'Accord','group'=>'Основные','is_filterable'=>true]);
            ProductSpecification::create(['product_id'=>$product->id,'spec_key'=>'wattage','spec_name'=>'Мощность','spec_value'=>'400 Вт','group'=>'Основные','is_filterable'=>true]);
            ProductSpecification::create(['product_id'=>$product->id,'spec_key'=>'form_factor','spec_name'=>'Форм-фактор','spec_value'=>'ATX','group'=>'Основные','is_filterable'=>true]);
            ProductSpecification::create(['product_id'=>$product->id,'spec_key'=>'certificate','spec_name'=>'Сертификат','spec_value'=>'80 PLUS White','group'=>'Основные','is_filterable'=>true]);
            ProductSpecification::create(['product_id'=>$product->id,'spec_key'=>'warranty','spec_name'=>'Гарантия','spec_value'=>'36 мес.','group'=>'Особенности','is_filterable'=>false]);
            ProductSpecification::create(['product_id'=>$product->id,'spec_key'=>'color','spec_name'=>'Цвет','spec_value'=>'Черный','group'=>'Внешний вид','is_filterable'=>true]);
            
            $product = Product::create([
                'title' => 'AeroCool ECO 400W',
                'price' => 2200,
                'old_price' => null,
                'discount_percent' => 0,
                'in_stock' => true,
                'on_sale' => false,
                'is_bestseller' => false,
                'credit_available' => true,
                'img' => 'AeroCool ECO 400W.webp',
                'product_type' => 'power_supply',
                'country' => 'Китай',
                'color' => 'Серый',
                'qty' => 18,
                'description' => '400-ваттный блок питания Aerocool ECO 400W представляет из себя надежное устройство, призванное решить задачу комплектации источником питания компьютера начального или среднего уровня. Модель соответствует форм-фактору ATX и имеет типовые габаритные размеры: 140x150x86 мм.',
                'category_id' => $powerSupplyCategory->id,
                'rating' => 4.7
            ]);
            $product->images()->createMany([
                ['image_path' => 'AeroCool ECO 400W.webp'],
                ['image_path' => 'AeroCool ECO 400W1.webp'],
                ['image_path' => 'AeroCool ECO 400W2.webp'],
            ]);
            ProductSpecification::create(['product_id'=>$product->id,'spec_key'=>'brand','spec_name'=>'Бренд','spec_value'=>'AeroCool','group'=>'Основные','is_filterable'=>true]);
            ProductSpecification::create(['product_id'=>$product->id,'spec_key'=>'wattage','spec_name'=>'Мощность','spec_value'=>'400 Вт','group'=>'Основные','is_filterable'=>true]);
            ProductSpecification::create(['product_id'=>$product->id,'spec_key'=>'form_factor','spec_name'=>'Форм-фактор','spec_value'=>'ATX','group'=>'Основные','is_filterable'=>true]);
            ProductSpecification::create(['product_id'=>$product->id,'spec_key'=>'fan_size','spec_name'=>'Размер вентилятора','spec_value'=>'120 мм','group'=>'Охлаждение','is_filterable'=>false]);
            ProductSpecification::create(['product_id'=>$product->id,'spec_key'=>'warranty','spec_name'=>'Гарантия','spec_value'=>'24 мес.','group'=>'Особенности','is_filterable'=>false]);
            ProductSpecification::create(['product_id'=>$product->id,'spec_key'=>'color','spec_name'=>'Цвет','spec_value'=>'Серый','group'=>'Внешний вид','is_filterable'=>true]);
            
            $product = Product::create([
                'title' => 'Formula V Line FX-450',
                'price' => 2300,
                'old_price' => null,
                'discount_percent' => 0,
                'in_stock' => true,
                'on_sale' => false,
                'is_bestseller' => false,
                'credit_available' => true,
                'img' => 'Formula V Line FX-450.webp',
                'product_type' => 'power_supply',
                'country' => 'Китай',
                'color' => 'Черный',
                'qty' => 12,
                'description' => 'Блок питания Formula-V Line предназначен для электропитания систем высокого уровня. Модель собрана из компонентов высокого качества и отличаются стабильной и надежной работой.',
                'category_id' => $powerSupplyCategory->id,
                'rating' => 4.8
            ]);
            $product->images()->createMany([
                ['image_path' => 'Formula V Line FX-450.webp'],
                ['image_path' => 'Formula V Line FX-4501.webp'],
                ['image_path' => 'Formula V Line FX-4502.webp'],
            ]);
            ProductSpecification::create(['product_id'=>$product->id,'spec_key'=>'brand','spec_name'=>'Бренд','spec_value'=>'Formula','group'=>'Основные','is_filterable'=>true]);
            ProductSpecification::create(['product_id'=>$product->id,'spec_key'=>'wattage','spec_name'=>'Мощность','spec_value'=>'450 Вт','group'=>'Основные','is_filterable'=>true]);
            ProductSpecification::create(['product_id'=>$product->id,'spec_key'=>'form_factor','spec_name'=>'Форм-фактор','spec_value'=>'ATX','group'=>'Основные','is_filterable'=>true]);
            ProductSpecification::create(['product_id'=>$product->id,'spec_key'=>'pfc','spec_name'=>'PFC','spec_value'=>'Активный','group'=>'Характеристики','is_filterable'=>false]);
            ProductSpecification::create(['product_id'=>$product->id,'spec_key'=>'fan_size','spec_name'=>'Размер вентилятора','spec_value'=>'120 мм','group'=>'Охлаждение','is_filterable'=>false]);
            ProductSpecification::create(['product_id'=>$product->id,'spec_key'=>'warranty','spec_name'=>'Гарантия','spec_value'=>'24 мес.','group'=>'Особенности','is_filterable'=>false]);
            ProductSpecification::create(['product_id'=>$product->id,'spec_key'=>'color','spec_name'=>'Цвет','spec_value'=>'Черный','group'=>'Внешний вид','is_filterable'=>true]);
            
            $product = Product::create([
                'title' => 'ACD SF0250',
                'price' => 2499,
                'old_price' => null,
                'discount_percent' => 0,
                'in_stock' => true,
                'on_sale' => false,
                'is_bestseller' => false,
                'credit_available' => true,
                'img' => 'ACD SF0250.webp',
                'product_type' => 'power_supply',
                'country' => 'Китай',
                'color' => 'Серый',
                'qty' => 10,
                'description' => 'ACD SF0250 - это блок питания мечты, разработанный для обеспечения максимальной мощности, производительности и эффективности для всех любителей компактных сборок.',
                'category_id' => $powerSupplyCategory->id,
                'rating' => 4.6
            ]);
            $product->images()->createMany([
                ['image_path' => 'ACD SF0250.webp'],
            ]);
            ProductSpecification::create(['product_id'=>$product->id,'spec_key'=>'brand','spec_name'=>'Бренд','spec_value'=>'ACD','group'=>'Основные','is_filterable'=>true]);
            ProductSpecification::create(['product_id'=>$product->id,'spec_key'=>'wattage','spec_name'=>'Мощность','spec_value'=>'250 Вт','group'=>'Основные','is_filterable'=>true]);
            ProductSpecification::create(['product_id'=>$product->id,'spec_key'=>'form_factor','spec_name'=>'Форм-фактор','spec_value'=>'SFX','group'=>'Основные','is_filterable'=>true]);
            ProductSpecification::create(['product_id'=>$product->id,'spec_key'=>'certificate','spec_name'=>'Сертификат','spec_value'=>'80 PLUS Standard','group'=>'Основные','is_filterable'=>true]);
            ProductSpecification::create(['product_id'=>$product->id,'spec_key'=>'pfc','spec_name'=>'PFC','spec_value'=>'Активный','group'=>'Характеристики','is_filterable'=>false]);
            ProductSpecification::create(['product_id'=>$product->id,'spec_key'=>'fan_size','spec_name'=>'Размер вентилятора','spec_value'=>'80 мм','group'=>'Охлаждение','is_filterable'=>false]);
            ProductSpecification::create(['product_id'=>$product->id,'spec_key'=>'warranty','spec_name'=>'Гарантия','spec_value'=>'36 мес.','group'=>'Особенности','is_filterable'=>false]);
            ProductSpecification::create(['product_id'=>$product->id,'spec_key'=>'color','spec_name'=>'Цвет','spec_value'=>'Серый','group'=>'Внешний вид','is_filterable'=>true]);
            
            $product = Product::create([
                'title' => 'Oсypus Beta P400',
                'price' => 2500,
                'old_price' => null,
                'discount_percent' => 0,
                'in_stock' => true,
                'on_sale' => false,
                'is_bestseller' => false,
                'credit_available' => true,
                'img' => 'Oсypus Beta P400.webp',
                'product_type' => 'power_supply',
                'country' => 'Китай',
                'color' => 'Черный',
                'qty' => 15,
                'description' => 'Блок питания Oсypus Beta P400 – устройство с общей максимальной мощностью 400 Ватт, предназначенное для обеспечения стабильной работы в компьютерах, не предъявляющих повышенных требований к источнику, например, в офисных ПК, рабочих станциях начального уровня.',
                'category_id' => $powerSupplyCategory->id,
                'rating' => 4.7
            ]);
            $product->images()->createMany([
                ['image_path' => 'Oсypus Beta P400.webp'],
                ['image_path' => 'Oсypus Beta P4001.webp'],
                ['image_path' => 'Oсypus Beta P4002.webp'],
            ]);
            ProductSpecification::create(['product_id'=>$product->id,'spec_key'=>'brand','spec_name'=>'Бренд','spec_value'=>'Oсypus','group'=>'Основные','is_filterable'=>true]);
            ProductSpecification::create(['product_id'=>$product->id,'spec_key'=>'wattage','spec_name'=>'Мощность','spec_value'=>'400 Вт','group'=>'Основные','is_filterable'=>true]);
            ProductSpecification::create(['product_id'=>$product->id,'spec_key'=>'form_factor','spec_name'=>'Форм-фактор','spec_value'=>'ATX','group'=>'Основные','is_filterable'=>true]);
            ProductSpecification::create(['product_id'=>$product->id,'spec_key'=>'pfc','spec_name'=>'PFC','spec_value'=>'Активный','group'=>'Характеристики','is_filterable'=>false]);
            ProductSpecification::create(['product_id'=>$product->id,'spec_key'=>'fan_size','spec_name'=>'Размер вентилятора','spec_value'=>'120 мм','group'=>'Охлаждение','is_filterable'=>false]);
            ProductSpecification::create(['product_id'=>$product->id,'spec_key'=>'warranty','spec_name'=>'Гарантия','spec_value'=>'36 мес.','group'=>'Особенности','is_filterable'=>false]);
            ProductSpecification::create(['product_id'=>$product->id,'spec_key'=>'color','spec_name'=>'Цвет','spec_value'=>'Черный','group'=>'Внешний вид','is_filterable'=>true]);
            
            $product = Product::create([
                'title' => 'DEEPCOOL PF350',
                'price' => 2400,
                'old_price' => null,
                'discount_percent' => 0,
                'in_stock' => true,
                'on_sale' => false,
                'is_bestseller' => false,
                'credit_available' => true,
                'img' => 'DEEPCOOL PF350.webp',
                'product_type' => 'power_supply',
                'country' => 'Китай',
                'color' => 'Черный',
                'qty' => 20,
                'description' => 'Блок питания DEEPCOOL PF350 с общей выходной мощностью по всем линиям 350 Вт сможет обеспечить стабильное питание компонентам системного блока, рассчитанного на работу с мультимедийными файлами или офисными документами.',
                'category_id' => $powerSupplyCategory->id,
                'rating' => 4.8
            ]);
            $product->images()->createMany([
                ['image_path' => 'DEEPCOOL PF350.webp'],
                ['image_path' => 'DEEPCOOL PF3501.webp'],
                ['image_path' => 'DEEPCOOL PF3502.webp'],
            ]);
            ProductSpecification::create(['product_id'=>$product->id,'spec_key'=>'brand','spec_name'=>'Бренд','spec_value'=>'DEEPCOOL','group'=>'Основные','is_filterable'=>true]);
            ProductSpecification::create(['product_id'=>$product->id,'spec_key'=>'wattage','spec_name'=>'Мощность','spec_value'=>'350 Вт','group'=>'Основные','is_filterable'=>true]);
            ProductSpecification::create(['product_id'=>$product->id,'spec_key'=>'form_factor','spec_name'=>'Форм-фактор','spec_value'=>'ATX','group'=>'Основные','is_filterable'=>true]);
            ProductSpecification::create(['product_id'=>$product->id,'spec_key'=>'certificate','spec_name'=>'Сертификат','spec_value'=>'80 PLUS Standard','group'=>'Основные','is_filterable'=>true]);
            ProductSpecification::create(['product_id'=>$product->id,'spec_key'=>'pfc','spec_name'=>'PFC','spec_value'=>'Активный','group'=>'Характеристики','is_filterable'=>false]);
            ProductSpecification::create(['product_id'=>$product->id,'spec_key'=>'fan_size','spec_name'=>'Размер вентилятора','spec_value'=>'120 мм','group'=>'Охлаждение','is_filterable'=>false]);
            ProductSpecification::create(['product_id'=>$product->id,'spec_key'=>'warranty','spec_name'=>'Гарантия','spec_value'=>'36 мес.','group'=>'Особенности','is_filterable'=>false]);
            ProductSpecification::create(['product_id'=>$product->id,'spec_key'=>'color','spec_name'=>'Цвет','spec_value'=>'Черный','group'=>'Внешний вид','is_filterable'=>true]);
            
            $product = Product::create([
                'title' => 'POWERMAN 500W',
                'price' => 2600,
                'old_price' => null,
                'discount_percent' => 0,
                'in_stock' => true,
                'on_sale' => false,
                'is_bestseller' => false,
                'credit_available' => true,
                'img' => 'POWERMAN 500W.webp',
                'product_type' => 'power_supply',
                'country' => 'Китай',
                'color' => 'Серый',
                'qty' => 15,
                'description' => 'Блок питания InWin Powerman 500W – качественное изделие. При его производстве использовались надежные компоненты. Исключается возможность возникновения дефектов. БП применяется для офисных компьютеров. Он подойдет для использования в игровых системах со средними параметрами энергопотребления.',
                'category_id' => $powerSupplyCategory->id,
                'rating' => 4.7
            ]);
            $product->images()->createMany([
                ['image_path' => 'POWERMAN 500W.webp'],
                ['image_path' => 'POWERMAN 500W1.webp'],
                ['image_path' => 'POWERMAN 500W2.webp'],
            ]);
            ProductSpecification::create(['product_id'=>$product->id,'spec_key'=>'brand','spec_name'=>'Бренд','spec_value'=>'POWERMAN','group'=>'Основные','is_filterable'=>true]);
            ProductSpecification::create(['product_id'=>$product->id,'spec_key'=>'wattage','spec_name'=>'Мощность','spec_value'=>'500 Вт','group'=>'Основные','is_filterable'=>true]);
            ProductSpecification::create(['product_id'=>$product->id,'spec_key'=>'form_factor','spec_name'=>'Форм-фактор','spec_value'=>'ATX','group'=>'Основные','is_filterable'=>true]);
            ProductSpecification::create(['product_id'=>$product->id,'spec_key'=>'fan_size','spec_name'=>'Размер вентилятора','spec_value'=>'120 мм','group'=>'Охлаждение','is_filterable'=>false]);
            ProductSpecification::create(['product_id'=>$product->id,'spec_key'=>'warranty','spec_name'=>'Гарантия','spec_value'=>'12 мес.','group'=>'Особенности','is_filterable'=>false]);
            ProductSpecification::create(['product_id'=>$product->id,'spec_key'=>'color','spec_name'=>'Цвет','spec_value'=>'Серый','group'=>'Внешний вид','is_filterable'=>true]);
            
            $product = Product::create([
                'title' => 'Accord ACC-450W-12',
                'price' => 2499,
                'old_price' => null,
                'discount_percent' => 0,
                'in_stock' => true,
                'on_sale' => false,
                'is_bestseller' => false,
                'credit_available' => true,
                'img' => 'Accord ACC-450W-12 [ACC-450W-12].webp',
                'product_type' => 'power_supply',
                'country' => 'Китай',
                'color' => 'Серый',
                'qty' => 18,
                'description' => 'Для эффективного питания всех элементов вашего ПК используйте блок питания Accord 450W, способный конвертировать высокое напряжение в необходимое компьютеру для его полноценной работы.',
                'category_id' => $powerSupplyCategory->id,
                'rating' => 4.6
            ]);
            $product->images()->createMany([
                ['image_path' => 'Accord ACC-450W-12 [ACC-450W-12].webp'],
                ['image_path' => 'Accord ACC-450W-12 [ACC-450W-12]1.webp'],
                ['image_path' => 'Accord ACC-450W-12 [ACC-450W-12]2.webp'],
            ]);
            ProductSpecification::create(['product_id'=>$product->id,'spec_key'=>'brand','spec_name'=>'Бренд','spec_value'=>'Accord','group'=>'Основные','is_filterable'=>true]);
            ProductSpecification::create(['product_id'=>$product->id,'spec_key'=>'wattage','spec_name'=>'Мощность','spec_value'=>'450 Вт','group'=>'Основные','is_filterable'=>true]);
            ProductSpecification::create(['product_id'=>$product->id,'spec_key'=>'form_factor','spec_name'=>'Форм-фактор','spec_value'=>'ATX','group'=>'Основные','is_filterable'=>true]);
            ProductSpecification::create(['product_id'=>$product->id,'spec_key'=>'certificate','spec_name'=>'Сертификат','spec_value'=>'80 PLUS Bronze','group'=>'Основные','is_filterable'=>true]);
            ProductSpecification::create(['product_id'=>$product->id,'spec_key'=>'pfc','spec_name'=>'PFC','spec_value'=>'Активный','group'=>'Характеристики','is_filterable'=>false]);
            ProductSpecification::create(['product_id'=>$product->id,'spec_key'=>'fan_size','spec_name'=>'Размер вентилятора','spec_value'=>'120 мм','group'=>'Охлаждение','is_filterable'=>false]);
            ProductSpecification::create(['product_id'=>$product->id,'spec_key'=>'warranty','spec_name'=>'Гарантия','spec_value'=>'12 мес.','group'=>'Особенности','is_filterable'=>false]);
            ProductSpecification::create(['product_id'=>$product->id,'spec_key'=>'color','spec_name'=>'Цвет','spec_value'=>'Серый','group'=>'Внешний вид','is_filterable'=>true]);
            
            $product = Product::create([
                'title' => 'KingPrice KPPSU500',
                'price' => 2700,
                'old_price' => null,
                'discount_percent' => 0,
                'in_stock' => true,
                'on_sale' => false,
                'is_bestseller' => false,
                'credit_available' => true,
                'img' => 'KingPrice KPPSU500.webp',
                'product_type' => 'power_supply',
                'country' => 'Китай',
                'color' => 'Черный',
                'qty' => 15,
                'description' => '500-ваттный блок питания KingPrice в черном корпусе ориентирован на оснащение универсальных компьютеров для дома или офиса. Мощность модели достаточна для бесперебойной работы процессора с несколькими ядрами, видеокарты со средним энергопотреблением, нескольких накопителей, плат расширения и других устройств.',
                'category_id' => $powerSupplyCategory->id,
                'rating' => 4.8
            ]);

            // Add images for KingPrice KPPSU500
            $product->images()->createMany([
                ['image_path' => 'KingPrice KPPSU500.webp'],
                ['image_path' => 'KingPrice KPPSU5001.webp'], // Assuming this file exists
                ['image_path' => 'KingPrice KPPSU5002.webp'], // Assuming this file exists
            ]);

            // Добавляю характеристики для KingPrice KPPSU500
            ProductSpecification::create(['product_id'=>$product->id,'spec_key'=>'brand','spec_name'=>'Бренд','spec_value'=>'KingPrice','group'=>'Основные','is_filterable'=>true]);
            ProductSpecification::create(['product_id'=>$product->id,'spec_key'=>'wattage','spec_name'=>'Мощность','spec_value'=>'500 Вт','group'=>'Основные','is_filterable'=>true]);
            ProductSpecification::create(['product_id'=>$product->id,'spec_key'=>'form_factor','spec_name'=>'Форм-фактор','spec_value'=>'ATX','group'=>'Основные','is_filterable'=>true]);
            ProductSpecification::create(['product_id'=>$product->id,'spec_key'=>'fan_size','spec_name'=>'Размер вентилятора','spec_value'=>'120 мм','group'=>'Охлаждение','is_filterable'=>false]);
            ProductSpecification::create(['product_id'=>$product->id,'spec_key'=>'warranty','spec_name'=>'Гарантия','spec_value'=>'24 мес.','group'=>'Особенности','is_filterable'=>false]);
            ProductSpecification::create(['product_id'=>$product->id,'spec_key'=>'color','spec_name'=>'Цвет','spec_value'=>'Черный','group'=>'Внешний вид','is_filterable'=>true]);

            // Product: KingPrice KPPSU600
            $product = Product::create([
                'title' => 'KingPrice KPPSU600',
                'price' => 2800,
                'old_price' => null,
                'discount_percent' => 0,
                'in_stock' => true,
                'on_sale' => false,
                'is_bestseller' => false,
                'credit_available' => true,
                'img' => 'KingPrice [KPPSU600].webp',
                'product_type' => 'power_supply',
                'country' => 'Китай',
                'color' => 'Черный',
                'qty' => 12,
                'description' => '600-ваттный блок питания KingPrice в черном корпусе ориентирован на оснащение универсальных компьютеров для дома или офиса. Мощность модели достаточна для бесперебойной работы процессора с несколькими ядрами, видеокарты со средним энергопотреблением, нескольких накопителей, плат расширения и других устройств.',
                'category_id' => $powerSupplyCategory->id,
                'rating' => 4.7
            ]);

            // Add images for KingPrice KPPSU600
            $product->images()->createMany([
                ['image_path' => 'KingPrice [KPPSU600].webp'],
                ['image_path' => 'KingPrice [KPPSU600]1.webp'], // Assuming this file exists
                ['image_path' => 'KingPrice [KPPSU600]2.webp'], // Assuming this file exists
            ]);

            // Добавляем спецификации для блока питания
            ProductSpecification::create(['product_id'=>$product->id,'spec_key'=>'brand','spec_name'=>'Бренд','spec_value'=>'KingPrice','group'=>'Основные','is_filterable'=>true]);
            ProductSpecification::create(['product_id'=>$product->id,'spec_key'=>'wattage','spec_name'=>'Мощность','spec_value'=>'600 Вт','group'=>'Основные','is_filterable'=>true]);
            ProductSpecification::create(['product_id'=>$product->id,'spec_key'=>'form_factor','spec_name'=>'Форм-фактор','spec_value'=>'ATX','group'=>'Основные','is_filterable'=>true]);
            ProductSpecification::create(['product_id'=>$product->id,'spec_key'=>'fan_size','spec_name'=>'Размер вентилятора','spec_value'=>'120 мм','group'=>'Охлаждение','is_filterable'=>false]);
            ProductSpecification::create(['product_id'=>$product->id,'spec_key'=>'warranty','spec_name'=>'Гарантия','spec_value'=>'24 мес.','group'=>'Особенности','is_filterable'=>false]);
            ProductSpecification::create(['product_id'=>$product->id,'spec_key'=>'color','spec_name'=>'Цвет','spec_value'=>'Черный','group'=>'Внешний вид','is_filterable'=>true]);
            
            $product = Product::create([
                'title' => 'Foxline 450W',
                'price' => 2800,
                'old_price' => null,
                'discount_percent' => 0,
                'in_stock' => true,
                'on_sale' => false,
                'is_bestseller' => false,
                'credit_available' => true,
                'img' => 'Foxline 450W.webp',
                'product_type' => 'power_supply',
                'country' => 'Китай',
                'color' => 'Серый',
                'qty' => 12,
                'description' => 'Блок питания Foxline 450W – качественное изделие, которое обеспечит все компоненты ПК необходимой электрической энергией. Он адаптирован для сборок с низким энергопотреблением.',
                'category_id' => $powerSupplyCategory->id,
                'rating' => 4.7
            ]);
            $product->images()->createMany([
                ['image_path' => 'Foxline 450W.webp', 'order' => 1],
                ['image_path' => 'Foxline 450W1.webp', 'order' => 2],
            ]);
            ProductSpecification::create(['product_id'=>$product->id,'spec_key'=>'brand','spec_name'=>'Бренд','spec_value'=>'Foxline','group'=>'Основные','is_filterable'=>true]);
            ProductSpecification::create(['product_id'=>$product->id,'spec_key'=>'wattage','spec_name'=>'Мощность','spec_value'=>'450 Вт','group'=>'Основные','is_filterable'=>true]);
            ProductSpecification::create(['product_id'=>$product->id,'spec_key'=>'form_factor','spec_name'=>'Форм-фактор','spec_value'=>'ATX','group'=>'Основные','is_filterable'=>true]);
            ProductSpecification::create(['product_id'=>$product->id,'spec_key'=>'certificate','spec_name'=>'Сертификат','spec_value'=>'80 PLUS Standard','group'=>'Основные','is_filterable'=>true]);
            ProductSpecification::create(['product_id'=>$product->id,'spec_key'=>'pfc','spec_name'=>'PFC','spec_value'=>'Активный','group'=>'Характеристики','is_filterable'=>false]);
            ProductSpecification::create(['product_id'=>$product->id,'spec_key'=>'fan_size','spec_name'=>'Размер вентилятора','spec_value'=>'120 мм','group'=>'Охлаждение','is_filterable'=>false]);
            ProductSpecification::create(['product_id'=>$product->id,'spec_key'=>'warranty','spec_name'=>'Гарантия','spec_value'=>'12 мес.','group'=>'Особенности','is_filterable'=>false]);
            ProductSpecification::create(['product_id'=>$product->id,'spec_key'=>'color','spec_name'=>'Цвет','spec_value'=>'Серый','group'=>'Внешний вид','is_filterable'=>true]);
        }
        
        //НАКОПИТЕЛИ

        // 500 ГБ 2.5" SATA накопитель Samsung 870 EVO [MZ-77E500BW]
        $product = Product::create([
            'title' => '500 ГБ 2.5" SATA накопитель Samsung 870 EVO [MZ-77E500BW]',
            'price' => 4500, // Условная цена
            'old_price' => null,
            'discount_percent' => 0,
            'in_stock' => true,
            'on_sale' => false,
            'is_bestseller' => false,
            'is_new' => true,
            'credit_available' => true,
            'img' => 'SATA накопитель Samsung 870 EVO.webp', // Предполагаемое имя файла изображения
            'product_type' => 'ssd', // Или как у вас обозначен тип для SSD
            'country' => 'Корея', // Условная страна для Samsung
            'color' => 'Черный', // Условный цвет
            'qty' => 15,
            'description' => 'SATA накопитель Samsung 870 EVO объемом 500 ГБ – вместительное и надежное устройство хранения с высокой производительностью. Он оснащен быстрым контроллером и флэш-памятью 3D NAND. Накопитель помогает повысить отзывчивость системы при обработке данных. В режиме последовательного чтения скорость достигает 560 Мбайт/с, что способствует более быстрой загрузке данных в процессе выполнения задач на ПК. Благодаря форм-фактору 2.5 дюйма накопитель подходит для установки в настольные и портативные компьютеры. Из преимуществ Samsung 870 EVO – аппаратное шифрование данных.',
            'category_id' => $storageCategory->id, // Убедитесь, что переменная $storageCategory определена и содержит ID категории накопителей
            'rating' => 0.0, // Условный рейтинг
        ]);

        $product->images()->createMany([
            ['image_path' => 'SATA накопитель Samsung 870 EVO.webp'],
            ['image_path' => 'SATA накопитель Samsung 870 EVO1.webp'], // Assuming this file exists
            ['image_path' => 'SATA накопитель Samsung 870 EVO2.webp'], // Assuming this file exists
        ]);

        // Добавляем спецификации для Samsung 870 EVO 500 ГБ
        ProductSpecification::create(['product_id'=>$product->id,'spec_key'=>'brand','spec_name'=>'Бренд','spec_value'=>'Samsung','group'=>'Основные','is_filterable'=>true]);
        ProductSpecification::create(['product_id'=>$product->id,'spec_key'=>'model','spec_name'=>'Модель','spec_value'=>'870 EVO','group'=>'Основные','is_filterable'=>false]);
        ProductSpecification::create(['product_id'=>$product->id,'spec_key'=>'capacity','spec_name'=>'Объем накопителя','spec_value'=>'500 ГБ','group'=>'Основные','is_filterable'=>true]);
        ProductSpecification::create(['product_id'=>$product->id,'spec_key'=>'form_factor','spec_name'=>'Форм-фактор','spec_value'=>'2.5"','group'=>'Основные','is_filterable'=>true]);
        ProductSpecification::create(['product_id'=>$product->id,'spec_key'=>'interface','spec_name'=>'Интерфейс','spec_value'=>'SATA III','group'=>'Основные','is_filterable'=>true]);
        ProductSpecification::create(['product_id'=>$product->id,'spec_key'=>'nand_type','spec_name'=>'Тип флэш-памяти','spec_value'=>'3D NAND','group'=>'Характеристики','is_filterable'=>false]);
        ProductSpecification::create(['product_id'=>$product->id,'spec_key'=>'controller','spec_name'=>'Контроллер','spec_value'=>'Samsung MKX','group'=>'Характеристики','is_filterable'=>false]); // Типичный контроллер для 870 EVO
        ProductSpecification::create(['product_id'=>$product->id,'spec_key'=>'read_speed','spec_name'=>'Скорость последовательного чтения','spec_value'=>'560 Мбайт/с','group'=>'Производительность','is_filterable'=>false]);
        ProductSpecification::create(['product_id'=>$product->id,'spec_key'=>'write_speed','spec_name'=>'Скорость последовательной записи','spec_value'=>'530 Мбайт/с','group'=>'Производительность','is_filterable'=>false]); // Типичная скорость записи для 870 EVO 500GB

                    // 480 ГБ 2.5" SATA накопитель Kingston A400 [SA400S37/480G]
                    $product = Product::create([
                        'title' => '480 ГБ 2.5" SATA накопитель Kingston A400 [SA400S37/480G]',
                        'price' => 3800, // Условная цена
                        'old_price' => null,
                        'discount_percent' => 0,
                        'in_stock' => true,
                        'on_sale' => false,
                        'is_bestseller' => false,
                        'is_new' => true,
                        'credit_available' => true,
                        'img' => 'SATA накопитель Kingston A400.webp', // Предполагаемое имя файла изображения
                        'product_type' => 'ssd', // Или как у вас обозначен тип для SSD
                        'country' => 'Тайвань', // Условная страна для Kingston
                        'color' => 'Черный', // Условный цвет
                        'qty' => 20,
                        'description' => 'SATA накопитель Kingston A400 объемом 480 ГБ станет надежным решением для хранения довольно большого количества различных файлов и повышения быстродействия аппаратной платформы ПК. Благодаря применению форм-фактора 2.5 дюйма и востребованному интерфейсу SATA это запоминающее устройство отличается широкой совместимостью как со стационарными компьютерами, так и ноутбуками. В режиме последовательного чтения скорость достигает 500 Мбайт/с, что способствует более быстрой загрузке и обработке информации. Kingston A400 выполнен в прочном ударостойком корпусе и отличается длительным ресурсом службы.',
                        'category_id' => $storageCategory->id, // Убедитесь, что переменная $storageCategory определена
                        'rating' => 0.0, // Условный рейтинг
                    ]);

                    $product->images()->createMany([
                        ['image_path' => 'SATA накопитель Kingston A400.webp'],
                        ['image_path' => 'SATA накопитель Kingston A4001.webp'], // Assuming this file exists
                    ]);
        
                    // Добавляем спецификации для Kingston A400 480 ГБ
                    ProductSpecification::create(['product_id'=>$product->id,'spec_key'=>'brand','spec_name'=>'Бренд','spec_value'=>'Kingston','group'=>'Основные','is_filterable'=>true]);
                    ProductSpecification::create(['product_id'=>$product->id,'spec_key'=>'model','spec_name'=>'Модель','spec_value'=>'A400','group'=>'Основные','is_filterable'=>false]);
                    ProductSpecification::create(['product_id'=>$product->id,'spec_key'=>'capacity','spec_name'=>'Объем накопителя','spec_value'=>'480 ГБ','group'=>'Основные','is_filterable'=>true]);
                    ProductSpecification::create(['product_id'=>$product->id,'spec_key'=>'form_factor','spec_name'=>'Форм-фактор','spec_value'=>'2.5"','group'=>'Основные','is_filterable'=>true]);
                    ProductSpecification::create(['product_id'=>$product->id,'spec_key'=>'interface','spec_name'=>'Интерфейс','spec_value'=>'SATA III','group'=>'Основные','is_filterable'=>true]);
                    ProductSpecification::create(['product_id'=>$product->id,'spec_key'=>'read_speed','spec_name'=>'Скорость последовательного чтения','spec_value'=>'500 Мбайт/с','group'=>'Производительность','is_filterable'=>false]);
                    // Скорость записи для A400 480ГБ обычно около 450 МБ/с, добавим для полноты
                    ProductSpecification::create(['product_id'=>$product->id,'spec_key'=>'write_speed','spec_name'=>'Скорость последовательной записи','spec_value'=>'450 Мбайт/с','group'=>'Производительность','is_filterable'=>false]);
                    ProductSpecification::create(['product_id'=>$product->id,'spec_key'=>'durability','spec_name'=>'Особенности','spec_value'=>'Ударостойкий корпус','group'=>'Особенности','is_filterable'=>false]);

            // 512 ГБ 2.5" SATA накопитель Apacer AS350 PANTHER [95.DB2E0.P100C]
            $product = Product::create([
                'title' => '512 ГБ 2.5" SATA накопитель Apacer AS350 PANTHER [95.DB2E0.P100C]',
                'price' => 4200, // Условная цена
                'old_price' => null,
                'discount_percent' => 0,
                'in_stock' => true,
                'on_sale' => false,
                'is_bestseller' => false,
                'is_new' => true,
                'credit_available' => true,
                'img' => 'SATA накопитель Apacer AS350 PANTHER.webp', // Предполагаемое имя файла изображения
                'product_type' => 'ssd', // Или как у вас обозначен тип для SSD
                'country' => 'Тайвань', // Условная страна для Apacer
                'color' => 'Черный', // Условный цвет (учитывая стилизацию под пантеру, скорее всего, темный)
                'qty' => 18,
                'description' => 'SSD-накопитель Apacer AS350 PANTHER [95.DB2E0.P100C] оправдывает свое название: корпус устройства стилизован соответствующим образом. Эффектный дизайн не является единственным достоинством этого 512-гигабайтного устройства. Накопитель не только отказоустойчив, но и обладает значительным (320 TBW) ресурсом. За работу устройства отвечает надежный контроллер Silicon Motion SM2258. Тип памяти – TLC 3D NAND. Накопитель Apacer AS350 PANTHER [95.DB2E0.P100C] характеризуется скоростными показателями, которые достаточны для эффективного функционирования в составе системы, рассчитанной на решение типовых задач. Максимальная скорость чтения сжатых данных равна 560 МБ/с. Соответствующий показатель для записи – 540 МБ/с. Накопитель соответствует 2.5-дюймовому форм-фактору. Тип интерфейса – SATA III. Устройство имеет стандартные габаритные размеры – 69.85x100x6.9 мм. Роль упаковки накопителя исполняет блистер из прозрачного пластика.',
                'category_id' => $storageCategory->id, // Убедитесь, что переменная $storageCategory определена
                'rating' => 0.0, // Условный рейтинг
            ]);
            $product->images()->createMany([
                ['image_path' => 'SATA накопитель Apacer AS350 PANTHER.webp'],
                ['image_path' => 'SATA накопитель Apacer AS350 PANTHER1.webp'], // Assuming this file exists
            ]);

            // Добавляем спецификации для Apacer AS350 PANTHER 512 ГБ
            ProductSpecification::create(['product_id'=>$product->id,'spec_key'=>'brand','spec_name'=>'Бренд','spec_value'=>'Apacer','group'=>'Основные','is_filterable'=>true]);
            ProductSpecification::create(['product_id'=>$product->id,'spec_key'=>'model','spec_name'=>'Модель','spec_value'=>'AS350 PANTHER','group'=>'Основные','is_filterable'=>false]);
            ProductSpecification::create(['product_id'=>$product->id,'spec_key'=>'capacity','spec_name'=>'Объем накопителя','spec_value'=>'512 ГБ','group'=>'Основные','is_filterable'=>true]);
            ProductSpecification::create(['product_id'=>$product->id,'spec_key'=>'form_factor','spec_name'=>'Форм-фактор','spec_value'=>'2.5"','group'=>'Основные','is_filterable'=>true]);
            ProductSpecification::create(['product_id'=>$product->id,'spec_key'=>'interface','spec_name'=>'Интерфейс','spec_value'=>'SATA III','group'=>'Основные','is_filterable'=>true]);
            ProductSpecification::create(['product_id'=>$product->id,'spec_key'=>'nand_type','spec_name'=>'Тип флэш-памяти','spec_value'=>'TLC 3D NAND','group'=>'Характеристики','is_filterable'=>false]);
            ProductSpecification::create(['product_id'=>$product->id,'spec_key'=>'controller','spec_name'=>'Контроллер','spec_value'=>'Silicon Motion SM2258','group'=>'Характеристики','is_filterable'=>false]);
            ProductSpecification::create(['product_id'=>$product->id,'spec_key'=>'read_speed','spec_name'=>'Скорость последовательного чтения','spec_value'=>'560 Мбайт/с','group'=>'Производительность','is_filterable'=>false]);
            ProductSpecification::create(['product_id'=>$product->id,'spec_key'=>'write_speed','spec_name'=>'Скорость последовательной записи','spec_value'=>'540 Мбайт/с','group'=>'Производительность','is_filterable'=>false]);
            ProductSpecification::create(['product_id'=>$product->id,'spec_key'=>'tbw','spec_name'=>'Ресурс записи (TBW)','spec_value'=>'320 TBW','group'=>'Надежность','is_filterable'=>false]);
            ProductSpecification::create(['product_id'=>$product->id,'spec_key'=>'dimensions','spec_name'=>'Размеры','spec_value'=>'69.85x100x6.9 мм','group'=>'Внешний вид','is_filterable'=>false]);
        
                        // 1000 ГБ M.2 NVMe накопитель ADATA XPG GAMMIX S11 Pro [AGAMMIXS11P-1TT-C]
                        $product = Product::create([
                            'title' => '1000 ГБ M.2 NVMe накопитель ADATA XPG GAMMIX S11 Pro [AGAMMIXS11P-1TT-C]',
                            'price' => 5799, // Цена из описания
                            'old_price' => null,
                            'discount_percent' => 0,
                            'in_stock' => true,
                            'on_sale' => false,
                            'is_bestseller' => true, // Указано "Хит продаж"
                            'is_new' => false, // Предполагаем, что это уже не новинка, если "Хит продаж"
                            'credit_available' => true,
                            'img' => 'накопитель ADATA XPG GAMMIX S11 Pro.webp', // Предполагаемое имя файла изображения
                            'product_type' => 'ssd', // Тип продукта
                            'country' => 'Тайвань', // Условная страна для ADATA
                            'color' => 'Черный', // Условный цвет (у него есть радиатор, обычно темный)
                            'qty' => 10, // Условное количество
                            'description' => 'SSD M.2 накопитель A-Data XPG GAMMIX S11 Pro [AGAMMIXS11P-1TT-C] станет отличным выбором для значительного повышения быстродействия вашего компьютера при выполнении самых разнообразных задач — будь то запуск игр, работа с файлами или монтаж видео. Благодаря используемым чипам памяти NAND с 3D-структурой и контроллеру Silicon Motion SM2262EN обработка файлов происходит со скоростью до 3500 МБ/с при чтении и 3000 МБ/с при записи, что обеспечит выдающуюся производительность и комфорт при работе. Также данная модель отличается длительным ресурсом, достигающим 640 TBW. SSD M.2 накопитель A-Data XPG GAMMIX S11 Pro выполнен в форм-факторе 2280, где 22 мм — это ширина, 80 мм — длина модуля. Модель отличается двусторонней компоновкой чипов памяти и имеет толщину 6.1 мм. Подключение осуществляется посредством интерфейса PCI-e 3.0 x4. Энергопотребление накопителя не превышает 0.33 Вт.',
                            'category_id' => $storageCategory->id, // Убедитесь, что переменная $storageCategory определена
                            'rating' => 4.85, // Рейтинг из описания
                        ]);

                        $product->images()->createMany([
                            ['image_path' => 'накопитель ADATA XPG GAMMIX S11 Pro.webp'],
                            ['image_path' => 'накопитель ADATA XPG GAMMIX S11 Pro1.webp'],
                            ['image_path' => 'накопитель ADATA XPG GAMMIX S11 Pro2.webp'], // Assuming this file exists
                        ]);
            
                        // Добавляем спецификации для ADATA XPG GAMMIX S11 Pro 1 ТБ
                        ProductSpecification::create(['product_id'=>$product->id,'spec_key'=>'brand','spec_name'=>'Бренд','spec_value'=>'ADATA','group'=>'Основные','is_filterable'=>true]);
                        ProductSpecification::create(['product_id'=>$product->id,'spec_key'=>'model','spec_name'=>'Серия/модель','spec_value'=>'XPG GAMMIX S11 Pro','group'=>'Основные','is_filterable'=>false]);
                        ProductSpecification::create(['product_id'=>$product->id,'spec_key'=>'capacity','spec_name'=>'Объем накопителя','spec_value'=>'1024 ГБ','group'=>'Основные','is_filterable'=>true]); // Указываем точный объем
                        ProductSpecification::create(['product_id'=>$product->id,'spec_key'=>'form_factor','spec_name'=>'Форм-фактор','spec_value'=>'M.2 2280','group'=>'Основные','is_filterable'=>true]);
                        ProductSpecification::create(['product_id'=>$product->id,'spec_key'=>'interface','spec_name'=>'Интерфейс','spec_value'=>'PCI-e 3.0 x4 (NVMe)','group'=>'Основные','is_filterable'=>true]);
                        ProductSpecification::create(['product_id'=>$product->id,'spec_key'=>'read_speed','spec_name'=>'Скорость последовательного чтения','spec_value'=>'3500 Мбайт/с','group'=>'Производительность','is_filterable'=>false]);
                        ProductSpecification::create(['product_id'=>$product->id,'spec_key'=>'write_speed','spec_name'=>'Скорость последовательной записи','spec_value'=>'3000 Мбайт/с','group'=>'Производительность','is_filterable'=>false]);
                        ProductSpecification::create(['product_id'=>$product->id,'spec_key'=>'nand_type','spec_name'=>'Тип флэш-памяти','spec_value'=>'3D TLC NAND','group'=>'Характеристики','is_filterable'=>false]);
                        ProductSpecification::create(['product_id'=>$product->id,'spec_key'=>'controller','spec_name'=>'Контроллер','spec_value'=>'Silicon Motion SM2262EN','group'=>'Характеристики','is_filterable'=>false]);
                        ProductSpecification::create(['product_id'=>$product->id,'spec_key'=>'tbw','spec_name'=>'Ресурс записи (TBW)','spec_value'=>'640 ТБ','group'=>'Надежность','is_filterable'=>false]);
                        ProductSpecification::create(['product_id'=>$product->id,'spec_key'=>'thickness','spec_name'=>'Толщина','spec_value'=>'6.1 мм','group'=>'Внешний вид','is_filterable'=>false]);
                        ProductSpecification::create(['product_id'=>$product->id,'spec_key'=>'power_consumption','spec_name'=>'Потребляемая мощность','spec_value'=>'0.33 Вт','group'=>'Энергопотребление','is_filterable'=>false]);
                        ProductSpecification::create(['product_id'=>$product->id,'spec_key'=>'features','spec_name'=>'Особенности','spec_value'=>'Радиатор охлаждения','group'=>'Особенности','is_filterable'=>false]); // Добавим про радиатор

                                    // 1000 ГБ M.2 NVMe накопитель Samsung 990 PRO [MZ-V9P1T0BW]
            $product = Product::create([
                'title' => '1000 ГБ M.2 NVMe накопитель Samsung 990 PRO [MZ-V9P1T0BW]',
                'price' => 11499, // Цена из описания
                'old_price' => null,
                'discount_percent' => 0,
                'in_stock' => true,
                'on_sale' => false,
                'is_bestseller' => false, // Нет информации, что это хит продаж
                'is_new' => true, // Предположим, что это относительно новая модель, хотя 990 PRO уже на рынке какое-то время
                'credit_available' => true,
                'img' => 'накопитель Samsung 990 PRO.webp', // Предполагаемое имя файла изображения
                'product_type' => 'ssd', // Тип продукта
                'country' => 'Корея', // Условная страна для Samsung
                'color' => 'Черный', // Условный цвет (обычно черная плата)
                'qty' => 15, // Условное количество
                'description' => 'SSD M.2 накопитель Samsung 990 PRO [MZ-V9P1T0BW] с интерфейсом PCIe 4.0 обеспечивает высокую производительность в требовательных программах для редактирования и обработки графики. В режиме последовательного чтения скорость составляет 7450 Мбайт/сек. Функция SLC-кеширования Turbo Write 2.0 отвечает за ускорение операций записи. Накопитель Samsung 990 PRO подключается к материнской плате с помощью разъема M.2 и предоставляет 1000 ГБ свободного пространства для хранения файлов. Технология интеллектуального контроля тепла гарантирует стабильную работу и экономичное потребление энергии. Сервисная утилита Magician позволяет переключаться между профилями производительности и контролировать состояние устройства.',
                'category_id' => $storageCategory->id, // Убедитесь, что переменная $storageCategory определена
                'rating' => 0.0, // Условный рейтинг
            ]);
            $product->images()->createMany([
                ['image_path' => 'накопитель Samsung 990 PRO.webp'],
                ['image_path' => 'накопитель Samsung 990 PRO1.webp'],
                ['image_path' => 'накопитель Samsung 990 PRO2.webp'], // Assuming this file exists
            ]);

            // Добавляем спецификации для Samsung 990 PRO 1 ТБ
            ProductSpecification::create(['product_id'=>$product->id,'spec_key'=>'brand','spec_name'=>'Бренд','spec_value'=>'Samsung','group'=>'Основные','is_filterable'=>true]);
            ProductSpecification::create(['product_id'=>$product->id,'spec_key'=>'model','spec_name'=>'Модель','spec_value'=>'990 PRO','group'=>'Основные','is_filterable'=>false]);
            ProductSpecification::create(['product_id'=>$product->id,'spec_key'=>'capacity','spec_name'=>'Объем накопителя','spec_value'=>'1000 ГБ','group'=>'Основные','is_filterable'=>true]);
            ProductSpecification::create(['product_id'=>$product->id,'spec_key'=>'form_factor','spec_name'=>'Форм-фактор','spec_value'=>'M.2 2280','group'=>'Основные','is_filterable'=>true]);
            ProductSpecification::create(['product_id'=>$product->id,'spec_key'=>'interface','spec_name'=>'Интерфейс','spec_value'=>'PCIe 4.0 x4 (NVMe)','group'=>'Основные','is_filterable'=>true]);
            ProductSpecification::create(['product_id'=>$product->id,'spec_key'=>'read_speed','spec_name'=>'Скорость последовательного чтения','spec_value'=>'7450 Мбайт/с','group'=>'Производительность','is_filterable'=>false]);
            ProductSpecification::create(['product_id'=>$product->id,'spec_key'=>'write_speed','spec_name'=>'Скорость последовательной записи','spec_value'=>'6900 Мбайт/с','group'=>'Производительность','is_filterable'=>false]); // Типичная скорость записи для 990 PRO 1TB
            ProductSpecification::create(['product_id'=>$product->id,'spec_key'=>'nand_type','spec_name'=>'Тип флэш-памяти','spec_value'=>'V-NAND 3-bit MLC (TLC)','group'=>'Характеристики','is_filterable'=>false]); // Более точное название для самсунг V-NAND TLC
            ProductSpecification::create(['product_id'=>$product->id,'spec_key'=>'controller','spec_name'=>'Контроллер','spec_value'=>'Samsung Pascal (Elpis)','group'=>'Характеристики','is_filterable'=>false]); // Типичный контроллер для 990 PRO
            ProductSpecification::create(['product_id'=>$product->id,'spec_key'=>'features','spec_name'=>'Особенности','spec_value'=>'SLC-кеширование (Turbo Write 2.0), Контроль тепла, Magician Utility','group'=>'Особенности','is_filterable'=>false]);
            ProductSpecification::create(['product_id'=>$product->id,'spec_key'=>'tbw','spec_name'=>'Ресурс записи (TBW)','spec_value'=>'600 ТБ','group'=>'Надежность','is_filterable'=>false]); // Типичный TBW для 990 PRO 1TB

                        // 2000 ГБ M.2 NVMe накопитель ADATA LEGEND 960 MAX [ALEG-960M-2TCS]
                        $product = Product::create([
                            'title' => '2000 ГБ M.2 NVMe накопитель ADATA LEGEND 960 MAX [ALEG-960M-2TCS]',
                            'price' => 16000, // Цена из описания
                            'old_price' => null,
                            'discount_percent' => 0,
                            'in_stock' => true,
                            'on_sale' => false,
                            'is_bestseller' => false, // Нет информации, что это хит продаж
                            'is_new' => true, // Предположим, что относительно новая модель
                            'credit_available' => true,
                            'img' => 'накопитель ADATA LEGEND 960 MAX.webp', // Предполагаемое имя файла изображения
                            'product_type' => 'ssd', // Тип продукта
                            'country' => 'Тайвань', // Условная страна для ADATA
                            'color' => 'Черный', // Условный цвет (радиатор темный)
                            'qty' => 8, // Условное количество
                            'description' => 'M.2 накопитель ADATA LEGEND 960 MAX с высокоскоростными показателями чтения и записи создан для компьютерных энтузиастов. Он совместим с платформами от Intel и AMD. Объем 2000 ГБ предоставляет возможность размещения большого количества файлов. Интерфейс PCI-E 4.x x4 гарантирует скорость до 7400 Мбайт/сек в режиме чтения для повышения быстродействия и отзывчивости игрового ПК. Алюминиевый радиатор эффективно отводит тепло и поддерживает стабильность функционирования накопителя ADATA LEGEND 960 MAX при разной вычислительной нагрузке.',
                            'category_id' => $storageCategory->id, // Убедитесь, что переменная $storageCategory определена
                            'rating' => 0.0, // Условный рейтинг
                        ]);

                        $product->images()->createMany([
                            ['image_path' => 'накопитель ADATA LEGEND 960 MAX.webp'],
                            ['image_path' => 'накопитель ADATA LEGEND 960 MAX1.webp'],
                            ['image_path' => 'накопитель ADATA LEGEND 960 MAX2.webp'], // Assuming this file exists
                        ]);
            
                        // Добавляем спецификации для ADATA LEGEND 960 MAX 2 ТБ
                        ProductSpecification::create(['product_id'=>$product->id,'spec_key'=>'brand','spec_name'=>'Бренд','spec_value'=>'ADATA','group'=>'Основные','is_filterable'=>true]);
                        ProductSpecification::create(['product_id'=>$product->id,'spec_key'=>'model','spec_name'=>'Серия/модель','spec_value'=>'LEGEND 960 MAX','group'=>'Основные','is_filterable'=>false]);
                        ProductSpecification::create(['product_id'=>$product->id,'spec_key'=>'capacity','spec_name'=>'Объем накопителя','spec_value'=>'2000 ГБ','group'=>'Основные','is_filterable'=>true]);
                        ProductSpecification::create(['product_id'=>$product->id,'spec_key'=>'form_factor','spec_name'=>'Форм-фактор','spec_value'=>'M.2 2280','group'=>'Основные','is_filterable'=>true]);
                        ProductSpecification::create(['product_id'=>$product->id,'spec_key'=>'interface','spec_name'=>'Интерфейс','spec_value'=>'PCIe 4.0 x4 (NVMe)','group'=>'Основные','is_filterable'=>true]);
                        ProductSpecification::create(['product_id'=>$product->id,'spec_key'=>'read_speed','spec_name'=>'Скорость последовательного чтения','spec_value'=>'7400 Мбайт/с','group'=>'Производительность','is_filterable'=>false]);
                        // Типичная скорость записи для этой модели 2ТБ
                        ProductSpecification::create(['product_id'=>$product->id,'spec_key'=>'write_speed','spec_name'=>'Скорость последовательной записи','spec_value'=>'6800 Мбайт/с','group'=>'Производительность','is_filterable'=>false]);
                        // Типичный ресурс записи для этой модели 2ТБ
                        ProductSpecification::create(['product_id'=>$product->id,'spec_key'=>'tbw','spec_name'=>'Ресурс записи (TBW)','spec_value'=>'1560 ТБ','group'=>'Надежность','is_filterable'=>false]);
                        ProductSpecification::create(['product_id'=>$product->id,'spec_key'=>'nand_type','spec_name'=>'Тип флэш-памяти','spec_value'=>'3D NAND','group'=>'Характеристики','is_filterable'=>false]); // Обычно TLC, но описание не уточняет
                        ProductSpecification::create(['product_id'=>$product->id,'spec_key'=>'controller','spec_name'=>'Контроллер','spec_value'=>'InnoGrit IG5236','group'=>'Характеристики','is_filterable'=>false]); // Типичный контроллер для этой модели
                        ProductSpecification::create(['product_id'=>$product->id,'spec_key'=>'features','spec_name'=>'Особенности','spec_value'=>'Алюминиевый радиатор','group'=>'Особенности','is_filterable'=>false]);
                    
                                    // 1 ТБ Жесткий диск WD Blue [WD10EZEX]
            $product = Product::create([
                'title' => '1 ТБ Жесткий диск WD Blue [WD10EZEX]',
                'price' => 4899, // Цена из описания
                'old_price' => null,
                'discount_percent' => 0,
                'in_stock' => true,
                'on_sale' => false,
                'is_bestseller' => false, // Нет информации, что хит продаж
                'is_new' => false, // Модель не новая
                'credit_available' => true,
                'img' => '1 ТБ Жесткий диск WD Blue [WD10EZEX].webp', // Предполагаемое имя файла изображения
                'product_type' => 'hdd', // Тип продукта - жесткий диск
                'country' => 'Таиланд', // Типичная страна производства WD
                'color' => 'Серый', // У жестких дисков обычно нет цвета как фильтруемой характеристики
                'qty' => 10, // Условное количество
                'description' => 'Жесткий диск WD Blue [WD10EZEX] станет надежным дополнением в настольном компьютере, обеспечивая надежность хранения различного контента и быстрый доступ к данным. Высокая производительность запоминающего устройства достигается за счет скорости вращения шпинделя 7200 об/мин и технологии NCQ. Металлическая конструкция отличается стойкостью к повреждениям, при этом накопитель обладает низкими показателями шума и вибрации. Среди других особенностей WD Blue [WD10EZEX] отмечаются емкость 1 ТБ и востребованный интерфейс SATA III.',
                'category_id' => $storageCategory->id, // Убедитесь, что переменная $storageCategory определена
                'rating' => 0.0, // Условный рейтинг
            ]);
            $product->images()->createMany([
                ['image_path' => '1 ТБ Жесткий диск WD Blue [WD10EZEX].webp'],
                ['image_path' => '1 ТБ Жесткий диск WD Blue [WD10EZEX]1.webp'],
                ['image_path' => '1 ТБ Жесткий диск WD Blue [WD10EZEX]2.webp'], // Assuming this file exists
            ]);

            // Добавляем спецификации для WD Blue 1 ТБ WD10EZEX
            ProductSpecification::create(['product_id'=>$product->id,'spec_key'=>'brand','spec_name'=>'Бренд','spec_value'=>'WD','group'=>'Основные','is_filterable'=>true]);
            ProductSpecification::create(['product_id'=>$product->id,'spec_key'=>'series','spec_name'=>'Серия','spec_value'=>'Blue','group'=>'Основные','is_filterable'=>true]); // Серия Blue важна для WD
            ProductSpecification::create(['product_id'=>$product->id,'spec_key'=>'model','spec_name'=>'Модель','spec_value'=>'WD10EZEX','group'=>'Основные','is_filterable'=>false]);
            ProductSpecification::create(['product_id'=>$product->id,'spec_key'=>'capacity','spec_name'=>'Объем накопителя','spec_value'=>'1 ТБ','group'=>'Основные','is_filterable'=>true]);
            ProductSpecification::create(['product_id'=>$product->id,'spec_key'=>'form_factor','spec_name'=>'Форм-фактор','spec_value'=>'3.5"','group'=>'Основные','is_filterable'=>true]); // Для настольных HDD 1ТБ это 3.5"
            ProductSpecification::create(['product_id'=>$product->id,'spec_key'=>'interface','spec_name'=>'Интерфейс','spec_value'=>'SATA III','group'=>'Основные','is_filterable'=>true]);
            ProductSpecification::create(['product_id'=>$product->id,'spec_key'=>'rotation_speed','spec_name'=>'Скорость вращения шпинделя','spec_value'=>'7200 об/мин','group'=>'Производительность','is_filterable'=>true]);
            ProductSpecification::create(['product_id'=>$product->id,'spec_key'=>'cache_size','spec_name'=>'Объем кэш-памяти','spec_value'=>'64 МБ','group'=>'Производительность','is_filterable'=>false]); // Типичный кэш для WD10EZEX
            ProductSpecification::create(['product_id'=>$product->id,'spec_key'=>'features','spec_name'=>'Особенности','spec_value'=>'NCQ, Низкий шум/вибрация','group'=>'Особенности','is_filterable'=>false]);
        
            // 4 ТБ Жесткий диск WD Purple Surveillance [WD43PURZ]
            $product = Product::create([
                'title' => '4 ТБ Жесткий диск WD Purple Surveillance [WD43PURZ]',
                'price' => 8299, // Цена из описания
                'old_price' => null,
                'discount_percent' => 0,
                'in_stock' => true,
                'on_sale' => false,
                'is_bestseller' => false, // Нет информации, что хит продаж
                'is_new' => true, // Предположим, что это относительно новая модель Purple
                'credit_available' => true,
                'img' => '4 ТБ Жесткий диск WD Purple Surveillance [WD43PURZ].webp', // Предполагаемое имя файла изображения
                'product_type' => 'hdd', // Тип продукта - жесткий диск
                'country' => 'Таиланд', // Типичная страна производства WD
                'color' => 'Серый', // У жестких дисков обычно нет цвета как фильтруемой характеристики
                'qty' => 5, // Условное количество
                'description' => 'Жесткий диск WD Purple Surveillance объемом 4 ТБ разработан для систем видеонаблюдения. Кэш память объемом 256 МБ позволяет накопителю работать без сбоев круглые сутки. Надежная конструкция устройства, его способность выдерживать вибрации и перепады температур делают его нацеленным на эксплуатацию в сложных условиях. WD Purple Surveillance с интерфейсом SATA III подходит для оптимизации в RAID-массивах. Технология AllFrame снижает потери кадров, улучшая качество воспроизведения видео и в целом потоковую передачу АТА. Скорость вращения шпинделя 5400 об/мин позволяет диску не зависать, а работать плавно при последовательной записи и чтении данных. Благодаря пропускной способности 6 Гбит/с накопитель быстро обрабатывает видеозаписи, сохраняя их в память.',
                'category_id' => $storageCategory->id, // Убедитесь, что переменная $storageCategory определена
                'rating' => 0.0, // Условный рейтинг
            ]);

            $product->images()->createMany([
                ['image_path' => '4 ТБ Жесткий диск WD Purple Surveillance [WD43PURZ].webp'],
                ['image_path' => '4 ТБ Жесткий диск WD Purple Surveillance [WD43PURZ]1.webp'],
                ['image_path' => '4 ТБ Жесткий диск WD Purple Surveillance [WD43PURZ]2.webp'], // Assuming this file exists
            ]);
            // Добавляем спецификации для WD Purple Surveillance 4 ТБ WD43PURZ
            ProductSpecification::create(['product_id'=>$product->id,'spec_key'=>'brand','spec_name'=>'Бренд','spec_value'=>'WD','group'=>'Основные','is_filterable'=>true]);
            ProductSpecification::create(['product_id'=>$product->id,'spec_key'=>'series','spec_name'=>'Серия','spec_value'=>'Purple Surveillance','group'=>'Основные','is_filterable'=>true]); // Серия Purple важна
            ProductSpecification::create(['product_id'=>$product->id,'spec_key'=>'model','spec_name'=>'Модель','spec_value'=>'WD43PURZ','group'=>'Основные','is_filterable'=>false]);
            ProductSpecification::create(['product_id'=>$product->id,'spec_key'=>'capacity','spec_name'=>'Объем накопителя','spec_value'=>'4 ТБ','group'=>'Основные','is_filterable'=>true]);
            ProductSpecification::create(['product_id'=>$product->id,'spec_key'=>'form_factor','spec_name'=>'Форм-фактор','spec_value'=>'3.5"','group'=>'Основные','is_filterable'=>true]); // Стандартный форм-фактор для такого HDD
            ProductSpecification::create(['product_id'=>$product->id,'spec_key'=>'interface','spec_name'=>'Интерфейс','spec_value'=>'SATA III','group'=>'Основные','is_filterable'=>true]);
            ProductSpecification::create(['product_id'=>$product->id,'spec_key'=>'cache_size','spec_name'=>'Объем кэш-памяти','spec_value'=>'256 МБ','group'=>'Производительность','is_filterable'=>false]);
            ProductSpecification::create(['product_id'=>$product->id,'spec_key'=>'rotation_speed','spec_name'=>'Скорость вращения шпинделя','spec_value'=>'5400 об/мин','group'=>'Производительность','is_filterable'=>true]);
            ProductSpecification::create(['product_id'=>$product->id,'spec_key'=>'intended_use','spec_name'=>'Назначение','spec_value'=>'Для систем видеонаблюдения','group'=>'Особенности','is_filterable'=>true]);
            ProductSpecification::create(['product_id'=>$product->id,'spec_key'=>'features','spec_name'=>'Особенности','spec_value'=>'Устойчивость к вибрациям, AllFrame, Оптимизация под RAID','group'=>'Особенности','is_filterable'=>false]);
        
                        // 8 ТБ Жесткий диск WD Purple [WD85PURZ]
                        $product = Product::create([
                            'title' => '8 ТБ Жесткий диск WD Purple [WD85PURZ]',
                            'price' => 16999, // Цена из описания
                            'old_price' => null,
                            'discount_percent' => 0,
                            'in_stock' => true,
                            'on_sale' => false,
                            'is_bestseller' => false, // Нет информации, что хит продаж
                            'is_new' => true, // Предположим, что относительно новая модель Purple
                            'credit_available' => true,
                            'img' => '8 ТБ Жесткий диск WD Purple [WD85PURZ].webp', // Предполагаемое имя файла изображения
                            'product_type' => 'hdd', // Тип продукта - жесткий диск
                            'country' => 'Таиланд', // Типичная страна производства WD
                            'color' => 'Серый', // У жестких дисков обычно нет цвета как фильтруемой характеристики
                            'qty' => 7, // Условное количество
                            'description' => 'Накопители WD Purple предназначены для круглосуточной записи в системах видеонаблюдения. Эти диски разработаны специально для систем видеонаблюдения, поэтому они выдерживают сильные перепады температур и вибрации оборудования сетевого видеорегистратора. Обычно жесткие диски для настольных компьютеров создаются с прицелом на работу только в течение коротких промежутков времени, а не для жестких условий круглосуточной эксплуатации в системах видеонаблюдения высокой четкости. Жесткие диски WD Purple для систем видеонаблюдения проверены на совместимость с широким спектром систем обеспечения безопасности, благодаря чему вы можете быть уверены в надежности специализированного оборудования. Применение эксклюзивной технологии AllFrame дает возможность уменьшить потерю кадров и улучшить воспроизведение видео.',
                            'category_id' => $storageCategory->id, // Убедитесь, что переменная $storageCategory определена
                            'rating' => 0.0, // Условный рейтинг
                        ]);
                        $product->images()->createMany([
                            ['image_path' => '8 ТБ Жесткий диск WD Purple [WD85PURZ].webp'],
                            ['image_path' => '8 ТБ Жесткий диск WD Purple [WD85PURZ]1.webp'],
                            ['image_path' => '8 ТБ Жесткий диск WD Purple [WD85PURZ]2.webp'], // Assuming this file exists
                        ]);

                        // Добавляем спецификации для WD Purple 8 ТБ WD85PURZ
                        ProductSpecification::create(['product_id'=>$product->id,'spec_key'=>'brand','spec_name'=>'Бренд','spec_value'=>'WD','group'=>'Основные','is_filterable'=>true]);
                        ProductSpecification::create(['product_id'=>$product->id,'spec_key'=>'series','spec_name'=>'Серия','spec_value'=>'Purple Surveillance','group'=>'Основные','is_filterable'=>true]); // Серия Purple важна
                        ProductSpecification::create(['product_id'=>$product->id,'spec_key'=>'model','spec_name'=>'Модель','spec_value'=>'WD85PURZ','group'=>'Основные','is_filterable'=>false]);
                        ProductSpecification::create(['product_id'=>$product->id,'spec_key'=>'capacity','spec_name'=>'Объем накопителя','spec_value'=>'8 ТБ','group'=>'Основные','is_filterable'=>true]);
                        ProductSpecification::create(['product_id'=>$product->id,'spec_key'=>'form_factor','spec_name'=>'Форм-фактор','spec_value'=>'3.5"','group'=>'Основные','is_filterable'=>true]); // Стандартный форм-фактор для такого HDD
                        ProductSpecification::create(['product_id'=>$product->id,'spec_key'=>'interface','spec_name'=>'Интерфейс','spec_value'=>'SATA III','group'=>'Основные','is_filterable'=>true]);
                        // Объем кэша для этой модели может быть 256MB или 512MB, описание не указывает. Пропускаем.
                        // Скорость вращения шпинделя для Purple тоже не всегда стандартная (IntelliPower). Пропускаем.
                        ProductSpecification::create(['product_id'=>$product->id,'spec_key'=>'intended_use','spec_name'=>'Назначение','spec_value'=>'Для систем видеонаблюдения (24/7)','group'=>'Особенности','is_filterable'=>true]);
                        ProductSpecification::create(['product_id'=>$product->id,'spec_key'=>'features','spec_name'=>'Особенности','spec_value'=>'Устойчивость к температурам/вибрациям, AllFrame, Совместимость с системами видеонаблюдения','group'=>'Особенности','is_filterable'=>false]);
                    
                        //оперативная память

                                    // Оперативная память Kingston FURY Beast Black [KF556C36BBEK2-32] 32 ГБ
            $product = Product::create([
                'title' => 'Оперативная память Kingston FURY Beast Black [KF556C36BBEK2-32] 32 ГБ',
                'price' => 8599, // Цена из описания
                'old_price' => null,
                'discount_percent' => 0,
                'in_stock' => true,
                'on_sale' => false,
                'is_bestseller' => false, // Нет информации, что хит продаж
                'is_new' => true, // Скорее всего, новая или актуальная модель
                'credit_available' => true,
                'img' => 'Оперативная память Kingston FURY Beast Black [KF556C36BBEK2-32] 32 ГБ.webp', // Предполагаемое имя файла изображения
                'product_type' => 'ram', // Тип продукта - оперативная память
                'country' => 'Тайвань', // Типичная страна производства для Kingston
                'color' => 'Черный', // Цвет из названия
                'qty' => 10, // Условное количество
                'description' => 'Оперативная память Kingston FURY Beast Black поколения DDR5 – это высокопроизводительное решение для геймерских и ресурсоемких ПК. Она представлена комплектом из двух модулей суммарным объемом 32 ГБ и тактовой частотой 5600 МГц. Поддержка Intel (XMP) 2.0 позволяет с удобством выполнять разгон. Алюминиевый ребристый радиатор способствует быстрому отведению тепла и помогает предотвратить перегрев чипов памяти. Интегрированная схема управления питанием гарантируют повышенную стабильность в эксплуатации оперативной памяти Kingston FURY Beast.',
                'category_id' => $ramCategory->id, // Убедитесь, что переменная $ramCategory определена и содержит ID категории оперативной памяти
                'rating' => 0.0, // Условный рейтинг
            ]);

            $product->images()->createMany([
                ['image_path' => 'Оперативная память Kingston FURY Beast Black [KF556C36BBEK2-32] 32 ГБ.webp'],
                ['image_path' => 'Оперативная память Kingston FURY Beast Black [KF556C36BBEK2-32] 32 ГБ1.webp'],
                ['image_path' => 'Оперативная память Kingston FURY Beast Black [KF556C36BBEK2-32] 32 ГБ2.webp'], // Assuming this file exists
            ]);

            // Добавляем спецификации для Kingston FURY Beast Black 32 ГБ
            ProductSpecification::create(['product_id'=>$product->id,'spec_key'=>'brand','spec_name'=>'Бренд','spec_value'=>'Kingston','group'=>'Основные','is_filterable'=>true]);
            ProductSpecification::create(['product_id'=>$product->id,'spec_key'=>'series','spec_name'=>'Серия','spec_value'=>'FURY Beast Black','group'=>'Основные','is_filterable'=>true]);
            ProductSpecification::create(['product_id'=>$product->id,'spec_key'=>'type','spec_name'=>'Тип памяти','spec_value'=>'DDR5','group'=>'Основные','is_filterable'=>true]);
            ProductSpecification::create(['product_id'=>$product->id,'spec_key'=>'capacity','spec_name'=>'Объем одного модуля','spec_value'=>'16 ГБ','group'=>'Основные','is_filterable'=>true]); // Указываем объем одного модуля, т.к. комплект 2x16=32
             ProductSpecification::create(['product_id'=>$product->id,'spec_key'=>'total_capacity','spec_name'=>'Суммарный объем памяти','spec_value'=>'32 ГБ','group'=>'Основные','is_filterable'=>true]); // Суммарный объем
            ProductSpecification::create(['product_id'=>$product->id,'spec_key'=>'modules_kit','spec_name'=>'Комплект','spec_value'=>'2x16 ГБ','group'=>'Основные','is_filterable'=>true]); // Указываем комплектность
            ProductSpecification::create(['product_id'=>$product->id,'spec_key'=>'frequency','spec_name'=>'Тактовая частота','spec_value'=>'5600 МГц','group'=>'Характеристики','is_filterable'=>true]);
            // Профиль XMP важен для разгона
            ProductSpecification::create(['product_id'=>$product->id,'spec_key'=>'xmp_profile','spec_name'=>'Профиль XMP','spec_value'=>'Intel XMP 2.0','group'=>'Характеристики','is_filterable'=>false]);
            // Тайминги (латентность) важны, но не указаны в описании. Пропускаем.
            ProductSpecification::create(['product_id'=>$product->id,'spec_key'=>'features','spec_name'=>'Особенности','spec_value'=>'Алюминиевый радиатор, Схема управления питанием','group'=>'Особенности','is_filterable'=>false]);
            ProductSpecification::create(['product_id'=>$product->id,'spec_key'=>'form_factor','spec_name'=>'Форм-фактор','spec_value'=>'DIMM','group'=>'Основные','is_filterable'=>true]); // Форм-фактор для десктопной ОЗУ
        

                        // Оперативная память ADATA XPG Lancer Blade [AX5U5600C4616G-DTLABBK] 32 ГБ
                        $product = Product::create([
                            'title' => 'Оперативная память ADATA XPG Lancer Blade [AX5U5600C4616G-DTLABBK] 32 ГБ',
                            'price' => 8000, // Условная цена (цена не указана в описании)
                            'old_price' => null,
                            'discount_percent' => 0,
                            'in_stock' => true,
                            'on_sale' => false,
                            'is_bestseller' => false, // Нет информации, что хит продаж
                            'is_new' => true, // Скорее всего, новая или актуальная модель
                            'credit_available' => true,
                            'img' => 'Оперативная память ADATA XPG Lancer Blade [AX5U5600C4616G-DTLABBK] 32 ГБ.webp', // Предполагаемое имя файла изображения
                            'product_type' => 'ram', // Тип продукта - оперативная память
                            'country' => 'Тайвань', // Типичная страна производства для ADATA
                            'color' => 'Черный', // Цвет из названия "Black"
                            'qty' => 12, // Условное количество
                            'description' => 'Оперативная память ADATA XPG Lancer Blade [AX5U5600C4616G-DTLABBK] представлена комплектом из двух модулей стандарта DDR5. Благодаря объему по 16 ГБ каждый и тактовой частоте 5600 МГц они обеспечивают повышение быстродействия аппаратной платформы ПК и снижение времени выполнения компьютерных задач. Профили AMD EXPO и Intel XMP предусматривают разгон системы. На модулях ОЗУ ADATA XPG Lancer Blade установлены алюминиевые радиаторы, которые рассеивают тепло и поддерживают низкий нагрев печатных плат. Технология On-Die ECC способна распознавать и корректировать ошибки в работе оперативной памяти. Интегральная схема управления питания Power Management IC снижает вероятность сбоев.',
                            'category_id' => $ramCategory->id, // Убедитесь, что переменная $ramCategory определена и содержит ID категории оперативной памяти
                            'rating' => 0.0, // Условный рейтинг
                        ]);

                        $product->images()->createMany([
                            ['image_path' => 'Оперативная память ADATA XPG Lancer Blade [AX5U5600C4616G-DTLABBK] 32 ГБ.webp'],
                            ['image_path' => 'Оперативная память ADATA XPG Lancer Blade [AX5U5600C4616G-DTLABBK] 32 ГБ1.webp'],
                            ['image_path' => 'Оперативная память ADATA XPG Lancer Blade [AX5U5600C4616G-DTLABBK] 32 ГБ2.webp'], // Assuming this file exists
                        ]);
            
                        // Добавляем спецификации для ADATA XPG Lancer Blade 32 ГБ
                        ProductSpecification::create(['product_id'=>$product->id,'spec_key'=>'brand','spec_name'=>'Бренд','spec_value'=>'ADATA (XPG)','group'=>'Основные','is_filterable'=>true]); // Указываем оба: ADATA и XPG
                        ProductSpecification::create(['product_id'=>$product->id,'spec_key'=>'series','spec_name'=>'Серия/модель','spec_value'=>'XPG Lancer Blade','group'=>'Основные','is_filterable'=>true]);
                        ProductSpecification::create(['product_id'=>$product->id,'spec_key'=>'type','spec_name'=>'Тип памяти','spec_value'=>'DDR5','group'=>'Основные','is_filterable'=>true]);
                        ProductSpecification::create(['product_id'=>$product->id,'spec_key'=>'capacity','spec_name'=>'Объем одного модуля','spec_value'=>'16 ГБ','group'=>'Основные','is_filterable'=>true]); // Указываем объем одного модуля
                         ProductSpecification::create(['product_id'=>$product->id,'spec_key'=>'total_capacity','spec_name'=>'Суммарный объем памяти','spec_value'=>'32 ГБ','group'=>'Основные','is_filterable'=>true]); // Суммарный объем
                        ProductSpecification::create(['product_id'=>$product->id,'spec_key'=>'modules_kit','spec_name'=>'Комплект','spec_value'=>'2x16 ГБ','group'=>'Основные','is_filterable'=>true]); // Указываем комплектность
                        ProductSpecification::create(['product_id'=>$product->id,'spec_key'=>'frequency','spec_name'=>'Тактовая частота','spec_value'=>'5600 МГц','group'=>'Характеристики','is_filterable'=>true]);
                        ProductSpecification::create(['product_id'=>$product->id,'spec_key'=>'xmp_profile','spec_name'=>'Профиль XMP','spec_value'=>'Intel XMP','group'=>'Характеристики','is_filterable'=>false]); // Поддержка Intel XMP
                        ProductSpecification::create(['product_id'=>$product->id,'spec_key'=>'expo_profile','spec_name'=>'Профиль EXPO','spec_value'=>'AMD EXPO','group'=>'Характеристики','is_filterable'=>false]); // Поддержка AMD EXPO
                        // Тайминги (латентность) важны, но не указаны в описании. Пропускаем.
                        ProductSpecification::create(['product_id'=>$product->id,'spec_key'=>'features','spec_name'=>'Особенности','spec_value'=>'Алюминиевый радиатор, On-Die ECC, Power Management IC','group'=>'Особенности','is_filterable'=>false]);
                        ProductSpecification::create(['product_id'=>$product->id,'spec_key'=>'form_factor','spec_name'=>'Форм-фактор','spec_value'=>'DIMM','group'=>'Основные','is_filterable'=>true]); // Форм-фактор для десктопной ОЗУ
                    
            // Оперативная память G.Skill Aegis [F4-3200C16D-32GIS] 32 ГБ
            $product = Product::create([
                'title' => 'Оперативная память G.Skill Aegis [F4-3200C16D-32GIS] 32 ГБ',
                'price' => 4999, // Цена из описания
                'old_price' => null,
                'discount_percent' => 0,
                'in_stock' => true,
                'on_sale' => false,
                'is_bestseller' => false, // Нет информации, что хит продаж
                'is_new' => false, // Модель Aegis 3200 DDR4 уже не новая
                'credit_available' => true,
                'img' => 'Оперативная память G.Skill Aegis [F4-3200C16D-32GIS] 32 ГБ.webp', // Предполагаемое имя файла изображения
                'product_type' => 'ram', // Тип продукта - оперативная память
                'country' => 'Тайвань', // Типичная страна производства для G.Skill
                'color' => 'Черный', // Условный цвет (обычно текстолит черный)
                'qty' => 15, // Условное количество
                'description' => 'Оперативная память G.Skill AEGIS состоит из двух модулей объемом по 16 ГБ стандарта DDR4, которые предназначены для повышения производительности и быстродействия ПК. Модули отличаются работой на частоте 3200 МГц, благодаря чему помогают добиться быстрой отзывчивости системы на выполнение различных задач. Память G.Skill AEGIS отличается экономичностью потребления энергии и низкими задержками. Благодаря небольшой высоте ОЗУ совместима с комплектующими различных производителей, а надежные компоненты гарантируют исключительную стабильность функционирования.',
                'category_id' => $ramCategory->id, // Убедитесь, что переменная $ramCategory определена и содержит ID категории оперативной памяти
                'rating' => 0.0, // Условный рейтинг
            ]);

            $product->images()->createMany([
                ['image_path' => 'Оперативная память G.Skill Aegis [F4-3200C16D-32GIS] 32 ГБ.webp'],
                ['image_path' => 'Оперативная память G.Skill Aegis [F4-3200C16D-32GIS] 32 ГБ1.webp'],
                ['image_path' => 'Оперативная память G.Skill Aegis [F4-3200C16D-32GIS] 32 ГБ2.webp'], // Assuming this file exists
            ]);

            // Добавляем спецификации для G.Skill Aegis 32 ГБ
            ProductSpecification::create(['product_id'=>$product->id,'spec_key'=>'brand','spec_name'=>'Бренд','spec_value'=>'G.Skill','group'=>'Основные','is_filterable'=>true]);
            ProductSpecification::create(['product_id'=>$product->id,'spec_key'=>'series','spec_name'=>'Серия/модель','spec_value'=>'Aegis','group'=>'Основные','is_filterable'=>true]);
            ProductSpecification::create(['product_id'=>$product->id,'spec_key'=>'model_code','spec_name'=>'Код модели','spec_value'=>'F4-3200C16D-32GIS','group'=>'Основные','is_filterable'=>false]); // Добавляем код модели для точности
            ProductSpecification::create(['product_id'=>$product->id,'spec_key'=>'type','spec_name'=>'Тип памяти','spec_value'=>'DDR4','group'=>'Основные','is_filterable'=>true]);
            ProductSpecification::create(['product_id'=>$product->id,'spec_key'=>'capacity','spec_name'=>'Объем одного модуля','spec_value'=>'16 ГБ','group'=>'Основные','is_filterable'=>true]); // Указываем объем одного модуля
            ProductSpecification::create(['product_id'=>$product->id,'spec_key'=>'total_capacity','spec_name'=>'Суммарный объем памяти','spec_value'=>'32 ГБ','group'=>'Основные','is_filterable'=>true]); // Суммарный объем
            ProductSpecification::create(['product_id'=>$product->id,'spec_key'=>'modules_kit','spec_name'=>'Комплект','spec_value'=>'2x16 ГБ','group'=>'Основные','is_filterable'=>true]); // Указываем комплектность
            ProductSpecification::create(['product_id'=>$product->id,'spec_key'=>'frequency','spec_name'=>'Тактовая частота','spec_value'=>'3200 МГц','group'=>'Характеристики','is_filterable'=>true]);
            // Тайминги (латентность) важны, в коде модели указано C16, добавим
            ProductSpecification::create(['product_id'=>$product->id,'spec_key'=>'latency_cas','spec_name'=>'Латентность (CAS Latency)','spec_value'=>'CL16','group'=>'Характеристики','is_filterable'=>false]);
            ProductSpecification::create(['product_id'=>$product->id,'spec_key'=>'features','spec_name'=>'Особенности','spec_value'=>'Экономичное потребление, Низкие задержки, Небольшая высота','group'=>'Особенности','is_filterable'=>false]);
            ProductSpecification::create(['product_id'=>$product->id,'spec_key'=>'form_factor','spec_name'=>'Форм-фактор','spec_value'=>'DIMM','group'=>'Основные','is_filterable'=>true]); // Форм-фактор для десктопной ОЗУ

            // Оперативная память ADATA XPG Lancer Blade [AX5U6400C3216G-DTLABBK] 32 ГБ
            $product = Product::create([
                'title' => 'Оперативная память ADATA XPG Lancer Blade [AX5U6400C3216G-DTLABBK] 32 ГБ',
                'price' => 11499, // Цена из описания
                'old_price' => null,
                'discount_percent' => 0,
                'in_stock' => true,
                'on_sale' => false,
                'is_bestseller' => false, // Нет информации, что хит продаж
                'is_new' => true, // Скорее всего, новая или актуальная модель
                'credit_available' => true,
                'img' => 'Оперативная память ADATA XPG Lancer Blade.webp', // Предполагаемое имя файла изображения
                'product_type' => 'ram', // Тип продукта - оперативная память
                'country' => 'Тайвань', // Типичная страна производства для ADATA
                'color' => 'Черный', // Цвет из описания
                'qty' => 10, // Условное количество
                'description' => 'Оперативная память ADATA XPG Lancer Blade [AX5U6400C3216G-DTLABBK] с тактовой частотой 6400 МГц обеспечивает повышение производительности и скорости в составе рабочей станции или игрового компьютера. В набор входят 2 модуля поколения DDR5 объемом по 16 ГБ. Функция On-Die ECC распознает и корректирует ошибки, что защищает от сбоев оперативной памяти. Технология Power Management IC управляет энергопотреблением и гарантирует стабильную работу. Ребристые алюминиевые радиаторы черного цвета отводят тепло от чипов памяти ADATA XPG Lancer Blade и предотвращают перегрев в режиме интенсивной вычислительной нагрузки.',
                'category_id' => $ramCategory->id, // Убедитесь, что переменная $ramCategory определена и содержит ID категории оперативной памяти
                'rating' => 0.0, // Условный рейтинг
            ]);

            $product->images()->createMany([
                ['image_path' => 'Оперативная память ADATA XPG Lancer Blade.webp'],
                ['image_path' => 'Оперативная память ADATA XPG Lancer Blade1.webp'],
                ['image_path' => 'Оперативная память ADATA XPG Lancer Blade2.webp'], // Assuming this file exists
            ]);

            // Добавляем спецификации для ADATA XPG Lancer Blade 32 ГБ 6400 МГц
            ProductSpecification::create(['product_id'=>$product->id,'spec_key'=>'brand','spec_name'=>'Бренд','spec_value'=>'ADATA (XPG)','group'=>'Основные','is_filterable'=>true]); // Указываем оба: ADATA и XPG
            ProductSpecification::create(['product_id'=>$product->id,'spec_key'=>'series','spec_name'=>'Серия/модель','spec_value'=>'XPG Lancer Blade','group'=>'Основные','is_filterable'=>true]);
             ProductSpecification::create(['product_id'=>$product->id,'spec_key'=>'model_code','spec_name'=>'Код модели','spec_value'=>'AX5U6400C3216G-DTLABBK','group'=>'Основные','is_filterable'=>false]); // Добавляем код модели для точности
            ProductSpecification::create(['product_id'=>$product->id,'spec_key'=>'type','spec_name'=>'Тип памяти','spec_value'=>'DDR5','group'=>'Основные','is_filterable'=>true]);
            ProductSpecification::create(['product_id'=>$product->id,'spec_key'=>'capacity','spec_name'=>'Объем одного модуля','spec_value'=>'16 ГБ','group'=>'Основные','is_filterable'=>true]); // Указываем объем одного модуля
             ProductSpecification::create(['product_id'=>$product->id,'spec_key'=>'total_capacity','spec_name'=>'Суммарный объем памяти','spec_value'=>'32 ГБ','group'=>'Основные','is_filterable'=>true]); // Суммарный объем
            ProductSpecification::create(['product_id'=>$product->id,'spec_key'=>'modules_kit','spec_name'=>'Комплект','spec_value'=>'2x16 ГБ','group'=>'Основные','is_filterable'=>true]); // Указываем комплектность
            ProductSpecification::create(['product_id'=>$product->id,'spec_key'=>'frequency','spec_name'=>'Тактовая частота','spec_value'=>'6400 МГц','group'=>'Характеристики','is_filterable'=>true]);
            // Тайминги (латентность) важны, в коде модели указано C32, добавим
            ProductSpecification::create(['product_id'=>$product->id,'spec_key'=>'latency_cas','spec_name'=>'Латентность (CAS Latency)','spec_value'=>'CL32','group'=>'Характеристики','is_filterable'=>false]);
            ProductSpecification::create(['product_id'=>$product->id,'spec_key'=>'features','spec_name'=>'Особенности','spec_value'=>'Алюминиевый радиатор, On-Die ECC, Power Management IC','group'=>'Особенности','is_filterable'=>false]);
            ProductSpecification::create(['product_id'=>$product->id,'spec_key'=>'form_factor','spec_name'=>'Форм-фактор','spec_value'=>'DIMM','group'=>'Основные','is_filterable'=>true]); // Форм-фактор для десктопной ОЗУ

                        // Оперативная память Kingston FURY Beast Black RGB [KF556C36BBEAK2-32] 32 ГБ
                        $product = Product::create([
                            'title' => 'Оперативная память Kingston FURY Beast Black RGB [KF556C36BBEAK2-32] 32 ГБ',
                            'price' => 9599, // Цена из описания
                            'old_price' => null,
                            'discount_percent' => 0,
                            'in_stock' => true,
                            'on_sale' => false,
                            'is_bestseller' => false, // Нет информации, что хит продаж
                            'is_new' => true, // Скорее всего, новая или актуальная модель
                            'credit_available' => true,
                            'img' => 'Kingston FURY Beast Black RGB.webp', // Предполагаемое имя файла изображения
                            'product_type' => 'ram', // Тип продукта - оперативная память
                            'country' => 'Тайвань', // Типичная страна производства для Kingston
                            'color' => 'Черный', // Цвет из названия
                            'qty' => 10, // Условное количество
                            'description' => 'Оперативная память Kingston FURY Beast Black RGB обладает высокопроизводительными характеристиками. Она оптимизирована для повышения производительности и скорости игрового ПК. В комплекте поставляются два модуля стандарта DDR5 объемом по 16 ГБ. Благодаря тактовой частоте 5600 МГц память помогает добиться более быстрого запуска программ и реалистичного воспроизведения игр. На планках установлены алюминиевые радиаторы оригинальной формы для быстрого отведения тепла при различных нагрузках. Среди преимуществ Kingston FURY Beast Black RGB – многоцветная подсветка RGB с широкими возможностями настройки и функцией синхронизации.',
                            'category_id' => $ramCategory->id, // Убедитесь, что переменная $ramCategory определена и содержит ID категории оперативной памяти
                            'rating' => 0.0, // Условный рейтинг
                        ]);

                        $product->images()->createMany([
                            ['image_path' => 'Kingston FURY Beast Black RGB.webp'],
                            ['image_path' => 'Kingston FURY Beast Black RGB1.webp'],
                            ['image_path' => 'Kingston FURY Beast Black RGB2.webp'], // Assuming this file exists
                        ]);
            
                        // Добавляем спецификации для Kingston FURY Beast Black RGB 32 ГБ
                        ProductSpecification::create(['product_id'=>$product->id,'spec_key'=>'brand','spec_name'=>'Бренд','spec_value'=>'Kingston','group'=>'Основные','is_filterable'=>true]);
                        ProductSpecification::create(['product_id'=>$product->id,'spec_key'=>'series','spec_name'=>'Серия','spec_value'=>'FURY Beast Black RGB','group'=>'Основные','is_filterable'=>true]);
                         ProductSpecification::create(['product_id'=>$product->id,'spec_key'=>'model_code','spec_name'=>'Код модели','spec_value'=>'KF556C36BBEAK2-32','group'=>'Основные','is_filterable'=>false]); // Добавляем код модели для точности
                        ProductSpecification::create(['product_id'=>$product->id,'spec_key'=>'type','spec_name'=>'Тип памяти','spec_value'=>'DDR5','group'=>'Основные','is_filterable'=>true]);
                        ProductSpecification::create(['product_id'=>$product->id,'spec_key'=>'capacity','spec_name'=>'Объем одного модуля','spec_value'=>'16 ГБ','group'=>'Основные','is_filterable'=>true]); // Указываем объем одного модуля
                         ProductSpecification::create(['product_id'=>$product->id,'spec_key'=>'total_capacity','spec_name'=>'Суммарный объем памяти','spec_value'=>'32 ГБ','group'=>'Основные','is_filterable'=>true]); // Суммарный объем
                        ProductSpecification::create(['product_id'=>$product->id,'spec_key'=>'modules_kit','spec_name'=>'Комплект','spec_value'=>'2x16 ГБ','group'=>'Основные','is_filterable'=>true]); // Указываем комплектность
                        ProductSpecification::create(['product_id'=>$product->id,'spec_key'=>'frequency','spec_name'=>'Тактовая частота','spec_value'=>'5600 МГц','group'=>'Характеристики','is_filterable'=>true]);
                        // Тайминги (латентность) важны, в коде модели указано C36, добавим
                        ProductSpecification::create(['product_id'=>$product->id,'spec_key'=>'latency_cas','spec_name'=>'Латентность (CAS Latency)','spec_value'=>'CL36','group'=>'Характеристики','is_filterable'=>false]);
                        ProductSpecification::create(['product_id'=>$product->id,'spec_key'=>'features','spec_name'=>'Особенности','spec_value'=>'Алюминиевый радиатор, RGB подсветка','group'=>'Особенности','is_filterable'=>false]);
                        ProductSpecification::create(['product_id'=>$product->id,'spec_key'=>'form_factor','spec_name'=>'Форм-фактор','spec_value'=>'DIMM','group'=>'Основные','is_filterable'=>true]); // Форм-фактор для десктопной ОЗУ
                    

                                    // Оперативная память Kingston FURY Beast Black [KF552C40BB-16] 16 ГБ
            $product = Product::create([
                'title' => 'Оперативная память Kingston FURY Beast Black [KF552C40BB-16] 16 ГБ',
                'price' => 4899, // Цена из описания
                'old_price' => null,
                'discount_percent' => 0,
                'in_stock' => true,
                'on_sale' => false,
                'is_bestseller' => false, // Нет информации, что хит продаж
                'is_new' => true, // Скорее всего, новая или актуальная модель
                'credit_available' => true,
                'img' => 'Kingston FURY Beast Black.webp', // Предполагаемое имя файла изображения
                'product_type' => 'ram', // Тип продукта - оперативная память
                'country' => 'Тайвань', // Типичная страна производства для Kingston
                'color' => 'Черный', // Цвет из названия
                'qty' => 15, // Условное количество
                'description' => 'Оперативная память Kingston FURY Beast Black соответствует стандарту DDR5 и разработана для игровых систем. Благодаря тактовой частоте 5200 МГц и высокой пропускной способности она обеспечивает быстродействие при обработке требовательных задач. Модуль отличается объемом 16 ГБ и поддержкой Intel (XMP) 3.0 для удобного разгона системы. Алюминиевый ребристый радиатор способствует быстрому отведению тепла и помогает предотвратить перегрев Kingston FURY Beast. Встроенная микросхема управления питанием и код коррекции ошибок ECC гарантируют повышенную стабильность в процессе эксплуатации оперативной памяти.',
                'category_id' => $ramCategory->id, // Убедитесь, что переменная $ramCategory определена и содержит ID категории оперативной памяти
                'rating' => 0.0, // Условный рейтинг
            ]);

            
            $product->images()->createMany([
                ['image_path' => 'Kingston FURY Beast Black.webp'],
                ['image_path' => 'Kingston FURY Beast Black1.webp'],
                ['image_path' => 'Kingston FURY Beast Black2.webp'], // Assuming this file exists
            ]);

            // Добавляем спецификации для Kingston FURY Beast Black 16 ГБ
            ProductSpecification::create(['product_id'=>$product->id,'spec_key'=>'brand','spec_name'=>'Бренд','spec_value'=>'Kingston','group'=>'Основные','is_filterable'=>true]);
            ProductSpecification::create(['product_id'=>$product->id,'spec_key'=>'series','spec_name'=>'Серия','spec_value'=>'FURY Beast Black','group'=>'Основные','is_filterable'=>true]);
             ProductSpecification::create(['product_id'=>$product->id,'spec_key'=>'model_code','spec_name'=>'Код модели','spec_value'=>'KF552C40BB-16','group'=>'Основные','is_filterable'=>false]); // Добавляем код модели для точности
            ProductSpecification::create(['product_id'=>$product->id,'spec_key'=>'type','spec_name'=>'Тип памяти','spec_value'=>'DDR5','group'=>'Основные','is_filterable'=>true]);
            // Здесь объем одного модуля 16 ГБ, суммарный тоже 16, т.к. это один модуль
            ProductSpecification::create(['product_id'=>$product->id,'spec_key'=>'capacity','spec_name'=>'Объем одного модуля','spec_value'=>'16 ГБ','group'=>'Основные','is_filterable'=>true]);
             ProductSpecification::create(['product_id'=>$product->id,'spec_key'=>'total_capacity','spec_name'=>'Суммарный объем памяти','spec_value'=>'16 ГБ','group'=>'Основные','is_filterable'=>true]);
            // Это один модуль, не комплект
            ProductSpecification::create(['product_id'=>$product->id,'spec_key'=>'modules_kit','spec_name'=>'Комплект','spec_value'=>'1x16 ГБ','group'=>'Основные','is_filterable'=>true]); // Указываем, что это 1 модуль
            ProductSpecification::create(['product_id'=>$product->id,'spec_key'=>'frequency','spec_name'=>'Тактовая частота','spec_value'=>'5200 МГц','group'=>'Характеристики','is_filterable'=>true]);
            // Тайминги (латентность) важны, в коде модели указано C40, добавим
            ProductSpecification::create(['product_id'=>$product->id,'spec_key'=>'latency_cas','spec_name'=>'Латентность (CAS Latency)','spec_value'=>'CL40','group'=>'Характеристики','is_filterable'=>false]);
            ProductSpecification::create(['product_id'=>$product->id,'spec_key'=>'xmp_profile','spec_name'=>'Профиль XMP','spec_value'=>'Intel XMP 3.0','group'=>'Характеристики','is_filterable'=>false]); // Поддержка Intel XMP 3.0
            ProductSpecification::create(['product_id'=>$product->id,'spec_key'=>'features','spec_name'=>'Особенности','spec_value'=>'Алюминиевый радиатор, Микросхема управления питанием, ECC','group'=>'Особенности','is_filterable'=>false]);
            ProductSpecification::create(['product_id'=>$product->id,'spec_key'=>'form_factor','spec_name'=>'Форм-фактор','spec_value'=>'DIMM','group'=>'Основные','is_filterable'=>true]); // Форм-фактор для десктопной ОЗУ
        

                        // Оперативная память ARDOR GAMING Reaper [DGSB432GB3200C18DC] 32 ГБ
                        $product = Product::create([
                            'title' => 'Оперативная память ARDOR GAMING Reaper [DGSB432GB3200C18DC] 32 ГБ',
                            'price' => 5299, // Цена из описания
                            'old_price' => null,
                            'discount_percent' => 0,
                            'in_stock' => true,
                            'on_sale' => false,
                            'is_bestseller' => false, // Нет информации, что хит продаж
                            'is_new' => false, // Модель DDR4, скорее всего, не является "новинкой"
                            'credit_available' => true,
                            'img' => 'ARDOR GAMING Reaper.webp', // Предполагаемое имя файла изображения
                            'product_type' => 'ram', // Тип продукта - оперативная память
                            'country' => 'Китай', // Типичная страна производства для ARDOR GAMING
                            'color' => 'Черный', // Цвет радиаторов из описания
                            'qty' => 18, // Условное количество
                            'description' => 'Оперативная память ARDOR GAMING Reaper [DGSB432GB3200C18DC] 32 ГБ позволит вам укомплектовать производительными модулями ОЗУ игровой системный блок или увеличить возможности уже эксплуатируемого ПК. Модули форм-фактора DIMM DDR4 рассчитаны на работу с тактовой частотой от 1600 до 3200 МГц. Память с пропускной способностью PC25600 представлена в виде пары планок емкостью по 16 ГБ. При монтаже в системный блок она автоматически разгоняется до максимальной частоты, поддерживаемой системой. ОЗУ набора ARDOR GAMING Reaper [DGSB432GB3200C18DC] предполагает работу при уровне входного напряжения 1.35 В. Особенностью модулей является наличие радиаторов черного цвета над чипами памяти. Радиаторы являются как дизайнерским решением, так и средством отвода тепла от нагревающихся под нагрузкой компонентов.',
                            'category_id' => $ramCategory->id, // Убедитесь, что переменная $ramCategory определена и содержит ID категории оперативной памяти
                            'rating' => 0.0, // Условный рейтинг
                        ]);

                        $product->images()->createMany([
                            ['image_path' => 'ARDOR GAMING Reaper.webp'],
                            ['image_path' => 'ARDOR GAMING Reaper1.webp'],
                            ['image_path' => 'ARDOR GAMING Reaper2.webp'], // Assuming this file exists
                        ]);
            
                        // Добавляем спецификации для ARDOR GAMING Reaper 32 ГБ
                        ProductSpecification::create(['product_id'=>$product->id,'spec_key'=>'brand','spec_name'=>'Бренд','spec_value'=>'ARDOR GAMING','group'=>'Основные','is_filterable'=>true]);
                        ProductSpecification::create(['product_id'=>$product->id,'spec_key'=>'series','spec_name'=>'Серия','spec_value'=>'Reaper','group'=>'Основные','is_filterable'=>true]);
                         ProductSpecification::create(['product_id'=>$product->id,'spec_key'=>'model_code','spec_name'=>'Код модели','spec_value'=>'DGSB432GB3200C18DC','group'=>'Основные','is_filterable'=>false]); // Добавляем код модели для точности
                        ProductSpecification::create(['product_id'=>$product->id,'spec_key'=>'type','spec_name'=>'Тип памяти','spec_value'=>'DDR4','group'=>'Основные','is_filterable'=>true]);
                        ProductSpecification::create(['product_id'=>$product->id,'spec_key'=>'capacity','spec_name'=>'Объем одного модуля','spec_value'=>'16 ГБ','group'=>'Основные','is_filterable'=>true]); // Указываем объем одного модуля
                         ProductSpecification::create(['product_id'=>$product->id,'spec_key'=>'total_capacity','spec_name'=>'Суммарный объем памяти','spec_value'=>'32 ГБ','group'=>'Основные','is_filterable'=>true]); // Суммарный объем
                        ProductSpecification::create(['product_id'=>$product->id,'spec_key'=>'modules_kit','spec_name'=>'Комплект','spec_value'=>'2x16 ГБ','group'=>'Основные','is_filterable'=>true]); // Указываем комплектность
                        ProductSpecification::create(['product_id'=>$product->id,'spec_key'=>'frequency','spec_name'=>'Тактовая частота','spec_value'=>'3200 МГц','group'=>'Характеристики','is_filterable'=>true]);
                        // Тайминги (латентность) важны, в коде модели указано C18, добавим
                        ProductSpecification::create(['product_id'=>$product->id,'spec_key'=>'latency_cas','spec_name'=>'Латентность (CAS Latency)','spec_value'=>'CL18','group'=>'Характеристики','is_filterable'=>false]);
                        ProductSpecification::create(['product_id'=>$product->id,'spec_key'=>'voltage','spec_name'=>'Напряжение питания','spec_value'=>'1.35 В','group'=>'Характеристики','is_filterable'=>false]);
                         ProductSpecification::create(['product_id'=>$product->id,'spec_key'=>'throughput','spec_name'=>'Пропускная способность','spec_value'=>'PC25600','group'=>'Характеристики','is_filterable'=>false]);
                        ProductSpecification::create(['product_id'=>$product->id,'spec_key'=>'features','spec_name'=>'Особенности','spec_value'=>'Радиатор охлаждения, Автоматический разгон','group'=>'Особенности','is_filterable'=>false]);
                        ProductSpecification::create(['product_id'=>$product->id,'spec_key'=>'form_factor','spec_name'=>'Форм-фактор','spec_value'=>'DIMM','group'=>'Основные','is_filterable'=>true]); // Форм-фактор для десктопной ОЗУ
                    
            // Оперативная память G.Skill Trident Z5 Royal [F5-6400J3239G32GX2-TR5S] 64 ГБ
            $product = Product::create([
                'title' => 'Оперативная память G.Skill Trident Z5 Royal [F5-6400J3239G32GX2-TR5S] 64 ГБ',
                'price' => 25999, // Цена из описания
                'old_price' => null,
                'discount_percent' => 0,
                'in_stock' => true,
                'on_sale' => false,
                'is_bestseller' => false, // Нет информации, что хит продаж
                'is_new' => true, // Скорее всего, новая или актуальная высокопроизводительная модель
                'credit_available' => true,
                'img' => 'G.Skill Trident Z5 Royal.webp', // Предполагаемое имя файла изображения
                'product_type' => 'ram', // Тип продукта - оперативная память
                'country' => 'Тайвань', // Типичная страна производства для G.Skill
                'color' => 'Серебристый/Черный (с RGB)', // Цвет радиатора и подсветка
                'qty' => 8, // Условное количество
                'description' => 'Оперативная память G.Skill Trident Z5 Royal совместима с геймерскими ПК на базе платформы Intel и предусматривает простой разгон при поддержке технологии XMP 3.0. Благодаря стандарту DDR5 с увеличенной пропускной способностью и высококачественным компонентам она способна обеспечить стабильно высокую производительность системы при запуске игр или ресурсоемких программ. Оперативная память поставляется комплектом из двух модулей объемом по 32 ГБ с частотой 6400 МГц. На планках G.Skill Trident Z5 Royal расположены алюминиевые радиаторы особой формы, которые отвечают за быстрое рассеивание тепла от микросхем памяти. Световая полоса в верхней части радиатора оснащена светодиодами с палитрой RGB. Фирменное ПО позволяет выполнять настройку параметров освещения и световых эффектов по предпочтениям пользователя.',
                'category_id' => $ramCategory->id, // Убедитесь, что переменная $ramCategory определена и содержит ID категории оперативной памяти
                'rating' => 0.0, // Условный рейтинг
            ]);

            $product->images()->createMany([
                ['image_path' => 'G.Skill Trident Z5 Royal.webp'],
                ['image_path' => 'G.Skill Trident Z5 Royal1.webp'],
                ['image_path' => 'G.Skill Trident Z5 Royal2.webp'], // Assuming this file exists
            ]);

            // Добавляем спецификации для G.Skill Trident Z5 Royal 64 ГБ
            ProductSpecification::create(['product_id'=>$product->id,'spec_key'=>'brand','spec_name'=>'Бренд','spec_value'=>'G.Skill','group'=>'Основные','is_filterable'=>true]);
            ProductSpecification::create(['product_id'=>$product->id,'spec_key'=>'series','spec_name'=>'Серия','spec_value'=>'Trident Z5 Royal','group'=>'Основные','is_filterable'=>true]);
             ProductSpecification::create(['product_id'=>$product->id,'spec_key'=>'model_code','spec_name'=>'Код модели','spec_value'=>'F5-6400J3239G32GX2-TR5S','group'=>'Основные','is_filterable'=>false]); // Добавляем код модели для точности
            ProductSpecification::create(['product_id'=>$product->id,'spec_key'=>'type','spec_name'=>'Тип памяти','spec_value'=>'DDR5','group'=>'Основные','is_filterable'=>true]);
            ProductSpecification::create(['product_id'=>$product->id,'spec_key'=>'capacity','spec_name'=>'Объем одного модуля','spec_value'=>'32 ГБ','group'=>'Основные','is_filterable'=>true]); // Указываем объем одного модуля
             ProductSpecification::create(['product_id'=>$product->id,'spec_key'=>'total_capacity','spec_name'=>'Суммарный объем памяти','spec_value'=>'64 ГБ','group'=>'Основные','is_filterable'=>true]); // Суммарный объем
            ProductSpecification::create(['product_id'=>$product->id,'spec_key'=>'modules_kit','spec_name'=>'Комплект','spec_value'=>'2x32 ГБ','group'=>'Основные','is_filterable'=>true]); // Указываем комплектность
            ProductSpecification::create(['product_id'=>$product->id,'spec_key'=>'frequency','spec_name'=>'Тактовая частота','spec_value'=>'6400 МГц','group'=>'Характеристики','is_filterable'=>true]);
            // Тайминги (латентность) важны, в коде модели указано C32, добавим
            ProductSpecification::create(['product_id'=>$product->id,'spec_key'=>'latency_cas','spec_name'=>'Латентность (CAS Latency)','spec_value'=>'CL32','group'=>'Характеристики','is_filterable'=>false]);
            ProductSpecification::create(['product_id'=>$product->id,'spec_key'=>'xmp_profile','spec_name'=>'Профиль XMP','spec_value'=>'Intel XMP 3.0','group'=>'Характеристики','is_filterable'=>false]); // Поддержка Intel XMP 3.0
            ProductSpecification::create(['product_id'=>$product->id,'spec_key'=>'features','spec_name'=>'Особенности','spec_value'=>'Алюминиевый радиатор, RGB подсветка, Настраиваемая подсветка (ПО)','group'=>'Особенности','is_filterable'=>false]);
            ProductSpecification::create(['product_id'=>$product->id,'spec_key'=>'form_factor','spec_name'=>'Форм-фактор','spec_value'=>'DIMM','group'=>'Основные','is_filterable'=>true]); // Форм-фактор для десктопной ОЗУ
        
            // Оперативная память Kingston FURY Renegade [KF436C16RB12K2/32] 32 ГБ
            $product = Product::create([
                'title' => 'Оперативная память Kingston FURY Renegade [KF436C16RB12K2/32] 32 ГБ',
                'price' => 7799, // Цена из описания
                'old_price' => null,
                'discount_percent' => 0,
                'in_stock' => true,
                'on_sale' => false,
                'is_bestseller' => false, // Нет информации, что хит продаж
                'is_new' => false, // Модель DDR4, скорее всего, не является "новинкой"
                'credit_available' => true,
                'img' => 'Kingston FURY Renegade.webp', // Предполагаемое имя файла изображения
                'product_type' => 'ram', // Тип продукта - оперативная память
                'country' => 'Тайвань', // Типичная страна производства для Kingston
                'color' => 'Черный', // Условный цвет (радиаторы черные)
                'qty' => 12, // Условное количество
                'description' => 'Двухранговая оперативная память Kingston FURY Renegade [KF436C16RB12K2/32] – 32-гигабайтный комплект из 2 модулей DDR4 по 16 ГБ. Набор рассчитан на оснащение игровых системных блоков. Разгонный потенциал устройства реализует технология XMP 2.0. Тактовая частота памяти составляет 3600 МГц. Тайминги модулей – 16-20-20. Совместимость набора с материнскими платами расширяет поддержка частот от 1600 МГц. Пропускная способность оперативной памяти Kingston FURY Renegade [KF436C16RB12K2/32] гарантирует высокую скорость обмена информацией между процессором и другими компонентами компьютера при использовании типового программного обеспечения. Комплект подходит для сборки и модернизации универсальных домашних и офисных системных блоков. Стабильность работы модулей улучшает технология ODT. Рабочий температурный диапазон устройства – от 0 до 85 °C. Память использует повышенное до 1.35 В напряжение питания. Избыточный нагрев модулей предотвращают радиаторы. Они увеличивают высоту памяти до 41.98 мм. Показатель важен при определении геометрической совместимости с кулером CPU.',
                'category_id' => $ramCategory->id, // Убедитесь, что переменная $ramCategory определена и содержит ID категории оперативной памяти
                'rating' => 0.0, // Условный рейтинг
            ]);

            $product->images()->createMany([
                ['image_path' => 'Kingston FURY Renegade.webp'],
                ['image_path' => 'Kingston FURY Renegade1.webp'],
                ['image_path' => 'Kingston FURY Renegade2.webp'], // Assuming this file exists
            ]);

            // Добавляем спецификации для Kingston FURY Renegade 32 ГБ
            ProductSpecification::create(['product_id'=>$product->id,'spec_key'=>'brand','spec_name'=>'Бренд','spec_value'=>'Kingston','group'=>'Основные','is_filterable'=>true]);
            ProductSpecification::create(['product_id'=>$product->id,'spec_key'=>'series','spec_name'=>'Серия','spec_value'=>'FURY Renegade','group'=>'Основные','is_filterable'=>true]);
             ProductSpecification::create(['product_id'=>$product->id,'spec_key'=>'model_code','spec_name'=>'Код модели','spec_value'=>'KF436C16RB12K2/32','group'=>'Основные','is_filterable'=>false]); // Добавляем код модели для точности
            ProductSpecification::create(['product_id'=>$product->id,'spec_key'=>'type','spec_name'=>'Тип памяти','spec_value'=>'DDR4','group'=>'Основные','is_filterable'=>true]);
            ProductSpecification::create(['product_id'=>$product->id,'spec_key'=>'capacity','spec_name'=>'Объем одного модуля','spec_value'=>'16 ГБ','group'=>'Основные','is_filterable'=>true]); // Указываем объем одного модуля
             ProductSpecification::create(['product_id'=>$product->id,'spec_key'=>'total_capacity','spec_name'=>'Суммарный объем памяти','spec_value'=>'32 ГБ','group'=>'Основные','is_filterable'=>true]); // Суммарный объем
            ProductSpecification::create(['product_id'=>$product->id,'spec_key'=>'modules_kit','spec_name'=>'Комплект','spec_value'=>'2x16 ГБ','group'=>'Основные','is_filterable'=>true]); // Указываем комплектность
            ProductSpecification::create(['product_id'=>$product->id,'spec_key'=>'frequency','spec_name'=>'Тактовая частота','spec_value'=>'3600 МГц','group'=>'Характеристики','is_filterable'=>true]);
            ProductSpecification::create(['product_id'=>$product->id,'spec_key'=>'min_frequency','spec_name'=>'Поддержка частот от','spec_value'=>'1600 МГц','group'=>'Характеристики','is_filterable'=>false]);
            ProductSpecification::create(['product_id'=>$product->id,'spec_key'=>'timings','spec_name'=>'Тайминги','spec_value'=>'16-20-20','group'=>'Характеристики','is_filterable'=>false]);
            ProductSpecification::create(['product_id'=>$product->id,'spec_key'=>'latency_cas','spec_name'=>'Латентность (CAS Latency)','spec_value'=>'CL16','group'=>'Характеристики','is_filterable'=>false]); // Отдельно CL для фильтрации, если потребуется
            ProductSpecification::create(['product_id'=>$product->id,'spec_key'=>'xmp_profile','spec_name'=>'Профиль XMP','spec_value'=>'XMP 2.0','group'=>'Характеристики','is_filterable'=>false]);
            ProductSpecification::create(['product_id'=>$product->id,'spec_key'=>'voltage','spec_name'=>'Напряжение питания','spec_value'=>'1.35 В','group'=>'Характеристики','is_filterable'=>false]);
            ProductSpecification::create(['product_id'=>$product->id,'spec_key'=>'features','spec_name'=>'Особенности','spec_value'=>'Двухранговая, Радиатор охлаждения, ODT','group'=>'Особенности','is_filterable'=>false]);
            ProductSpecification::create(['product_id'=>$product->id,'spec_key'=>'height','spec_name'=>'Высота','spec_value'=>'41.98 мм','group'=>'Внешний вид','is_filterable'=>false]); // Высота важна для совместимости
            ProductSpecification::create(['product_id'=>$product->id,'spec_key'=>'form_factor','spec_name'=>'Форм-фактор','spec_value'=>'DIMM','group'=>'Основные','is_filterable'=>true]); // Форм-фактор для десктопной ОЗУ
        
            // Процессор AMD Ryzen 7 7800X3D OEM
            $product = Product::create([
                'title' => 'Процессор AMD Ryzen 7 7800X3D OEM',
                'price' => 37799, // Цена из описания
                'old_price' => null,
                'discount_percent' => 0,
                'in_stock' => true,
                'on_sale' => false,
                'is_bestseller' => false, // Нет информации, что хит продаж
                'is_new' => true, // Актуальная модель
                'credit_available' => true,
                'img' => 'AMD Ryzen 7 7800X3D OEM.webp', // Предполагаемое имя файла изображения
                'product_type' => 'processor', // Тип продукта - процессор
                'country' => 'Китай', // Типичная страна производства для процессоров
                'color' => 'Серый', // У процессоров нет цвета как фильтруемой характеристики
                'qty' => 5, // Условное количество
                'description' => '8-ядерный процессор AMD Ryzen 7 7800X3D OEM – оснащение для игровых компьютеров и производительных универсальных ПК для дома или офиса. Модель базируется на архитектуре AMD Zen 4 и произведена по техпроцессу TSMC 5nm FinFET. Базовая частота процессора – 4.2 ГГц. CPU поддерживает до 16 потоков. В турборежиме частота процессора может повышаться до 5 ГГц. Любые операции ускоряют 8-мегабайтный кэш L2 и 96-мегабайтный кэш L3. Встроенный контроллер PCI Express соответствует версии 5.0 и поддерживает 24 линии. Особенность процессора AMD Ryzen 7 7800X3D OEM – наличие интегрированного графического ядра. GPU AMD Radeon Graphics работает на частотах до 2200 МГц. Производительность встроенной графики сопоставима с быстродействием видеокарт среднего класса. Процессор совместим с памятью DDR5, объем которой ограничен 128 ГБ. Максимальная частота ОЗУ – 5200 МГц. Возможно использование модулей с коррекцией ошибок. Процессор поддерживает аппаратную виртуализацию. Это расширяет функциональность ПК при работе с виртуальными машинами. OEM-комплектация модели означает отсутствие кулера.',
                'category_id' => $processorCategory->id, // Убедитесь, что переменная $processorCategory определена и содержит ID категории процессоров
                'rating' => 0.0, // Условный рейтинг
            ]);

            $product->images()->createMany([
                ['image_path' => 'AMD Ryzen 7 7800X3D OEM.webp'],
                ['image_path' => 'AMD Ryzen 7 7800X3D OEM1.webp'],
            ]);

            // Добавляем спецификации для AMD Ryzen 7 7800X3D OEM
            ProductSpecification::create(['product_id'=>$product->id,'spec_key'=>'brand','spec_name'=>'Бренд','spec_value'=>'AMD','group'=>'Основные','is_filterable'=>true]);
            ProductSpecification::create(['product_id'=>$product->id,'spec_key'=>'series','spec_name'=>'Серия','spec_value'=>'Ryzen 7','group'=>'Основные','is_filterable'=>true]);
            ProductSpecification::create(['product_id'=>$product->id,'spec_key'=>'model','spec_name'=>'Модель','spec_value'=>'7800X3D','group'=>'Основные','is_filterable'=>false]);
            ProductSpecification::create(['product_id'=>$product->id,'spec_key'=>'socket','spec_name'=>'Сокет','spec_value'=>'AM5','group'=>'Основные','is_filterable'=>true]); // Основываясь на архитектуре Zen 4
            ProductSpecification::create(['product_id'=>$product->id,'spec_key'=>'architecture','spec_name'=>'Архитектура','spec_value'=>'AMD Zen 4','group'=>'Основные','is_filterable'=>false]);
            ProductSpecification::create(['product_id'=>$product->id,'spec_key'=>'cores','spec_name'=>'Количество ядер','spec_value'=>'8','group'=>'Основные','is_filterable'=>true]);
            ProductSpecification::create(['product_id'=>$product->id,'spec_key'=>'threads','spec_name'=>'Количество потоков','spec_value'=>'16','group'=>'Основные','is_filterable'=>false]);
            ProductSpecification::create(['product_id'=>$product->id,'spec_key'=>'tech_process','spec_name'=>'Техпроцесс','spec_value'=>'TSMC 5nm FinFET','group'=>'Характеристики','is_filterable'=>false]);
            ProductSpecification::create(['product_id'=>$product->id,'spec_key'=>'base_frequency','spec_name'=>'Базовая частота','spec_value'=>'4.2 ГГц','group'=>'Производительность','is_filterable'=>false]);
            ProductSpecification::create(['product_id'=>$product->id,'spec_key'=>'boost_frequency','spec_name'=>'Максимальная частота в турбо режиме','spec_value'=>'5 ГГц','group'=>'Производительность','is_filterable'=>false]);
            ProductSpecification::create(['product_id'=>$product->id,'spec_key'=>'cache_l2','spec_name'=>'Объем кэша L2','spec_value'=>'8 МБ','group'=>'Производительность','is_filterable'=>false]);
            ProductSpecification::create(['product_id'=>$product->id,'spec_key'=>'cache_l3','spec_name'=>'Объем кэша L3','spec_value'=>'96 МБ','group'=>'Производительность','is_filterable'=>false]); // С учетом 3D V-Cache
            ProductSpecification::create(['product_id'=>$product->id,'spec_key'=>'integrated_graphics','spec_name'=>'Встроенная графика','spec_value'=>'AMD Radeon Graphics','group'=>'Графика','is_filterable'=>true]);
            ProductSpecification::create(['product_id'=>$product->id,'spec_key'=>'graphics_frequency','spec_name'=>'Частота графического ядра','spec_value'=>'2200 МГц','group'=>'Графика','is_filterable'=>false]);
            ProductSpecification::create(['product_id'=>$product->id,'spec_key'=>'pcie_version','spec_name'=>'Версия PCI Express','spec_value'=>'5.0','group'=>'Разъемы и интерфейсы','is_filterable'=>true]);
            ProductSpecification::create(['product_id'=>$product->id,'spec_key'=>'pcie_lanes','spec_name'=>'Количество линий PCI Express','spec_value'=>'24','group'=>'Разъемы и интерфейсы','is_filterable'=>false]);
            ProductSpecification::create(['product_id'=>$product->id,'spec_key'=>'memory_type','spec_name'=>'Тип поддерживаемой памяти','spec_value'=>'DDR5','group'=>'Память','is_filterable'=>true]);
            ProductSpecification::create(['product_id'=>$product->id,'spec_key'=>'max_memory','spec_name'=>'Максимальный объем памяти','spec_value'=>'128 ГБ','group'=>'Память','is_filterable'=>false]);
            ProductSpecification::create(['product_id'=>$product->id,'spec_key'=>'max_memory_frequency','spec_name'=>'Максимальная частота памяти','spec_value'=>'5200 МГц','group'=>'Память','is_filterable'=>false]);
            ProductSpecification::create(['product_id'=>$product->id,'spec_key'=>'ecc_support','spec_name'=>'Поддержка памяти с коррекцией ошибок (ECC)','spec_value'=>'Да','group'=>'Память','is_filterable'=>false]);
            ProductSpecification::create(['product_id'=>$product->id,'spec_key'=>'virtualization','spec_name'=>'Аппаратная виртуализация','spec_value'=>'Да','group'=>'Особенности','is_filterable'=>false]);
            ProductSpecification::create(['product_id'=>$product->id,'spec_key'=>'package_type','spec_name'=>'Комплектация','spec_value'=>'OEM','group'=>'Особенности','is_filterable'=>true]); // OEM/BOX
        
            // Процессор AMD Ryzen 5 7500F OEM
            $product = Product::create([
                'title' => 'Процессор AMD Ryzen 5 7500F OEM',
                'price' => 13799, // Цена из описания
                'old_price' => null,
                'discount_percent' => 0,
                'in_stock' => true,
                'on_sale' => false,
                'is_bestseller' => false, // Нет информации, что хит продаж
                'is_new' => true, // Актуальная модель
                'credit_available' => true,
                'img' => 'AMD Ryzen 5 7500F.webp', // Предполагаемое имя файла изображения
                'product_type' => 'processor', // Тип продукта - процессор
                'country' => 'Китай', // Типичная страна производства для процессоров
                'color' => 'Серый', // У процессоров нет цвета как фильтруемой характеристики
                'qty' => 10, // Условное количество
                'description' => 'Процессор AMD Ryzen 5 7500F – модель с 6 производительными ядрами и трехуровневым кэшем. Чипсет способен одновременно обрабатывать до 12 вычислительных потоков информации, благодаря чему система сможет справиться с одновременным запуском нескольких ресурсоемких программ. Функционирование с тактовой частотой 3.7 ГГц и поддержка оперативной памяти DDR5 с объемом до 128 ГБ позволит компьютерной сборке стабильно работать в многозадачном режиме. Процессор AMD Ryzen 5 7500F имеет свободный множитель для ручного разгона тактовой частоты без изменения частоты системной шины, что позволит добиться увеличения производительности системы. Поддержка режима ECC позволит использовать устройство в составе сервера вместе с модулями оперативной памяти с технологией коррекции ошибок. В модели отсутствует интегрированное графическое ядро, чтобы вы могли оснастить сборку видеокартой с нужными характеристиками.',
                'category_id' => $processorCategory->id, // Убедитесь, что переменная $processorCategory определена и содержит ID категории процессоров
                'rating' => 0.0, // Условный рейтинг
            ]);

            $product->images()->createMany([
                ['image_path' => 'AMD Ryzen 5 7500F.webp'],
                ['image_path' => 'AMD Ryzen 5 7500F.webp1'],
            ]);

            // Добавляем спецификации для AMD Ryzen 5 7500F OEM
            ProductSpecification::create(['product_id'=>$product->id,'spec_key'=>'brand','spec_name'=>'Бренд','spec_value'=>'AMD','group'=>'Основные','is_filterable'=>true]);
            ProductSpecification::create(['product_id'=>$product->id,'spec_key'=>'series','spec_name'=>'Серия','spec_value'=>'Ryzen 5','group'=>'Основные','is_filterable'=>true]);
            ProductSpecification::create(['product_id'=>$product->id,'spec_key'=>'model','spec_name'=>'Модель','spec_value'=>'7500F','group'=>'Основные','is_filterable'=>false]);
            ProductSpecification::create(['product_id'=>$product->id,'spec_key'=>'socket','spec_name'=>'Сокет','spec_value'=>'AM5','group'=>'Основные','is_filterable'=>true]); // Основываясь на архитектуре Zen 4
            ProductSpecification::create(['product_id'=>$product->id,'spec_key'=>'cores','spec_name'=>'Количество ядер','spec_value'=>'6','group'=>'Основные','is_filterable'=>true]);
            ProductSpecification::create(['product_id'=>$product->id,'spec_key'=>'threads','spec_name'=>'Количество потоков','spec_value'=>'12','group'=>'Основные','is_filterable'=>false]);
            ProductSpecification::create(['product_id'=>$product->id,'spec_key'=>'base_frequency','spec_name'=>'Базовая частота','spec_value'=>'3.7 ГГц','group'=>'Производительность','is_filterable'=>false]);
            ProductSpecification::create(['product_id'=>$product->id,'spec_key'=>'cache','spec_name'=>'Кэш','spec_value'=>'Трехуровневый','group'=>'Производительность','is_filterable'=>false]); // Объем не указан, указываем тип
            ProductSpecification::create(['product_id'=>$product->id,'spec_key'=>'integrated_graphics','spec_name'=>'Встроенная графика','spec_value'=>'Отсутствует','group'=>'Графика','is_filterable'=>true]);
            ProductSpecification::create(['product_id'=>$product->id,'spec_key'=>'memory_type','spec_name'=>'Тип поддерживаемой памяти','spec_value'=>'DDR5','group'=>'Память','is_filterable'=>true]);
            ProductSpecification::create(['product_id'=>$product->id,'spec_key'=>'max_memory','spec_name'=>'Максимальный объем памяти','spec_value'=>'128 ГБ','group'=>'Память','is_filterable'=>false]);
            ProductSpecification::create(['product_id'=>$product->id,'spec_key'=>'ecc_support','spec_name'=>'Поддержка памяти с коррекцией ошибок (ECC)','spec_value'=>'Да','group'=>'Память','is_filterable'=>false]);
             ProductSpecification::create(['product_id'=>$product->id,'spec_key'=>'unlocked_multiplier','spec_name'=>'Свободный множитель','spec_value'=>'Да','group'=>'Особенности','is_filterable'=>false]);
            ProductSpecification::create(['product_id'=>$product->id,'spec_key'=>'package_type','spec_name'=>'Комплектация','spec_value'=>'OEM','group'=>'Особенности','is_filterable'=>true]); // OEM/BOX
        
            // Процессор AMD Ryzen 7 9800X3D OEM
            $product = Product::create([
                'title' => 'Процессор AMD Ryzen 7 9800X3D OEM',
                'price' => 54299, // Цена из описания
                'old_price' => null,
                'discount_percent' => 0,
                'in_stock' => true,
                'on_sale' => false,
                'is_bestseller' => false, // Нет информации, что хит продаж
                'is_new' => true, // Основан на новой архитектуре Zen 5
                'credit_available' => true,
                'img' => 'MD Ryzen 7 9800X3D OEM.webp', // Предполагаемое имя файла изображения
                'product_type' => 'processor', // Тип продукта - процессор
                'country' => 'Китай', // Типичная страна производства для процессоров
                'color' => "Серый", // У процессоров нет цвета как фильтруемой характеристики
                'qty' => 5, // Условное количество
                'description' => 'Построенный на архитектуре Zen 5 процессор AMD Ryzen 7 9800X3D OEM предусматривает 8 ядер, которые обрабатывают до 16 потоков информации и обеспечивают всю систему быстродействием при повышенных нагрузках. Использование сокета AM5 для подключения гарантирует совместимость чипсета с различными моделями материнских плат. Процессор AMD Ryzen 7 9800X3D OEM функционирует с тактовой частотой 4.7 ГГц и поддерживает разгон до 5.2 ГГц для стабильной работы компьютера при запуске ресурсоемких программ. Высокая энергоэффективность чипсета подтверждается тепловыделением на уровне 120 Вт.',
                'category_id' => $processorCategory->id, // Убедитесь, что переменная $processorCategory определена и содержит ID категории процессоров
                'rating' => 0.0, // Условный рейтинг
            ]);

            $product->images()->createMany([
                ['image_path' => 'MD Ryzen 7 9800X3D OEM.webp'],
                ['image_path' => 'MD Ryzen 7 9800X3D OEM1.webp'],
            ]);

            // Добавляем спецификации для AMD Ryzen 7 9800X3D OEM
            ProductSpecification::create(['product_id'=>$product->id,'spec_key'=>'brand','spec_name'=>'Бренд','spec_value'=>'AMD','group'=>'Основные','is_filterable'=>true]);
            ProductSpecification::create(['product_id'=>$product->id,'spec_key'=>'series','spec_name'=>'Серия','spec_value'=>'Ryzen 7','group'=>'Основные','is_filterable'=>true]);
            ProductSpecification::create(['product_id'=>$product->id,'spec_key'=>'model','spec_name'=>'Модель','spec_value'=>'9800X3D','group'=>'Основные','is_filterable'=>false]);
            ProductSpecification::create(['product_id'=>$product->id,'spec_key'=>'socket','spec_name'=>'Сокет','spec_value'=>'AM5','group'=>'Основные','is_filterable'=>true]);
            ProductSpecification::create(['product_id'=>$product->id,'spec_key'=>'architecture','spec_name'=>'Архитектура','spec_value'=>'AMD Zen 5','group'=>'Основные','is_filterable'=>false]);
            ProductSpecification::create(['product_id'=>$product->id,'spec_key'=>'cores','spec_name'=>'Количество ядер','spec_value'=>'8','group'=>'Основные','is_filterable'=>true]);
            ProductSpecification::create(['product_id'=>$product->id,'spec_key'=>'threads','spec_name'=>'Количество потоков','spec_value'=>'16','group'=>'Основные','is_filterable'=>false]);
            ProductSpecification::create(['product_id'=>$product->id,'spec_key'=>'base_frequency','spec_name'=>'Базовая частота','spec_value'=>'4.7 ГГц','group'=>'Производительность','is_filterable'=>false]);
            ProductSpecification::create(['product_id'=>$product->id,'spec_key'=>'boost_frequency','spec_name'=>'Максимальная частота в турбо режиме','spec_value'=>'5.2 ГГц','group'=>'Производительность','is_filterable'=>false]);
            // Объем кэша L3 для 9800X3D должен быть 96МБ, основываясь на других X3D моделях. Добавим.
            ProductSpecification::create(['product_id'=>$product->id,'spec_key'=>'cache_l3','spec_name'=>'Объем кэша L3','spec_value'=>'96 МБ','group'=>'Производительность','is_filterable'=>false]);
            // Описание не упоминает встроенную графику, но 9800X3D, как правило, ее не имеет. Укажем явно.
            ProductSpecification::create(['product_id'=>$product->id,'spec_key'=>'integrated_graphics','spec_name'=>'Встроенная графика','spec_value'=>'Отсутствует','group'=>'Графика','is_filterable'=>true]);
            // Описание не упоминает PCIe и память, но для Zen 5 AM5 это PCIe 5.0 и DDR5. Добавим.
            ProductSpecification::create(['product_id'=>$product->id,'spec_key'=>'pcie_version','spec_name'=>'Версия PCI Express','spec_value'=>'5.0','group'=>'Разъемы и интерфейсы','is_filterable'=>true]);
            ProductSpecification::create(['product_id'=>$product->id,'spec_key'=>'memory_type','spec_name'=>'Тип поддерживаемой памяти','spec_value'=>'DDR5','group'=>'Память','is_filterable'=>true]);
            ProductSpecification::create(['product_id'=>$product->id,'spec_key'=>'tdp','spec_name'=>'Тепловыделение (TDP)','spec_value'=>'120 Вт','group'=>'Энергопотребление','is_filterable'=>false]);
            ProductSpecification::create(['product_id'=>$product->id,'spec_key'=>'package_type','spec_name'=>'Комплектация','spec_value'=>'OEM','group'=>'Особенности','is_filterable'=>true]); // OEM/BOX
        
                        // Процессор AMD Ryzen 5 5600 OEM
                        $product = Product::create([
                            'title' => 'Процессор AMD Ryzen 5 5600 OEM',
                            'price' => 8099, // Цена из описания
                            'old_price' => null,
                            'discount_percent' => 0,
                            'in_stock' => true,
                            'on_sale' => false,
                            'is_bestseller' => false, // Нет информации, что хит продаж
                            'is_new' => false, // Модель не является новинкой
                            'credit_available' => true,
                            'img' => 'Процессор AMD Ryzen 5 5600 OEM.webp', // Предполагаемое имя файла изображения
                            'product_type' => 'processor', // Тип продукта - процессор
                            'country' => 'Китай', // Типичная страна производства для процессоров
                            'color' => 'Серый', // У процессоров нет цвета как фильтруемой характеристики
                            'qty' => 15, // Условное количество
                            'description' => 'Процессор AMD Ryzen 5 5600 OEM поможет повысить производительность игрового ПК. Для этого его архитектура, основанная на 7-нм техпроцессе, представлена 6 ядрами, которые выполняют операции в 12-поточном режиме. Рабочая частота составляет 3.5 ГГц и может быть автоматически увеличена до 4.4 ГГц, что предупреждает торможение процессов загрузки. Наличие свободного множителя позволит еще увеличить частоту, чтобы улучшить игровые параметры. Поддержка виртуализации оптимизирует использование мощностей чипа. К процессору AMD Ryzen 5 5600 OEM можно подключить 2 модуля оперативной памяти, работающих с частотой 3200 МГц и имеющих объем 128 ГБ. В совокупности с кэш-памятью L3 на 32 МБ это поможет быстро выполнять действия в многозадачном режиме, с комфортом работать в сложных программах, загружать игровые локации без искажения. Низкое энергопотребление предусматривает тепловыделение на уровне 65 Вт. Благодаря этому процессор не перегревается даже при нагрузке.',
                            'category_id' => $processorCategory->id, // Убедитесь, что переменная $processorCategory определена и содержит ID категории процессоров
                            'rating' => 0.0, // Условный рейтинг
                        ]);

                        $product->images()->createMany([
                            ['image_path' => 'Процессор AMD Ryzen 5 5600 OEM.webp'],
                            ['image_path' => 'Процессор AMD Ryzen 5 5600 OEM1.webp'],
                        ]);
            
                        // Добавляем спецификации для AMD Ryzen 5 5600 OEM
                        ProductSpecification::create(['product_id'=>$product->id,'spec_key'=>'brand','spec_name'=>'Бренд','spec_value'=>'AMD','group'=>'Основные','is_filterable'=>true]);
                        ProductSpecification::create(['product_id'=>$product->id,'spec_key'=>'series','spec_name'=>'Серия','spec_value'=>'Ryzen 5','group'=>'Основные','is_filterable'=>true]);
                        ProductSpecification::create(['product_id'=>$product->id,'spec_key'=>'model','spec_name'=>'Модель','spec_value'=>'5600','group'=>'Основные','is_filterable'=>false]);
                        ProductSpecification::create(['product_id'=>$product->id,'spec_key'=>'socket','spec_name'=>'Сокет','spec_value'=>'AM4','group'=>'Основные','is_filterable'=>true]); // Основываясь на модели 5600
                        ProductSpecification::create(['product_id'=>$product->id,'spec_key'=>'architecture','spec_name'=>'Архитектура','spec_value'=>'Zen 3','group'=>'Основные','is_filterable'=>false]); // Основываясь на модели 5600
                        ProductSpecification::create(['product_id'=>$product->id,'spec_key'=>'cores','spec_name'=>'Количество ядер','spec_value'=>'6','group'=>'Основные','is_filterable'=>true]);
                        ProductSpecification::create(['product_id'=>$product->id,'spec_key'=>'threads','spec_name'=>'Количество потоков','spec_value'=>'12','group'=>'Основные','is_filterable'=>false]);
                        ProductSpecification::create(['product_id'=>$product->id,'spec_key'=>'tech_process','spec_name'=>'Техпроцесс','spec_value'=>'7 нм','group'=>'Характеристики','is_filterable'=>false]);
                        ProductSpecification::create(['product_id'=>$product->id,'spec_key'=>'base_frequency','spec_name'=>'Базовая частота','spec_value'=>'3.5 ГГц','group'=>'Производительность','is_filterable'=>false]);
                        ProductSpecification::create(['product_id'=>$product->id,'spec_key'=>'boost_frequency','spec_name'=>'Максимальная частота','spec_value'=>'4.4 ГГц','group'=>'Производительность','is_filterable'=>false]);
                        ProductSpecification::create(['product_id'=>$product->id,'spec_key'=>'cache_l3','spec_name'=>'Объем кэша L3','spec_value'=>'32 МБ','group'=>'Производительность','is_filterable'=>false]);
                        ProductSpecification::create(['product_id'=>$product->id,'spec_key'=>'integrated_graphics','spec_name'=>'Встроенная графика','spec_value'=>'Отсутствует','group'=>'Графика','is_filterable'=>true]);
                        ProductSpecification::create(['product_id'=>$product->id,'spec_key'=>'memory_type','spec_name'=>'Тип поддерживаемой памяти','spec_value'=>'DDR4','group'=>'Память','is_filterable'=>true]); // Основываясь на модели 5600
                        ProductSpecification::create(['product_id'=>$product->id,'spec_key'=>'max_memory','spec_name'=>'Максимальный объем памяти','spec_value'=>'128 ГБ','group'=>'Память','is_filterable'=>false]);
                        ProductSpecification::create(['product_id'=>$product->id,'spec_key'=>'max_memory_frequency','spec_name'=>'Максимальная частота памяти','spec_value'=>'3200 МГц','group'=>'Память','is_filterable'=>false]);
                        ProductSpecification::create(['product_id'=>$product->id,'spec_key'=>'virtualization','spec_name'=>'Поддержка виртуализации','spec_value'=>'Да','group'=>'Особенности','is_filterable'=>false]);
                        ProductSpecification::create(['product_id'=>$product->id,'spec_key'=>'unlocked_multiplier','spec_name'=>'Свободный множитель','spec_value'=>'Да','group'=>'Особенности','is_filterable'=>false]);
                        ProductSpecification::create(['product_id'=>$product->id,'spec_key'=>'tdp','spec_name'=>'Тепловыделение (TDP)','spec_value'=>'65 Вт','group'=>'Энергопотребление','is_filterable'=>false]);
                        ProductSpecification::create(['product_id'=>$product->id,'spec_key'=>'package_type','spec_name'=>'Комплектация','spec_value'=>'OEM','group'=>'Особенности','is_filterable'=>true]); // OEM/BOX
                    

                                    // Процессор Intel Core i5-12400 OEM
            $product = Product::create([
                'title' => 'Процессор Intel Core i5-12400 OEM',
                'price' => 12299, // Цена из описания
                'old_price' => null,
                'discount_percent' => 0,
                'in_stock' => true,
                'on_sale' => false,
                'is_bestseller' => false, // Нет информации, что хит продаж
                'is_new' => false, // Модель не является новинкой
                'credit_available' => true,
                'img' => 'Процессор Intel Core i5-12400 OEM.webp', // Предполагаемое имя файла изображения
                'product_type' => 'processor', // Тип продукта - процессор
                'country' => 'Китай', // Типичная страна производства для процессоров
                'color' => 'Серый', // У процессоров нет цвета как фильтруемой характеристики
                'qty' => 15, // Условное количество
                'description' => 'Процессор Intel Core i5-12400 OEM является одним из представителей линейки процессоров Intel Alder Lake. Устройство имеет 6 ядер и 12 потоков, что позволяет выполнять множество задач одновременно. Оно также поддерживает технологию Hyper-Threading, которая повышает производительность. Частота процессора составляет 2.5 ГГц и может достигать 4.4 ГГц в режиме Turbo Boost. Он также обладает кэш-памятью L3 в размере 16 МБ, что способствует более быстрому доступу к данным. Устройство поддерживает технологию Intel Thermal Velocity Boost, которая автоматически повышает частоту при наличии дополнительного теплового запаса. Это позволяет достичь большей производительности при выполнении требовательных задач. Core i5-12400 OEM дополнен интегрированной графикой Intel UHD Graphics 730. Она обеспечивает достойную производительность для повседневных задач, воспроизведения видео и «легкого» гейминга.',
                'category_id' => $processorCategory->id, // Убедитесь, что переменная $processorCategory определена и содержит ID категории процессоров
                'rating' => 0.0, // Условный рейтинг
            ]);

            $product->images()->createMany([
                ['image_path' => 'Процессор Intel Core i5-12400 OEM.webp'],
                ['image_path' => 'Процессор Intel Core i5-12400 OEM1.webp'],
            ]);

            // Добавляем спецификации для Intel Core i5-12400 OEM
            ProductSpecification::create(['product_id'=>$product->id,'spec_key'=>'brand','spec_name'=>'Бренд','spec_value'=>'Intel','group'=>'Основные','is_filterable'=>true]);
            ProductSpecification::create(['product_id'=>$product->id,'spec_key'=>'series','spec_name'=>'Серия','spec_value'=>'Core i5','group'=>'Основные','is_filterable'=>true]);
            ProductSpecification::create(['product_id'=>$product->id,'spec_key'=>'model','spec_name'=>'Модель','spec_value'=>'12400','group'=>'Основные','is_filterable'=>false]);
            ProductSpecification::create(['product_id'=>$product->id,'spec_key'=>'socket','spec_name'=>'Сокет','spec_value'=>'LGA 1700','group'=>'Основные','is_filterable'=>true]); // Для Alder Lake
            ProductSpecification::create(['product_id'=>$product->id,'spec_key'=>'architecture','spec_name'=>'Архитектура','spec_value'=>'Intel Alder Lake','group'=>'Основные','is_filterable'=>false]);
            ProductSpecification::create(['product_id'=>$product->id,'spec_key'=>'cores','spec_name'=>'Количество ядер','spec_value'=>'6','group'=>'Основные','is_filterable'=>true]);
            ProductSpecification::create(['product_id'=>$product->id,'spec_key'=>'threads','spec_name'=>'Количество потоков','spec_value'=>'12','group'=>'Основные','is_filterable'=>false]);
            ProductSpecification::create(['product_id'=>$product->id,'spec_key'=>'base_frequency','spec_name'=>'Базовая частота','spec_value'=>'2.5 ГГц','group'=>'Производительность','is_filterable'=>false]);
            ProductSpecification::create(['product_id'=>$product->id,'spec_key'=>'boost_frequency','spec_name'=>'Максимальная частота в режиме Turbo Boost','spec_value'=>'4.4 ГГц','group'=>'Производительность','is_filterable'=>false]);
            ProductSpecification::create(['product_id'=>$product->id,'spec_key'=>'cache_l3','spec_name'=>'Объем кэша L3','spec_value'=>'18 МБ','group'=>'Производительность','is_filterable'=>false]); // Фактический объем L3 у 12400 - 18 МБ, описание указало 16 МБ, но 18 МБ точнее.
            ProductSpecification::create(['product_id'=>$product->id,'spec_key'=>'integrated_graphics','spec_name'=>'Встроенная графика','spec_value'=>'Intel UHD Graphics 730','group'=>'Графика','is_filterable'=>true]);
            // Частоту графики описание не указало.
            ProductSpecification::create(['product_id'=>$product->id,'spec_key'=>'pcie_version','spec_name'=>'Версия PCI Express','spec_value'=>'5.0 (для CPU)', 'group'=>'Разъемы и интерфейсы','is_filterable'=>true]); // CPU поддерживает 5.0
            ProductSpecification::create(['product_id'=>$product->id,'spec_key'=>'memory_type','spec_name'=>'Тип поддерживаемой памяти','spec_value'=>'DDR4, DDR5','group'=>'Память','is_filterable'=>true]); // Поддерживает оба типа
            ProductSpecification::create(['product_id'=>$product->id,'spec_key'=>'tdp','spec_name'=>'Тепловыделение (TDP)','spec_value'=>'65 Вт', 'group'=>'Энергопотребление','is_filterable'=>false]); // Базовый TDP
            ProductSpecification::create(['product_id'=>$product->id,'spec_key'=>'features','spec_name'=>'Особенности','spec_value'=>'Hyper-Threading, Turbo Boost, Thermal Velocity Boost','group'=>'Особенности','is_filterable'=>false]);
            ProductSpecification::create(['product_id'=>$product->id,'spec_key'=>'package_type','spec_name'=>'Комплектация','spec_value'=>'OEM','group'=>'Особенности','is_filterable'=>true]); // OEM/BOX
        
            //системы охлаждения

            $coolingCategory = \App\Models\Category::where('name', 'Системы охлаждения')->first();


            $product = Product::create([
                'title' => 'Система охлаждения MSI MAG CORELIQUID E360',
                'price' => 13499, // Цена из описания
                'old_price' => null,
                'discount_percent' => 0,
                'in_stock' => true,
                'on_sale' => false,
                'is_bestseller' => false,
                'is_new' => true,
                'credit_available' => true,
                'img' => 'Система охлаждения MSI MAG CORELIQUID E360.webp', // Предполагаемое имя файла изображения
                'product_type' => 'cooling', // Тип продукта - система охлаждения
                'country' => 'Китай', // Типичная страна производства для MSI
                'color' => 'Черный', // Обычно черный радиатор и вентиляторы
                'qty' => 7, // Условное количество
                'description' => 'Компания MSI представляет серию MAG CORELIQUID E – высокопроизводительные системы водяного охлаждения с оригинальным дизайном, в котором прослеживается концепция течения не только воды, но и времени, примером чего является солнечное затмение. Верхняя часть водоблока вращается на 270°, поэтому ее легко можно выровнять в нужном положении, не переустанавливая всю систему охлаждения. Благодаря увеличенной зоне контакта медного основания водоблока улучшен отвод тепла от центрального процессора, а общая эффективность системы повышена за счет большой плотности микроканалов. При использовании системы водяного охлаждения MSI совместно с материнской платой MSI скорость вращения ее вентиляторов и работу помпы можно регулировать через приложение MSI CENTER. Гибкая настройка параметров аппаратных компонентов поможет оптимизировать компьютер под различные сценарии. Добавить ярких красок своей компьютерной системе можно с помощью подсветки Mystic Light, которая способна отображать 16,8 млн. различных оттенков и множество динамических визуальных эффектов. Управление ей осуществляется через соответствующее приложение MSI.',
                'category_id' => $coolingCategory->id, // Убедитесь, что переменная $coolingCategory определена и содержит ID категории систем охлаждения
                'rating' => 0.0, // Условный рейтинг
            ]);

            
            $product->images()->createMany([
                ['image_path' => 'Система охлаждения MSI MAG CORELIQUID E360.webp'],
                ['image_path' => 'Система охлаждения MSI MAG CORELIQUID E3601.webp'],
                ['image_path' => 'Система охлаждения MSI MAG CORELIQUID E3602.webp'],
            ]);

            // Добавляем спецификации для MSI MAG CORELIQUID E360
            ProductSpecification::create(['product_id'=>$product->id,'spec_key'=>'brand','spec_name'=>'Бренд','spec_value'=>'MSI','group'=>'Основные','is_filterable'=>true]);
            ProductSpecification::create(['product_id'=>$product->id,'spec_key'=>'series','spec_name'=>'Серия','spec_value'=>'MAG CORELIQUID E','group'=>'Основные','is_filterable'=>true]);
            ProductSpecification::create(['product_id'=>$product->id,'spec_key'=>'model','spec_name'=>'Модель','spec_value'=>'E360','group'=>'Основные','is_filterable'=>false]);
            ProductSpecification::create(['product_id'=>$product->id,'spec_key'=>'type','spec_name'=>'Тип охлаждения','spec_value'=>'Жидкостное (СВО)','group'=>'Основные','is_filterable'=>true]);
            ProductSpecification::create(['product_id'=>$product->id,'spec_key'=>'radiator_size','spec_name'=>'Размер радиатора','spec_value'=>'360 мм','group'=>'Основные','is_filterable'=>true]);
            ProductSpecification::create(['product_id'=>$product->id,'spec_key'=>'block_rotation','spec_name'=>'Поворот водоблока','spec_value'=>'270°','group'=>'Особенности','is_filterable'=>false]);
            ProductSpecification::create(['product_id'=>$product->id,'spec_key'=>'base_material','spec_name'=>'Основание водоблока','spec_value'=>'Медь','group'=>'Особенности','is_filterable'=>false]);
            ProductSpecification::create(['product_id'=>$product->id,'spec_key'=>'microchannel_density','spec_name'=>'Плотность микроканалов','spec_value'=>'Высокая','group'=>'Особенности','is_filterable'=>false]);
            ProductSpecification::create(['product_id'=>$product->id,'spec_key'=>'fan_control','spec_name'=>'Управление вентиляторами','spec_value'=>'MSI CENTER','group'=>'Особенности','is_filterable'=>false]);
            ProductSpecification::create(['product_id'=>$product->id,'spec_key'=>'pump_control','spec_name'=>'Управление помпой','spec_value'=>'MSI CENTER','group'=>'Особенности','is_filterable'=>false]);
            ProductSpecification::create(['product_id'=>$product->id,'spec_key'=>'rgb','spec_name'=>'Подсветка','spec_value'=>'Mystic Light RGB, 16.8 млн цветов','group'=>'Особенности','is_filterable'=>true]);
            ProductSpecification::create(['product_id'=>$product->id,'spec_key'=>'color','spec_name'=>'Цвет','spec_value'=>'Черный','group'=>'Внешний вид','is_filterable'=>true]);
        
            $product = Product::create([
                'title' => 'Система охлаждения MSI MEG CORELIQUID S360',
                'price' => 19999, // Цена из описания
                'old_price' => null,
                'discount_percent' => 0,
                'in_stock' => true,
                'on_sale' => false,
                'is_bestseller' => false,
                'is_new' => true,
                'credit_available' => true,
                'img' => 'Система охлаждения MSI MEG CORELIQUID S360.webp', // Предполагаемое имя файла изображения
                'product_type' => 'cooling', // Тип продукта - система охлаждения
                'country' => 'Китай', // Типичная страна производства для MSI
                'color' => 'Черный', // Обычно черный радиатор и вентиляторы
                'qty' => 5, // Условное количество
                'description' => 'Жидкостная система охлаждения MSI MEG CORELIQUID S360 подходит для охлаждения высокопроизводительного процессора, в том числе – используемого в составе игрового компьютера высокого класса. Устройство совместимо с подавляющим большинством распространенных в настоящее время сокетов. СЖО оснащена крупным, но легким радиатором, произведенным с использованием высококачественного алюминия. Материал водоблока – медь. Главная особенность модели – наличие дисплея, на который выводится информация о температуре процессора. Система охлаждения MSI MEG CORELIQUID S360 оборудована тремя 120-миллиметровыми вентиляторами, которые могут останавливаться. Максимальная частота вращения вентиляторов высока – 2000 оборотов в минуту. Регулировка скорости вращения осуществляется в автоматическом режиме. При максимальной скорости каждый из вентиляторов генерирует воздушный поток 56.2 CFM. Уровень шума при этом невысок – 22.7 дБ. Система является необслуживаемой. Это означает, что замена компонентов и хладагента не осуществляется.',
                'category_id' => $coolingCategory->id, // Убедитесь, что переменная $coolingCategory определена и содержит ID категории систем охлаждения
                'rating' => 0.0, // Условный рейтинг
            ]);

            $product->images()->createMany([
                ['image_path' => 'Система охлаждения MSI MEG CORELIQUID S360.webp'],
                ['image_path' => 'Система охлаждения MSI MEG CORELIQUID S3601.webp'],
                ['image_path' => 'Система охлаждения MSI MEG CORELIQUID S3602.webp'],
            ]);

            // Добавляем спецификации для MSI MEG CORELIQUID S360
            ProductSpecification::create(['product_id'=>$product->id,'spec_key'=>'brand','spec_name'=>'Бренд','spec_value'=>'MSI','group'=>'Основные','is_filterable'=>true]);
            ProductSpecification::create(['product_id'=>$product->id,'spec_key'=>'series','spec_name'=>'Серия','spec_value'=>'MEG CORELIQUID S','group'=>'Основные','is_filterable'=>true]);
            ProductSpecification::create(['product_id'=>$product->id,'spec_key'=>'model','spec_name'=>'Модель','spec_value'=>'S360','group'=>'Основные','is_filterable'=>false]);
            ProductSpecification::create(['product_id'=>$product->id,'spec_key'=>'type','spec_name'=>'Тип охлаждения','spec_value'=>'Жидкостное (СВО)','group'=>'Основные','is_filterable'=>true]);
            ProductSpecification::create(['product_id'=>$product->id,'spec_key'=>'radiator_size','spec_name'=>'Размер радиатора','spec_value'=>'360 мм','group'=>'Основные','is_filterable'=>true]);
            ProductSpecification::create(['product_id'=>$product->id,'spec_key'=>'radiator_material','spec_name'=>'Материал радиатора','spec_value'=>'Алюминий','group'=>'Особенности','is_filterable'=>false]);
            ProductSpecification::create(['product_id'=>$product->id,'spec_key'=>'block_material','spec_name'=>'Материал водоблока','spec_value'=>'Медь','group'=>'Особенности','is_filterable'=>false]);
            ProductSpecification::create(['product_id'=>$product->id,'spec_key'=>'display','spec_name'=>'Дисплей','spec_value'=>'Есть, отображение температуры','group'=>'Особенности','is_filterable'=>true]);
            ProductSpecification::create(['product_id'=>$product->id,'spec_key'=>'fan_count','spec_name'=>'Количество вентиляторов','spec_value'=>'3 x 120 мм','group'=>'Охлаждение','is_filterable'=>false]);
            ProductSpecification::create(['product_id'=>$product->id,'spec_key'=>'fan_speed','spec_name'=>'Максимальная скорость вращения вентиляторов','spec_value'=>'2000 об/мин','group'=>'Охлаждение','is_filterable'=>false]);
            ProductSpecification::create(['product_id'=>$product->id,'spec_key'=>'airflow','spec_name'=>'Максимальный воздушный поток','spec_value'=>'56.2 CFM','group'=>'Охлаждение','is_filterable'=>false]);
            ProductSpecification::create(['product_id'=>$product->id,'spec_key'=>'noise_level','spec_name'=>'Уровень шума','spec_value'=>'22.7 дБ','group'=>'Охлаждение','is_filterable'=>false]);
            ProductSpecification::create(['product_id'=>$product->id,'spec_key'=>'fan_control','spec_name'=>'Управление вентиляторами','spec_value'=>'Автоматическое','group'=>'Особенности','is_filterable'=>false]);
            ProductSpecification::create(['product_id'=>$product->id,'spec_key'=>'serviceability','spec_name'=>'Обслуживание','spec_value'=>'Необслуживаемая','group'=>'Особенности','is_filterable'=>false]);
            ProductSpecification::create(['product_id'=>$product->id,'spec_key'=>'compatibility','spec_name'=>'Совместимость с сокетами','spec_value'=>'Широкая (Intel/AMD)','group'=>'Особенности','is_filterable'=>false]);
            ProductSpecification::create(['product_id'=>$product->id,'spec_key'=>'color','spec_name'=>'Цвет','spec_value'=>'Черный','group'=>'Внешний вид','is_filterable'=>true]);


                        // Система охлаждения DEEPCOOL LT720
                        $product = Product::create([
                            'title' => 'Система охлаждения DEEPCOOL LT720',
                            'price' => 11599, // Цена из описания
                            'old_price' => null,
                            'discount_percent' => 0,
                            'in_stock' => true,
                            'on_sale' => false,
                            'is_bestseller' => false,
                            'is_new' => true,
                            'credit_available' => true,
                            'img' => 'Система охлаждения DEEPCOOL LT720.webp', // Предполагаемое имя файла изображения
                            'product_type' => 'cooling', // Тип продукта - система охлаждения
                            'country' => 'Китай', // Типичная страна производства для DeepCool
                            'color' => 'Черный', // Обычно черный радиатор и вентиляторы
                            'qty' => 8, // Условное количество
                            'description' => 'Система охлаждения DeepCool LT720 оснащена мощным насосом, эргономичной конструкцией радиатора и 3 кулерами по 12 см. Водоблок дополнен ARGB-подсветкой с эффектом бесконечного зеркала. Вы можете управлять эффектами свечения и настраивать их синхронную работу. Вентиляторы с подшипниками скольжения действуют тихо, прочные лопасти сохраняют устойчивость на любой скорости. DeepCool LT720 оснащена мощным водяным насосом с микроканалами и медной пластиной для эффективного охлаждения. СЖО идет в комплекте с набором крепежей, которые помогают интуитивно понимать особенности установки. Гибкие трубки обладают надежной защитой от протечек. Благодаря функции PWM кулеры увеличивают мощность при сильном нагреве и экономят энергопотребление при нормальной температуре.',
                            'category_id' => $coolingCategory->id, // Убедитесь, что переменная $coolingCategory определена и содержит ID категории систем охлаждения
                            'rating' => 0.0, // Условный рейтинг
                        ]);

                        
                        $product->images()->createMany([
                            ['image_path' => 'Система охлаждения DEEPCOOL LT720.webp'],
                            ['image_path' => 'Система охлаждения DEEPCOOL LT7201.webp'],
                            ['image_path' => 'Система охлаждения DEEPCOOL LT7202.webp'],
                        ]);
            
                        // Добавляем спецификации для DEEPCOOL LT720
                        ProductSpecification::create(['product_id'=>$product->id,'spec_key'=>'brand','spec_name'=>'Бренд','spec_value'=>'DEEPCOOL','group'=>'Основные','is_filterable'=>true]);
                        ProductSpecification::create(['product_id'=>$product->id,'spec_key'=>'model','spec_name'=>'Модель','spec_value'=>'LT720','group'=>'Основные','is_filterable'=>false]);
                        ProductSpecification::create(['product_id'=>$product->id,'spec_key'=>'type','spec_name'=>'Тип охлаждения','spec_value'=>'Жидкостное (СВО)','group'=>'Основные','is_filterable'=>true]);
                        ProductSpecification::create(['product_id'=>$product->id,'spec_key'=>'radiator_size','spec_name'=>'Размер радиатора','spec_value'=>'360 мм','group'=>'Основные','is_filterable'=>true]);
                        ProductSpecification::create(['product_id'=>$product->id,'spec_key'=>'fan_count','spec_name'=>'Количество вентиляторов','spec_value'=>'3 x 120 мм','group'=>'Охлаждение','is_filterable'=>false]);
                        ProductSpecification::create(['product_id'=>$product->id,'spec_key'=>'fan_bearing','spec_name'=>'Тип подшипника','spec_value'=>'Гидродинамический (скольжения)','group'=>'Охлаждение','is_filterable'=>false]);
                        ProductSpecification::create(['product_id'=>$product->id,'spec_key'=>'block_material','spec_name'=>'Материал водоблока','spec_value'=>'Медь','group'=>'Особенности','is_filterable'=>false]);
                        ProductSpecification::create(['product_id'=>$product->id,'spec_key'=>'radiator_material','spec_name'=>'Материал радиатора','spec_value'=>'Алюминий','group'=>'Особенности','is_filterable'=>false]);
                        ProductSpecification::create(['product_id'=>$product->id,'spec_key'=>'microchannel','spec_name'=>'Микроканалы','spec_value'=>'Есть','group'=>'Особенности','is_filterable'=>false]);
                        ProductSpecification::create(['product_id'=>$product->id,'spec_key'=>'mirror_effect','spec_name'=>'Подсветка','spec_value'=>'ARGB, эффект бесконечного зеркала','group'=>'Особенности','is_filterable'=>true]);
                        ProductSpecification::create(['product_id'=>$product->id,'spec_key'=>'fan_control','spec_name'=>'Управление вентиляторами','spec_value'=>'PWM','group'=>'Особенности','is_filterable'=>false]);
                        ProductSpecification::create(['product_id'=>$product->id,'spec_key'=>'serviceability','spec_name'=>'Обслуживание','spec_value'=>'Необслуживаемая','group'=>'Особенности','is_filterable'=>false]);
                        ProductSpecification::create(['product_id'=>$product->id,'spec_key'=>'tubing','spec_name'=>'Трубки','spec_value'=>'Гибкие, защита от протечек','group'=>'Особенности','is_filterable'=>false]);
                        ProductSpecification::create(['product_id'=>$product->id,'spec_key'=>'color','spec_name'=>'Цвет','spec_value'=>'Черный','group'=>'Внешний вид','is_filterable'=>true]);
                    

                                    // Система охлаждения Arctic Cooling Liquid Freezer III 360 A-RGB
            $product = Product::create([
                'title' => 'Система охлаждения Arctic Cooling Liquid Freezer III 360 A-RGB',
                'price' => 9999, // Цена из описания
                'old_price' => null,
                'discount_percent' => 0,
                'in_stock' => true,
                'on_sale' => false,
                'is_bestseller' => false,
                'is_new' => true, // Предположим, что актуальная модель
                'credit_available' => true,
                'img' => 'Система охлаждения Arctic Cooling Liquid Freezer III 360 A-RGB.webp', // Предполагаемое имя файла изображения
                'product_type' => 'cooling', // Тип продукта - система охлаждения
                'country' => 'Китай', // Типичная страна производства для Arctic Cooling
                'color' => 'Черный', // Обычно черный радиатор и вентиляторы
                'qty' => 10, // Условное количество
                'description' => 'Система охлаждения Arctic Cooling Liquid Freezer III 360 A-RGB – трехсекционная модель с гидродинамическими подшипниками в вентиляторах для увеличенного рабочего ресурса и сниженного до 22.5 дБ уровня шума. Устройство предусматривает радиатор с размерами 120x398x38 мм и увеличенной площадью за счет увеличения плотности ребер. В оснащение системы охлаждения Arctic Cooling Liquid Freezer III 360 A-RGB входят три вентилятора 120x120 мм с поддержкой автоматической регулировки скорости вращения в диапазоне 200-2000 об/мин для поддержания низкой температуры процессора при нагрузках.',
                'category_id' => $coolingCategory->id, // Убедитесь, что переменная $coolingCategory определена и содержит ID категории систем охлаждения
                'rating' => 0.0, // Условный рейтинг
            ]);

            $product->images()->createMany([
                ['image_path' => 'Система охлаждения Arctic Cooling Liquid Freezer III 360 A-RGB.webp'],
                ['image_path' => 'Система охлаждения Arctic Cooling Liquid Freezer III 360 A-RGB1.webp'],
                ['image_path' => 'Система охлаждения Arctic Cooling Liquid Freezer III 360 A-RGB2.webp'],
            ]);

            // Добавляем спецификации для Arctic Cooling Liquid Freezer III 360 A-RGB
            ProductSpecification::create(['product_id'=>$product->id,'spec_key'=>'brand','spec_name'=>'Бренд','spec_value'=>'Arctic Cooling','group'=>'Основные','is_filterable'=>true]);
            ProductSpecification::create(['product_id'=>$product->id,'spec_key'=>'series','spec_name'=>'Серия','spec_value'=>'Liquid Freezer III','group'=>'Основные','is_filterable'=>true]);
            ProductSpecification::create(['product_id'=>$product->id,'spec_key'=>'model','spec_name'=>'Модель','spec_value'=>'360 A-RGB','group'=>'Основные','is_filterable'=>false]);
            ProductSpecification::create(['product_id'=>$product->id,'spec_key'=>'type','spec_name'=>'Тип охлаждения','spec_value'=>'Жидкостное (СВО)','group'=>'Основные','is_filterable'=>true]);
            ProductSpecification::create(['product_id'=>$product->id,'spec_key'=>'radiator_size','spec_name'=>'Размер радиатора','spec_value'=>'360 мм','group'=>'Основные','is_filterable'=>true]);
            ProductSpecification::create(['product_id'=>$product->id,'spec_key'=>'fan_count','spec_name'=>'Количество вентиляторов','spec_value'=>'3','group'=>'Охлаждение','is_filterable'=>false]);
            ProductSpecification::create(['product_id'=>$product->id,'spec_key'=>'fan_size','spec_name'=>'Размер вентилятора','spec_value'=>'120 мм','group'=>'Охлаждение','is_filterable'=>true]);
            ProductSpecification::create(['product_id'=>$product->id,'spec_key'=>'fan_bearing','spec_name'=>'Тип подшипника','spec_value'=>'Гидродинамический','group'=>'Охлаждение','is_filterable'=>false]);
            ProductSpecification::create(['product_id'=>$product->id,'spec_key'=>'noise_level','spec_name'=>'Уровень шума','spec_value'=>'22.5 дБ','group'=>'Охлаждение','is_filterable'=>false]);
            ProductSpecification::create(['product_id'=>$product->id,'spec_key'=>'fan_speed','spec_name'=>'Скорость вращения вентиляторов','spec_value'=>'200-2000 об/мин','group'=>'Охлаждение','is_filterable'=>false]);
            ProductSpecification::create(['product_id'=>$product->id,'spec_key'=>'fan_control','spec_name'=>'Управление вентиляторами','spec_value'=>'Автоматическое (PWM)','group'=>'Особенности','is_filterable'=>false]);
            ProductSpecification::create(['product_id'=>$product->id,'spec_key'=>'radiator_dimensions','spec_name'=>'Размеры радиатора','spec_value'=>'120x398x38 мм','group'=>'Особенности','is_filterable'=>false]);
            ProductSpecification::create(['product_id'=>$product->id,'spec_key'=>'radiator_fin_density','spec_name'=>'Плотность ребер радиатора','spec_value'=>'Увеличенная','group'=>'Особенности','is_filterable'=>false]);
            ProductSpecification::create(['product_id'=>$product->id,'spec_key'=>'rgb','spec_name'=>'Подсветка','spec_value'=>'A-RGB','group'=>'Особенности','is_filterable'=>true]);
            ProductSpecification::create(['product_id'=>$product->id,'spec_key'=>'color','spec_name'=>'Цвет','spec_value'=>'Черный','group'=>'Внешний вид','is_filterable'=>true]);
        

                        // Система охлаждения DEEPCOOL LE520
                        $product = Product::create([
                            'title' => 'Система охлаждения DEEPCOOL LE520',
                            'price' => 4999, // Цена из описания
                            'old_price' => null,
                            'discount_percent' => 0,
                            'in_stock' => true,
                            'on_sale' => false,
                            'is_bestseller' => false,
                            'is_new' => true, // Предположим, что актуальная модель
                            'credit_available' => true,
                            'img' => 'Система охлаждения DEEPCOOL LE520.webp', // Предполагаемое имя файла изображения
                            'product_type' => 'cooling', // Тип продукта - система охлаждения
                            'country' => 'Китай', // Типичная страна производства для DeepCool
                            'color' => 'Черный', // Обычно черный радиатор и вентиляторы
                            'qty' => 12, // Условное количество
                            'description' => 'Система охлаждения DEEPCOOL LE520 способна справляться с рассеиванием тепловой мощности до 220 Вт от процессоров Intel и AMD. Кулер состоит из медного водоблока, двухсекционного алюминиевого радиатора и двух вентиляторов 120 мм. Совместная работа этих компонентов гарантирует стабильное отведение тепла при разных вычислительных нагрузках на процессор. Вентиляторы отличаются регулируемой скоростью вращения в пределах 500-2250 об/мин, гидродинамическим подшипником и низким уровнем шума 32.9 дБ. Интегрированные светодиоды ARGB формируют эффектное освещение с настраиваемой палитрой оттенков. Трубки длиной 410 мм гарантируют удобство при монтаже системы водяного охлаждения DEEPCOOL LE520.',
                            'category_id' => $coolingCategory->id, // Убедитесь, что переменная $coolingCategory определена и содержит ID категории систем охлаждения
                            'rating' => 0.0, // Условный рейтинг
                        ]);

                        $product->images()->createMany([
                            ['image_path' => 'Система охлаждения DEEPCOOL LE520.webp'],
                            ['image_path' => 'Система охлаждения DEEPCOOL LE5201.webp'],
                            ['image_path' => 'Система охлаждения DEEPCOOL LE5202.webp'],
                        ]);
            
                        // Добавляем спецификации для DEEPCOOL LE520
                        ProductSpecification::create(['product_id'=>$product->id,'spec_key'=>'brand','spec_name'=>'Бренд','spec_value'=>'DEEPCOOL','group'=>'Основные','is_filterable'=>true]);
                        ProductSpecification::create(['product_id'=>$product->id,'spec_key'=>'series','spec_name'=>'Серия','spec_value'=>'LE','group'=>'Основные','is_filterable'=>true]); // Предположим серию LE
                        ProductSpecification::create(['product_id'=>$product->id,'spec_key'=>'model','spec_name'=>'Модель','spec_value'=>'LE520','group'=>'Основные','is_filterable'=>false]);
                        ProductSpecification::create(['product_id'=>$product->id,'spec_key'=>'type','spec_name'=>'Тип охлаждения','spec_value'=>'Жидкостное (СВО)','group'=>'Основные','is_filterable'=>true]);
                        ProductSpecification::create(['product_id'=>$product->id,'spec_key'=>'tdp','spec_name'=>'Рассеиваемая мощность (TDP)','spec_value'=>'220 Вт','group'=>'Основные','is_filterable'=>false]);
                        ProductSpecification::create(['product_id'=>$product->id,'spec_key'=>'radiator_size','spec_name'=>'Размер радиатора','spec_value'=>'240 мм','group'=>'Основные','is_filterable'=>true]); // LE520 обычно 240мм (по названию 520?) - уточнение, LE520 это 240мм, а LT720 360мм
                        ProductSpecification::create(['product_id'=>$product->id,'spec_key'=>'fan_count','spec_name'=>'Количество вентиляторов','spec_value'=>'2','group'=>'Охлаждение','is_filterable'=>false]);
                        ProductSpecification::create(['product_id'=>$product->id,'spec_key'=>'fan_size','spec_name'=>'Размер вентилятора','spec_value'=>'120 мм','group'=>'Охлаждение','is_filterable'=>true]);
                        ProductSpecification::create(['product_id'=>$product->id,'spec_key'=>'fan_bearing','spec_name'=>'Тип подшипника','spec_value'=>'Гидродинамический','group'=>'Охлаждение','is_filterable'=>false]);
                        ProductSpecification::create(['product_id'=>$product->id,'spec_key'=>'fan_speed','spec_name'=>'Скорость вращения вентиляторов','spec_value'=>'500-2250 об/мин','group'=>'Охлаждение','is_filterable'=>false]);
                        ProductSpecification::create(['product_id'=>$product->id,'spec_key'=>'noise_level','spec_name'=>'Уровень шума','spec_value'=>'32.9 дБ','group'=>'Охлаждение','is_filterable'=>false]);
                        ProductSpecification::create(['product_id'=>$product->id,'spec_key'=>'block_material','spec_name'=>'Материал водоблока','spec_value'=>'Медь','group'=>'Особенности','is_filterable'=>false]);
                        ProductSpecification::create(['product_id'=>$product->id,'spec_key'=>'radiator_material','spec_name'=>'Материал радиатора','spec_value'=>'Алюминий','group'=>'Особенности','is_filterable'=>false]);
                        ProductSpecification::create(['product_id'=>$product->id,'spec_key'=>'rgb','spec_name'=>'Подсветка','spec_value'=>'ARGB','group'=>'Особенности','is_filterable'=>true]);
                        ProductSpecification::create(['product_id'=>$product->id,'spec_key'=>'tubing_length','spec_name'=>'Длина трубок','spec_value'=>'410 мм','group'=>'Особенности','is_filterable'=>false]);
                        ProductSpecification::create(['product_id'=>$product->id,'spec_key'=>'fan_control','spec_name'=>'Управление вентиляторами','spec_value'=>'PWM','group'=>'Особенности','is_filterable'=>false]); // Основываясь на регулируемой скорости
                        ProductSpecification::create(['product_id'=>$product->id,'spec_key'=>'color','spec_name'=>'Цвет','spec_value'=>'Черный','group'=>'Внешний вид','is_filterable'=>true]);
                    
                                    // Система охлаждения MSI MAG CORELIQUID E360 WHITE
            $product = Product::create([
                'title' => 'Система охлаждения MSI MAG CORELIQUID E360 WHITE',
                'price' => 14699, // Цена из описания
                'old_price' => null,
                'discount_percent' => 0,
                'in_stock' => true,
                'on_sale' => false,
                'is_bestseller' => false,
                'is_new' => true, // Предположим, что актуальная модель
                'credit_available' => true,
                'img' => 'MSI MAG CORELIQUID E360 WHITE.webp', // Предполагаемое имя файла изображения
                'product_type' => 'cooling', // Тип продукта - система охлаждения
                'country' => 'Китай', // Типичная страна производства для MSI
                'color' => 'Белый', // Цвет из названия
                'qty' => 6, // Условное количество
                'description' => 'Система охлаждения MSI MAG CORELIQUID E360 обеспечит удобство установки благодаря вращающейся на 270° верхней части водоблока. Вы сможете выровнять ее в нужном положении без необходимости переустановки всей системы охлаждения. Улучшенный отвод тепла от центрального процессора гарантирует увеличенная зона контакта медного основания водоблока. Помпа обеспечивает равномерное движение охладителя для стабильной температуры охлаждаемого компонента. Низкий уровень вибраций достигнут благодаря трехфазному мотору в конструкции помпы. Система охлаждения MSI MAG CORELIQUID E360 предусматривает 12-канальную внутреннюю структуру радиатора для быстрого рассеивания тепла. Благодаря многослойным пластиковым соединительным трубкам с прочной сетчатой оплеткой предотвращается испарение охладителя. Сниженный уровень шума и вибраций вентиляторов достигнут за счет гидродинамических подшипников.',
                'category_id' => $coolingCategory->id, // Убедитесь, что переменная $coolingCategory определена и содержит ID категории систем охлаждения
                'rating' => 0.0, // Условный рейтинг
            ]);

            
            $product->images()->createMany([
                ['image_path' => 'MSI MAG CORELIQUID E360 WHITE.webp'],
                ['image_path' => 'MSI MAG CORELIQUID E360 WHITE1.webp'],
                ['image_path' => 'MSI MAG CORELIQUID E360 WHITE2.webp'],
            ]);

            // Добавляем спецификации для MSI MAG CORELIQUID E360 WHITE
            ProductSpecification::create(['product_id'=>$product->id,'spec_key'=>'brand','spec_name'=>'Бренд','spec_value'=>'MSI','group'=>'Основные','is_filterable'=>true]);
            ProductSpecification::create(['product_id'=>$product->id,'spec_key'=>'series','spec_name'=>'Серия','spec_value'=>'MAG CORELIQUID E','group'=>'Основные','is_filterable'=>true]);
            ProductSpecification::create(['product_id'=>$product->id,'spec_key'=>'model','spec_name'=>'Модель','spec_value'=>'E360 WHITE','group'=>'Основные','is_filterable'=>false]);
            ProductSpecification::create(['product_id'=>$product->id,'spec_key'=>'type','spec_name'=>'Тип охлаждения','spec_value'=>'Жидкостное (СВО)','group'=>'Основные','is_filterable'=>true]);
            ProductSpecification::create(['product_id'=>$product->id,'spec_key'=>'radiator_size','spec_name'=>'Размер радиатора','spec_value'=>'360 мм','group'=>'Основные','is_filterable'=>true]);
            ProductSpecification::create(['product_id'=>$product->id,'spec_key'=>'block_rotation','spec_name'=>'Поворот водоблока','spec_value'=>'270°','group'=>'Особенности','is_filterable'=>false]);
            ProductSpecification::create(['product_id'=>$product->id,'spec_key'=>'base_material','spec_name'=>'Основание водоблока','spec_value'=>'Медь','group'=>'Особенности','is_filterable'=>false]);
            ProductSpecification::create(['product_id'=>$product->id,'spec_key'=>'pump_motor','spec_name'=>'Мотор помпы','spec_value'=>'Трехфазный','group'=>'Особенности','is_filterable'=>false]);
            ProductSpecification::create(['product_id'=>$product->id,'spec_key'=>'radiator_structure','spec_name'=>'Структура радиатора','spec_value'=>'12-канальная','group'=>'Особенности','is_filterable'=>false]);
            ProductSpecification::create(['product_id'=>$product->id,'spec_key'=>'tubing','spec_name'=>'Трубки','spec_value'=>'Многослойные, сетчатая оплетка','group'=>'Особенности','is_filterable'=>false]);
            ProductSpecification::create(['product_id'=>$product->id,'spec_key'=>'fan_bearing','spec_name'=>'Тип подшипника вентиляторов','spec_value'=>'Гидродинамический','group'=>'Охлаждение','is_filterable'=>false]);
            ProductSpecification::create(['product_id'=>$product->id,'spec_key'=>'color','spec_name'=>'Цвет','spec_value'=>'Белый','group'=>'Внешний вид','is_filterable'=>true]);
            // RGB подсветка не упомянута в описании, пропускаем.

                        // Система охлаждения Cougar Poseidon Elite 360 ARGB
                        $product = Product::create([
                            'title' => 'Система охлаждения Cougar Poseidon Elite 360 ARGB',
                            'price' => 7099, // Цена из описания
                            'old_price' => null,
                            'discount_percent' => 0,
                            'in_stock' => true,
                            'on_sale' => false,
                            'is_bestseller' => false,
                            'is_new' => true, // Предположим, что актуальная модель
                            'credit_available' => true,
                            'img' => 'Cougar Poseidon Elite 360 ARGB.webp', // Предполагаемое имя файла изображения
                            'product_type' => 'cooling', // Тип продукта - система охлаждения
                            'country' => 'Китай', // Типичная страна производства для Cougar
                            'color' => 'Черный', // Обычно черный радиатор и вентиляторы
                            'qty' => 9, // Условное количество
                            'description' => 'Система охлаждения Cougar Poseidon Elite 360 ARGB подходит для установки в процессоры с 15 типами сокетов. Алюминиевый радиатор с расположенными под прямым углом ребрами быстро отводит тепло. Гибкие трубки длиной 40 см упрощают установку устройства в любой части корпуса. Вы можете синхронизировать СЖО Cougar Poseidon Elite 360 ARGB с материнской платой для управления режимами подсветки кулеров и водоблока. Модель оснащена 3 вентиляторами с технологией PWM для автонастройки скорости движения лопастей. Подшипники скольжения отвечают за тихую работу кулеров в любом режиме.',
                            'category_id' => $coolingCategory->id, // Убедитесь, что переменная $coolingCategory определена и содержит ID категории систем охлаждения
                            'rating' => 0.0, // Условный рейтинг
                        ]);

                        $product->images()->createMany([
                            ['image_path' => 'Cougar Poseidon Elite 360 ARGB.webp'],
                            ['image_path' => 'Cougar Poseidon Elite 360 ARGB1.webp'],
                            ['image_path' => 'Cougar Poseidon Elite 360 ARGB2.webp'],
                        ]);
            
                        // Добавляем спецификации для Cougar Poseidon Elite 360 ARGB
                        ProductSpecification::create(['product_id'=>$product->id,'spec_key'=>'brand','spec_name'=>'Бренд','spec_value'=>'Cougar','group'=>'Основные','is_filterable'=>true]);
                        ProductSpecification::create(['product_id'=>$product->id,'spec_key'=>'series','spec_name'=>'Серия','spec_value'=>'Poseidon Elite','group'=>'Основные','is_filterable'=>true]); // Предположим серию Poseidon Elite
                        ProductSpecification::create(['product_id'=>$product->id,'spec_key'=>'model','spec_name'=>'Модель','spec_value'=>'360 ARGB','group'=>'Основные','is_filterable'=>false]);
                        ProductSpecification::create(['product_id'=>$product->id,'spec_key'=>'type','spec_name'=>'Тип охлаждения','spec_value'=>'Жидкостное (СВО)','group'=>'Основные','is_filterable'=>true]);
                        ProductSpecification::create(['product_id'=>$product->id,'spec_key'=>'radiator_size','spec_name'=>'Размер радиатора','spec_value'=>'360 мм','group'=>'Основные','is_filterable'=>true]);
                        ProductSpecification::create(['product_id'=>$product->id,'spec_key'=>'socket_support','spec_name'=>'Совместимость с сокетами','spec_value'=>'15 типов','group'=>'Особенности','is_filterable'=>false]); // Указываем количество типов сокетов
                        ProductSpecification::create(['product_id'=>$product->id,'spec_key'=>'radiator_material','spec_name'=>'Материал радиатора','spec_value'=>'Алюминий','group'=>'Особенности','is_filterable'=>false]);
                        ProductSpecification::create(['product_id'=>$product->id,'spec_key'=>'tubing_length','spec_name'=>'Длина трубок','spec_value'=>'40 см','group'=>'Особенности','is_filterable'=>false]);
                        ProductSpecification::create(['product_id'=>$product->id,'spec_key'=>'tubing','spec_name'=>'Трубки','spec_value'=>'Гибкие','group'=>'Особенности','is_filterable'=>false]);
                        ProductSpecification::create(['product_id'=>$product->id,'spec_key'=>'rgb','spec_name'=>'Подсветка','spec_value'=>'ARGB (водоблок и вентиляторы)','group'=>'Особенности','is_filterable'=>true]);
                        ProductSpecification::create(['product_id'=>$product->id,'spec_key'=>'rgb_sync','spec_name'=>'Синхронизация подсветки','spec_value'=>'С материнской платой','group'=>'Особенности','is_filterable'=>false]);
                        ProductSpecification::create(['product_id'=>$product->id,'spec_key'=>'fan_count','spec_name'=>'Количество вентиляторов','spec_value'=>'3','group'=>'Охлаждение','is_filterable'=>false]);
                        ProductSpecification::create(['product_id'=>$product->id,'spec_key'=>'fan_control','spec_name'=>'Управление вентиляторами','spec_value'=>'PWM','group'=>'Особенности','is_filterable'=>false]);
                        ProductSpecification::create(['product_id'=>$product->id,'spec_key'=>'fan_bearing','spec_name'=>'Тип подшипника вентиляторов','spec_value'=>'Гидродинамический (скольжения)','group'=>'Охлаждение','is_filterable'=>false]);
                        ProductSpecification::create(['product_id'=>$product->id,'spec_key'=>'color','spec_name'=>'Цвет','spec_value'=>'Черный','group'=>'Внешний вид','is_filterable'=>true]);
                    
                    // Система охлаждения Ocypus Iota L36 BK
                    $product = Product::create([
                        'title' => 'Система охлаждения Ocypus Iota L36 BK',
                        'price' => 7599, // Цена из описания
                        'old_price' => null,
                        'discount_percent' => 0,
                        'in_stock' => true,
                        'on_sale' => false,
                        'is_bestseller' => false,
                        'is_new' => true, // Предположим, что актуальная модель
                        'credit_available' => true,
                        'img' => 'Ocypus Iota L36 BK.webp', // Предполагаемое имя файла изображения
                        'product_type' => 'cooling', // Тип продукта - система охлаждения
                        'country' => 'Китай', // Типичная страна производства для Ocypus
                        'color' => 'Черный', // Цвет из описания
                        'qty' => 8, // Условное количество
                        'description' => 'Система жидкостного охлаждения Ocypus Iota L36 BK предназначена для высокоэффективного охлаждения мощных процессоров в игровых и рабочих ПК. Сочетая в себе стильный черный дизайн и высокую производительность, эта СЖО оснащена тремя вентиляторами с диапазоном скорости от 500 до 2000 оборотов в минуту. Благодаря такому диапазону, система способна обеспечивать воздушный поток до 77 CFM, что позволяет эффективно отводить тепло даже при высоких нагрузках. Радиатор, выполненный из качественных материалов, обеспечивает оптимальный теплообмен, а вентиляторы работают с максимальным уровнем шума всего 29 дБА, обеспечивая тихую работу даже при интенсивной эксплуатации. Ocypus Iota L36 BK идеально подходит для пользователей, которым требуется надежное и производительное решение для охлаждения. Система поддерживает процессоры и может легко справляться с разгоном, предотвращая перегрев и повышая стабильность работы системы. Благодаря универсальной совместимости с популярными сокетами и компактным габаритам, эта СЖО легко интегрируется в большинство корпусов, предлагая высокую эффективность без компромиссов по качеству.',
                        'category_id' => $coolingCategory->id, // Убедитесь, что переменная $coolingCategory определена и содержит ID категории систем охлаждения
                        'rating' => 0.0, // Условный рейтинг
                    ]);

                    $product->images()->createMany([
                        ['image_path' => 'Ocypus Iota L36 BK.webp'],
                        ['image_path' => 'Ocypus Iota L36 BK1.webp'],
                        ['image_path' => 'Ocypus Iota L36 BK2.webp'],
                    ]);
        
                    // Добавляем спецификации для Ocypus Iota L36 BK
                    ProductSpecification::create(['product_id'=>$product->id,'spec_key'=>'brand','spec_name'=>'Бренд','spec_value'=>'Ocypus','group'=>'Основные','is_filterable'=>true]);
                    ProductSpecification::create(['product_id'=>$product->id,'spec_key'=>'model','spec_name'=>'Модель','spec_value'=>'Iota L36 BK','group'=>'Основные','is_filterable'=>false]);
                    ProductSpecification::create(['product_id'=>$product->id,'spec_key'=>'type','spec_name'=>'Тип охлаждения','spec_value'=>'Жидкостное (СВО)','group'=>'Основные','is_filterable'=>true]);
                    ProductSpecification::create(['product_id'=>$product->id,'spec_key'=>'radiator_size','spec_name'=>'Размер радиатора','spec_value'=>'360 мм','group'=>'Основные','is_filterable'=>true]); // Инferred from L36
                    ProductSpecification::create(['product_id'=>$product->id,'spec_key'=>'fan_count','spec_name'=>'Количество вентиляторов','spec_value'=>'3','group'=>'Охлаждение','is_filterable'=>false]);
                    ProductSpecification::create(['product_id'=>$product->id,'spec_key'=>'fan_speed_range','spec_name'=>'Скорость вентиляторов','spec_value'=>'500 - 2000 об/мин','group'=>'Охлаждение','is_filterable'=>false]);
                    ProductSpecification::create(['product_id'=>$product->id,'spec_key'=>'airflow','spec_name'=>'Воздушный поток','spec_value'=>'77 CFM','group'=>'Охлаждение','is_filterable'=>false]);
                    ProductSpecification::create(['product_id'=>$product->id,'spec_key'=>'noise_level','spec_name'=>'Уровень шума','spec_value'=>'29 дБА','group'=>'Охлаждение','is_filterable'=>false]);
                     // Совместимость с сокетами не указана явно, пропускаем или добавляем placeholder если нужно
                    ProductSpecification::create(['product_id'=>$product->id,'spec_key'=>'color','spec_name'=>'Цвет','spec_value'=>'Черный','group'=>'Внешний вид','is_filterable'=>true]);
                    // Возможно добавить спецификации по материалам, размеру водоблока и т.д., если есть в описании или известны.
                
                                // Система охлаждения ID-Cooling SL360 XE WHITE
            $product = Product::create([
                'title' => 'Система охлаждения ID-Cooling SL360 XE WHITE',
                'price' => 9299, // Цена из описания
                'old_price' => null,
                'discount_percent' => 0,
                'in_stock' => true,
                'on_sale' => false,
                'is_bestseller' => false,
                'is_new' => true, // Предположим, что актуальная модель
                'credit_available' => true,
                'img' => 'ID-Cooling SL360 XE WHITE.webp', // Предполагаемое имя файла изображения
                'product_type' => 'cooling', // Тип продукта - система охлаждения
                'country' => 'Китай', // Типичная страна производства
                'color' => 'Белый', // Цвет из описания
                'qty' => 7, // Условное количество
                'description' => 'Система охлаждения ID-Cooling SL360 XE WHITE белого цвета рассеивает мощность до 350 Вт. Показатель позволяет использовать устройство для обслуживания мощных многоядерных процессоров в составе производительных игровых или универсальных системных блоков. Модель совместима с сокетами AM5, AM4, LGA 1700, LGA 1200, LGA 1151, LGA 1150, LGA 1155, LGA 1156, LGA 2066, LGA 2011 и LGA 1851. Система оснащена тонким 3-секционным алюминиевым радиатором с высокой плотностью ребер. Особенностью устройства является жидкокристаллический экран, на который выводится информация о состоянии процессора. Дисплей с диагональю 2.1 дюйма и разрешением 480x480 поддерживается операционными системами Windows 10 и 11. Система охлаждения ID-Cooling SL360 XE WHITE оборудована вентиляторами на базе малошумных гидродинамических подшипников скольжения. Поддерживается широкий диапазон частоты вращения – от 500 до 1800 об/мин. Скорость вентиляторов изменяется автоматически. Система является необслуживаемой: она не рассчитана на замену компонентов и хладагента. Устройство украшено адресуемой подсветкой RGB.',
                'category_id' => $coolingCategory->id, // Убедитесь, что переменная $coolingCategory определена и содержит ID категории систем охлаждения
                'rating' => 0.0, // Условный рейтинг
            ]);

            $product->images()->createMany([
                ['image_path' => 'ID-Cooling SL360 XE WHITE.webp'],
                ['image_path' => 'ID-Cooling SL360 XE WHITE1.webp'],
                ['image_path' => 'ID-Cooling SL360 XE WHITE2.webp'],
            ]);

            // Добавляем спецификации для ID-Cooling SL360 XE WHITE
            ProductSpecification::create(['product_id'=>$product->id,'spec_key'=>'brand','spec_name'=>'Бренд','spec_value'=>'ID-Cooling','group'=>'Основные','is_filterable'=>true]);
            ProductSpecification::create(['product_id'=>$product->id,'spec_key'=>'model','spec_name'=>'Модель','spec_value'=>'SL360 XE WHITE','group'=>'Основные','is_filterable'=>false]);
            ProductSpecification::create(['product_id'=>$product->id,'spec_key'=>'type','spec_name'=>'Тип охлаждения','spec_value'=>'Жидкостное (СВО)','group'=>'Основные','is_filterable'=>true]);
            ProductSpecification::create(['product_id'=>$product->id,'spec_key'=>'radiator_size','spec_name'=>'Размер радиатора','spec_value'=>'360 мм','group'=>'Основные','is_filterable'=>true]); // Из названия SL360
            ProductSpecification::create(['product_id'=>$product->id,'spec_key'=>'tdp','spec_name'=>'Рассеиваемая мощность (TDP)','spec_value'=>'350 Вт','group'=>'Основные','is_filterable'=>false]);
            ProductSpecification::create(['product_id'=>$product->id,'spec_key'=>'socket_support','spec_name'=>'Совместимость с сокетами','spec_value'=>'AM5, AM4, LGA 1700, LGA 1200, LGA 1151, LGA 1150, LGA 1155, LGA 1156, LGA 2066, LGA 2011, LGA 1851','group'=>'Особенности','is_filterable'=>false]);
            ProductSpecification::create(['product_id'=>$product->id,'spec_key'=>'fan_count','spec_name'=>'Количество вентиляторов','spec_value'=>'3','group'=>'Охлаждение','is_filterable'=>false]); // Из названия SL360
            ProductSpecification::create(['product_id'=>$product->id,'spec_key'=>'fan_speed_range','spec_name'=>'Скорость вентиляторов','spec_value'=>'500 - 1800 об/мин','group'=>'Охлаждение','is_filterable'=>false]);
            ProductSpecification::create(['product_id'=>$product->id,'spec_key'=>'fan_bearing','spec_name'=>'Тип подшипника вентиляторов','spec_value'=>'Гидродинамический (скольжения)','group'=>'Охлаждение','is_filterable'=>false]);
            ProductSpecification::create(['product_id'=>$product->id,'spec_key'=>'display','spec_name'=>'Дисплей на водоблоке','spec_value'=>'LCD 2.1" 480x480','group'=>'Особенности','is_filterable'=>false]);
            ProductSpecification::create(['product_id'=>$product->id,'spec_key'=>'rgb','spec_name'=>'Подсветка','spec_value'=>'ARGB','group'=>'Особенности','is_filterable'=>true]);
            ProductSpecification::create(['product_id'=>$product->id,'spec_key'=>'maintenance','spec_name'=>'Обслуживание','spec_value'=>'Необслуживаемая','group'=>'Особенности','is_filterable'=>false]);
            ProductSpecification::create(['product_id'=>$product->id,'spec_key'=>'color','spec_name'=>'Цвет','spec_value'=>'Белый','group'=>'Внешний вид','is_filterable'=>true]);
        


        // Создаем несколько тестовых товаров для категории корпусов
        if($caseCategory) {
            // --- СИДИРОВАНИЕ КОРПУСОВ ДЛЯ ПК ---

            // Массив со всеми корпусами, включая NZXT H510
            $cases = [
                 // NZXT H510 - Перемещен сюда, если он не создается отдельно выше
                 [
                    'title' => 'NZXT H510 Корпус',
                    'description' => 'Корпус NZXT H510, Mid Tower, ATX, 2x120 мм вентилятора, USB 3.1 Type-C, закаленное стекло',
                    'price' => 8990,
                    'old_price' => 9990,
                    'discount_percent' => 10,
                    'in_stock' => true,
                    'on_sale' => true,
                    'is_bestseller' => false,
                    'credit_available' => true,
                    'img' => 'NZXT H510 Корпус.webp', // У этого товара пока нет картинки в сидере, img => null
                    'product_type' => 'case',
                    'country' => 'Китай',
                    'color' => 'Белый',
                    'qty' => 20,
                    'category_id' => $caseCategory->id,
                    'rating' => 4.8,
                    'specs' => [ // НАЧАЛО блока спецификаций
                         ['key'=>'brand', 'name'=>'Бренд', 'value'=>'NZXT', 'group'=>'Основные', 'is_filterable'=>true],
                         ['key'=>'model', 'name'=>'Модель', 'value'=>'H510', 'group'=>'Основные', 'is_filterable'=>true],
                         ['key'=>'form_factor', 'name'=>'Типоразмер корпуса', 'value'=>'Mid-Tower', 'group'=>'Основные', 'is_filterable'=>true],
                         ['key'=>'orientation', 'name'=>'Ориентация материнской платы', 'value'=>'вертикально', 'group'=>'Основные', 'is_filterable'=>false],
                         ['key'=>'length', 'name'=>'Длина', 'value'=>'428 мм', 'group'=>'Габариты', 'is_filterable'=>false],
                         ['key'=>'width', 'name'=>'Ширина', 'value'=>'210 мм', 'group'=>'Габариты', 'is_filterable'=>false],
                         ['key'=>'height', 'name'=>'Высота', 'value'=>'460 мм', 'group'=>'Габариты', 'is_filterable'=>false],
                         ['key'=>'weight', 'name'=>'Вес', 'value'=>'6.6 кг', 'group'=>'Габариты', 'is_filterable'=>false],
                         ['key'=>'color', 'name'=>'Основной цвет', 'value'=>'Белый', 'group'=>'Внешний вид', 'is_filterable'=>true],
                         ['key'=>'material', 'name'=>'Материал корпуса', 'value'=>'сталь, закаленное стекло', 'group'=>'Основные', 'is_filterable'=>true],
                         ['key'=>'window', 'name'=>'Наличие окна на боковой стенке', 'value'=>'слева', 'group'=>'Внешний вид', 'is_filterable'=>true],
                         ['key'=>'window_material', 'name'=>'Материал окна', 'value'=>'закаленное стекло', 'group'=>'Внешний вид', 'is_filterable'=>false],
                         ['key'=>'lighting_type', 'name'=>'Тип подсветки', 'value'=>'нет', 'group'=>'Внешний вид', 'is_filterable'=>true],
                         ['key'=>'mobo_compat', 'name'=>'Форм-фактор совместимых плат', 'value'=>'ATX, Micro-ATX, Mini-ITX', 'group'=>'Совместимость', 'is_filterable'=>true],
                         ['key'=>'psu_compat', 'name'=>'Форм-фактор совместимых блоков питания', 'value'=>'ATX', 'group'=>'Совместимость', 'is_filterable'=>false],
                         ['key'=>'psu_position', 'name'=>'Размещение блока питания', 'value'=>'нижнее', 'group'=>'Совместимость', 'is_filterable'=>false],
                         ['key'=>'max_psu_length', 'name'=>'Максимальная длина блока питания', 'value'=>'180 мм', 'group'=>'Совместимость', 'is_filterable'=>false],
                         ['key'=>'slots_horizontal', 'name'=>'Горизонтальные слоты расширения', 'value'=>'7', 'group'=>'Совместимость', 'is_filterable'=>false],
                         ['key'=>'gpu_length', 'name'=>'Максимальная длина устанавливаемой видеокарты', 'value'=>'360 мм', 'group'=>'Совместимость', 'is_filterable'=>true],
                         ['key'=>'cpu_cooler_height', 'name'=>'Максимальная высота процессорного кулера', 'value'=>'165 мм', 'group'=>'Совместимость', 'is_filterable'=>true],
                         ['key'=>'ssd_slots', 'name'=>'Количество отсеков 2.5" накопителей', 'value'=>'2+1 шт', 'group'=>'Совместимость', 'is_filterable'=>false],
                         ['key'=>'hdd_slots', 'name'=>'Число внутренних отсеков 3.5"', 'value'=>'2+1 шт', 'group'=>'Совместимость', 'is_filterable'=>false],
                         ['key'=>'fans_included', 'name'=>'Вентиляторы в комплекте', 'value'=>'2 x 120 мм', 'group'=>'Охлаждение', 'is_filterable'=>false],
                         ['key'=>'fan_support_front', 'name'=>'Поддержка фронтальных вентиляторов', 'value'=>'2 x 120 или 2 x 140 мм', 'group'=>'Охлаждение', 'is_filterable'=>false],
                         ['key'=>'fan_support_rear', 'name'=>'Поддержка тыловых вентиляторов', 'value'=>'1 x 120 мм', 'group'=>'Охлаждение', 'is_filterable'=>false],
                         ['key'=>'fan_support_top', 'name'=>'Поддержка верхних вентиляторов', 'value'=>'1 x 120 или 1 x 140 мм', 'group'=>'Охлаждение', 'is_filterable'=>false],
                         ['key'=>'liquid_cooling', 'name'=>'Возможность установки системы жидкостного охлаждения', 'value'=>'есть', 'group'=>'Охлаждение', 'is_filterable'=>true],
                         ['key'=>'rad_support_front', 'name'=>'Фронтальный монтажный размер радиатора СЖО', 'value'=>'60 мм, 280 мм', 'group'=>'Охлаждение', 'is_filterable'=>false],
                         ['key'=>'rad_support_rear', 'name'=>'Тыловой монтажный размер радиатора СЖО', 'value'=>'120 мм', 'group'=>'Охлаждение', 'is_filterable'=>false],
                         ['key'=>'io_panel_position', 'name'=>'Расположение I / O панели', 'value'=>'сверху', 'group'=>'Интерфейсы', 'is_filterable'=>false],
                         ['key'=>'io_ports', 'name'=>'Разъемы', 'value'=>'USB 3.1 Type-C х 1, USB 3.0 Type-A х 1, Audio x 1', 'group'=>'Интерфейсы', 'is_filterable'=>false],
                         ['key'=>'dust_filter', 'name'=>'Пылевой фильтр', 'value'=>'есть', 'group'=>'Обслуживание', 'is_filterable'=>false],
                    ], // КОНЕЦ блока спецификаций
                ],
                // 1. ZALMAN N4 Rev.1 черный
                [
                    'title' => 'ZALMAN N4 Rev.1 черный Корпус',
                    'img' => 'ZALMAN N4 Rev.1 черный Корпус.webp',
                    'description' => 'Корпус ZALMAN N4 Rev.1 черного цвета относится к форм-фактору Mid-Tower. Его конфигурация позволяет собрать внутри производительную «машину». Пластик, сталь и закаленное стекло лежат в основе прочности конструкции. FRGB-подсветка дополняет ее стильный дизайн. Управлять ей можно при помощи кнопки снаружи. В комплекте поставки уже предусмотрено по 3 вентилятора диаметром 12 и 14 см. Боковые панели фиксируются на винты. Для грамотной организации проводов внутри ZALMAN N4 Rev.1 предусмотрена возможность прокладывания кабелей вдоль стенки. О чистоте внутри, которая влияет на работоспособность компонентов, призван позаботиться пылевой фильтр.',
                    'price' => 4700,
                    'category_id' => $caseCategory->id,
                'product_type' => 'case',
                    'country' => 'Китай',
                    'color' => 'черный',
                    'specs' => [
                        ['key'=>'brand', 'name'=>'Бренд', 'value'=>'ZALMAN', 'group'=>'Основные', 'is_filterable'=>true],
                        ['key'=>'form_factor', 'name'=>'Типоразмер корпуса', 'value'=>'Mid-Tower', 'group'=>'Основные', 'is_filterable'=>true],
                        ['key'=>'orientation', 'name'=>'Ориентация материнской платы', 'value'=>'вертикально', 'group'=>'Основные', 'is_filterable'=>false],
                        ['key'=>'length', 'name'=>'Длина', 'value'=>'396 мм', 'group'=>'Габариты', 'is_filterable'=>false],
                        ['key'=>'width', 'name'=>'Ширина', 'value'=>'204 мм', 'group'=>'Габариты', 'is_filterable'=>false],
                        ['key'=>'height', 'name'=>'Высота', 'value'=>'446 мм', 'group'=>'Габариты', 'is_filterable'=>false],
                        ['key'=>'weight', 'name'=>'Вес', 'value'=>'4.7 кг', 'group'=>'Габариты', 'is_filterable'=>false],
                        ['key'=>'color', 'name'=>'Основной цвет', 'value'=>'черный', 'group'=>'Внешний вид', 'is_filterable'=>true],
                        ['key'=>'material', 'name'=>'Материал корпуса', 'value'=>'пластик, сталь, стекло', 'group'=>'Основные', 'is_filterable'=>true],
                        ['key'=>'metal_thickness', 'name'=>'Толщина металла', 'value'=>'0.4 мм', 'group'=>'Внешний вид', 'is_filterable'=>false],
                        ['key'=>'window', 'name'=>'Наличие окна на боковой стенке', 'value'=>'слева', 'group'=>'Внешний вид', 'is_filterable'=>true],
                        ['key'=>'window_material', 'name'=>'Материал окна', 'value'=>'закаленное стекло', 'group'=>'Внешний вид', 'is_filterable'=>false],
                        ['key'=>'front_panel_material', 'name'=>'Материал фронтальной панели', 'value'=>'металлическая сетка, пластик', 'group'=>'Внешний вид', 'is_filterable'=>false],
                        ['key'=>'lighting_type', 'name'=>'Тип подсветки', 'value'=>'FRGB', 'group'=>'Внешний вид', 'is_filterable'=>true],
                        ['key'=>'lighting_color', 'name'=>'Цвет подсветки', 'value'=>'многоцветная', 'group'=>'Внешний вид', 'is_filterable'=>false],
                        ['key'=>'lighting_source', 'name'=>'Источник подсветки', 'value'=>'вентилятор', 'group'=>'Внешний вид', 'is_filterable'=>false],
                        ['key'=>'lighting_connector', 'name'=>'Разъем подключения подсветки', 'value'=>'Molex', 'group'=>'Внешний вид', 'is_filterable'=>false],
                        ['key'=>'lighting_control', 'name'=>'Способ управления подсветкой', 'value'=>'кнопка на корпусе', 'group'=>'Внешний вид', 'is_filterable'=>false],
                        ['key'=>'mobo_compat', 'name'=>'Форм-фактор совместимых плат', 'value'=>'Micro-ATX, Mini-ITX, Standard-ATX', 'group'=>'Совместимость', 'is_filterable'=>true],
                        ['key'=>'psu_compat', 'name'=>'Форм-фактор совместимых блоков питания', 'value'=>'ATX', 'group'=>'Совместимость', 'is_filterable'=>false],
                        ['key'=>'psu_position', 'name'=>'Размещение блока питания', 'value'=>'нижнее', 'group'=>'Совместимость', 'is_filterable'=>false],
                        ['key'=>'psu_length', 'name'=>'Максимальная длина блока питания', 'value'=>'180 мм', 'group'=>'Совместимость', 'is_filterable'=>false],
                        ['key'=>'slots_horizontal', 'name'=>'Горизонтальные слоты расширения', 'value'=>'7', 'group'=>'Совместимость', 'is_filterable'=>false],
                        ['key'=>'gpu_length', 'name'=>'Максимальная длина устанавливаемой видеокарты', 'value'=>'315 мм', 'group'=>'Совместимость', 'is_filterable'=>true],
                        ['key'=>'cpu_cooler_height', 'name'=>'Максимальная высота процессорного кулера', 'value'=>'163 мм', 'group'=>'Совместимость', 'is_filterable'=>true],
                        ['key'=>'ssd_slots', 'name'=>'Количество отсеков 2.5" накопителей', 'value'=>'4 шт', 'group'=>'Совместимость', 'is_filterable'=>false],
                        ['key'=>'hdd_slots', 'name'=>'Число внутренних отсеков 3.5"', 'value'=>'2 шт', 'group'=>'Совместимость', 'is_filterable'=>false],
                        ['key'=>'fans_included', 'name'=>'Вентиляторы в комплекте', 'value'=>'3 x 120 мм, 3 x 140 мм', 'group'=>'Охлаждение', 'is_filterable'=>false],
                        ['key'=>'fan_support_front', 'name'=>'Поддержка фронтальных вентиляторов', 'value'=>'2 x 180 или 3 x 140 или 3 x 120 мм', 'group'=>'Охлаждение', 'is_filterable'=>false],
                        ['key'=>'fan_support_rear', 'name'=>'Поддержка тыловых вентиляторов', 'value'=>'1 x 120 мм', 'group'=>'Охлаждение', 'is_filterable'=>false],
                        ['key'=>'fan_support_top', 'name'=>'Поддержка верхних вентиляторов', 'value'=>'2 x 120 мм', 'group'=>'Охлаждение', 'is_filterable'=>false],
                        ['key'=>'fan_support_bottom', 'name'=>'Поддержка нижних вентиляторов', 'value'=>'2 x 120 мм', 'group'=>'Охлаждение', 'is_filterable'=>false],
                        ['key'=>'liquid_cooling', 'name'=>'Возможность установки системы жидкостного охлаждения', 'value'=>'есть', 'group'=>'Охлаждение', 'is_filterable'=>true],
                        ['key'=>'rad_support_front', 'name'=>'Фронтальный монтажный размер радиатора СЖО', 'value'=>'120 мм, 240 мм', 'group'=>'Охлаждение', 'is_filterable'=>false],
                        ['key'=>'io_panel_position', 'name'=>'Расположение I / O панели', 'value'=>'сверху', 'group'=>'Интерфейсы', 'is_filterable'=>false],
                        ['key'=>'io_ports', 'name'=>'Разъемы', 'value'=>'3.5 мм jack (аудио) х 1, 3.5 мм jack (микрофон) х 1, USB 2.0 Type-A х 2, USB 3.2 Gen 1 Type-A х 1', 'group'=>'Интерфейсы', 'is_filterable'=>false],
                        ['key'=>'dust_filter', 'name'=>'Пылевой фильтр', 'value'=>'есть', 'group'=>'Обслуживание', 'is_filterable'=>false],
                    ]
                ],
                // 2. Cougar Duoface Pro RGB [CGR-5AD1B-RGB] черный
                [
                    'title' => 'Cougar Duoface Pro RGB [CGR-5AD1B-RGB] черный Корпус',
                    'img' => 'Cougar Duoface Pro RGB [CGR-5AD1B-RGB] черный Корпус.webp',
                    'description' => 'Черный корпус Cougar Duoface Pro RGB [CGR-5AD1B-RGB] – высококачественная конфигурация с красивым и стильным дизайном. Он привлекает внимание своей сбалансированностью и комфортом использования. Он имеет инновационную конструкцию, позволяющую изменять в одно касание внешний вид. Достаточно перевернуть его и поменять настройки RGB-подсветки в зависимости от ваших настроений. Корпус Cougar Duoface Pro RGB [CGR-5AD1B-RGB ] оснащен необходимыми возможностями для эффективной вентиляции и охлаждения компонентов. Также он предоставляет удобный доступ к кабельным маршрутам и съемным «карманам» для хранения гарнитуры, мыши и клавиатуры.',
                    'price' => 8200,
                'category_id' => $caseCategory->id,
                    'product_type' => 'case',
                'country' => 'Китай',
                    'color' => 'черный',
                    'specs' => [
                        ['key'=>'brand', 'name'=>'Бренд', 'value'=>'Cougar', 'group'=>'Основные', 'is_filterable'=>true],
                        ['key'=>'model', 'name'=>'Модель', 'value'=>'Duoface Pro RGB [CGR-5AD1B-RGB]', 'group'=>'Основные', 'is_filterable'=>false],
                        ['key'=>'form_factor', 'name'=>'Типоразмер корпуса', 'value'=>'Mid-Tower', 'group'=>'Основные', 'is_filterable'=>true],
                        ['key'=>'orientation', 'name'=>'Ориентация материнской платы', 'value'=>'вертикально', 'group'=>'Основные', 'is_filterable'=>false],
                        ['key'=>'length', 'name'=>'Длина', 'value'=>'465 мм', 'group'=>'Габариты', 'is_filterable'=>false],
                        ['key'=>'width', 'name'=>'Ширина', 'value'=>'240 мм', 'group'=>'Габариты', 'is_filterable'=>false],
                        ['key'=>'height', 'name'=>'Высота', 'value'=>'496 мм', 'group'=>'Габариты', 'is_filterable'=>false],
                        ['key'=>'color', 'name'=>'Основной цвет', 'value'=>'черный', 'group'=>'Внешний вид', 'is_filterable'=>true],
                        ['key'=>'material', 'name'=>'Материал корпуса', 'value'=>'пластик, сталь, стекло', 'group'=>'Основные', 'is_filterable'=>true],
                        ['key'=>'window', 'name'=>'Наличие окна на боковой стенке', 'value'=>'слева', 'group'=>'Внешний вид', 'is_filterable'=>true],
                        ['key'=>'window_material', 'name'=>'Материал окна', 'value'=>'закаленное стекло', 'group'=>'Внешний вид', 'is_filterable'=>false],
                        ['key'=>'front_panel_material', 'name'=>'Материал фронтальной панели', 'value'=>'закаленное стекло, металлическая сетка', 'group'=>'Внешний вид', 'is_filterable'=>false],
                        ['key'=>'lighting_type', 'name'=>'Тип подсветки', 'value'=>'ARGB', 'group'=>'Внешний вид', 'is_filterable'=>true],
                        ['key'=>'lighting_color', 'name'=>'Цвет подсветки', 'value'=>'многоцветная', 'group'=>'Внешний вид', 'is_filterable'=>false],
                        ['key'=>'lighting_source', 'name'=>'Источник подсветки', 'value'=>'вентилятор, корпус', 'group'=>'Внешний вид', 'is_filterable'=>false],
                        ['key'=>'lighting_connector', 'name'=>'Разъем подключения подсветки', 'value'=>'3-pin 5V-D-G (ARGB)', 'group'=>'Внешний вид', 'is_filterable'=>false],
                        ['key'=>'lighting_control', 'name'=>'Способ управления подсветкой', 'value'=>'кнопка на корпусе', 'group'=>'Внешний вид', 'is_filterable'=>false],
                        ['key'=>'mobo_compat', 'name'=>'Форм-фактор совместимых плат', 'value'=>'E-ATX, Micro-ATX, Mini-ITX, SSI-CEB, Standard-ATX', 'group'=>'Совместимость', 'is_filterable'=>true],
                        ['key'=>'psu_compat', 'name'=>'Форм-фактор совместимых блоков питания', 'value'=>'ATX', 'group'=>'Совместимость', 'is_filterable'=>false],
                        ['key'=>'psu_position', 'name'=>'Размещение блока питания', 'value'=>'нижнее', 'group'=>'Совместимость', 'is_filterable'=>false],
                        ['key'=>'max_psu_length', 'name'=>'Максимальная длина блока питания', 'value'=>'200 мм', 'group'=>'Совместимость', 'is_filterable'=>false],
                        ['key'=>'slots_horizontal', 'name'=>'Горизонтальные слоты расширения', 'value'=>'7', 'group'=>'Совместимость', 'is_filterable'=>false],
                        ['key'=>'slots_vertical', 'name'=>'Вертикальные слоты расширения', 'value'=>'3', 'group'=>'Совместимость', 'is_filterable'=>false],
                        ['key'=>'gpu_length', 'name'=>'Максимальная длина устанавливаемой видеокарты', 'value'=>'390 мм', 'group'=>'Совместимость', 'is_filterable'=>true],
                        ['key'=>'cpu_cooler_height', 'name'=>'Максимальная высота процессорного кулера', 'value'=>'190 мм', 'group'=>'Совместимость', 'is_filterable'=>true],
                        ['key'=>'ssd_slots', 'name'=>'Количество отсеков 2.5" накопителей', 'value'=>'2 шт', 'group'=>'Совместимость', 'is_filterable'=>false],
                        ['key'=>'hdd_slots', 'name'=>'Число внутренних отсеков 3.5"', 'value'=>'2 шт', 'group'=>'Совместимость', 'is_filterable'=>false],
                        ['key'=>'fans_included', 'name'=>'Вентиляторы в комплекте', 'value'=>'4 x 120 мм', 'group'=>'Охлаждение', 'is_filterable'=>false],
                        ['key'=>'fan_support_front', 'name'=>'Поддержка фронтальных вентиляторов', 'value'=>'2 x 140 или 3 x 120 мм', 'group'=>'Охлаждение', 'is_filterable'=>false],
                        ['key'=>'fan_support_rear', 'name'=>'Поддержка тыловых вентиляторов', 'value'=>'1 x 140 или 1 x 120 мм', 'group'=>'Охлаждение', 'is_filterable'=>false],
                        ['key'=>'fan_support_top', 'name'=>'Поддержка верхних вентиляторов', 'value'=>'2 x 140 или 3 x 120 мм', 'group'=>'Охлаждение', 'is_filterable'=>false],
                        ['key'=>'fan_support_bottom', 'name'=>'Поддержка нижних вентиляторов', 'value'=>'2 x 120 мм', 'group'=>'Охлаждение', 'is_filterable'=>false],
                        ['key'=>'liquid_cooling', 'name'=>'Возможность установки системы жидкостного охлаждения', 'value'=>'есть', 'group'=>'Охлаждение', 'is_filterable'=>true],
                        ['key'=>'rad_support_front', 'name'=>'Фронтальный монтажный размер радиатора СЖО', 'value'=>'120 мм, 140 мм, 240 мм, 280 мм, 360 мм', 'group'=>'Охлаждение', 'is_filterable'=>false],
                        ['key'=>'rad_support_top', 'name'=>'Верхний монтажный размер радиатора СЖО', 'value'=>'120 мм, 140 мм, 240 мм, 280 мм, 360 мм', 'group'=>'Охлаждение', 'is_filterable'=>false],
                        ['key'=>'rad_support_rear', 'name'=>'Тыловой монтажный размер радиатора СЖО', 'value'=>'120 мм, 140 мм', 'group'=>'Охлаждение', 'is_filterable'=>false],
                        ['key'=>'io_panel_position', 'name'=>'Расположение I / O панели', 'value'=>'сверху', 'group'=>'Интерфейсы', 'is_filterable'=>false],
                        ['key'=>'io_ports', 'name'=>'Разъемы', 'value'=>'3.5 мм jack (микрофон/аудио) х 1, USB 2.0 Type-A х 1, USB 3.2 Gen 1 Type-A х 2, USB 3.2 Gen 2 Type-C х 1', 'group'=>'Интерфейсы', 'is_filterable'=>false],
                        ['key'=>'dust_filter', 'name'=>'Пылевой фильтр', 'value'=>'есть', 'group'=>'Обслуживание', 'is_filterable'=>false],
                    ]
                ],
                // 3. ARDOR GAMING Rare M2 ARGB черный
                [
                    'title' => 'ARDOR GAMING Rare M2 ARGB черный Корпус',
                    'img' => 'ARDOR GAMING Rare M2 ARGB черный Корпус.webp',
                    'description' => 'Черный корпус ARDOR GAMING Rare M2 ARGB для игрового ПК обладает типоразмером Mid-Tower. Конструкция оснащена металлической решеткой на фронтальной панели и стеклянной боковой стенкой. Такой дизайн подчеркивает ARGB-подсветку 4 установленных вентиляторов и позволяет настраивать систему жидкостного охлаждения. ARDOR GAMING Rare M2 ARGB допускает нижнее размещение блока питания и вертикальную установку материнской платы. Модель оснащена 8 горизонтальными слотами расширения для добавления звуковых и видеокарт длиной до 38 см, интерфейсных плат и других устройств. В корпусе есть 7 мест для установки накопителей 2.5" и 2 внутренних отсека 3.5".',
                    'price' => 5800,
                    'qty' => 21, 
                    'category_id' => $caseCategory->id,
                    'product_type' => 'case',
                'country' => 'Китай',
                    'color' => 'черный',
                    'specs' => [
                        ['key'=>'brand', 'name'=>'Бренд', 'value'=>'ARDOR GAMING', 'group'=>'Основные', 'is_filterable'=>true],
                        ['key'=>'model', 'name'=>'Модель', 'value'=>'Rare M2 ARGB', 'group'=>'Основные', 'is_filterable'=>false],
                        ['key'=>'form_factor', 'name'=>'Типоразмер корпуса', 'value'=>'Mid-Tower', 'group'=>'Основные', 'is_filterable'=>true],
                        ['key'=>'orientation', 'name'=>'Ориентация материнской платы', 'value'=>'вертикально', 'group'=>'Основные', 'is_filterable'=>false],
                        ['key'=>'length', 'name'=>'Длина', 'value'=>'447 мм', 'group'=>'Габариты', 'is_filterable'=>false],
                        ['key'=>'width', 'name'=>'Ширина', 'value'=>'220 мм', 'group'=>'Габариты', 'is_filterable'=>false],
                        ['key'=>'height', 'name'=>'Высота', 'value'=>'510 мм', 'group'=>'Габариты', 'is_filterable'=>false],
                        ['key'=>'weight', 'name'=>'Вес', 'value'=>'6.7 кг', 'group'=>'Габариты', 'is_filterable'=>false],
                        ['key'=>'color', 'name'=>'Основной цвет', 'value'=>'черный', 'group'=>'Внешний вид', 'is_filterable'=>true],
                        ['key'=>'material', 'name'=>'Материал корпуса', 'value'=>'сталь, стекло', 'group'=>'Основные', 'is_filterable'=>true],
                        ['key'=>'metal_thickness', 'name'=>'Толщина металла', 'value'=>'0.7 мм', 'group'=>'Внешний вид', 'is_filterable'=>false],
                        ['key'=>'window', 'name'=>'Наличие окна на боковой стенке', 'value'=>'слева', 'group'=>'Внешний вид', 'is_filterable'=>true],
                        ['key'=>'window_material', 'name'=>'Материал окна', 'value'=>'закаленное стекло', 'group'=>'Внешний вид', 'is_filterable'=>false],
                        ['key'=>'front_panel_material', 'name'=>'Материал фронтальной панели', 'value'=>'металлическая сетка', 'group'=>'Внешний вид', 'is_filterable'=>false],
                        ['key'=>'lighting_type', 'name'=>'Тип подсветки', 'value'=>'ARGB', 'group'=>'Внешний вид', 'is_filterable'=>true],
                        ['key'=>'lighting_color', 'name'=>'Цвет подсветки', 'value'=>'многоцветная', 'group'=>'Внешний вид', 'is_filterable'=>false],
                        ['key'=>'lighting_source', 'name'=>'Источник подсветки', 'value'=>'вентилятор', 'group'=>'Внешний вид', 'is_filterable'=>false],
                        ['key'=>'lighting_connector', 'name'=>'Разъем подключения подсветки', 'value'=>'3-pin 5V-D-G (ARGB)', 'group'=>'Внешний вид', 'is_filterable'=>false],
                        ['key'=>'mobo_compat', 'name'=>'Форм-фактор совместимых плат', 'value'=>'E-ATX, Micro-ATX, Mini-ITX, Standard-ATX', 'group'=>'Совместимость', 'is_filterable'=>true],
                        ['key'=>'psu_compat', 'name'=>'Форм-фактор совместимых блоков питания', 'value'=>'ATX', 'group'=>'Совместимость', 'is_filterable'=>false],
                        ['key'=>'psu_position', 'name'=>'Размещение блока питания', 'value'=>'нижнее', 'group'=>'Совместимость', 'is_filterable'=>false],
                        ['key'=>'max_psu_length', 'name'=>'Максимальная длина блока питания', 'value'=>'200 мм', 'group'=>'Совместимость', 'is_filterable'=>false],
                        ['key'=>'slots_horizontal', 'name'=>'Горизонтальные слоты расширения', 'value'=>'7', 'group'=>'Совместимость', 'is_filterable'=>false],
                        ['key'=>'slots_vertical', 'name'=>'Вертикальные слоты расширения', 'value'=>'3', 'group'=>'Совместимость', 'is_filterable'=>false],
                        ['key'=>'gpu_length', 'name'=>'Максимальная длина устанавливаемой видеокарты', 'value'=>'380 мм', 'group'=>'Совместимость', 'is_filterable'=>true],
                        ['key'=>'cpu_cooler_height', 'name'=>'Максимальная высота процессорного кулера', 'value'=>'165 мм', 'group'=>'Совместимость', 'is_filterable'=>true],
                        ['key'=>'ssd_slots', 'name'=>'Количество отсеков 2.5" накопителей', 'value'=>'7 шт', 'group'=>'Совместимость', 'is_filterable'=>false],
                        ['key'=>'hdd_slots', 'name'=>'Число внутренних отсеков 3.5"', 'value'=>'2 шт', 'group'=>'Совместимость', 'is_filterable'=>false],
                        ['key'=>'fans_included', 'name'=>'Вентиляторы в комплекте', 'value'=>'4 x 120 мм', 'group'=>'Охлаждение', 'is_filterable'=>false],
                        ['key'=>'fan_support_front', 'name'=>'Поддержка фронтальных вентиляторов', 'value'=>'2 x 140 или 3 x 120 мм', 'group'=>'Охлаждение', 'is_filterable'=>false],
                        ['key'=>'fan_support_rear', 'name'=>'Поддержка тыловых вентиляторов', 'value'=>'1 x 120 мм', 'group'=>'Охлаждение', 'is_filterable'=>false],
                        ['key'=>'fan_support_top', 'name'=>'Поддержка верхних вентиляторов', 'value'=>'2 x 140 или 2 x 120 мм', 'group'=>'Охлаждение', 'is_filterable'=>false],
                        ['key'=>'fan_support_bottom', 'name'=>'Поддержка нижних вентиляторов', 'value'=>'2 x 120 мм', 'group'=>'Охлаждение', 'is_filterable'=>false],
                        ['key'=>'liquid_cooling', 'name'=>'Возможность установки системы жидкостного охлаждения', 'value'=>'есть', 'group'=>'Охлаждение', 'is_filterable'=>true],
                        ['key'=>'rad_support_front', 'name'=>'Фронтальный монтажный размер радиатора СЖО', 'value'=>'120 мм, 240 мм, 280 мм', 'group'=>'Охлаждение', 'is_filterable'=>false],
                        ['key'=>'rad_support_top', 'name'=>'Верхний монтажный размер радиатора СЖО', 'value'=>'240 мм, 280 мм', 'group'=>'Охлаждение', 'is_filterable'=>false],
                        ['key'=>'rad_support_rear', 'name'=>'Тыловой монтажный размер радиатора СЖО', 'value'=>'120 мм', 'group'=>'Охлаждение', 'is_filterable'=>false],
                        ['key'=>'io_panel_position', 'name'=>'Расположение I / O панели', 'value'=>'сверху', 'group'=>'Интерфейсы', 'is_filterable'=>false],
                        ['key'=>'io_ports', 'name'=>'Разъемы', 'value'=>'3.5 мм jack (аудио) х 1, 3.5 мм jack (микрофон) х 1, USB 2.0 Type-A х 2, USB 3.2 Gen 1 Type-A х 2', 'group'=>'Интерфейсы', 'is_filterable'=>false],
                        ['key'=>'dust_filter', 'name'=>'Пылевой фильтр', 'value'=>'есть', 'group'=>'Обслуживание', 'is_filterable'=>false],
                    ]
                ],
                // 4. Cougar MX600 RGB [3857C90.0017] черный
                [
                    'title' => 'Корпус Cougar MX600 RGB [3857C90.0017] черный',
                    'img' => 'Корпус Cougar MX600 RGB [3857C90.0017] черный.webp',
                    'description' => 'Корпус Cougar MX600 RGB черного цвета представлен в типоразмере Full-Tower и обладает габаритами 478x235x515 мм. В нем можно разместить все необходимые комплектующие и обеспечить при этом нормальную циркуляцию потоков воздуха для охлаждения «горячих» компонентов сборки. Материнская плата располагается в корпусе вертикально, блок питания длиной до 180 мм размещается снизу. При сборке ПК в представленной модели можно задействовать видеокарту длиной до 400 мм и процессорный кулер высотой до 180 мм. Корпус Cougar MX600 RGB предусматривает в своей конструкции окно из закаленного стекла, которое позволит наслаждаться красочной подсветкой компонентов сборки. Модель укомплектована 4 вентиляторами диагональю 120 мм (1 шт.) и 140 мм (3 шт.). Устройство поддерживает установку системы жидкостного охлаждения, благодаря которой обеспечивается эффективное охлаждение процессора или видеокарты в сочетании с низким уровнем шума. Прокладка кабелей за задней стенкой способствует упорядоченному расположению проводов и улучшенному воздухообмену.',
                    'price' => 10200,
                    'qty' => 72, 
                    'category_id' => $caseCategory->id,
                    'product_type' => 'case',
                    'country' => 'Китай',
                    'color' => 'черный',
                    'specs' => [
                        ['key'=>'brand', 'name'=>'Бренд', 'value'=>'Cougar', 'group'=>'Основные', 'is_filterable'=>true],
                        ['key'=>'model', 'name'=>'Модель', 'value'=>'MX600 RGB [3857C90.0017]', 'group'=>'Основные', 'is_filterable'=>false],
                        ['key'=>'form_factor', 'name'=>'Типоразмер корпуса', 'value'=>'Full-Tower', 'group'=>'Основные', 'is_filterable'=>true],
                        ['key'=>'orientation', 'name'=>'Ориентация материнской платы', 'value'=>'вертикально', 'group'=>'Основные', 'is_filterable'=>false],
                        ['key'=>'length', 'name'=>'Длина', 'value'=>'478 мм', 'group'=>'Габариты', 'is_filterable'=>false],
                        ['key'=>'width', 'name'=>'Ширина', 'value'=>'235 мм', 'group'=>'Габариты', 'is_filterable'=>false],
                        ['key'=>'height', 'name'=>'Высота', 'value'=>'515 мм', 'group'=>'Габариты', 'is_filterable'=>false],
                        ['key'=>'weight', 'name'=>'Вес', 'value'=>'10.65 кг', 'group'=>'Габариты', 'is_filterable'=>false],
                        ['key'=>'color', 'name'=>'Основной цвет', 'value'=>'черный', 'group'=>'Внешний вид', 'is_filterable'=>true],
                        ['key'=>'material', 'name'=>'Материал корпуса', 'value'=>'пластик, сталь, стекло', 'group'=>'Основные', 'is_filterable'=>true],
                        ['key'=>'window', 'name'=>'Наличие окна на боковой стенке', 'value'=>'слева', 'group'=>'Внешний вид', 'is_filterable'=>true],
                        ['key'=>'window_material', 'name'=>'Материал окна', 'value'=>'закаленное стекло', 'group'=>'Внешний вид', 'is_filterable'=>false],
                        ['key'=>'front_panel_material', 'name'=>'Материал фронтальной панели', 'value'=>'пластик', 'group'=>'Внешний вид', 'is_filterable'=>false],
                        ['key'=>'lighting_type', 'name'=>'Тип подсветки', 'value'=>'ARGB', 'group'=>'Внешний вид', 'is_filterable'=>true],
                        ['key'=>'lighting_color', 'name'=>'Цвет подсветки', 'value'=>'многоцветная', 'group'=>'Внешний вид', 'is_filterable'=>false],
                        ['key'=>'lighting_source', 'name'=>'Источник подсветки', 'value'=>'вентилятор', 'group'=>'Внешний вид', 'is_filterable'=>false],
                        ['key'=>'lighting_connector', 'name'=>'Разъем подключения подсветки', 'value'=>'3-pin 5V-D-G (ARGB)', 'group'=>'Внешний вид', 'is_filterable'=>false],
                        ['key'=>'lighting_control', 'name'=>'Способ управления подсветкой', 'value'=>'кнопка на корпусе', 'group'=>'Внешний вид', 'is_filterable'=>false],
                        ['key'=>'mobo_compat', 'name'=>'Форм-фактор совместимых плат', 'value'=>'E-ATX, Micro-ATX, Mini-ITX, SSI-CEB, Standard-ATX', 'group'=>'Совместимость', 'is_filterable'=>true],
                        ['key'=>'psu_compat', 'name'=>'Форм-фактор совместимых блоков питания', 'value'=>'ATX', 'group'=>'Совместимость', 'is_filterable'=>false],
                        ['key'=>'psu_position', 'name'=>'Размещение блока питания', 'value'=>'нижнее', 'group'=>'Совместимость', 'is_filterable'=>false],
                        ['key'=>'max_psu_length', 'name'=>'Максимальная длина блока питания', 'value'=>'180 мм', 'group'=>'Совместимость', 'is_filterable'=>false],
                        ['key'=>'slots_horizontal', 'name'=>'Горизонтальные слоты расширения', 'value'=>'8', 'group'=>'Совместимость', 'is_filterable'=>false],
                        ['key'=>'slots_vertical', 'name'=>'Вертикальные слоты расширения', 'value'=>'3', 'group'=>'Совместимость', 'is_filterable'=>false],
                        ['key'=>'gpu_length', 'name'=>'Максимальная длина устанавливаемой видеокарты', 'value'=>'400 мм', 'group'=>'Совместимость', 'is_filterable'=>true],
                        ['key'=>'cpu_cooler_height', 'name'=>'Максимальная высота процессорного кулера', 'value'=>'180 мм', 'group'=>'Совместимость', 'is_filterable'=>true],
                        ['key'=>'ssd_slots', 'name'=>'Количество отсеков 2.5" накопителей', 'value'=>'4 шт', 'group'=>'Совместимость', 'is_filterable'=>false],
                        ['key'=>'hdd_slots', 'name'=>'Число внутренних отсеков 3.5"', 'value'=>'2 шт', 'group'=>'Совместимость', 'is_filterable'=>false],
                        ['key'=>'fans_included', 'name'=>'Вентиляторы в комплекте', 'value'=>'1 x 120 мм, 3 x 140 мм', 'group'=>'Охлаждение', 'is_filterable'=>false],
                        ['key'=>'fan_support_front', 'name'=>'Поддержка фронтальных вентиляторов', 'value'=>'3 x 140 или 3 x 120 мм', 'group'=>'Охлаждение', 'is_filterable'=>false],
                        ['key'=>'fan_support_rear', 'name'=>'Поддержка тыловых вентиляторов', 'value'=>'1 x 140 или 1 x 120 мм', 'group'=>'Охлаждение', 'is_filterable'=>false],
                        ['key'=>'fan_support_top', 'name'=>'Поддержка верхних вентиляторов', 'value'=>'3 x 140 или 3 x 120 мм', 'group'=>'Охлаждение', 'is_filterable'=>false],
                        ['key'=>'fan_support_bottom', 'name'=>'Поддержка нижних вентиляторов', 'value'=>'2 x 120 мм', 'group'=>'Охлаждение', 'is_filterable'=>false],
                        ['key'=>'liquid_cooling', 'name'=>'Возможность установки системы жидкостного охлаждения', 'value'=>'есть', 'group'=>'Охлаждение', 'is_filterable'=>true],
                        ['key'=>'rad_support_front', 'name'=>'Фронтальный монтажный размер радиатора СЖО', 'value'=>'120 мм, 140 мм, 240 мм, 280 мм, 360 мм', 'group'=>'Охлаждение', 'is_filterable'=>false],
                        ['key'=>'rad_support_top', 'name'=>'Верхний монтажный размер радиатора СЖО', 'value'=>'120 мм, 140 мм, 240 мм, 280 мм, 360 мм', 'group'=>'Охлаждение', 'is_filterable'=>false],
                        ['key'=>'rad_support_rear', 'name'=>'Тыловой монтажный размер радиатора СЖО', 'value'=>'120 мм, 140 мм', 'group'=>'Охлаждение', 'is_filterable'=>false],
                        ['key'=>'io_panel_position', 'name'=>'Расположение I / O панели', 'value'=>'сверху', 'group'=>'Интерфейсы', 'is_filterable'=>false],
                        ['key'=>'io_ports', 'name'=>'Разъемы', 'value'=>'3.5 мм jack (микрофон/аудио) х 1, USB 3.2 Gen 1 Type-A х 2, USB 3.2 Gen 2 Type-C х 1', 'group'=>'Интерфейсы', 'is_filterable'=>false],
                        ['key'=>'dust_filter', 'name'=>'Пылевой фильтр', 'value'=>'есть', 'group'=>'Обслуживание', 'is_filterable'=>false],
                    ]
                ],
                // 5. MONTECH KING 95 PRO черный
                [
                    'title' => 'Корпус MONTECH KING 95 PRO черный',
                    'img' => 'Корпус MONTECH KING 95 PRO черный.webp',
                    'description' => 'Корпус MONTECH KING 95 PRO черного цвета — это современный Mid-Tower с уникальным дизайном и панелями из закаленного стекла. Поддерживает материнские платы до E-ATX, видеокарты длиной до 400 мм и процессорные кулеры высотой до 175 мм. В комплекте 4 ARGB вентилятора 140 мм. Отличается отличной продуваемостью и возможностью установки СЖО до 360 мм.','price' => 10900,
                    'category_id' => $caseCategory->id,
                    'product_type' => 'case',
                    'qty' => 5, 
                    'country' => 'Китай',
                    'color' => 'черный',
                    'specs' => [
                        ['key'=>'brand', 'name'=>'Бренд', 'value'=>'MONTECH', 'group'=>'Основные', 'is_filterable'=>true],
                        ['key'=>'model', 'name'=>'Модель', 'value'=>'KING 95 PRO', 'group'=>'Основные', 'is_filterable'=>false],
                        ['key'=>'form_factor', 'name'=>'Типоразмер корпуса', 'value'=>'Mid-Tower', 'group'=>'Основные', 'is_filterable'=>true],
                        ['key'=>'orientation', 'name'=>'Ориентация материнской платы', 'value'=>'вертикально', 'group'=>'Основные', 'is_filterable'=>false],
                        ['key'=>'length', 'name'=>'Длина', 'value'=>'490 мм', 'group'=>'Габариты', 'is_filterable'=>false],
                        ['key'=>'width', 'name'=>'Ширина', 'value'=>'230 мм', 'group'=>'Габариты', 'is_filterable'=>false],
                        ['key'=>'height', 'name'=>'Высота', 'value'=>'490 мм', 'group'=>'Габариты', 'is_filterable'=>false],
                        ['key'=>'weight', 'name'=>'Вес', 'value'=>'10.2 кг', 'group'=>'Габариты', 'is_filterable'=>false],
                        ['key'=>'color', 'name'=>'Основной цвет', 'value'=>'черный', 'group'=>'Внешний вид', 'is_filterable'=>true],
                        ['key'=>'material', 'name'=>'Материал корпуса', 'value'=>'сталь, стекло', 'group'=>'Основные', 'is_filterable'=>true],
                        ['key'=>'window', 'name'=>'Наличие окна на боковой стенке', 'value'=>'слева, справа', 'group'=>'Внешний вид', 'is_filterable'=>true],
                        ['key'=>'window_material', 'name'=>'Материал окна', 'value'=>'закаленное стекло', 'group'=>'Внешний вид', 'is_filterable'=>false],
                        ['key'=>'lighting_type', 'name'=>'Тип подсветки', 'value'=>'ARGB', 'group'=>'Внешний вид', 'is_filterable'=>true],
                        ['key'=>'lighting_color', 'name'=>'Цвет подсветки', 'value'=>'многоцветная', 'group'=>'Внешний вид', 'is_filterable'=>false],
                        ['key'=>'lighting_source', 'name'=>'Источник подсветки', 'value'=>'вентилятор', 'group'=>'Внешний вид', 'is_filterable'=>false],
                        ['key'=>'mobo_compat', 'name'=>'Форм-фактор совместимых плат', 'value'=>'E-ATX, ATX, Micro-ATX, Mini-ITX', 'group'=>'Совместимость', 'is_filterable'=>true],
                        ['key'=>'psu_compat', 'name'=>'Форм-фактор совместимых блоков питания', 'value'=>'ATX', 'group'=>'Совместимость', 'is_filterable'=>false],
                        ['key'=>'psu_position', 'name'=>'Размещение блока питания', 'value'=>'нижнее', 'group'=>'Совместимость', 'is_filterable'=>false],
                        ['key'=>'max_psu_length', 'name'=>'Максимальная длина блока питания', 'value'=>'200 мм', 'group'=>'Совместимость', 'is_filterable'=>false],
                        ['key'=>'slots_horizontal', 'name'=>'Горизонтальные слоты расширения', 'value'=>'7', 'group'=>'Совместимость', 'is_filterable'=>false],
                        ['key'=>'slots_vertical', 'name'=>'Вертикальные слоты расширения', 'value'=>'3', 'group'=>'Совместимость', 'is_filterable'=>false],
                        ['key'=>'gpu_length', 'name'=>'Максимальная длина устанавливаемой видеокарты', 'value'=>'400 мм', 'group'=>'Совместимость', 'is_filterable'=>true],
                        ['key'=>'cpu_cooler_height', 'name'=>'Максимальная высота процессорного кулера', 'value'=>'175 мм', 'group'=>'Совместимость', 'is_filterable'=>true],
                        ['key'=>'ssd_slots', 'name'=>'Количество отсеков 2.5" накопителей', 'value'=>'4 шт', 'group'=>'Совместимость', 'is_filterable'=>false],
                        ['key'=>'hdd_slots', 'name'=>'Число внутренних отсеков 3.5"', 'value'=>'2 шт', 'group'=>'Совместимость', 'is_filterable'=>false],
                        ['key'=>'fans_included', 'name'=>'Вентиляторы в комплекте', 'value'=>'4 x 140 мм ARGB', 'group'=>'Охлаждение', 'is_filterable'=>false],
                        ['key'=>'fan_support_front', 'name'=>'Поддержка фронтальных вентиляторов', 'value'=>'3 x 120/140 мм', 'group'=>'Охлаждение', 'is_filterable'=>false],
                        ['key'=>'fan_support_rear', 'name'=>'Поддержка тыловых вентиляторов', 'value'=>'1 x 120/140 мм', 'group'=>'Охлаждение', 'is_filterable'=>false],
                        ['key'=>'fan_support_top', 'name'=>'Поддержка верхних вентиляторов', 'value'=>'3 x 120/140 мм', 'group'=>'Охлаждение', 'is_filterable'=>false],
                        ['key'=>'fan_support_bottom', 'name'=>'Поддержка нижних вентиляторов', 'value'=>'2 x 120 мм', 'group'=>'Охлаждение', 'is_filterable'=>false],
                        ['key'=>'liquid_cooling', 'name'=>'Возможность установки системы жидкостного охлаждения', 'value'=>'есть', 'group'=>'Охлаждение', 'is_filterable'=>true],
                        ['key'=>'rad_support_front', 'name'=>'Фронтальный монтажный размер радиатора СЖО', 'value'=>'360 мм', 'group'=>'Охлаждение', 'is_filterable'=>false],
                        ['key'=>'rad_support_top', 'name'=>'Верхний монтажный размер радиатора СЖО', 'value'=>'360 мм', 'group'=>'Охлаждение', 'is_filterable'=>false],
                        ['key'=>'rad_support_rear', 'name'=>'Тыловой монтажный размер радиатора СЖО', 'value'=>'120/140 мм', 'group'=>'Охлаждение', 'is_filterable'=>false],
                        ['key'=>'io_panel_position', 'name'=>'Расположение I / O панели', 'value'=>'сверху', 'group'=>'Интерфейсы', 'is_filterable'=>false],
                        ['key'=>'io_ports', 'name'=>'Разъемы', 'value'=>'USB 3.0 x 2, USB 2.0 x 2, Audio x 1, Mic x 1', 'group'=>'Интерфейсы', 'is_filterable'=>false],
                        ['key'=>'dust_filter', 'name'=>'Пылевой фильтр', 'value'=>'есть', 'group'=>'Обслуживание', 'is_filterable'=>false],
                    ]
                ],
                // 6. ARDOR GAMING Rare M6 черный
                [
                    'title' => 'Корпус ARDOR GAMING Rare M6 черный',
                    'img' => 'Корпус ARDOR GAMING Rare M6 черный.webp',
                    'description' => 'Корпус ARDOR GAMING Rare M6 черный — современный Mid-Tower с боковой панелью из закаленного стекла, поддержкой материнских плат до ATX, видеокарт до 340 мм и процессорных кулеров до 160 мм. В комплекте 4 ARGB вентилятора 120 мм.','price' => 5900,
                    'category_id' => $caseCategory->id,
                    'product_type' => 'case',
                    'qty' => 7, 
                    'country' => 'Китай',
                    'color' => 'черный',
                    'specs' => [
                        ['key'=>'brand', 'name'=>'Бренд', 'value'=>'ARDOR GAMING', 'group'=>'Основные', 'is_filterable'=>true],
                        ['key'=>'model', 'name'=>'Модель', 'value'=>'Rare M6', 'group'=>'Основные', 'is_filterable'=>false],
                        ['key'=>'form_factor', 'name'=>'Типоразмер корпуса', 'value'=>'Mid-Tower', 'group'=>'Основные', 'is_filterable'=>true],
                        ['key'=>'orientation', 'name'=>'Ориентация материнской платы', 'value'=>'вертикально', 'group'=>'Основные', 'is_filterable'=>false],
                        ['key'=>'length', 'name'=>'Длина', 'value'=>'410 мм', 'group'=>'Габариты', 'is_filterable'=>false],
                        ['key'=>'width', 'name'=>'Ширина', 'value'=>'210 мм', 'group'=>'Габариты', 'is_filterable'=>false],
                        ['key'=>'height', 'name'=>'Высота', 'value'=>'480 мм', 'group'=>'Габариты', 'is_filterable'=>false],
                        ['key'=>'weight', 'name'=>'Вес', 'value'=>'5.5 кг', 'group'=>'Габариты', 'is_filterable'=>false],
                        ['key'=>'color', 'name'=>'Основной цвет', 'value'=>'черный', 'group'=>'Внешний вид', 'is_filterable'=>true],
                        ['key'=>'material', 'name'=>'Материал корпуса', 'value'=>'сталь, стекло', 'group'=>'Основные', 'is_filterable'=>true],
                        ['key'=>'window', 'name'=>'Наличие окна на боковой стенке', 'value'=>'слева', 'group'=>'Внешний вид', 'is_filterable'=>true],
                        ['key'=>'window_material', 'name'=>'Материал окна', 'value'=>'закаленное стекло', 'group'=>'Внешний вид', 'is_filterable'=>false],
                        ['key'=>'lighting_type', 'name'=>'Тип подсветки', 'value'=>'ARGB', 'group'=>'Внешний вид', 'is_filterable'=>true],
                        ['key'=>'lighting_color', 'name'=>'Цвет подсветки', 'value'=>'многоцветная', 'group'=>'Внешний вид', 'is_filterable'=>false],
                        ['key'=>'lighting_source', 'name'=>'Источник подсветки', 'value'=>'вентилятор', 'group'=>'Внешний вид', 'is_filterable'=>false],
                        ['key'=>'mobo_compat', 'name'=>'Форм-фактор совместимых плат', 'value'=>'ATX, Micro-ATX, Mini-ITX', 'group'=>'Совместимость', 'is_filterable'=>true],
                        ['key'=>'psu_compat', 'name'=>'Форм-фактор совместимых блоков питания', 'value'=>'ATX', 'group'=>'Совместимость', 'is_filterable'=>false],
                        ['key'=>'psu_position', 'name'=>'Размещение блока питания', 'value'=>'нижнее', 'group'=>'Совместимость', 'is_filterable'=>false],
                        ['key'=>'max_psu_length', 'name'=>'Максимальная длина блока питания', 'value'=>'180 мм', 'group'=>'Совместимость', 'is_filterable'=>false],
                        ['key'=>'slots_horizontal', 'name'=>'Горизонтальные слоты расширения', 'value'=>'7', 'group'=>'Совместимость', 'is_filterable'=>false],
                        ['key'=>'slots_vertical', 'name'=>'Вертикальные слоты расширения', 'value'=>'3', 'group'=>'Совместимость', 'is_filterable'=>false],
                        ['key'=>'gpu_length', 'name'=>'Максимальная длина устанавливаемой видеокарты', 'value'=>'340 мм', 'group'=>'Совместимость', 'is_filterable'=>true],
                        ['key'=>'cpu_cooler_height', 'name'=>'Максимальная высота процессорного кулера', 'value'=>'160 мм', 'group'=>'Совместимость', 'is_filterable'=>true],
                        ['key'=>'ssd_slots', 'name'=>'Количество отсеков 2.5" накопителей', 'value'=>'2 шт', 'group'=>'Совместимость', 'is_filterable'=>false],
                        ['key'=>'hdd_slots', 'name'=>'Число внутренних отсеков 3.5"', 'value'=>'2 шт', 'group'=>'Совместимость', 'is_filterable'=>false],
                        ['key'=>'fans_included', 'name'=>'Вентиляторы в комплекте', 'value'=>'4 x 120 мм ARGB', 'group'=>'Охлаждение', 'is_filterable'=>false],
                        ['key'=>'fan_support_front', 'name'=>'Поддержка фронтальных вентиляторов', 'value'=>'3 x 120 мм', 'group'=>'Охлаждение', 'is_filterable'=>false],
                        ['key'=>'fan_support_rear', 'name'=>'Поддержка тыловых вентиляторов', 'value'=>'1 x 120 мм', 'group'=>'Охлаждение', 'is_filterable'=>false],
                        ['key'=>'fan_support_top', 'name'=>'Поддержка верхних вентиляторов', 'value'=>'2 x 120 мм', 'group'=>'Охлаждение', 'is_filterable'=>false],
                        ['key'=>'fan_support_bottom', 'name'=>'Поддержка нижних вентиляторов', 'value'=>'2 x 120 мм', 'group'=>'Охлаждение', 'is_filterable'=>false],
                        ['key'=>'liquid_cooling', 'name'=>'Возможность установки системы жидкостного охлаждения', 'value'=>'есть', 'group'=>'Охлаждение', 'is_filterable'=>true],
                        ['key'=>'rad_support_front', 'name'=>'Фронтальный монтажный размер радиатора СЖО', 'value'=>'240 мм', 'group'=>'Охлаждение', 'is_filterable'=>false],
                        ['key'=>'rad_support_top', 'name'=>'Верхний монтажный размер радиатора СЖО', 'value'=>'240 мм', 'group'=>'Охлаждение', 'is_filterable'=>false],
                        ['key'=>'rad_support_rear', 'name'=>'Тыловой монтажный размер радиатора СЖО', 'value'=>'120 мм', 'group'=>'Охлаждение', 'is_filterable'=>false],
                        ['key'=>'io_panel_position', 'name'=>'Расположение I / O панели', 'value'=>'сверху', 'group'=>'Интерфейсы', 'is_filterable'=>false],
                        ['key'=>'io_ports', 'name'=>'Разъемы', 'value'=>'USB 2.0 x 2, USB 3.0 x 1, Audio x 1, Mic x 1', 'group'=>'Интерфейсы', 'is_filterable'=>false],
                        ['key'=>'dust_filter', 'name'=>'Пылевой фильтр', 'value'=>'есть', 'group'=>'Обслуживание', 'is_filterable'=>false],
                    ]
                ],
                // 7. ARDOR GAMING Rare Minicase MS1 черный
                [
                    'title' => 'Корпус ARDOR GAMING Rare Minicase MS1 черный',
                    'description' => 'Компактный корпус ARDOR GAMING Rare Minicase MS1 черного цвета для Mini-ITX сборок. Поддержка видеокарт до 320 мм, процессорных кулеров до 140 мм, 2 ARGB вентилятора 120 мм в комплекте, боковая панель из закаленного стекла.','price' => 4200,
                    'category_id' => $caseCategory->id,
                    'product_type' => 'case',
                    'img' => 'Корпус ARDOR GAMING Rare Minicase MS1 черный.webp',
                    'country' => 'Китай',
                    'qty' => 21, 
                    'color' => 'черный',
                    'specs' => [
                        ['key'=>'brand', 'name'=>'Бренд', 'value'=>'ARDOR GAMING', 'group'=>'Основные', 'is_filterable'=>true],
                        ['key'=>'model', 'name'=>'Модель', 'value'=>'Rare Minicase MS1', 'group'=>'Основные', 'is_filterable'=>false],
                        ['key'=>'form_factor', 'name'=>'Типоразмер корпуса', 'value'=>'Mini-Tower', 'group'=>'Основные', 'is_filterable'=>true],
                        ['key'=>'orientation', 'name'=>'Ориентация материнской платы', 'value'=>'вертикально', 'group'=>'Основные', 'is_filterable'=>false],
                        ['key'=>'length', 'name'=>'Длина', 'value'=>'340 мм', 'group'=>'Габариты', 'is_filterable'=>false],
                        ['key'=>'width', 'name'=>'Ширина', 'value'=>'180 мм', 'group'=>'Габариты', 'is_filterable'=>false],
                        ['key'=>'height', 'name'=>'Высота', 'value'=>'370 мм', 'group'=>'Габариты', 'is_filterable'=>false],
                        ['key'=>'weight', 'name'=>'Вес', 'value'=>'3.2 кг', 'group'=>'Габариты', 'is_filterable'=>false],
                        ['key'=>'color', 'name'=>'Основной цвет', 'value'=>'черный', 'group'=>'Внешний вид', 'is_filterable'=>true],
                        ['key'=>'material', 'name'=>'Материал корпуса', 'value'=>'сталь, стекло', 'group'=>'Основные', 'is_filterable'=>true],
                        ['key'=>'window', 'name'=>'Наличие окна на боковой стенке', 'value'=>'слева', 'group'=>'Внешний вид', 'is_filterable'=>true],
                        ['key'=>'window_material', 'name'=>'Материал окна', 'value'=>'закаленное стекло', 'group'=>'Внешний вид', 'is_filterable'=>false],
                        ['key'=>'lighting_type', 'name'=>'Тип подсветки', 'value'=>'ARGB', 'group'=>'Внешний вид', 'is_filterable'=>true],
                        ['key'=>'lighting_color', 'name'=>'Цвет подсветки', 'value'=>'многоцветная', 'group'=>'Внешний вид', 'is_filterable'=>false],
                        ['key'=>'lighting_source', 'name'=>'Источник подсветки', 'value'=>'вентилятор', 'group'=>'Внешний вид', 'is_filterable'=>false],
                        ['key'=>'mobo_compat', 'name'=>'Форм-фактор совместимых плат', 'value'=>'Mini-ITX', 'group'=>'Совместимость', 'is_filterable'=>true],
                        ['key'=>'psu_compat', 'name'=>'Форм-фактор совместимых блоков питания', 'value'=>'SFX', 'group'=>'Совместимость', 'is_filterable'=>false],
                        ['key'=>'psu_position', 'name'=>'Размещение блока питания', 'value'=>'нижнее', 'group'=>'Совместимость', 'is_filterable'=>false],
                        ['key'=>'max_psu_length', 'name'=>'Максимальная длина блока питания', 'value'=>'150 мм', 'group'=>'Совместимость', 'is_filterable'=>false],
                        ['key'=>'slots_horizontal', 'name'=>'Горизонтальные слоты расширения', 'value'=>'2', 'group'=>'Совместимость', 'is_filterable'=>false],
                        ['key'=>'slots_vertical', 'name'=>'Вертикальные слоты расширения', 'value'=>'2', 'group'=>'Совместимость', 'is_filterable'=>false],
                        ['key'=>'gpu_length', 'name'=>'Максимальная длина устанавливаемой видеокарты', 'value'=>'320 мм', 'group'=>'Совместимость', 'is_filterable'=>true],
                        ['key'=>'cpu_cooler_height', 'name'=>'Максимальная высота процессорного кулера', 'value'=>'140 мм', 'group'=>'Совместимость', 'is_filterable'=>true],
                        ['key'=>'ssd_slots', 'name'=>'Количество отсеков 2.5" накопителей', 'value'=>'2 шт', 'group'=>'Совместимость', 'is_filterable'=>false],
                        ['key'=>'hdd_slots', 'name'=>'Число внутренних отсеков 3.5"', 'value'=>'2 шт', 'group'=>'Совместимость', 'is_filterable'=>false],
                        ['key'=>'fans_included', 'name'=>'Вентиляторы в комплекте', 'value'=>'2 x 120 мм ARGB', 'group'=>'Охлаждение', 'is_filterable'=>false],
                        ['key'=>'fan_support_front', 'name'=>'Поддержка фронтальных вентиляторов', 'value'=>'1 x 120 мм', 'group'=>'Охлаждение', 'is_filterable'=>false],
                        ['key'=>'fan_support_rear', 'name'=>'Поддержка тыловых вентиляторов', 'value'=>'1 x 120 мм', 'group'=>'Охлаждение', 'is_filterable'=>false],
                        ['key'=>'fan_support_top', 'name'=>'Поддержка верхних вентиляторов', 'value'=>'1 x 120 мм', 'group'=>'Охлаждение', 'is_filterable'=>false],
                        ['key'=>'fan_support_bottom', 'name'=>'Поддержка нижних вентиляторов', 'value'=>'1 x 120 мм', 'group'=>'Охлаждение', 'is_filterable'=>false],
                        ['key'=>'liquid_cooling', 'name'=>'Возможность установки системы жидкостного охлаждения', 'value'=>'нет', 'group'=>'Охлаждение', 'is_filterable'=>true],
                        ['key'=>'io_panel_position', 'name'=>'Расположение I / O панели', 'value'=>'сверху', 'group'=>'Интерфейсы', 'is_filterable'=>false],
                        ['key'=>'io_ports', 'name'=>'Разъемы', 'value'=>'USB 2.0 x 1, USB 3.0 x 1, Audio x 1', 'group'=>'Интерфейсы', 'is_filterable'=>false],
                        ['key'=>'dust_filter', 'name'=>'Пылевой фильтр', 'value'=>'есть', 'group'=>'Обслуживание', 'is_filterable'=>false],
                    ]
                ],
                // 8. LIAN LI LANCOOL III черный
                [
                    'title' => 'Корпус LIAN LI LANCOOL III черный',
                    'description' => 'Корпус LIAN LI LANCOOL III черного цвета — это современный Full-Tower с поддержкой E-ATX, 4 вентиляторами 140 мм в комплекте, панелями из закаленного стекла и отличной продуваемостью.','price' => 16900,
                    'category_id' => $caseCategory->id,
                    'product_type' => 'case',
                    'qty' => 19, 
                    'country' => 'Китай',
                    'img' => 'Корпус LIAN LI LANCOOL III черный.webp',
                    'color' => 'черный',
                    'specs' => [
                        ['key'=>'brand', 'name'=>'Бренд', 'value'=>'LIAN LI', 'group'=>'Основные', 'is_filterable'=>true],
                        ['key'=>'model', 'name'=>'Модель', 'value'=>'LANCOOL III', 'group'=>'Основные', 'is_filterable'=>false],
                        ['key'=>'form_factor', 'name'=>'Типоразмер корпуса', 'value'=>'Full-Tower', 'group'=>'Основные', 'is_filterable'=>true],
                        ['key'=>'orientation', 'name'=>'Ориентация материнской платы', 'value'=>'вертикально', 'group'=>'Основные', 'is_filterable'=>false],
                        ['key'=>'length', 'name'=>'Длина', 'value'=>'523 мм', 'group'=>'Габариты', 'is_filterable'=>false],
                        ['key'=>'width', 'name'=>'Ширина', 'value'=>'238 мм', 'group'=>'Габариты', 'is_filterable'=>false],
                        ['key'=>'height', 'name'=>'Высота', 'value'=>'526 мм', 'group'=>'Габариты', 'is_filterable'=>false],
                        ['key'=>'weight', 'name'=>'Вес', 'value'=>'14.5 кг', 'group'=>'Габариты', 'is_filterable'=>false],
                        ['key'=>'color', 'name'=>'Основной цвет', 'value'=>'черный', 'group'=>'Внешний вид', 'is_filterable'=>true],
                        ['key'=>'material', 'name'=>'Материал корпуса', 'value'=>'сталь, стекло', 'group'=>'Основные', 'is_filterable'=>true],
                        ['key'=>'window', 'name'=>'Наличие окна на боковой стенке', 'value'=>'слева, справа', 'group'=>'Внешний вид', 'is_filterable'=>true],
                        ['key'=>'window_material', 'name'=>'Материал окна', 'value'=>'закаленное стекло', 'group'=>'Внешний вид', 'is_filterable'=>false],
                        ['key'=>'lighting_type', 'name'=>'Тип подсветки', 'value'=>'нет', 'group'=>'Внешний вид', 'is_filterable'=>true],
                        ['key'=>'mobo_compat', 'name'=>'Форм-фактор совместимых плат', 'value'=>'E-ATX, ATX, Micro-ATX, Mini-ITX', 'group'=>'Совместимость', 'is_filterable'=>true],
                        ['key'=>'psu_compat', 'name'=>'Форм-фактор совместимых блоков питания', 'value'=>'ATX', 'group'=>'Совместимость', 'is_filterable'=>false],
                        ['key'=>'psu_position', 'name'=>'Размещение блока питания', 'value'=>'нижнее', 'group'=>'Совместимость', 'is_filterable'=>false],
                        ['key'=>'max_psu_length', 'name'=>'Максимальная длина блока питания', 'value'=>'220 мм', 'group'=>'Совместимость', 'is_filterable'=>false],
                        ['key'=>'slots_horizontal', 'name'=>'Горизонтальные слоты расширения', 'value'=>'8', 'group'=>'Совместимость', 'is_filterable'=>false],
                        ['key'=>'slots_vertical', 'name'=>'Вертикальные слоты расширения', 'value'=>'3', 'group'=>'Совместимость', 'is_filterable'=>false],
                        ['key'=>'gpu_length', 'name'=>'Максимальная длина устанавливаемой видеокарты', 'value'=>'420 мм', 'group'=>'Совместимость', 'is_filterable'=>true],
                        ['key'=>'cpu_cooler_height', 'name'=>'Максимальная высота процессорного кулера', 'value'=>'187 мм', 'group'=>'Совместимость', 'is_filterable'=>true],
                        ['key'=>'ssd_slots', 'name'=>'Количество отсеков 2.5" накопителей', 'value'=>'4 шт', 'group'=>'Совместимость', 'is_filterable'=>false],
                        ['key'=>'hdd_slots', 'name'=>'Число внутренних отсеков 3.5"', 'value'=>'4 шт', 'group'=>'Совместимость', 'is_filterable'=>false],
                        ['key'=>'fans_included', 'name'=>'Вентиляторы в комплекте', 'value'=>'4 x 140 мм', 'group'=>'Охлаждение', 'is_filterable'=>false],
                        ['key'=>'fan_support_front', 'name'=>'Поддержка фронтальных вентиляторов', 'value'=>'3 x 120/140 мм', 'group'=>'Охлаждение', 'is_filterable'=>false],
                        ['key'=>'fan_support_rear', 'name'=>'Поддержка тыловых вентиляторов', 'value'=>'1 x 120/140 мм', 'group'=>'Охлаждение', 'is_filterable'=>false],
                        ['key'=>'fan_support_top', 'name'=>'Поддержка верхних вентиляторов', 'value'=>'3 x 120/140 мм', 'group'=>'Охлаждение', 'is_filterable'=>false],
                        ['key'=>'fan_support_bottom', 'name'=>'Поддержка нижних вентиляторов', 'value'=>'2 x 120 мм', 'group'=>'Охлаждение', 'is_filterable'=>false],
                        ['key'=>'liquid_cooling', 'name'=>'Возможность установки системы жидкостного охлаждения', 'value'=>'есть', 'group'=>'Охлаждение', 'is_filterable'=>true],
                        ['key'=>'rad_support_front', 'name'=>'Фронтальный монтажный размер радиатора СЖО', 'value'=>'360 мм', 'group'=>'Охлаждение', 'is_filterable'=>false],
                        ['key'=>'rad_support_top', 'name'=>'Верхний монтажный размер радиатора СЖО', 'value'=>'360 мм', 'group'=>'Охлаждение', 'is_filterable'=>false],
                        ['key'=>'rad_support_rear', 'name'=>'Тыловой монтажный размер радиатора СЖО', 'value'=>'120/140 мм', 'group'=>'Охлаждение', 'is_filterable'=>false],
                        ['key'=>'io_panel_position', 'name'=>'Расположение I / O панели', 'value'=>'сверху', 'group'=>'Интерфейсы', 'is_filterable'=>false],
                        ['key'=>'io_ports', 'name'=>'Разъемы', 'value'=>'USB 3.0 x 2, USB 3.1 Type-C x 1, Audio x 1, Mic x 1', 'group'=>'Интерфейсы', 'is_filterable'=>false],
                        ['key'=>'dust_filter', 'name'=>'Пылевой фильтр', 'value'=>'есть', 'group'=>'Обслуживание', 'is_filterable'=>false],
                    ]
                ],
                // 9. MONTECH KING 95 PRO белый
                [
                    'title' => 'Корпус MONTECH KING 95 PRO белый',
                    'description' => 'Корпус MONTECH KING 95 PRO белого цвета — это современный Mid-Tower с уникальным дизайном и панелями из закаленного стекла. Поддерживает материнские платы до E-ATX, видеокарты длиной до 400 мм и процессорные кулеры высотой до 175 мм. В комплекте 4 ARGB вентилятора 140 мм. Отличается отличной продуваемостью и возможностью установки СЖО до 360 мм.','price' => 11200,
                    'category_id' => $caseCategory->id,
                    'product_type' => 'case',
                    'qty' => 5, 
                    'country' => 'Китай',
                    'color' => 'белый',
                    'img' => 'Корпус MONTECH KING 95 PRO белый.webp',
                    'specs' => [
                        ['key'=>'brand', 'name'=>'Бренд', 'value'=>'MONTECH', 'group'=>'Основные', 'is_filterable'=>true],
                        ['key'=>'model', 'name'=>'Модель', 'value'=>'KING 95 PRO', 'group'=>'Основные', 'is_filterable'=>false],
                        ['key'=>'form_factor', 'name'=>'Типоразмер корпуса', 'value'=>'Mid-Tower', 'group'=>'Основные', 'is_filterable'=>true],
                        ['key'=>'orientation', 'name'=>'Ориентация материнской платы', 'value'=>'вертикально', 'group'=>'Основные', 'is_filterable'=>false],
                        ['key'=>'length', 'name'=>'Длина', 'value'=>'490 мм', 'group'=>'Габариты', 'is_filterable'=>false],
                        ['key'=>'width', 'name'=>'Ширина', 'value'=>'230 мм', 'group'=>'Габариты', 'is_filterable'=>false],
                        ['key'=>'height', 'name'=>'Высота', 'value'=>'490 мм', 'group'=>'Габариты', 'is_filterable'=>false],
                        ['key'=>'weight', 'name'=>'Вес', 'value'=>'10.2 кг', 'group'=>'Габариты', 'is_filterable'=>false],
                        ['key'=>'color', 'name'=>'Основной цвет', 'value'=>'белый', 'group'=>'Внешний вид', 'is_filterable'=>true],
                        ['key'=>'material', 'name'=>'Материал корпуса', 'value'=>'сталь, стекло', 'group'=>'Основные', 'is_filterable'=>true],
                        ['key'=>'window', 'name'=>'Наличие окна на боковой стенке', 'value'=>'слева, справа', 'group'=>'Внешний вид', 'is_filterable'=>true],
                        ['key'=>'window_material', 'name'=>'Материал окна', 'value'=>'закаленное стекло', 'group'=>'Внешний вид', 'is_filterable'=>false],
                        ['key'=>'lighting_type', 'name'=>'Тип подсветки', 'value'=>'ARGB', 'group'=>'Внешний вид', 'is_filterable'=>true],
                        ['key'=>'lighting_color', 'name'=>'Цвет подсветки', 'value'=>'многоцветная', 'group'=>'Внешний вид', 'is_filterable'=>false],
                        ['key'=>'lighting_source', 'name'=>'Источник подсветки', 'value'=>'вентилятор', 'group'=>'Внешний вид', 'is_filterable'=>false],
                        ['key'=>'mobo_compat', 'name'=>'Форм-фактор совместимых плат', 'value'=>'E-ATX, ATX, Micro-ATX, Mini-ITX', 'group'=>'Совместимость', 'is_filterable'=>true],
                        ['key'=>'psu_compat', 'name'=>'Форм-фактор совместимых блоков питания', 'value'=>'ATX', 'group'=>'Совместимость', 'is_filterable'=>false],
                        ['key'=>'psu_position', 'name'=>'Размещение блока питания', 'value'=>'нижнее', 'group'=>'Совместимость', 'is_filterable'=>false],
                        ['key'=>'max_psu_length', 'name'=>'Максимальная длина блока питания', 'value'=>'200 мм', 'group'=>'Совместимость', 'is_filterable'=>false],
                        ['key'=>'slots_horizontal', 'name'=>'Горизонтальные слоты расширения', 'value'=>'7', 'group'=>'Совместимость', 'is_filterable'=>false],
                        ['key'=>'slots_vertical', 'name'=>'Вертикальные слоты расширения', 'value'=>'3', 'group'=>'Совместимость', 'is_filterable'=>false],
                        ['key'=>'gpu_length', 'name'=>'Максимальная длина устанавливаемой видеокарты', 'value'=>'400 мм', 'group'=>'Совместимость', 'is_filterable'=>true],
                        ['key'=>'cpu_cooler_height', 'name'=>'Максимальная высота процессорного кулера', 'value'=>'175 мм', 'group'=>'Совместимость', 'is_filterable'=>true],
                        ['key'=>'ssd_slots', 'name'=>'Количество отсеков 2.5" накопителей', 'value'=>'4 шт', 'group'=>'Совместимость', 'is_filterable'=>false],
                        ['key'=>'hdd_slots', 'name'=>'Число внутренних отсеков 3.5"', 'value'=>'2 шт', 'group'=>'Совместимость', 'is_filterable'=>false],
                        ['key'=>'fans_included', 'name'=>'Вентиляторы в комплекте', 'value'=>'4 x 140 мм ARGB', 'group'=>'Охлаждение', 'is_filterable'=>false],
                        ['key'=>'fan_support_front', 'name'=>'Поддержка фронтальных вентиляторов', 'value'=>'3 x 120/140 мм', 'group'=>'Охлаждение', 'is_filterable'=>false],
                        ['key'=>'fan_support_rear', 'name'=>'Поддержка тыловых вентиляторов', 'value'=>'1 x 120/140 мм', 'group'=>'Охлаждение', 'is_filterable'=>false],
                        ['key'=>'fan_support_top', 'name'=>'Поддержка верхних вентиляторов', 'value'=>'3 x 120/140 мм', 'group'=>'Охлаждение', 'is_filterable'=>false],
                        ['key'=>'fan_support_bottom', 'name'=>'Поддержка нижних вентиляторов', 'value'=>'2 x 120 мм', 'group'=>'Охлаждение', 'is_filterable'=>false],
                        ['key'=>'liquid_cooling', 'name'=>'Возможность установки системы жидкостного охлаждения', 'value'=>'есть', 'group'=>'Охлаждение', 'is_filterable'=>true],
                        ['key'=>'rad_support_front', 'name'=>'Фронтальный монтажный размер радиатора СЖО', 'value'=>'360 мм', 'group'=>'Охлаждение', 'is_filterable'=>false],
                        ['key'=>'rad_support_top', 'name'=>'Верхний монтажный размер радиатора СЖО', 'value'=>'360 мм', 'group'=>'Охлаждение', 'is_filterable'=>false],
                        ['key'=>'rad_support_rear', 'name'=>'Тыловой монтажный размер радиатора СЖО', 'value'=>'120/140 мм', 'group'=>'Охлаждение', 'is_filterable'=>false],
                        ['key'=>'io_panel_position', 'name'=>'Расположение I / O панели', 'value'=>'сверху', 'group'=>'Интерфейсы', 'is_filterable'=>false],
                        ['key'=>'io_ports', 'name'=>'Разъемы', 'value'=>'USB 3.0 x 2, USB 2.0 x 2, Audio x 1, Mic x 1', 'group'=>'Интерфейсы', 'is_filterable'=>false],
                        ['key'=>'dust_filter', 'name'=>'Пылевой фильтр', 'value'=>'есть', 'group'=>'Обслуживание', 'is_filterable'=>false],
                    ]
                ],
                // 10. Cougar FV150 RGB белый
                [
                    'title' => 'Корпус Cougar FV150 RGB белый',
                    'description' => 'Белый корпус Cougar FV150 RGB — это компактный Mini-Tower с ARGB-подсветкой, панелями из закаленного стекла, поддержкой видеокарт до 380 мм и процессорных кулеров до 160 мм.','price' => 9900,
                    'category_id' => $caseCategory->id,
                    'product_type' => 'case',
                    'qty' => 9, 
                    'img' => 'Корпус Cougar FV150 RGB белый.webp',
                    'country' => 'Китай',
                    'color' => 'белый',
                    'specs' => [
                        ['key'=>'brand', 'name'=>'Бренд', 'value'=>'Cougar', 'group'=>'Основные', 'is_filterable'=>true],
                        ['key'=>'model', 'name'=>'Модель', 'value'=>'FV150 RGB', 'group'=>'Основные', 'is_filterable'=>false],
                        ['key'=>'form_factor', 'name'=>'Типоразмер корпуса', 'value'=>'Mini-Tower', 'group'=>'Основные', 'is_filterable'=>true],
                        ['key'=>'orientation', 'name'=>'Ориентация материнской платы', 'value'=>'вертикально', 'group'=>'Основные', 'is_filterable'=>false],
                        ['key'=>'length', 'name'=>'Длина', 'value'=>'415 мм', 'group'=>'Габариты', 'is_filterable'=>false],
                        ['key'=>'width', 'name'=>'Ширина', 'value'=>'230 мм', 'group'=>'Габариты', 'is_filterable'=>false],
                        ['key'=>'height', 'name'=>'Высота', 'value'=>'380 мм', 'group'=>'Габариты', 'is_filterable'=>false],
                        ['key'=>'weight', 'name'=>'Вес', 'value'=>'6.1 кг', 'group'=>'Габариты', 'is_filterable'=>false],
                        ['key'=>'color', 'name'=>'Основной цвет', 'value'=>'белый', 'group'=>'Внешний вид', 'is_filterable'=>true],
                        ['key'=>'material', 'name'=>'Материал корпуса', 'value'=>'сталь, стекло', 'group'=>'Основные', 'is_filterable'=>true],
                        ['key'=>'window', 'name'=>'Наличие окна на боковой стенке', 'value'=>'слева', 'group'=>'Внешний вид', 'is_filterable'=>true],
                        ['key'=>'window_material', 'name'=>'Материал окна', 'value'=>'закаленное стекло', 'group'=>'Внешний вид', 'is_filterable'=>false],
                        ['key'=>'lighting_type', 'name'=>'Тип подсветки', 'value'=>'ARGB', 'group'=>'Внешний вид', 'is_filterable'=>true],
                        ['key'=>'lighting_color', 'name'=>'Цвет подсветки', 'value'=>'многоцветная', 'group'=>'Внешний вид', 'is_filterable'=>false],
                        ['key'=>'lighting_source', 'name'=>'Источник подсветки', 'value'=>'вентилятор', 'group'=>'Внешний вид', 'is_filterable'=>false],
                        ['key'=>'mobo_compat', 'name'=>'Форм-фактор совместимых плат', 'value'=>'Micro-ATX, Mini-ITX', 'group'=>'Совместимость', 'is_filterable'=>true],
                        ['key'=>'psu_compat', 'name'=>'Форм-фактор совместимых блоков питания', 'value'=>'ATX', 'group'=>'Совместимость', 'is_filterable'=>false],
                        ['key'=>'psu_position', 'name'=>'Размещение блока питания', 'value'=>'нижнее', 'group'=>'Совместимость', 'is_filterable'=>false],
                        ['key'=>'max_psu_length', 'name'=>'Максимальная длина блока питания', 'value'=>'180 мм', 'group'=>'Совместимость', 'is_filterable'=>false],
                        ['key'=>'slots_horizontal', 'name'=>'Горизонтальные слоты расширения', 'value'=>'4', 'group'=>'Совместимость', 'is_filterable'=>false],
                        ['key'=>'slots_vertical', 'name'=>'Вертикальные слоты расширения', 'value'=>'3', 'group'=>'Совместимость', 'is_filterable'=>false],
                        ['key'=>'gpu_length', 'name'=>'Максимальная длина устанавливаемой видеокарты', 'value'=>'380 мм', 'group'=>'Совместимость', 'is_filterable'=>true],
                        ['key'=>'cpu_cooler_height', 'name'=>'Максимальная высота процессорного кулера', 'value'=>'160 мм', 'group'=>'Совместимость', 'is_filterable'=>true],
                        ['key'=>'ssd_slots', 'name'=>'Количество отсеков 2.5" накопителей', 'value'=>'2 шт', 'group'=>'Совместимость', 'is_filterable'=>false],
                        ['key'=>'hdd_slots', 'name'=>'Число внутренних отсеков 3.5"', 'value'=>'1 шт', 'group'=>'Совместимость', 'is_filterable'=>false],
                        ['key'=>'fans_included', 'name'=>'Вентиляторы в комплекте', 'value'=>'3 x 120 мм ARGB', 'group'=>'Охлаждение', 'is_filterable'=>false],
                        ['key'=>'fan_support_front', 'name'=>'Поддержка фронтальных вентиляторов', 'value'=>'2 x 120 мм', 'group'=>'Охлаждение', 'is_filterable'=>false],
                        ['key'=>'fan_support_rear', 'name'=>'Поддержка тыловых вентиляторов', 'value'=>'1 x 120 мм', 'group'=>'Охлаждение', 'is_filterable'=>false],
                        ['key'=>'fan_support_top', 'name'=>'Поддержка верхних вентиляторов', 'value'=>'2 x 120 мм', 'group'=>'Охлаждение', 'is_filterable'=>false],
                        ['key'=>'fan_support_bottom', 'name'=>'Поддержка нижних вентиляторов', 'value'=>'1 x 120 мм', 'group'=>'Охлаждение', 'is_filterable'=>false],
                        ['key'=>'liquid_cooling', 'name'=>'Возможность установки системы жидкостного охлаждения', 'value'=>'есть', 'group'=>'Охлаждение', 'is_filterable'=>true],
                        ['key'=>'rad_support_front', 'name'=>'Фронтальный монтажный размер радиатора СЖО', 'value'=>'240 мм', 'group'=>'Охлаждение', 'is_filterable'=>false],
                        ['key'=>'rad_support_top', 'name'=>'Верхний монтажный размер радиатора СЖО', 'value'=>'240 мм', 'group'=>'Охлаждение', 'is_filterable'=>false],
                        ['key'=>'rad_support_rear', 'name'=>'Тыловой монтажный размер радиатора СЖО', 'value'=>'120 мм', 'group'=>'Охлаждение', 'is_filterable'=>false],
                        ['key'=>'io_panel_position', 'name'=>'Расположение I / O панели', 'value'=>'сверху', 'group'=>'Интерфейсы', 'is_filterable'=>false],
                        ['key'=>'io_ports', 'name'=>'Разъемы', 'value'=>'USB 2.0 x 1, USB 3.0 x 1, Audio x 1', 'group'=>'Интерфейсы', 'is_filterable'=>false],
                        ['key'=>'dust_filter', 'name'=>'Пылевой фильтр', 'value'=>'есть', 'group'=>'Обслуживание', 'is_filterable'=>false],
                    ]
                ],
                // 11. MONTECH X2 MESH (B) черный
                [
                    'title' => 'Корпус MONTECH X2 MESH (B) черный',
                    'description' => 'Корпус MONTECH X2 MESH (B) черного цвета — это бюджетный Mid-Tower с отличной продуваемостью, поддержкой видеокарт до 305 мм и процессорных кулеров до 160 мм. В комплекте 2 вентилятора 120 мм.','price' => 3900,
                    'category_id' => $caseCategory->id,
                    'product_type' => 'case',
                    'qty' => 12, 
                    'img' => 'Корпус MONTECH X2 MESH (B) черный.webp',
                    'country' => 'Китай',
                    'color' => 'черный',
                    'specs' => [
                        ['key'=>'brand', 'name'=>'Бренд', 'value'=>'MONTECH', 'group'=>'Основные', 'is_filterable'=>true],
                        ['key'=>'model', 'name'=>'Модель', 'value'=>'X2 MESH (B)', 'group'=>'Основные', 'is_filterable'=>false],
                        ['key'=>'form_factor', 'name'=>'Типоразмер корпуса', 'value'=>'Mid-Tower', 'group'=>'Основные', 'is_filterable'=>true],
                        ['key'=>'orientation', 'name'=>'Ориентация материнской платы', 'value'=>'вертикально', 'group'=>'Основные', 'is_filterable'=>false],
                        ['key'=>'length', 'name'=>'Длина', 'value'=>'360 мм', 'group'=>'Габариты', 'is_filterable'=>false],
                        ['key'=>'width', 'name'=>'Ширина', 'value'=>'200 мм', 'group'=>'Габариты', 'is_filterable'=>false],
                        ['key'=>'height', 'name'=>'Высота', 'value'=>'430 мм', 'group'=>'Габариты', 'is_filterable'=>false],
                        ['key'=>'weight', 'name'=>'Вес', 'value'=>'3.8 кг', 'group'=>'Габариты', 'is_filterable'=>false],
                        ['key'=>'color', 'name'=>'Основной цвет', 'value'=>'черный', 'group'=>'Внешний вид', 'is_filterable'=>true],
                        ['key'=>'material', 'name'=>'Материал корпуса', 'value'=>'сталь, пластик', 'group'=>'Основные', 'is_filterable'=>true],
                        ['key'=>'window', 'name'=>'Наличие окна на боковой стенке', 'value'=>'нет', 'group'=>'Внешний вид', 'is_filterable'=>true],
                        ['key'=>'lighting_type', 'name'=>'Тип подсветки', 'value'=>'нет', 'group'=>'Внешний вид', 'is_filterable'=>true],
                        ['key'=>'mobo_compat', 'name'=>'Форм-фактор совместимых плат', 'value'=>'ATX, Micro-ATX, Mini-ITX', 'group'=>'Совместимость', 'is_filterable'=>true],
                        ['key'=>'psu_compat', 'name'=>'Форм-фактор совместимых блоков питания', 'value'=>'ATX', 'group'=>'Совместимость', 'is_filterable'=>false],
                        ['key'=>'psu_position', 'name'=>'Размещение блока питания', 'value'=>'нижнее', 'group'=>'Совместимость', 'is_filterable'=>false],
                        ['key'=>'max_psu_length', 'name'=>'Максимальная длина блока питания', 'value'=>'160 мм', 'group'=>'Совместимость', 'is_filterable'=>false],
                        ['key'=>'slots_horizontal', 'name'=>'Горизонтальные слоты расширения', 'value'=>'7', 'group'=>'Совместимость', 'is_filterable'=>false],
                        ['key'=>'slots_vertical', 'name'=>'Вертикальные слоты расширения', 'value'=>'3', 'group'=>'Совместимость', 'is_filterable'=>false],
                        ['key'=>'gpu_length', 'name'=>'Максимальная длина устанавливаемой видеокарты', 'value'=>'305 мм', 'group'=>'Совместимость', 'is_filterable'=>true],
                        ['key'=>'cpu_cooler_height', 'name'=>'Максимальная высота процессорного кулера', 'value'=>'160 мм', 'group'=>'Совместимость', 'is_filterable'=>true],
                        ['key'=>'ssd_slots', 'name'=>'Количество отсеков 2.5" накопителей', 'value'=>'2 шт', 'group'=>'Совместимость', 'is_filterable'=>false],
                        ['key'=>'hdd_slots', 'name'=>'Число внутренних отсеков 3.5"', 'value'=>'2 шт', 'group'=>'Совместимость', 'is_filterable'=>false],
                        ['key'=>'fans_included', 'name'=>'Вентиляторы в комплекте', 'value'=>'2 x 120 мм', 'group'=>'Охлаждение', 'is_filterable'=>false],
                        ['key'=>'fan_support_front', 'name'=>'Поддержка фронтальных вентиляторов', 'value'=>'2 x 120 мм', 'group'=>'Охлаждение', 'is_filterable'=>false],
                        ['key'=>'fan_support_rear', 'name'=>'Поддержка тыловых вентиляторов', 'value'=>'1 x 120 мм', 'group'=>'Охлаждение', 'is_filterable'=>false],
                        ['key'=>'fan_support_top', 'name'=>'Поддержка верхних вентиляторов', 'value'=>'1 x 120 мм', 'group'=>'Охлаждение', 'is_filterable'=>false],
                        ['key'=>'fan_support_bottom', 'name'=>'Поддержка нижних вентиляторов', 'value'=>'1 x 120 мм', 'group'=>'Охлаждение', 'is_filterable'=>false],
                        ['key'=>'liquid_cooling', 'name'=>'Возможность установки системы жидкостного охлаждения', 'value'=>'нет', 'group'=>'Охлаждение', 'is_filterable'=>true],
                        ['key'=>'io_panel_position', 'name'=>'Расположение I / O панели', 'value'=>'сверху', 'group'=>'Интерфейсы', 'is_filterable'=>false],
                        ['key'=>'io_ports', 'name'=>'Разъемы', 'value'=>'USB 2.0 x 2, USB 3.0 x 1, Audio x 1', 'group'=>'Интерфейсы', 'is_filterable'=>false],
                        ['key'=>'dust_filter', 'name'=>'Пылевой фильтр', 'value'=>'есть', 'group'=>'Обслуживание', 'is_filterable'=>false],
                    ]
                ],
                // 12. ARDOR GAMING Crystal CC2 черный
                [
                    'title' => 'Корпус ARDOR GAMING Crystal CC2 черный',
                    'description' => 'Корпус ARDOR GAMING Crystal CC2 черного цвета — это Mid-Tower с панелями из закаленного стекла, поддержкой видеокарт до 350 мм и процессорных кулеров до 160 мм. В комплекте 4 ARGB вентилятора 120 мм.','price' => 6700,
                    'category_id' => $caseCategory->id,
                    'product_type' => 'case',
                    'qty' => 9, 
                    'country' => 'Китай',
                    'img' => 'Корпус ARDOR GAMING Crystal CC2 черный.webp',
                    'color' => 'черный',
                    'specs' => [
                        ['key'=>'brand', 'name'=>'Бренд', 'value'=>'ARDOR GAMING', 'group'=>'Основные', 'is_filterable'=>true],
                        ['key'=>'model', 'name'=>'Модель', 'value'=>'Crystal CC2', 'group'=>'Основные', 'is_filterable'=>false],
                        ['key'=>'form_factor', 'name'=>'Типоразмер корпуса', 'value'=>'Mid-Tower', 'group'=>'Основные', 'is_filterable'=>true],
                        ['key'=>'orientation', 'name'=>'Ориентация материнской платы', 'value'=>'вертикально', 'group'=>'Основные', 'is_filterable'=>false],
                        ['key'=>'length', 'name'=>'Длина', 'value'=>'410 мм', 'group'=>'Габариты', 'is_filterable'=>false],
                        ['key'=>'width', 'name'=>'Ширина', 'value'=>'210 мм', 'group'=>'Габариты', 'is_filterable'=>false],
                        ['key'=>'height', 'name'=>'Высота', 'value'=>'480 мм', 'group'=>'Габариты', 'is_filterable'=>false],
                        ['key'=>'weight', 'name'=>'Вес', 'value'=>'5.8 кг', 'group'=>'Габариты', 'is_filterable'=>false],
                        ['key'=>'color', 'name'=>'Основной цвет', 'value'=>'черный', 'group'=>'Внешний вид', 'is_filterable'=>true],
                        ['key'=>'material', 'name'=>'Материал корпуса', 'value'=>'сталь, стекло', 'group'=>'Основные', 'is_filterable'=>true],
                        ['key'=>'window', 'name'=>'Наличие окна на боковой стенке', 'value'=>'слева', 'group'=>'Внешний вид', 'is_filterable'=>true],
                        ['key'=>'window_material', 'name'=>'Материал окна', 'value'=>'закаленное стекло', 'group'=>'Внешний вид', 'is_filterable'=>false],
                        ['key'=>'lighting_type', 'name'=>'Тип подсветки', 'value'=>'ARGB', 'group'=>'Внешний вид', 'is_filterable'=>true],
                        ['key'=>'lighting_color', 'name'=>'Цвет подсветки', 'value'=>'многоцветная', 'group'=>'Внешний вид', 'is_filterable'=>false],
                        ['key'=>'lighting_source', 'name'=>'Источник подсветки', 'value'=>'вентилятор', 'group'=>'Внешний вид', 'is_filterable'=>false],
                        ['key'=>'mobo_compat', 'name'=>'Форм-фактор совместимых плат', 'value'=>'ATX, Micro-ATX, Mini-ITX', 'group'=>'Совместимость', 'is_filterable'=>true],
                        ['key'=>'psu_compat', 'name'=>'Форм-фактор совместимых блоков питания', 'value'=>'ATX', 'group'=>'Совместимость', 'is_filterable'=>false],
                        ['key'=>'psu_position', 'name'=>'Размещение блока питания', 'value'=>'нижнее', 'group'=>'Совместимость', 'is_filterable'=>false],
                        ['key'=>'max_psu_length', 'name'=>'Максимальная длина блока питания', 'value'=>'180 мм', 'group'=>'Совместимость', 'is_filterable'=>false],
                        ['key'=>'slots_horizontal', 'name'=>'Горизонтальные слоты расширения', 'value'=>'7', 'group'=>'Совместимость', 'is_filterable'=>false],
                        ['key'=>'slots_vertical', 'name'=>'Вертикальные слоты расширения', 'value'=>'3', 'group'=>'Совместимость', 'is_filterable'=>false],
                        ['key'=>'gpu_length', 'name'=>'Максимальная длина устанавливаемой видеокарты', 'value'=>'350 мм', 'group'=>'Совместимость', 'is_filterable'=>true],
                        ['key'=>'cpu_cooler_height', 'name'=>'Максимальная высота процессорного кулера', 'value'=>'160 мм', 'group'=>'Совместимость', 'is_filterable'=>true],
                        ['key'=>'ssd_slots', 'name'=>'Количество отсеков 2.5" накопителей', 'value'=>'2 шт', 'group'=>'Совместимость', 'is_filterable'=>false],
                        ['key'=>'hdd_slots', 'name'=>'Число внутренних отсеков 3.5"', 'value'=>'2 шт', 'group'=>'Совместимость', 'is_filterable'=>false],
                        ['key'=>'fans_included', 'name'=>'Вентиляторы в комплекте', 'value'=>'4 x 120 мм ARGB', 'group'=>'Охлаждение', 'is_filterable'=>false],
                        ['key'=>'fan_support_front', 'name'=>'Поддержка фронтальных вентиляторов', 'value'=>'3 x 120 мм', 'group'=>'Охлаждение', 'is_filterable'=>false],
                        ['key'=>'fan_support_rear', 'name'=>'Поддержка тыловых вентиляторов', 'value'=>'1 x 120 мм', 'group'=>'Охлаждение', 'is_filterable'=>false],
                        ['key'=>'fan_support_top', 'name'=>'Поддержка верхних вентиляторов', 'value'=>'2 x 120 мм', 'group'=>'Охлаждение', 'is_filterable'=>false],
                        ['key'=>'fan_support_bottom', 'name'=>'Поддержка нижних вентиляторов', 'value'=>'2 x 120 мм', 'group'=>'Охлаждение', 'is_filterable'=>false],
                        ['key'=>'liquid_cooling', 'name'=>'Возможность установки системы жидкостного охлаждения', 'value'=>'есть', 'group'=>'Охлаждение', 'is_filterable'=>true],
                        ['key'=>'rad_support_front', 'name'=>'Фронтальный монтажный размер радиатора СЖО', 'value'=>'240 мм', 'group'=>'Охлаждение', 'is_filterable'=>false],
                        ['key'=>'rad_support_top', 'name'=>'Верхний монтажный размер радиатора СЖО', 'value'=>'240 мм', 'group'=>'Охлаждение', 'is_filterable'=>false],
                        ['key'=>'rad_support_rear', 'name'=>'Тыловой монтажный размер радиатора СЖО', 'value'=>'120 мм', 'group'=>'Охлаждение', 'is_filterable'=>false],
                        ['key'=>'io_panel_position', 'name'=>'Расположение I / O панели', 'value'=>'сверху', 'group'=>'Интерфейсы', 'is_filterable'=>false],
                        ['key'=>'io_ports', 'name'=>'Разъемы', 'value'=>'USB 2.0 x 2, USB 3.0 x 1, Audio x 1', 'group'=>'Интерфейсы', 'is_filterable'=>false],
                        ['key'=>'dust_filter', 'name'=>'Пылевой фильтр', 'value'=>'есть', 'group'=>'Обслуживание', 'is_filterable'=>false],
                    ]
                    ],
                
            ];

            // Удаляем старые корпуса перед созданием новых из массива $cases
            Product::where('product_type', 'case')->delete();

            // Добавляем цикл для создания товаров и спецификаций из массива $cases
            foreach ($cases as $case) {
                $product = Product::create([
                    'title' => $case['title'],
                    'price' => $case['price'] ?? 0, // Используем 0, если цена не указана
                    'old_price' => $case['old_price'] ?? null,
                    'discount_percent' => $case['discount_percent'] ?? 0,
                    'in_stock' => $case['in_stock'] ?? true, // По умолчанию в наличии
                    'on_sale' => $case['on_sale'] ?? false,
                    'is_bestseller' => $case['is_bestseller'] ?? false,
                    'is_new' => $case['is_new'] ?? false,
                    'credit_available' => $case['credit_available'] ?? true,
                    'img' => $case['img'] ?? null,
                    'product_type' => $case['product_type'] ?? 'case', // Проверяем, есть ли поле product_type в массиве $case
                    'country' => $case['country'] ?? null,
                    'color' => $case['color'] ?? null,
                    'qty' => $case['qty'] ?? 0, // По умолчанию 0, если количество не указано
                    'description' => $case['description'] ?? 'Описание отсутствует', // Добавляем описание
                    'category_id' => $caseCategory->id, // Используем category_id корпуса
                    'rating' => $case['rating'] ?? 0.0, // Добавляем рейтинг
                ]);

                // Добавляем спецификации к созданному продукту ТОЛЬКО если они существуют в массиве $case
                if (!empty($case['specs'])) {
                    foreach ($case['specs'] as $spec) {
                        // Пропускаем добавление спецификации цвета, если она уже есть как поле продукта
                        if ($spec['key'] === 'color' || $spec['name'] === 'Основной цвет') {
                            continue;
                        }
                        ProductSpecification::create([
                            'product_id' => $product->id,
                            'spec_key' => $spec['key'],
                            'spec_name' => $spec['name'],
                            'spec_value' => $spec['value'],
                            'group' => $spec['group'],
                            'is_filterable' => $spec['is_filterable'],
                        ]);
                    }
                }

                if ($case['title'] === 'NZXT H510 Корпус') {
                    $product->images()->createMany([
                        ['image_path' => 'NZXT H510 Корпус.webp'],
                        ['image_path' => 'NZXT H510 Корпус1.webp'],
                        ['image_path' => 'NZXT H510 Корпус2.webp'],
                    ]);
                }

                if ($case['title'] === 'ZALMAN N4 Rev.1 черный Корпус') {
                    $product->images()->createMany([
                        ['image_path' => 'ZALMAN N4 Rev.1 черный Корпус.webp'],
                        ['image_path' => 'ZALMAN N4 Rev.1 черный Корпус1.webp'],
                        ['image_path' => 'ZALMAN N4 Rev.1 черный Корпус2.webp'],
                    ]);
                }

                if ($case['title'] === 'Cougar Duoface Pro RGB [CGR-5AD1B-RGB] черный Корпус') {
                    $product->images()->createMany([
                        ['image_path' => 'Cougar Duoface Pro RGB [CGR-5AD1B-RGB] черный Корпус.webp'],
                        ['image_path' => 'Cougar Duoface Pro RGB [CGR-5AD1B-RGB] черный Корпус1.webp'],
                        ['image_path' => 'Cougar Duoface Pro RGB [CGR-5AD1B-RGB] черный Корпус2.webp'],
                    ]);
                }

                if ($case['title'] === 'ARDOR GAMING Rare M2 ARGB черный Корпус') {
                    $product->images()->createMany([
                        ['image_path' => 'ARDOR GAMING Rare M2 ARGB черный Корпус.webp'],
                        ['image_path' => 'ARDOR GAMING Rare M2 ARGB черный Корпус1.webp'],
                        ['image_path' => 'ARDOR GAMING Rare M2 ARGB черный Корпус2.webp'],
                    ]);
                }

                if ($case['title'] === 'Корпус Cougar MX600 RGB [3857C90.0017] черный') {
                    $product->images()->createMany([
                        ['image_path' => 'Корпус Cougar MX600 RGB [3857C90.0017] черный.webp'],
                        ['image_path' => 'Корпус Cougar MX600 RGB [3857C90.0017] черный1.webp'],
                        ['image_path' => 'Корпус Cougar MX600 RGB [3857C90.0017] черный2.webp'],
                    ]);
                }

                if ($case['title'] === 'Корпус MONTECH KING 95 PRO черный') {
                    $product->images()->createMany([
                        ['image_path' => 'Корпус MONTECH KING 95 PRO черный.webp'],
                        ['image_path' => 'Корпус MONTECH KING 95 PRO черный1.webp'],
                        ['image_path' => 'Корпус MONTECH KING 95 PRO черный2.webp'],
                    ]);
                }

                if ($case['title'] === 'Корпус ARDOR GAMING Rare M6 черный') {
                    $product->images()->createMany([
                        ['image_path' => 'Корпус ARDOR GAMING Rare M6 черный.webp'],
                        ['image_path' => 'Корпус ARDOR GAMING Rare M6 черный1.webp'],
                        ['image_path' => 'Корпус ARDOR GAMING Rare M6 черный2.webp'],
                    ]);
                }

                if ($case['title'] === 'Корпус ARDOR GAMING Rare Minicase MS1 черный') {
                    $product->images()->createMany([
                        ['image_path' => 'Корпус ARDOR GAMING Rare Minicase MS1 черный.webp'],
                        ['image_path' => 'Корпус ARDOR GAMING Rare Minicase MS1 черный1.webp'],
                        ['image_path' => 'Корпус ARDOR GAMING Rare Minicase MS1 черный2.webp'],
                    ]);
                }

                if ($case['title'] === 'Корпус LIAN LI LANCOOL III черный') {
                    $product->images()->createMany([
                        ['image_path' => 'Корпус LIAN LI LANCOOL III черный.webp'],
                        ['image_path' => 'Корпус LIAN LI LANCOOL III черный1.webp'],
                        ['image_path' => 'Корпус LIAN LI LANCOOL III черный2.webp'],
                    ]);
                }

                if ($case['title'] === 'Корпус MONTECH KING 95 PRO белый') {
                    $product->images()->createMany([
                        ['image_path' => 'Корпус MONTECH KING 95 PRO белый.webp'],
                        ['image_path' => 'Корпус MONTECH KING 95 PRO белый1.webp'],
                        ['image_path' => 'Корпус MONTECH KING 95 PRO белый2.webp'],
                    ]);
                }

                if ($case['title'] === 'Корпус Cougar FV150 RGB белый') {
                    $product->images()->createMany([
                        ['image_path' => 'Корпус Cougar FV150 RGB белый.webp'],
                        ['image_path' => 'Корпус Cougar FV150 RGB белый1.webp'],
                        ['image_path' => 'Корпус Cougar FV150 RGB белый2.webp'],
                    ]);
                }

                if ($case['title'] === 'Корпус MONTECH X2 MESH (B) черный') {
                    $product->images()->createMany([
                        ['image_path' => 'Корпус MONTECH X2 MESH (B) черный.webp'],
                        ['image_path' => 'Корпус MONTECH X2 MESH (B) черный1.webp'],
                        ['image_path' => 'Корпус MONTECH X2 MESH (B) черный2.webp'],
                    ]);
                }

                // Добавляем изображение для корпуса ARDOR GAMING Crystal CC2 черный
                if ($case['title'] === 'Корпус ARDOR GAMING Crystal CC2 черный') {
                    $product->images()->createMany([
                        ['image_path' => 'Корпус ARDOR GAMING Crystal CC2 черный.webp'],
                        ['image_path' => 'Корпус ARDOR GAMING Crystal CC2 черный1.webp'],
                        ['image_path' => 'Корпус ARDOR GAMING Crystal CC2 черный2.webp'],
                    ]);
                }
            }
        }
    }
}
