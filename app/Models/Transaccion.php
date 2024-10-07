<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Transaccion extends Model
{
    use HasFactory;
    use LogsActivity;
    
    protected $table = 'transacciones';

    protected $fillable = [
        'cliente_id',
        'transaccion',
        'valor',
    ];

    public function cliente()
    {
        return $this->belongsTo(Cliente::class);
    }

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logOnly([
                'cliente_id',
                'transaccion',
                'valor'
            ])  // Atributos que deseas registrar
            ->useLogName('transaccion')  // Nombre del log (puedes cambiarlo)
            ->setDescriptionForEvent(function(string $eventName) {
                return match($eventName) {
                    'created' => 'Cliente ha sido creado',
                    'updated' => 'Cliente ha sido actualizado',
                    'deleted' => 'Cliente ha sido eliminado',
                    default => "Cliente ha tenido una acción: {$eventName}",
                };
            });
    }
}
