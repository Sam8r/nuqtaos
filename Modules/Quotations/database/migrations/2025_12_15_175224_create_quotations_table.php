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
        Schema::create('quotations', function (Blueprint $table) {
            $table->id();
            $table->string('number')->unique();
            $table->date('issue_date');
            $table->date('valid_until');
            $table->string('status')->default('Draft');
            $table->text('terms_and_conditions')->nullable();
            $table->decimal('subtotal', 15, 2);
            $table->decimal('discount_total', 15, 2);
            $table->decimal('tax_total', 15, 2);
            $table->decimal('total');
            $table->foreignId('client_id')->constrained('clients')->onDelete('cascade');
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('quotations');
    }
};
