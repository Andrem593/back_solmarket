<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Subcategoria extends Model
{
    use HasFactory;

    protected $table = 'subcategoria';

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
