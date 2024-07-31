<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class VentaDetalle extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $table = 'ventas_detalles';

    protected $fillable = [
        'venta_encabezado_id',
        'producto_id',
        'cantidad',
        'precio',
        'total',
        'estado',
    ];

    public function saleHeader()
    {
        return $this->belongsTo(VentaEncabezado::class, 'venta_encabezado_id');
    }

    public function product()
    {
        return $this->belongsTo(Producto::class, 'producto_id');
    }


}
