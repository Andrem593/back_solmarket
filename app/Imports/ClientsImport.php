<?php

namespace App\Imports;

use App\Models\Cliente;
use App\Models\Transaccion;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;

class ClientsImport implements ToCollection
{
    /**
    * @param Collection $collection
    */
    public function collection(Collection $rows)
    {
        foreach ($rows as $key => $row) {
            // Saltar la primera fila de encabezado
            if ($key == 0) continue;

            $cliente = Cliente::where('cedula', $row[4])->first();
            if ($cliente) {
                $trasaccion = str_pad($row[20], 10, '0', STR_PAD_LEFT);
                $tran = Transaccion::where('cliente_id', $cliente->id)
                    ->where('transaccion', $trasaccion)
                    ->first();
                if (!$tran) {
                    $nuevoSaldo = floatval($row[27]) + $cliente->valor;
                    $cliente->update([
                        'valor' => $nuevoSaldo,
                    ]);
                    Transaccion::create([
                        'cliente_id' => $cliente->id,
                        'transaccion' => $trasaccion,
                        'valor' => $row[27],
                    ]);
                }
            }
        }
    }
}
