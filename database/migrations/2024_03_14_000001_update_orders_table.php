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
        Schema::table('orders', function (Blueprint $table) {
            // Удаляем старые колонки
            $table->dropColumn(['uid', 'pid', 'qty']);
            
            // Добавляем новые колонки
            $table->foreignId('user_id')->after('id');
            $table->foreignId('product_id')->after('user_id');
            $table->integer('quantity')->after('product_id');
            $table->timestamp('updated_at')->nullable();
            
            // Добавляем внешние ключи
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('product_id')->references('id')->on('products')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            // Удаляем внешние ключи
            $table->dropForeign(['user_id']);
            $table->dropForeign(['product_id']);
            
            // Удаляем новые колонки
            $table->dropColumn(['user_id', 'product_id', 'quantity', 'updated_at']);
            
            // Возвращаем старые колонки
            $table->integer('uid');
            $table->integer('pid');
            $table->integer('qty');
        });
    }
}; 