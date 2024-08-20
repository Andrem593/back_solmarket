<?php

namespace App\Filament\Resources\VentaEncabezadoResource\Pages;

use Filament\Actions;
use App\Exports\VentasExport;
use Filament\Pages\Actions\ButtonAction;
use Filament\Resources\Pages\ListRecords;
use App\Filament\Resources\VentaEncabezadoResource;
use Maatwebsite\Excel\Excel;

class ListVentaEncabezados extends ListRecords
{
    protected static string $resource = VentaEncabezadoResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make()->label('Agregar Venta')
            ->mutateFormDataUsing(function (array $data): array {
                $data['user_id'] = auth()->id();
                return $data;
            }),
            ButtonAction::make('Reporte')
                ->label('Reporte')                
                ->color('primary')
                ->action(function (Excel $excel) {
                    return $excel->download(new VentasExport, 'ventas.xlsx');
                }),
        ];
    }
}
