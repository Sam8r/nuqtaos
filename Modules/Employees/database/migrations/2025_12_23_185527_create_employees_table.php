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
        Schema::create('employees', function (Blueprint $table) {
            $table->id();
            $table->json('name');
            $table->string('phone');
            $table->string('emergency_contact')->nullable();
            $table->date('joining_date');
            $table->string('contract_type');
            $table->string('status');
            $table->decimal('salary', 15, 2);
            $table->string('documents')->nullable();
            $table->time('work_start')->nullable();
            $table->time('work_end')->nullable();
            $table->foreignId('position_id')->constrained('positions')->onDelete('cascade');
            $table->foreignId('department_id')->constrained('departments')->onDelete('cascade');
            $table->foreignId('user_id')->nullable()->constrained('users')->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('employees');
    }
};
