<?php

namespace App\Models;

use App\Models\Transaccion;
use App\Models\VentaEncabezado;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;


class Cliente extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $fillable = [
        'id',
        'cedula',
        'nombres',
        'valor',
        'cpl',
        'pabellon',
        'estado',
    ];

    public function transacciones()
    {
        return $this->hasMany(Transaccion::class, 'cliente_id', 'id');
    }

    public function ventas()
    {
        return $this->hasMany(VentaEncabezado::class, 'cliente_id', 'id');
    }

    // Mutator para formatear el valor a 2 decimales
    public function getValorAttribute($value)
    {
        return number_format((float) $value, 2, '.', '');
    }
}
