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
        Schema::create('pedidos_detalles', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('pedido_encabezado_id');
            $table->foreign('pedido_encabezado_id')->references('id')->on('pedidos_encabezados')->onDelete('set null');
            $table->integer('producto_id');
            $table->foreign('producto_id')->references('id')->on('productos')->onDelete('set null');
            $table->integer('cantidad');
            $table->float('precio');
            $table->float('total');
            $table->integer('estado')->default(1);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pedidos_detalles');
    }
};
