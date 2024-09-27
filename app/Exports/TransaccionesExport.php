<?php

namespace App\Exports;

use Carbon\Carbon;
use App\Models\Transaccion;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\FromCollection;

class TransaccionesExport implements FromCollection,WithHeadings
{
    public $fechaInicio;
    public $fechaFin;


    public function __construct($fechaInicio, $fechaFin)
    {
        $this->fechaInicio = Carbon::parse($fechaInicio)->startOfDay();
        $this->fechaFin = Carbon::parse($fechaFin)->endOfDay();
    }

    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {

        $transacciones = Transaccion::with('cliente')
        ->whereBetween('created_at', [$this->fechaInicio, $this->fechaFin])
        ->orderBy('created_at', 'desc')
        ->get();
        return $transacciones->map(function ($transaccion) {
            return [
                'Cedula' => $transaccion->cliente->cedula ?? '',
                'Nombres' => $transaccion->cliente->nombres ?? '',
                'CPL' => $transaccion->cliente->cpl ?? '',
                'Transacción' => $transaccion->transaccion,
                'Valor' => $transaccion->valor,
                'Fecha' => Carbon::parse($transaccion->created_at)->format('d/m/Y'),
            ];
        });
    }

    public function headings(): array
    {
        return [
            'Cedula',
            'Nombres',
            'CPL',
            'Transacción',
            'Valor',
            'Fecha',
        ];
    }
}
