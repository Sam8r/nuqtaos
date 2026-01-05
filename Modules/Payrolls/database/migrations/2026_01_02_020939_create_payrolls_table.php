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
        Schema::create('payrolls', function (Blueprint $table) {
            $table->id();
            $table->date('month_year');
            $table->decimal('basic_salary', 10, 2);
            $table->decimal('daily_rate', 10, 2);
            $table->decimal('bonuses_total', 10, 2)->default(0);
            $table->decimal('deductions_total', 10, 2)->default(0);
            $table->decimal('net_salary', 10, 2);
            $table->text('notes')->nullable();
            $table->foreignId('employee_id')->constrained()->onDelete('cascade');
            $table->unique(['employee_id', 'month_year']);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('payrolls');
    }
};
