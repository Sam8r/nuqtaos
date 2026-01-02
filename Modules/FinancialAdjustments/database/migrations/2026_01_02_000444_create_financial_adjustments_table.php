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
        Schema::create('financial_adjustments', function (Blueprint $table) {
            $table->id();
            $table->string('type');
            $table->string('category');
            $table->decimal('amount', 10, 2)->nullable();
            $table->unsignedTinyInteger('days_count')->nullable();
            $table->string('reason');
            $table->string('status');
            $table->date('processing_date');
            $table->foreignId('employee_id')->constrained()->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('financial_adjustments');
    }
};
