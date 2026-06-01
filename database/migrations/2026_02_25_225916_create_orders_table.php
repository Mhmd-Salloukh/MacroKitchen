<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->decimal('total_price', 10, 2)->default(0);

            $table->enum('status', ['pending', 'ready', 'delivered'])->default('pending');

            // Total macros snapshot
            $table->integer('total_calories')->default(0);
            $table->integer('total_proteins')->default(0);
            $table->integer('total_carbs')->default(0);
            $table->integer('total_fats')->default(0);
            $table->text('note')->nullable();
            $table->timestamps();
            $table->timestamp('delivered_at')->nullable();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};