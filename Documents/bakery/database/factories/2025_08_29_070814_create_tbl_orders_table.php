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
    Schema::create('tbl_orders', function (Blueprint $table) {
        $table->id();
    $table->foreignId('customer_id')->nullable()
          ->constrained('tbl_customers') // must match the exact table name
          ->nullOnDelete();
            $table->foreignId('prepared_by')->nullable()->constrained('tbl_users')->nullOnDelete();
    $table->foreignId('delivered_by')->nullable()->constrained('tbl_users')->nullOnDelete();
        $table->string('order_number')->unique(); // tracking code
        $table->string('order_type'); // online / walk-in
        $table->decimal('total_amount', 10, 2);

        $table->enum('status', ['new','processing','shipped','completed','cancel'])
              ->default('new');

        $table->string('payment_status')->default('unpaid'); // unpaid, paid, partially_paid
        $table->string('payment_method')->nullable(); // cash, card, online

        $table->decimal('shipping_amount', 10, 2)->default(0);
        $table->dateTime('scheduled_delivery')->nullable();

        $table->text('notes')->nullable(); // extra instructions
        $table->timestamps();
    });
}


    

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tbl_orders');
    }
};