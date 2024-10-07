<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;

class VentaEncabezado extends Model
{
    use HasFactory;
    use SoftDeletes;
    use LogsActivity;

    protected $table = 'ventas_encabezados';

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
        'subcategoria_id',
        'centro_costo_id'

    ];

    public $with = ['salesDetail', 'client', 'user'];


    protected $appends = ['centro_costo','subcategoria'];

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logOnly([
                'user_id',
                'cliente_id',
                'saldo_actual',
                'saldo',
                'subtotal',
                'iva',
                'total',
                'fecha',
                'estado',
                'subcategoria_id',
                'centro_costo_id'
            ])  // Atributos que deseas registrar
            ->useLogName('venta_encabezado')  // Nombre del log (puedes cambiarlo)
            ->setDescriptionForEvent(function(string $eventName) {
                return match($eventName) {
                    'created' => 'Venta Encabezado ha sido creado',
                    'updated' => 'Venta Encabezado ha sido actualizado',
                    'deleted' => 'Venta Encabezado ha sido eliminado',
                    default => "Venta Encabezado ha tenido una acción: {$eventName}",
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

    public function salesDetail()
    {
        return $this->hasMany(VentaDetalle::class, 'venta_encabezado_id', 'id')->where('estado', 1);
    }


    public function client()
    {
        return $this->belongsTo(Cliente::class, 'cliente_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
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
