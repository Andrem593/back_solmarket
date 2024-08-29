<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Transaccion extends Model
{
    use HasFactory;
    
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
}
