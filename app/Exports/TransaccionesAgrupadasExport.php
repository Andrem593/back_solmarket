<?php

namespace App\Exports;

use Carbon\Carbon;
use App\Models\Transaccion;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\FromCollection;

class TransaccionesAgrupadasExport implements FromCollection,WithHeadings
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
        $transacciones = Transaccion::join('clientes', 'clientes.id', '=', 'transacciones.cliente_id')
        ->select('cliente_id', DB::raw('SUM(transacciones.valor) as valor'), 'clientes.cedula', 'clientes.nombres', 'clientes.cpl')
        ->whereBetween('transacciones.created_at', [$this->fechaInicio, $this->fechaFin])
        ->groupBy('cliente_id', 'clientes.cedula', 'clientes.nombres', 'clientes.cpl')
        ->orderBy('clientes.nombres')
        ->get();
        return $transacciones->map(function ($transaccion) {
            return [
                'Cedula' => $transaccion->cedula ?? '',
                'Nombres' => $transaccion->nombres ?? '',                
                'Valor' => $transaccion->valor,
            ];
        });
    }

    public function headings(): array
    {
        return [
            'Cedula',
            'Nombres',
            'Valor',
        ];
    }
}
