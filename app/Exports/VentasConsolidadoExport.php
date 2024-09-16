<?php

namespace App\Exports;

use App\Models\Cliente;
use App\Models\Transaccion;
use App\Models\VentaEncabezado;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\FromCollection;

class VentasConsolidadoExport implements FromCollection, WithHeadings
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
        $saldoProvisali = Transaccion::where('migracion', 1)
            ->select('cliente_id', \DB::raw('sum(valor) as valor'))
            ->with('cliente')
            ->groupBy('cliente_id')
            ->get()
            ->keyBy('cliente_id');

        $ventas =  Cliente::whereHas('ventas', function ($query) {
            $query->whereBetween('fecha', [$this->fechaInicio, $this->fechaFin]);
        })
            ->orWhereHas('transacciones', function ($query) {
                $query->whereBetween('created_at', [$this->fechaInicio, $this->fechaFin])
                    ->where('migracion', 0);
            })
            ->with([
                'ventas' => function ($query) {
                    $query->whereBetween('fecha', [$this->fechaInicio, $this->fechaFin]);
                },
                'transacciones' => function ($query) {
                    $query->whereBetween('created_at', [$this->fechaInicio, $this->fechaFin]);
                }
            ])
            ->get();


        $collection =  $ventas->map(function ($cliente) use ($saldoProvisali) {
            $ventas = $cliente->ventas->sum('total');
            $transacciones = $cliente->transacciones->sum('valor');
            $saldoProvisali = isset($saldoProvisali[$cliente->id]) ? $saldoProvisali[$cliente->id]->valor : 0;
            $saldo = $transacciones + $saldoProvisali - $ventas;
            if ($saldo < 0) {
                $saldo = 0;
            }  
            return [
                'id' => $cliente->id,
                'Cédula' => $cliente->cedula,
                'Cliente' => $cliente->nombres,
                'CPL' => $cliente->cpl,
                'Pabellón' => $cliente->pabellon,
                'Saldo Provisali' => $saldoProvisali,
                'Transacciones' => $transacciones,
                'Ventas' => $ventas,
                'Saldo' => $saldo,
            ];
        });

        // Añadir los clientes que solo tienen saldo provisali y no están en la colección de ventas
        $clientesFaltantes = $saldoProvisali->filter(function ($saldo) use ($collection) {
            return !$collection->contains('id', $saldo->cliente_id);
        });

        $clientesFaltantes->each(function($clienteSaldo) use (&$collection) {
            $collection->push([
                'id' => $clienteSaldo->cliente_id,
                'Cédula' => $clienteSaldo->cliente->cedula ?? '',
                'Cliente' => $clienteSaldo->cliente->nombres ?? '',
                'CPL' => $clienteSaldo->cliente->cpl ?? '',
                'Pabellón' => $clienteSaldo->cliente->pabellon ?? '',
                'Saldo Provisali' => $clienteSaldo->valor ?? 0,
                'Transacciones' => 0,
                'Ventas' => 0,
                'Saldo' => $clienteSaldo->cliente->valor ?? 0,
            ]);
        });

        return $collection;
    }


    public function headings(): array
    {
        return [
            'ID',
            'Cédula',
            'Cliente',
            'CPL',
            'Pabellón',
            'Saldo Provisali',
            'Total Transacciones',
            'Total Ventas',
            'Saldo Actual',
        ];
    }
}
