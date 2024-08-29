<?php

namespace App\Filament\Resources\TransaccionResource\Pages;

use Maatwebsite\Excel\Excel;
use App\Exports\TransaccionesExport;
use Filament\Pages\Actions\ButtonAction;
use Filament\Resources\Pages\ListRecords;
use App\Filament\Resources\TransaccionResource;

class ListTransaccions extends ListRecords
{
    protected static string $resource = TransaccionResource::class;

    protected function getHeaderActions(): array
    {
        return [
            ButtonAction::make('Exportar')               
                ->label('Exportar')
                ->action(function (Excel $excel) {
                    return $excel->download(new TransaccionesExport, 'transacciones.xlsx');
                }),
        ];
    }
}
