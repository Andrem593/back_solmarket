<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;

class Producto extends Model
{
    use HasFactory;
    use SoftDeletes;
    use LogsActivity;

    protected $fillable = [
        'nombre',
        'descripcion',
        'stock',
        'precio',
        'estado',
        'bodega',
    ];

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logOnly([
                'nombre',
                'descripcion',
                'stock',
                'precio',
                'estado',
                'bodega'
            ])  // Atributos que deseas registrar
            ->useLogName('producto')  // Nombre del log (puedes cambiarlo)
            ->setDescriptionForEvent(function(string $eventName) {
                return match($eventName) {
                    'created' => 'Producto ha sido creado',
                    'updated' => 'Producto ha sido actualizado',
                    'deleted' => 'Producto ha sido eliminado',
                    default => "Producto ha tenido una acción: {$eventName}",
                };
            });
    }

    public function saleDetail()
    {
        return $this->hasMany(VentaDetalle::class, 'producto_id')->where('estado', 1);
    }
}
