<?php

namespace App\Filament\Resources\ClienteResource\Pages;

use Filament\Actions;
use Filament\Actions\ImportAction;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;
use Filament\Notifications\Notification;
use App\Filament\Imports\ClienteImporter;
use Filament\Resources\Pages\ListRecords;
use App\Filament\Resources\ClienteResource;

class ListClientes extends ListRecords
{
    protected static string $resource = ClienteResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make()
            ->label('Importar')
            ->icon('heroicon-o-arrow-up-tray')
                ->using(function (array $data, string $model): Model {
                    $filePath = storage_path('app/public/' . urldecode($data['archivo']));

                    if (!file_exists($filePath)) {
                        throw new \Exception("File {$filePath} does not exist");
                    }
                    $fileContent = file_get_contents($filePath);
                    $fileContent = str_replace(chr(0), '', $fileContent);
                    $fileContent = str_replace("\r\n", "\n", $fileContent);
                    $fileContent = str_replace("\n\r", "\n", $fileContent);
                    $fileContent = str_replace("\r", "\n", $fileContent);
                    $fileContent = str_replace("\t", ",", $fileContent);
                    $lines = array_filter(explode("\n", $fileContent)); // separo por salto de linea y elimino lineas vacias
                    $data = [];
                    if (count($lines) > 0) {
                        foreach ($lines as $line) {
                            $info = explode(',', $line);
                            if (count($info) > 1) {
                                $data[] = [
                                    'cedula' => $info[1],
                                    'nombres' => $info[10],
                                    'valor' => $info[3],
                                    'estado' => 1,
                                    'created_at' => now(),
                                    'updated_at' => now(),
                                ];
                            }
                        }
                    }
                    $model::insert($data);

                    return $model::create($data);
                }) ->successNotification(
                    Notification::make()
                         ->success()
                         ->title('Importacion Realizada')
                         ->body('La importacion se realizo con exito.'),
                 ),
        
        ];
    }
}
