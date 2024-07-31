<?php

namespace App\Filament\Resources\VentaEncabezadoResource\Pages;

use App\Filament\Resources\VentaEncabezadoResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditVentaEncabezado extends EditRecord
{
    protected static string $resource = VentaEncabezadoResource::class;

    public static function getNavigationLabel(): string
    {
        return 'Editar ';
    }


   
}
