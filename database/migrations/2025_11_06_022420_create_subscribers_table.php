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
        Schema::create('subscribers', function (Blueprint $table) {
            $table->id();
            $table->string('msisdn', 20);
            $table->string('service_id')->nullable(); // API service_id (not foreign key)
            $table->string('status')->default('active'); // active, inactive, suspended
            $table->string('subscription_plan')->default('daily');
            $table->decimal('rate', 8, 2)->default(1.00);
            $table->timestamp('subscribed_at')->nullable();
            $table->timestamp('last_active_at')->nullable();
            $table->timestamp('last_billing_date')->nullable();
            $table->timestamp('unsubscribed_at')->nullable();
            $table->string('operator')->nullable();
            $table->string('session_id')->nullable();
            $table->integer('expired_in')->nullable();
            $table->text('metadata')->nullable(); // JSON field for additional data
            $table->timestamps();

            // Indexes
            $table->index('status');
            $table->index('subscribed_at');
            $table->index('service_id');
            $table->unique(['msisdn', 'service_id']); // Unique combination of msisdn and service_id
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('subscribers');
    }
};
