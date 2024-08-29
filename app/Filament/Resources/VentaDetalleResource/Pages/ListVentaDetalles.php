<?php

namespace App\Filament\Resources\VentaDetalleResource\Pages;

use Filament\Actions;
use Maatwebsite\Excel\Excel;
use App\Exports\VentasDetallesExport;
use Filament\Pages\Actions\ButtonAction;
use Filament\Resources\Pages\ListRecords;
use App\Filament\Resources\VentaDetalleResource;

class ListVentaDetalles extends ListRecords
{
    protected static string $resource = VentaDetalleResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make()->label('Agregar detalle de Venta'),
            ButtonAction::make('Reporte')
                ->label('reporte')                
                ->color('primary')
                ->action(function (Excel $excel) {
                    return $excel->download(new VentasDetallesExport, 'ventas_detalles.xlsx');
                }),
        ];
    }
}
