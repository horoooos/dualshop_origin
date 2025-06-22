<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        try {
            Schema::table('products', function (Blueprint $table) {
                if (!Schema::hasColumn('products', 'rating')) {
                    $table->decimal('rating', 3, 2)->default(0);
                }
                if (!Schema::hasColumn('products', 'is_seasonal')) {
                    $table->boolean('is_seasonal')->default(false);
                }
            });
        } catch (\Exception $e) {
            // Запишем ошибку в лог, но не остановим миграцию
            \Illuminate\Support\Facades\Log::error('Ошибка при добавлении полей rating и is_seasonal: ' . $e->getMessage());
        }
    }

    public function down()
    {
        Schema::table('products', function (Blueprint $table) {
            // Проверяем наличие колонок перед их удалением
            if (Schema::hasColumn('products', 'rating')) {
                $table->dropColumn('rating');
            }
            if (Schema::hasColumn('products', 'is_seasonal')) {
                $table->dropColumn('is_seasonal');
            }
        });
    }
}; 