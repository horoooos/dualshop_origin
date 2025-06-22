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
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->decimal('price', 10, 2);
            $table->string('img')->nullable();
            $table->string('product_type')->nullable();
            $table->string('country');
            $table->string('color')->nullable();
            $table->integer('qty')->default(0);
            $table->boolean('in_stock')->default(true);
            $table->text('description')->nullable();
            $table->json('specs_data')->nullable();
            $table->foreignId('category_id')->constrained()->onDelete('cascade');
            $table->decimal('rating', 3, 2)->default(0.00);
            $table->boolean('is_seasonal')->default(false);
            $table->boolean('on_sale')->default(false);
            $table->boolean('credit_available')->default(false);
            $table->boolean('is_bestseller')->default(false);
            $table->boolean('is_new')->default(false);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};

