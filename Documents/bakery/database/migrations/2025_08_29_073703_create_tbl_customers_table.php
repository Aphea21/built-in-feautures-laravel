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
Schema::create('tbl_customers', function (Blueprint $table) {
    $table->id();
    $table->string('name'); // Required: always needed
    $table->string('email')->unique()->nullable(); // Optional for guest checkout
    $table->string('phone'); // Required: for contact & delivery
    $table->string('street_address'); // Required: delivery address
    $table->string('city')->nullable(); // Optional for guests
    $table->string('state')->nullable(); // Optional for guests
    $table->string('zip_code')->nullable(); // Optional for guests
    $table->string('country')->default('Philippines'); // You can set default
    $table->boolean('is_registered')->default(false); // Flag if user created account
    $table->timestamps();
});


    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tbl_customers');
    }
};