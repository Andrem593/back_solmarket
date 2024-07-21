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
        Schema::create('ventas_encabezados', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('user_id');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('set null');
            $table->integer('cliente_id');
            $table->foreign('cliente_id')->references('id')->on('clientes')->onDelete('set null');
            $table->float('saldo_actual');
            $table->float('saldo');
            $table->float('subtotal');
            $table->float('iva');
            $table->float('total');
            $table->date('fecha');
            $table->integer('estado')->default(1);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ventas_encabezados');
    }
};
