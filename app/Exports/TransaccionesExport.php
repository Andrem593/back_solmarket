<?php

namespace App\Exports;

use Carbon\Carbon;
use App\Models\Transaccion;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\FromCollection;

class TransaccionesExport implements FromCollection,WithHeadings
{
    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {

        $transacciones = Transaccion::with('cliente')
        ->orderBy('created_at', 'desc')
        ->get();
        return $transacciones->map(function ($transaccion) {
            return [
                'Cedula' => $transaccion->cliente->cedula ?? '',
                'Nombres' => $transaccion->cliente->nombres ?? '',
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
            'Transacción',
            'Valor',
            'Fecha',
        ];
    }
}
