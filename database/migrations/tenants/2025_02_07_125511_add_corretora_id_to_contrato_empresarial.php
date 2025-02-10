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
        if (!Schema::hasColumn('contrato_empresarial', 'corretora_id')) {
            Schema::table('contrato_empresarial', function (Blueprint $table) {
                $table->foreignId('corretora_id')->nullable()->constrained('corretoras')->onDelete('cascade')->after('id');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('contrato_empresarial', function (Blueprint $table) {
            $table->dropConstrainedForeignId('corretora_id');
        });
    }
};
