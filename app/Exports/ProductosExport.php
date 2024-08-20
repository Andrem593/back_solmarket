<?php

namespace App\Exports;

use App\Models\Producto;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\FromCollection;

class ProductosExport implements FromCollection, WithHeadings
{
    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        $productos = Producto::all();

        return $productos->map(function ($producto) {
            return [
                'Nombre' => $producto->nombre,
                'Precio' => $producto->precio,
                'Stock' => $producto->stock,
                'Estado' => $producto->estado ? 'ACTIVO' : 'INACTIVO',
            ];
        });
    }

    public function headings(): array
    {
        return [
            'Nombre',
            'Precio',
            'Stock',
            'Estado',
        ];
    }
}
