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
        Schema::create('transactions', function (Blueprint $table) {
            $table->id();
            $table->string('transaction_id')->unique();
            $table->foreignId('subscriber_id')->nullable()->constrained()->onDelete('cascade');
            $table->string('msisdn', 20);
            $table->string('type'); // subscription, renewal, unsubscribe
            $table->string('status')->default('pending'); // pending, success, failed
            $table->decimal('amount', 8, 2)->default(0.00);
            $table->string('currency', 3)->default('IQD');
            $table->string('operator')->nullable();
            $table->string('callback_url')->nullable();
            $table->text('request_payload')->nullable();
            $table->text('response_payload')->nullable();
            $table->timestamp('processed_at')->nullable();
            $table->timestamps();

            // Indexes
            $table->index('transaction_id');
            $table->index('msisdn');
            $table->index('status');
            $table->index('type');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('transactions');
    }
};
