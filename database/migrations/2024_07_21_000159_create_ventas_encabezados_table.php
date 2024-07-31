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
            $table->id();
            $table->foreignId('user_id')->constrained('users');
            $table->foreignId('cliente_id')->constrained('clientes');
            $table->float('saldo_actual');
            $table->float('saldo');
            $table->float('subtotal');
            $table->float('iva');
            $table->float('total');
            $table->date('fecha');
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
        Schema::dropIfExists('ventas_encabezados');
    }
};
