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
        $fechaIncio = now()->format('Y-m-d');
        $fechaFin = now()->format('Y-m-d');

        return [
            Actions\CreateAction::make()->label('Agregar detalle de Venta'),
            Actions\Action::make('Reporte')
                ->label('Reporte Detalles Venta')
                ->modalHeading('Selecciona el rango de fechas')
                ->form([
                    \Filament\Forms\Components\DatePicker::make('fechaInicio')
                        ->label('Fecha Inicio')
                        ->default($fechaIncio)
                        ->required(),
                    \Filament\Forms\Components\DatePicker::make('fechaFin')
                        ->label('Fecha Fin')
                        ->default($fechaFin)
                        ->required(),
                ])
                ->action(function (Excel $excel, array $data) {
                    $fechaIncio = $data['fechaInicio'];
                    $fechaFin = $data['fechaFin'];
                    return $excel->download(new VentasDetallesExport($fechaIncio, $fechaFin), 'ventas_detalles.xlsx');
                }),
        ];
    }
}
