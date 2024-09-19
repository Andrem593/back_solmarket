<?php

namespace App\Http\Controllers;

use App\Models\CentroDeCosto;
use Illuminate\Http\Request;

class CentroDeCostoController extends Controller
{
    public function index(Request $request)
    {
        return CentroDeCosto::where('estado', 1)
            ->filters()
            ->limit(20)
            ->get();
    }
}
