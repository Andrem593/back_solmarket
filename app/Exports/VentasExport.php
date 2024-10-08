<?php

namespace App\Exports;

use App\Models\VentaEncabezado;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\WithFooter;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\FromCollection;
use PhpOffice\PhpSpreadsheet\Style\NumberFormat;
use Maatwebsite\Excel\Concerns\WithColumnFormatting;

class VentasExport implements FromCollection, WithHeadings, WithEvents
{

    use Exportable;

    protected $totals;

    public function collection()
    {
        $ventas = VentaEncabezado::with('user', 'client')
        ->where('estado', 1)
        ->get();


        $this->totals = [      
            'subtotal' => $ventas->sum('subtotal'),
            'iva' => $ventas->sum('iva'),
            'total' => $ventas->sum('total'),
        ];

        return $ventas->map(function ($venta) {
            return [
                'Usuario' => $venta->user->name ?? '',
                'Cliente' => $venta->client->nombres ?? '',
                'CPL' => $venta->client->cpl ?? '',
                'Pabellón' => $venta->client->pabellon ?? '',
                'Saldo Anterior' => $venta->saldo_actual ?? '',
                'Saldo Actual' => $venta->saldo ?? '',
                'Subtotal' => $venta->subtotal ?? '',
                'IVA' => $venta->iva ?? '',
                'Total' => $venta->total ?? '',
                'Fecha' => $venta->fecha ?? '',
                'Estado' => $venta->estado ? 'ACTIVO' : 'ANULADO',
            ];
        });
    }

    public function headings(): array
    {
        return [
            'Usuario',
            'Cliente',
            'CPL',
            'Pabellón',
            'Saldo Anterior',
            'Saldo Actual',
            'Subtotal',
            'IVA',
            'Total',
            'Fecha',
            'Estado',
        ];
    }


    public function columnFormats(): array
    {
        return [
            'E' => NumberFormat::FORMAT_CURRENCY_USD_SIMPLE,
            'F' => NumberFormat::FORMAT_CURRENCY_USD_SIMPLE,
            'G' => NumberFormat::FORMAT_CURRENCY_USD_SIMPLE,
            'H' => NumberFormat::FORMAT_CURRENCY_USD_SIMPLE,
            'I' => NumberFormat::FORMAT_CURRENCY_USD_SIMPLE,
        ];
    }


    public function footer(): array
    {
        return [
            'Totales',
            '',
            $this->totals['saldo_anterior'],
            $this->totals['saldo_actual'],
            $this->totals['subtotal'],
            $this->totals['iva'],
            $this->totals['total'],
            '',
            ''
        ];
    }

    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function (AfterSheet $event) {
                $sheet = $event->sheet->getDelegate();
                $lastRow = $sheet->getHighestRow() + 1;

                $sheet->setCellValue('A' . $lastRow, 'Totales');                                
                $sheet->setCellValue('G' . $lastRow, $this->totals['subtotal']);
                $sheet->setCellValue('H' . $lastRow, $this->totals['iva']);
                $sheet->setCellValue('I' . $lastRow, $this->totals['total']);
            },
        ];
    }
}