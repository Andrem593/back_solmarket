<?php

namespace App\Http\Controllers;

use App\Models\Subcategoria;
use Illuminate\Http\Request;

class SubcategoriaController extends Controller
{
    public function index(Request $request)
    {
        return Subcategoria::where('estado', 1)
            ->filters()
            ->limit(20)
            ->get();
    }
}
