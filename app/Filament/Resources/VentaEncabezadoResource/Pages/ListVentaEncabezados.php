<?php

namespace App\Filament\Resources\VentaEncabezadoResource\Pages;

use App\Filament\Resources\VentaEncabezadoResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

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
        ];
    }
}
