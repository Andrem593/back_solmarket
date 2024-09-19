<?php

namespace App\Filament\Resources\TransaccionResource\Pages;

use Maatwebsite\Excel\Excel;
use App\Exports\TransaccionesExport;
use Filament\Pages\Actions\ButtonAction;
use Filament\Resources\Pages\ListRecords;
use App\Filament\Resources\TransaccionResource;
use Filament\Actions;
use App\Exports\TransaccionesAgrupadasExport;

class ListTransaccions extends ListRecords
{
    protected static string $resource = TransaccionResource::class;

    protected function getHeaderActions(): array
    {
        $fechaIncio = now()->format('Y-m-d');
        $fechaFin = now()->format('Y-m-d');
        return [
            Actions\Action::make('generateReport')
                ->label('Exportar')
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

                    return $excel->download(new TransaccionesExport($fechaIncio, $fechaFin), "TRANSACCIONES BANCOS DESDE $fechaIncio HASTA $fechaFin .xlsx");
                }),
            Actions\Action::make('generateReportSales')
                ->label('Reporte Transacciones Agrupadas')
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

                    return $excel->download(new TransaccionesAgrupadasExport($fechaIncio, $fechaFin), "TRANSACCIONES AGRUPADAS POR PPL DESDE $fechaIncio HASTA $fechaFin .xlsx");
                }),
        ];
    }
}
