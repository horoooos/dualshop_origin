<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('products', function (Blueprint $table) {
            // Поля для статуса наличия
            if (!Schema::hasColumn('products', 'in_stock')) {
                $table->boolean('in_stock')->default(true)->after('qty');
            }
            
            // Поля для ценовой информации
            if (!Schema::hasColumn('products', 'old_price')) {
                $table->decimal('old_price', 10, 2)->nullable()->after('price');
            }
            if (!Schema::hasColumn('products', 'discount_percent')) {
                $table->decimal('discount_percent', 5, 2)->default(0)->after('old_price');
            }
            
            // Поля для фильтрации по статусу
            if (!Schema::hasColumn('products', 'on_sale')) {
                $table->boolean('on_sale')->default(false)->after('is_seasonal');
            }
            if (!Schema::hasColumn('products', 'credit_available')) {
                $table->boolean('credit_available')->default(false)->after('on_sale');
            }
            if (!Schema::hasColumn('products', 'is_bestseller')) {
                $table->boolean('is_bestseller')->default(false)->after('credit_available');
            }
            if (!Schema::hasColumn('products', 'is_new')) {
                $table->boolean('is_new')->default(false)->after('is_bestseller');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('products', function (Blueprint $table) {
            $table->dropColumn([
                'in_stock',
                'old_price',
                'discount_percent',
                'on_sale',
                'credit_available',
                'is_bestseller',
                'is_new'
            ]);
        });
    }
}; 