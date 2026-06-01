<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('order_item_extras', function (Blueprint $table) {
            $table->id();
            $table->foreignId('order_item_id')->constrained()->onDelete('cascade');
            $table->foreignId('extra_id')->constrained()->onDelete('cascade');
            $table->string('extra_name');
            $table->decimal('extra_unit_price', 8, 2);
            $table->decimal('extra_line_total', 10, 2);

            // Subtotal macros (extra × quantity)
            $table->integer('extra_calories')->default(0);
            $table->integer('extra_proteins')->default(0);
            $table->integer('extra_carbs')->default(0);
            $table->integer('extra_fats')->default(0);

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('order_item_extras');
    }
};