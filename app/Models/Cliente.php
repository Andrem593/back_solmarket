<?php

namespace App\Models;

use App\Models\Transaccion;
use App\Models\VentaEncabezado;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;


class Cliente extends Model
{
    use HasFactory;
    use SoftDeletes;
    use LogsActivity;

    protected $fillable = [
        'id',
        'cedula',
        'tipo_identificacion',
        'nombres',
        'genero',
        'nacionalidad',
        'valor',
        'cpl',
        'pabellon',
        'estado',
        'ala',
        'subcategoria_id',
        'centro_costo_id'
    ];

    protected $appends = ['centro_costo','subcategoria'];

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logOnly([
                'cedula',
                'tipo_identificacion',
                'nombres',
                'genero',
                'nacionalidad',
                'valor',
                'cpl',
                'pabellon',
                'estado',
                'ala',
                'subcategoria_id',
                'centro_costo_id'
                ])  // Atributos que deseas registrar
            ->useLogName('cliente')  // Nombre del log (puedes cambiarlo)
            ->setDescriptionForEvent(function(string $eventName) {
                return match($eventName) {
                    'created' => 'Cliente ha sido creado',
                    'updated' => 'Cliente ha sido actualizado',
                    'deleted' => 'Cliente ha sido eliminado',
                    default => "Cliente ha tenido una acción: {$eventName}",
                };
            });
    }

    public function getCentroCostoAttribute()
    {
        if($this->centro_costo_id){
            return $this->costCenter->nombre;
        }else{
            return "";
        }
    }

    public function getSubcategoriaAttribute()
    {
        if($this->subcategoria_id){
            return $this->subcategory->nombre;
        }else{
            return "";
        }
    }

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

    public function subcategory()
    {
        return $this->belongsTo(Subcategoria::class, 'subcategoria_id');
    }

    public function costCenter()
    {
        return $this->belongsTo(CentroDeCosto::class, 'centro_costo_id');
    }
}
