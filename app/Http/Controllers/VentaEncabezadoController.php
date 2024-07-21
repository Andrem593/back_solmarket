<?php

namespace App\Http\Controllers;

use App\Models\Cliente;
use App\Models\PedidoEncabezado;
use App\Models\VentaEncabezado;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class VentaEncabezadoController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {


    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'cliente_id' => 'required',
            'productos' => 'required',
        ]);

        $request->merge([
            "user_id" => auth()->user()->id,
            "fecha" => date('Y-m-d'),
        ]);

        // dd($request->all());

        try {

            DB::beginTransaction();

            $saleHeader = VentaEncabezado::create($request->all());


            $saleHeader->salesDetail()->createMany($request->productos);

            $client = Cliente::findOrFail($saleHeader->cliente_id);

            //Verificar si es mayor el total consultar

            //Verificar si tiene un pedido reservado
            if(isset($request->id) && $request->id != ''){
                $orderHeader = PedidoEncabezado::findOrFail($request->id);

                // $client->valor = $orderHeader->saldo_actual - collect($request->productos)->sum('total') ;
                //Verificar el estado que mismo le pongo
                $orderHeader->estado = 2 ;
                $orderHeader->save();

            }else{
            }
            $client->valor = $request->saldo ;

            $client->save();

            DB::commit();

        } catch (\Throwable $th) {

            DB::rollBack();

            return response()->json([
                'success' => false,
                'message' => 'Lo sentimos, algo ha ido mal, inténtelo de nuevo más tarde.',
            ], 500);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
