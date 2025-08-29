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
       Schema::create('tbl_batches', function (Blueprint $table) {
    $table->id();
    $table->foreignId('product_id')->constrained('tbl_products')->onDelete('cascade');
    $table->integer('quantity');
    $table->decimal('cost_per_batch', 10, 2);
    $table->date('production_date');
    $table->date('expiry_date');
    $table->timestamps();
});

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tbl_batches');
    }
};