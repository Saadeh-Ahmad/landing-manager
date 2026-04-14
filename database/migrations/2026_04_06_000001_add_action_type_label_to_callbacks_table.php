<?php

use App\Models\Callback;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('callbacks', function (Blueprint $table) {
            $table->string('action_type_label', 32)->nullable();
        });

        $map = [
            Callback::ACTION_UNSUB => 'unsub',
            Callback::ACTION_SUB => 'sub',
            Callback::ACTION_RENEWAL => 'renewal',
            Callback::ACTION_OUT_OF_BALANCE => 'out_of_balance',
        ];

        foreach ($map as $type => $label) {
            DB::table('callbacks')->where('action_type', $type)->update(['action_type_label' => $label]);
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('callbacks', function (Blueprint $table) {
            $table->dropColumn('action_type_label');
        });
    }
};
