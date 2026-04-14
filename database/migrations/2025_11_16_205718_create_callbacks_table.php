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
        Schema::create('callbacks', function (Blueprint $table) {
            $table->id();
            $table->string('username')->nullable();
            $table->string('password')->nullable();
            $table->string('msisdn', 20);
            $table->integer('action_type'); // 1=sub, 2=unsub, 3=renewal
            $table->string('service_id')->nullable();
            $table->string('sp_id')->nullable();
            $table->string('date')->nullable();
            $table->string('requestid')->nullable();
            $table->string('sc')->nullable(); // shortcode
            $table->string('status')->default('pending'); // pending, processing, completed, failed
            $table->text('error_message')->nullable();
            $table->timestamp('processed_at')->nullable();
            $table->timestamps();
            
            $table->index('status');
            $table->index('action_type');
            $table->index('msisdn');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('callbacks');
    }
};
