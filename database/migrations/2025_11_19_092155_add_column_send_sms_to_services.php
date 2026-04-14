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
        Schema::table('services', function (Blueprint $table) {
            $table->boolean('is_smart')->default(false)->after('shortcode');
            $table->string('alphanumeric')->nullable()->after('is_smart');
            $table->text('sub_message')->nullable()->after('alphanumeric');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('services', function (Blueprint $table) {
            $table->boolean('smart');
            $table->string('alphanumeric');
            $table->text('sub_message');
        });
    }
};
