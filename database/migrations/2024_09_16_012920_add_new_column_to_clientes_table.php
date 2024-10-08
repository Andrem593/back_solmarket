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
        Schema::table('clientes', function (Blueprint $table) {
            $table->integer('centro_costo_id')->nullable();
            $table->foreign('centro_costo_id')->references('id')->on('centro_de_costo')->onDelete('set null');
            $table->integer('subcategoria_id')->nullable();
            $table->foreign('subcategoria_id')->references('id')->on('subcategoria')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('clientes', function (Blueprint $table) {
            $table->dropColumn('centro_costo_id');
            $table->dropColumn('subcategoria_id');
        });
    }
};
