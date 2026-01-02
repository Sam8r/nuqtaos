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
        Schema::create('settings', function (Blueprint $table) {
            $table->id();
            $table->string('name')->nullable();
            $table->string('logo')->nullable();
            $table->string('phone')->nullable();
            $table->string('email')->nullable();
            $table->text('address')->nullable();
            $table->string('tax_number')->nullable();
            $table->string('currency')->default('USD');
            $table->string('salary_currency')->default('USD');
            $table->decimal('tax', 5, 2)->nullable();
            $table->string('default_printable_language')->nullable();
            $table->unsignedTinyInteger('break_minutes')->default(0);
            $table->unsignedTinyInteger('overtime_minutes')->default(30);
            $table->unsignedTinyInteger('days_off_limit')->default(5);
            $table->integer('encashment_limit')->default(2);
            $table->unsignedTinyInteger('working_days_per_month')->default(20);
            $table->string('overtime_active_mode')->default('percentage');
            $table->decimal('overtime_percentage', 8, 2)->default(1.5);
            $table->decimal('overtime_fixed_rate', 10, 2)->default(0);
            $table->time('default_work_from')->default('09:00');
            $table->time('default_work_to')->default('17:00');
            $table->unsignedTinyInteger('grace_period_minutes')->default(15);
            $table->json('work_type_days')->nullable();
            $table->double('company_latitude')->nullable();
            $table->double('company_longitude')->nullable();
            $table->integer('radius_meter')->default(100);
            $table->string('default_leave_type')->default('Paid');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('settings');
    }
};
