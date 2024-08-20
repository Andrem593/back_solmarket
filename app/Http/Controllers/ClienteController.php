<?php

namespace App\Http\Controllers;

use App\Models\Cliente;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class ClienteController extends Controller
{
    /**
     * Display a listing of the resource.
     */
public function index(Request $request) : JsonResponse
{
    $query = Cliente::query();

    $query->when($request->input('cedula'), function ($query, $cedula) {
        $query->where('cedula', 'like', '%' . $cedula . '%');
    });

    $query->when($request->input('nombres'), function ($query, $nombres) {
        $query->where('nombres', 'like', '%' . $nombres . '%')
            ->orWhere('cedula', 'like', '%' . $nombres . '%');

    });

    $perPage = $request->input('perPage') ?? 10;

    if ($perPage === 'all') {
        $clientes = $query
        ->orderBy('valor', 'desc')
        ->limit(30)
        ->get();
    } else {
        $clientes = $query
        ->orderBy('valor', 'desc')
        ->limit(30)
        ->paginate($perPage);
    }

    return response()->json($clientes);
}

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request) : JsonResponse
    {
        $request->validate([
            'cedula' => ['required', 'unique:clientes,cedula', 'digits:10']
        ]);

        $cliente = Cliente::create($request->all());
        return response()->json($cliente, 201);
    }

    /**
     * Display the specified resource.
     */
    public function show( $id)
    {
        $cliente = Cliente::findOrFail($id);
        return response()->json($cliente);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Cliente $id)
    {

    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $cliente = Cliente::findOrFail($id);
        $cliente->update($request->all());
        return response()->json($cliente);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy( $id)
    {
        $cliente = Cliente::findOrFail($id);
        $cliente->delete();
        return response()->json(null, 204);
    }
}
