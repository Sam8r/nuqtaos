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
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->string('code')->unique();
            $table->json('name');
            $table->json('description')->nullable();
            $table->unsignedMediumInteger('price');
            $table->enum('type', ['Service', 'Physical']);
            $table->string('unit')->nullable();
            $table->string('sku')->nullable()->unique();
            $table->string('qr_value')->nullable()->unique();
            $table->string('images')->nullable();
            $table->enum('status', ['Active', 'Inactive', 'Discontinued'])->default('active');
            $table->foreignId('category_id')->constrained('categories')->onDelete('cascade');
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};
