<?php

namespace App\Http\Controllers;

use App\Models\Cliente;
use App\Models\PedidoDetalle;
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

        // Se deve verificar si existe un pedido guardado no puedo realizar la venta
        // SOlo debo devolver al stock cuando cambio de cliente, pero si existe un pedido reservado, devuelvo lo que no se encuentre en ese pedido
        // Debo de enviar el id de la venta, para que sque la diferencia al devolver, es decir los produtos iguales que se le agrgeo una cantidad debe devolver la difrencia

        try {

            DB::beginTransaction();

            $orderHeaderController = new PedidoEncabezadoController ;

            if($request->id){


            }else{
                $orderHeader = PedidoEncabezado::where('cliente_id', $request->cliente_id)->where('estado', 1)->first();
                if($orderHeader){
                    foreach ($request->productos  as $key => $detail) {
                        $newAmout = $detail['cantidad'] ;
                        $orderHeaderController->changeProductStockValue($detail['producto_id'],  $newAmout , 2);
                    }
                    DB::commit();

                    return response()->json([
                        'success' => false,
                        'message' => 'La solicitud no puede ser procesada porque ya existe un pedido guardado de ese cliente.',
                    ], 409); // Usamos 409 Conflict en lugar de 500 Internal Server Error
                }

            }



            $saleHeader = VentaEncabezado::create($request->all());


            $saleHeader->salesDetail()->createMany($request->productos);

            $client = Cliente::findOrFail($saleHeader->cliente_id);

            //Verificar si es mayor el total consultar

            //Verificar si tiene un pedido reservado
            if(isset($request->id) && $request->id != ''){
                $orderHeader = PedidoEncabezado::findOrFail($request->id);


                //Verificar el estado que mismo le pongo
                $orderHeader->estado = 2 ;
                $orderHeader->save();

            }else{
            }

            if(($client->valor - $request->total) < 0){
                DB::rollBack();
                return response()->json([
                    'success' => false,
                    'message' => 'No tiene suficiente saldo el cliente.',
                ], 409); // Usamos 409 Conflict en lugar de 500 Internal Server Error

            }
            $client->valor = $client->valor - $request->total ;

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
