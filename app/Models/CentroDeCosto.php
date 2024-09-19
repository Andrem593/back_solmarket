<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CentroDeCosto extends Model
{
    use HasFactory;

    protected $table = 'centro_de_costo';

    protected $fillable = [
        'nombre'
    ];

    public function scopeFilters($query)
    {
        if (isset(request()->nombre)) {
            $query->where('nombre', 'like', '%'.request()->nombre.'%');
        }
    }

}
