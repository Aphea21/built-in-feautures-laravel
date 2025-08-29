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
    Schema::create('tbl_order_items', function (Blueprint $table) {
        $table->id();
        $table->foreignId('order_id')
              ->constrained('tbl_orders')
              ->onDelete('cascade');   // ✅ Delete order items if order is deleted

        $table->foreignId('product_id')
              ->constrained('tbl_products')
              ->onDelete('cascade');   // ✅ Delete order items if product is deleted

        $table->integer('quantity')->default(1);
        $table->decimal('unit_amount', 10, 2)->nullable();
        $table->decimal('total_amount', 10, 2)->nullable(); 
        $table->timestamps();
    });
}

    

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tbl_order_items');
    }
};