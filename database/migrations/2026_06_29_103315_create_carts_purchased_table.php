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
        Schema::create('carts_purchased', function (Blueprint $table) {
            $table->id();

            $table->foreignId('user_id')->constrained(); 

            $table->decimal('subtotal', 10, 2);
            $table->decimal('tax', 8, 2)->default(0);
            $table->decimal('total', 10, 2);

            $table->string('payment_method'); // e.g., 'cash', 'card', 'digital_wallet'
            $table->string('status')->default('completed'); // 'completed', 'voided', 'refunded'

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('carts_purchased');
    }
};
