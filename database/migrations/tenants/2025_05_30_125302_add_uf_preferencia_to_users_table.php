<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::connection('tenant')->table('users', function (Blueprint $table) {
            $table->string('uf_preferencia')->nullable()->after('id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::connection('tenant')->table('users', function (Blueprint $table) {
            $table->dropColumn('uf_preferencia');
        });
    }
};
