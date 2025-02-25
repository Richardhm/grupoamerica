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
        Schema::create('concessionarias', function (Blueprint $table) {
            $table->id();
            $table->string('nome', 255);
            $table->string('imagem', 255)->nullable();
            $table->integer('meta_individual')->default(0);
            $table->integer('individual')->default(0);
            $table->integer('meta_super_simples')->default(0);
            $table->integer('super_simples')->default(0);
            $table->integer('meta_pme')->default(0);
            $table->integer('pme')->default(0);
            $table->integer('meta_adesao')->default(0);
            $table->integer('adesao')->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('concessionarias');
    }
};
