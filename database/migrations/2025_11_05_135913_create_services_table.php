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
        Schema::create('services', function (Blueprint $table) {
            $table->id();
            $table->string('name')->unique(); // Service name/identifier
            $table->string('type')->default('vas'); // vas, dcb, evina, otp, etc.
            $table->string('merchant_name', 100)->nullable()->default('MediaWorld');
            $table->string('display_name'); // Display name for UI
            $table->text('description')->nullable();

            // API Configuration
            $table->string('api_host')->nullable();
            $table->string('api_username')->nullable();
            $table->string('api_password')->nullable();
            $table->string('sp_id')->nullable();
            $table->string('service_id')->nullable();
            $table->string('shortcode')->nullable();
            $table->integer('timeout')->default(30);

            // Additional Settings (JSON for flexibility)
            $table->json('settings')->nullable();

            // Status & Mode
            $table->boolean('is_active')->default(true);
            $table->boolean('enable_evina_fraud')->default(false);
            $table->enum('subscribtion_type', ['daily', 'weekly', 'monthly'])->default('daily');
            $table->string('mode')->default('live'); // live, dummy, test

            // Pricing
            $table->decimal('price', 10, 2)->nullable();
            $table->string('currency', 10)->default('IQD');

            // Metadata
            $table->string('operator')->nullable(); // Zain, Asiacell, etc.
            $table->string('country_code', 10)->default('964');

            $table->timestamps();
            $table->softDeletes();

            // Indexes
            $table->index('type');
            $table->index('is_active');
            $table->index('operator');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('services');
    }
};
