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
            $table->id();
            $table->foreignId('pedido_encabezado_id')->constrained('pedidos_encabezados');
            $table->foreignId('producto_id')->constrained('productos');
            $table->integer('cantidad');
            $table->float('precio');
            $table->float('total');
            $table->integer('estado')->default(1);
            $table->timestamps();
            $table->softDeletes();

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
