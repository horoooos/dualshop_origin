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
            $table->decimal('discount', 5, 2)->default(0)->after('price');
            $table->boolean('is_seasonal')->default(false)->after('discount');
            $table->boolean('is_popular')->default(false)->after('is_seasonal');
            $table->boolean('is_new')->default(true)->after('is_popular');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('products', function (Blueprint $table) {
            $table->dropColumn(['discount', 'is_seasonal', 'is_popular', 'is_new']);
        });
    }
};
