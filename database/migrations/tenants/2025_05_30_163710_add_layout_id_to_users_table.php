<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::connection('tenant')->table('users', function (Blueprint $table) {
            $table->foreignId('layout_id')->nullable()->after('id')->default(1)->constrained('layouts')->onDelete('set null');
        });
    }

    public function down(): void
    {
        Schema::connection('tenant')->table('users', function (Blueprint $table) {
            $table->dropForeign(['layout_id']);
            $table->dropColumn('layout_id');
        });
    }
};
