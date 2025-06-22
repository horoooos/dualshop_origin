<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migration.
     */
    public function up(): void
    {
        Schema::create('product_specifications', function (Blueprint $table) {
            $table->id();
            $table->foreignId('product_id')->constrained()->onDelete('cascade');
            $table->string('spec_key', 50)->index(); // Ключ характеристики (например, 'ram', 'cpu')
            $table->string('spec_name', 100); // Отображаемое название характеристики
            $table->string('spec_value', 255); // Значение характеристики
            $table->string('group', 100)->nullable(); // Группа характеристик (например, 'Технические характеристики')
            $table->integer('sort_order')->default(0); // Порядок сортировки
            $table->boolean('is_filterable')->default(false); // Можно ли фильтровать по этой характеристике
            $table->timestamps();
            
            // Индексы для быстрого поиска
            $table->index(['spec_key', 'spec_value']);
            $table->index(['product_id', 'spec_key']);
            $table->index('is_filterable');
        });
    }

    /**
     * Reverse the migration.
     */
    public function down(): void
    {
        Schema::dropIfExists('product_specifications');
    }
};
