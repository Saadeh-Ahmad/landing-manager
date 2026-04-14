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
        Schema::create('system_configs', function (Blueprint $table) {
            $table->id();
            $table->string('key')->unique(); // Configuration key (e.g., 'billing_api_url')
            $table->text('value')->nullable(); // Configuration value
            $table->string('type')->default('string'); // Data type: string, integer, boolean, json, array
            $table->string('group')->nullable(); // Group/category (e.g., 'session', 'endpoints', 'evina')
            $table->text('description')->nullable(); // Human-readable description
            $table->boolean('is_active')->default(true); // Enable/disable config
            $table->timestamps();
            
            // Indexes
            $table->index('key');
            $table->index('group');
            $table->index('is_active');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('system_configs');
    }
};
