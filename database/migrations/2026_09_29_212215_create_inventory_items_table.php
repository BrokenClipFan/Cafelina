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
        Schema::create('inventory_items', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->decimal('current_stock', 10, 2)->default(0);
            $table->string('unit')->default('pcs'); // e.g., 'Kilo', 'Grams', 'Pack', 'Litre', 'Pcs'
            $table->decimal('price_per_unit', 10, 2)->default(0);
            $table->decimal('max_stock', 10, 2)->default(0); // 25% of this is low stock
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('inventory_items');
    }
};
