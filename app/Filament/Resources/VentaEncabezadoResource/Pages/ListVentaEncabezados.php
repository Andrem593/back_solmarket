<?php

namespace App\Filament\Resources\VentaEncabezadoResource\Pages;

use Filament\Actions;
use App\Exports\VentasExport;
use Filament\Pages\Actions\ButtonAction;
use Filament\Resources\Pages\ListRecords;
use App\Filament\Resources\VentaEncabezadoResource;
use Maatwebsite\Excel\Excel;
use App\Exports\VentasConsolidadoExport;

class ListVentaEncabezados extends ListRecords
{
    protected static string $resource = VentaEncabezadoResource::class;

    protected function getHeaderActions(): array
    {
        $fechaIncio = now()->format('Y-m-d');
        $fechaFin = now()->format('Y-m-d');
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
            Actions\Action::make('generateReport')
                ->label('Reporte Venta y Saldos')
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
                    
                    return $excel->download(new VentasConsolidadoExport($fechaIncio, $fechaFin), 'ventas_saldos.xlsx');
                }),
        ];
    }
}
