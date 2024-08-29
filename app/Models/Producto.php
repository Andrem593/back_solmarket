<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;


class Producto extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $fillable = [
        'nombre',
        'descripcion',
        'stock',
        'precio',
        'estado',
        'bodega',
    ];

    public function saleDetail()
    {
        return $this->hasMany(VentaDetalle::class, 'producto_id')->where('estado', 1);
    }
}
