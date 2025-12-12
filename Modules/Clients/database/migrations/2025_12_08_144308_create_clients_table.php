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
        Schema::create('clients', function (Blueprint $table) {
            $table->id();
            $table->json('company_name');
            $table->string('contact_person_details')->nullable();
            $table->string('address');
            $table->string('tax_number')->nullable();
            $table->json('registration_documents')->nullable();
            $table->unsignedInteger('credit_limit');
            $table->json('payment_terms')->nullable();
            $table->json('industry_type')->nullable();
            $table->string('status');
            $table->string('tier');
            $table->string('country');
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('clients');
    }
};
