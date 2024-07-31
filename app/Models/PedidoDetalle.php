<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class PedidoDetalle extends Model
{
    use HasFactory;
    use SoftDeletes;


    protected $table = 'pedidos_detalles';

    protected $fillable = [
        'pedido_encabezado_id',
        'producto_id',
        'cantidad',
        'precio',
        'total',
        'estado',
    ];

    public function ordersHeader()
    {
        return $this->belongsTo(PedidoEncabezado::class, 'pedido_encabezado_id');
    }

    public function product()
    {
        return $this->belongsTo(Producto::class, 'producto_id');
    }

}
