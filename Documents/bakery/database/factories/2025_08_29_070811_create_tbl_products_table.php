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
        Schema::create('tbl_products', function (Blueprint $table) {
            $table->id();
            $table->foreignId('category_id')
                ->nullable()
                ->constrained('tbl_category')
                ->onDelete('set null'); // safer than cascade

            $table->string('name');
            $table->string('sku')->unique(); // stock keeping unit
            $table->longText('description')->nullable();
            $table->integer('batch_size')->default(1); // for batch costing
            $table->decimal('price', 10, 2);
            $table->integer('shelf_life_days'); // expiry automation
            $table->boolean('is_active')->default(true);
            $table->boolean('is_featured')->default(false);
            $table->boolean('in_stock')->default(true); // for quick checks
            $table->boolean('on_sale')->default(false);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tbl_products');
    }
};
