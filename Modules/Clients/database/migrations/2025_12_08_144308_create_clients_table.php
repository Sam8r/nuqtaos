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
            $table->string('company_name_en');
            $table->string('company_name_ar');
            $table->string('contact_person_details');
            $table->string('address');
            $table->string('tax_number')->nullable();
//            $table->json('registration_docs')->nullable();
            $table->unsignedInteger('credit_limit');
            $table->string('payment_terms')->nullable();
            $table->string('industry_type')->nullable();
            $table->string('status');
            $table->string('tier');
            $table->string('geographic_location');
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
