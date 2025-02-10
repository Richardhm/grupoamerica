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
        Schema::table('folha_mes', function (Blueprint $table) {
            if (!Schema::hasColumn('folha_mes', 'corretora_id')) {
                $table->foreignId('corretora_id')->constrained('corretoras')->onDelete('cascade')->after('status');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('folha_mes', function (Blueprint $table) {
            $table->dropForeign(['corretora_id']);
            $table->dropColumn('corretora_id');
        });
    }
};
