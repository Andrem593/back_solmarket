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

        $groupedVentas = $ventas_detalles->groupBy(function ($venta_detalle) {
            // Agrupar por cliente
            return $venta_detalle->saleHeader->client->nombres ?? 'VENTA ANULADA';
        });

        $formattedVentas = collect();

        foreach ($groupedVentas as $cliente => $ventas) {            
            $formattedVentas->push([
                'Venta' => '',
                'Cliente' => $cliente,
                'CPL'=> '',
                'Pabellón' => '',
                'Producto' => '',
                'Cantidad' => '',
                'Precio' => '',
                'Total' => '',
                'Fecha' => '',
                'Estado' => '',
            ]);

            foreach ($ventas as $venta_detalle) {
                try {
                    $id = $venta_detalle->saleHeader->id;
                    $id = str_pad($id, 5, '0', STR_PAD_LEFT);
                    $fecha = $venta_detalle->saleHeader->fecha;
                    $estado = $venta_detalle->saleHeader->estado == 1 ? 'ACTIVO' : 'INACTIVO';
                    $cpl = $venta_detalle->saleHeader->client->cpl;
                    $pabellon = $venta_detalle->saleHeader->client->pabellon;
                } catch (\Throwable $th) {
                    $id = 'VENTA ANULADA';
                    $fecha = 'VENTA ANULADA';
                    $estado = 'INACTIVO';
                    $cpl = 'VENTA ANULADA';
                    $pabellon = 'VENTA ANULADA';
                }

                $formattedVentas->push([
                    'Venta' => $id,
                    'Cliente' => '', // Dejar vacío el nombre del cliente para que no se repita
                    'CPL'=> $cpl,
                    'Pabellón' => $pabellon,
                    'Producto' => $venta_detalle->product->nombre,
                    'Cantidad' => $venta_detalle->cantidad,
                    'Precio' => $venta_detalle->precio,
                    'Total' => $venta_detalle->total,
                    'Fecha' => $fecha,
                    'Estado' => $estado,
                ]);
            }
        }

        return $formattedVentas;
    }


    public function headings(): array
    {
        return [
            'Venta',
            'Cliente',
            'CPL',
            'Pabellón',
            'Producto',
            'Cantidad',
            'Precio',
            'Total',
            'Fecha',
            'Estado',
        ];
    }
}
