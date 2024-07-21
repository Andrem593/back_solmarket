<?php

namespace App\Http\Controllers;

use App\Models\Cliente;
use App\Models\PedidoDetalle;
use App\Models\PedidoEncabezado;
use App\Models\Producto;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PedidoEncabezadoController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $orderHeaders = PedidoEncabezado::with('user', 'client', 'ordersDetails.product' )
            ->where('estado', 1)
            ->orderBy('created_at', 'desc')
            ->get()
            ->map(function($orderHeader){
                $orderHeader->nombre_completo = $orderHeader->client->nombres ;
                $orderHeader->nombres = $orderHeader->client->nombres ;
                $orderHeader->cedula = $orderHeader->client->cedula ;
                $orderHeader->saldo = $orderHeader->client->valor ;
                $orderHeader->total = $orderHeader->total ;
                $orderHeader->subtotal_iva = 0 ;
                $orderHeader->descuento = 0 ;
                $orderHeader->productos = $orderHeader->ordersDetails->map(function($detail){
                    $detail->nombre = $detail->product->nombre ;
                    $detail->img = $detail->product->img ;
                    return  $detail ;
                }) ;
                return $orderHeader ;

            });

        return $orderHeaders ;
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

        $orderHeader = PedidoEncabezado::where('cliente_id', $request->cliente_id)->where('estado', 1)->first();
        if($orderHeader){
            return response()->json([
                'success' => false,
                'message' => 'La solicitud no puede ser procesada porque ya existe un registro de ese cliente.',
            ], 409); // Usamos 409 Conflict en lugar de 500 Internal Server Error
        }

        try {

            DB::beginTransaction();

            $orderHeader = PedidoEncabezado::create($request->all());

            // foreach ($request->productos as $key => $detail) {
            //     $detail['pedido_encabezado_id'] = $orderHeader->id ;
            //     dd($detail);
            //     PedidoDetalle::create($detail);
            //     # code...
            // }
            $orderHeader->ordersDetails()->createMany($request->productos);

            $client = Cliente::findOrFail($orderHeader->cliente_id);

            //Verificar si es mayor el total consultar

            $client->valor -= $orderHeader->total ;

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
    public function update(Request $request, $id)
    {
        $request->validate([
            'cliente_id' => 'required',
            'productos' => 'required',
        ]);

        $request->merge([
            "user_id" => auth()->user()->id,
            "fecha" => date('Y-m-d'),
        ]);

        $orderHeader = PedidoEncabezado::findOrFail($id);

        try {

            DB::beginTransaction();

            foreach ($request->productos as $key => $detail) {
                if(isset($detail['id']) && $detail['id'] != ""){
                    $orderDetail = PedidoDetalle::findOrFail($detail['id']);
                    $orderDetail->cantidad = $detail['cantidad'] ;
                    $orderDetail->precio = $detail['precio'] ;
                    $orderDetail->total = $detail['total'] ;
                    $orderDetail->save();

                }else{
                    $detail['pedido_encabezado_id'] = $orderHeader->id ;
                    PedidoDetalle::create($detail);
                }
            }

            //Actualizo Valor del  saldo del Cliente
            $client = Cliente::findOrFail($orderHeader->cliente_id);

            $client->valor = $orderHeader->saldo_actual - collect($request->productos)->sum('total') ;

            $client->save();

            //Actualizo el encabezado
            $orderHeader->user_id = $request->user_id ;
            $orderHeader->saldo_actual = $request->saldo_actual ;
            $orderHeader->saldo = $request->saldo ;
            $orderHeader->subtotal = $request->subtotal ;
            $orderHeader->iva = $request->iva ;
            $orderHeader->total = $request->total ;
            $orderHeader->fecha = $request->fecha ;

            $orderHeader->save();

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
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        try {

            DB::beginTransaction();

            $orderHeaders = PedidoEncabezado::findOrFail($id);

            $ordersDetails = PedidoDetalle::where('pedido_encabezado_id', $orderHeaders->id )->where('estado' , 1)->get();

            $totalRecover = 0 ;
            foreach ($ordersDetails as $key => $detail) {
                $product = Producto::findOrfail($detail->producto_id);
                $product->stock += $detail->cantidad ;
                $product->save();

                $totalRecover += $detail->total ;
            }

            $client = Cliente::findOrFail($orderHeaders->cliente_id);
            $client->valor += $totalRecover ;
            $client->save();

            $orderHeaders->estado = 0 ;
            $orderHeaders->save();

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
     * Disminuye el stock
     * type: 1 = disminuye, otro = aumenta
     */
    public function changeProductStockValue($idProduct, $amount, $type)
    {
        $product = Producto::findOrFail($idProduct);
        $product->stock =  $type == 1 ? $product->stock - $amount : $product->stock + $amount ;
        $product->save();
    }


    /**
     * Disminuir el stock de varios productos
     */
    public function returnQuantityToProductStock(Request $request)
    {
        $products = $request->productos;
        try {
            DB::beginTransaction();
            foreach ($products as $key => $product) {
                $this->changeProductStockValue($product['producto_id'], $product['cantidad'], 2) ;
            }
            DB::commit();
        } catch (\Throwable $th) {
            DB::rollBack();

            return response()->json([
                'success' => false,
                'message' => 'Lo sentimos, algo ha ido mal, inténtelo de nuevo más tarde.',
            ], 500);
        }

    }

}
