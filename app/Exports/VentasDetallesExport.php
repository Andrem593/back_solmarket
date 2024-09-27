<?php

namespace App\Exports;

use App\Models\VentaDetalle;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\FromCollection;

class VentasDetallesExport implements FromCollection, WithHeadings
{
    public $fechaInicio;
    public $fechaFin;


    public function __construct($fechaInicio, $fechaFin)
    {
        $this->fechaInicio = $fechaInicio;
        $this->fechaFin = $fechaFin;
    }

    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        $ventas_detalles = VentaDetalle::with(['saleHeader', 'product' => function($query) {
            $query->withTrashed(); // Incluir productos eliminados con SoftDelete
        }])
        ->whereHas('saleHeader', function ($query) {
            $query->whereBetween('fecha', [$this->fechaInicio, $this->fechaFin]);
        })
        ->get();


        return $ventas_detalles->map(function ($venta_detalle) {   

            try {
                $cliente = $venta_detalle->saleHeader->client->nombres;
                $estado = $venta_detalle->saleHeader->estado;
                $id = $venta_detalle->saleHeader->id;
                $id = str_pad($id, 5, '0', STR_PAD_LEFT);
                $fecha = $venta_detalle->saleHeader->fecha;
            } catch (\Throwable $th) {
                $cliente = 'VENTA ANULADA';
                $estado = 'INACTIVO';
                $id = 'VENTA ANULADA';
                $fecha = 'VENTA ANULADA';
            }
            return [
                'Venta'=> $id,
                'Cliente' => $cliente,
                'Producto' => $venta_detalle->product->nombre,
                'Cantidad' => $venta_detalle->cantidad,
                'Precio' => $venta_detalle->precio,
                'Total' => $venta_detalle->total,
                'fecha' => $fecha,
                'Estado' => $estado,
            ];

        });
    }


    public function headings(): array
    {
        return [
            'Venta',
            'Cliente',
            'Producto',
            'Cantidad',
            'Precio',
            'Total',
            'Fecha',
            'Estado',
        ];
    }
}
