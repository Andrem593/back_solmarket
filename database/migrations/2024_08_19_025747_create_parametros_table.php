<?php

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
        Schema::create('parametros', function (Blueprint $table) {
            $table->id();
            $table->text('descripcion'); // Campo 'descripcion' de tipo texto
            $table->float('valor'); // Campo 'valor' de tipo entero
            $table->integer('estado')->default(1);
            $table->timestamps();
        });

        DB::table('parametros')->insert([
            ['descripcion' => 'porcentaje', 'valor' => 10],
        ]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('parametros');
    }
};
