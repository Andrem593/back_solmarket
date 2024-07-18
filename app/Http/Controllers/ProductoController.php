<?php

namespace App\Http\Controllers;

use App\Models\Producto;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class ProductoController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request) : JsonResponse
    {
        $query = Producto::query();
        $query->when($request->input('nombre'), function ($query, $nombre) {
            $query->where('nombre', 'like', '%' . $nombre . '%');
        });
        $query->when($request->input('descripcion'), function ($query, $descripcion) {
            $query->where('descripcion', 'like', '%' . $descripcion . '%');
        });
        $perPage = $request->input('perPage') ?? 10;
        $products = $query->paginate($perPage);
        return response()->json($products);
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
         $product = Producto::create($request->all());
        return response()->json($product, 201);
    }

    /**
     * Display the specified resource.
     */
    public function show($id) : JsonResponse
    {
        $product = Producto::findOrFail($id);
        return response()->json($product);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Producto $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $product = Producto::findOrFail($id);
        $product->update($request->all());
        return response()->json($product);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id) : JsonResponse
    {
        $product = Producto::findOrFail($id);
        $product->delete();
        return response()->json(null, 204);
    }
}
