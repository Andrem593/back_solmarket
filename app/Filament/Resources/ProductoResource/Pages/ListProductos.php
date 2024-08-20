<?php

namespace App\Filament\Resources\ProductoResource\Pages;

use Filament\Actions;
use Maatwebsite\Excel\Excel;
use App\Exports\ProductosExport;
use Filament\Pages\Actions\ButtonAction;
use Filament\Resources\Pages\ListRecords;
use App\Filament\Resources\ProductoResource;

class ListProductos extends ListRecords
{
    protected static string $resource = ProductoResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make()
                ->label('Agregar Producto')
                ->successNotificationTitle('Producto Creado'),
            ButtonAction::make('Reporte')
                ->label('Reporte')
                ->color('primary')
                ->action(function (Excel $excel) {
                    return $excel->download(new ProductosExport, 'productos.xlsx');
                }),
        ];
    }
}
