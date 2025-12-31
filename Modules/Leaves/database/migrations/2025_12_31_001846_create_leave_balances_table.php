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
        Schema::create('leave_balances', function (Blueprint $table) {
            $table->id();
            $table->unsignedTinyInteger('current_balance')->default(3);
            $table->unsignedTinyInteger('accumulated_balance')->default(0);
            $table->year('year');
            $table->unsignedTinyInteger('month');
            $table->foreignId('employee_id')->constrained('employees')->onDelete('cascade');
            $table->unique(['employee_id', 'year', 'month']);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('leave_balances');
    }
};
