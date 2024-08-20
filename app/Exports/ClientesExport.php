<?php

namespace App\Exports;

use App\Models\Cliente;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\FromCollection;

class ClientesExport implements FromCollection, WithHeadings
{
    public $cpl;

    public function __construct($cpl)
    {
        $this->cpl = $cpl;
    }

    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        if ($this->cpl == 0) {
            $clientes = Cliente::whereNull('cpl')
            ->orderBy('nombres')
            ->get();
        }else{
            $clientes = Cliente::where('cpl', $this->cpl)
            ->orderBy('nombres')
            ->get();
        }        

        return $clientes->map(function ($cliente) {
            return [
                'Cedula' => $cliente->cedula,
                'Nombres' => $cliente->nombres,
                'Valor' => $cliente->valor,
                'CPL' => $cliente->cpl,
                'Pabellon' => $cliente->pabellon,
            ];
        });
    }

    public function headings(): array
    {
        return [
            'Cedula',
            'Nombres',
            'Valor',
            'CPL',
            'Pabellon',
        ];
    }
}
