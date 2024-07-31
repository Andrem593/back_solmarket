<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class PedidoEncabezado extends Model
{
    use HasFactory;
    use SoftDeletes;


    protected $table = 'pedidos_encabezados';

    protected $fillable = [
        'user_id',
        'cliente_id',
        'saldo_actual',
        'saldo',
        'subtotal',
        'iva',
        'total',
        'fecha',
        'estado',
    ];


    public function ordersDetails()
    {
        return $this->hasMany(PedidoDetalle::class, 'pedido_encabezado_id', 'id')->where('estado', 1);
    }


    public function client()
    {
        return $this->belongsTo(Cliente::class, 'cliente_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }


}
