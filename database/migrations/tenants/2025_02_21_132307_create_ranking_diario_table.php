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
        Schema::create('ranking_diario', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id');
            $table->string('nome');
            $table->unsignedBigInteger('corretora_id');
            $table->integer('vidas_individual')->default(0);
            $table->integer('vidas_coletivo')->default(0);
            $table->integer('vidas_empresarial')->default(0);
            $table->date('data');
            $table->timestamps();
            $table->foreign('user_id')->references('id')->on('users');
            $table->foreign('corretora_id')->references('id')->on('corretoras');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ranking_diario');
    }
};
