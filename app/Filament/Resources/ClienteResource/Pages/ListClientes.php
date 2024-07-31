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
use App\Models\Cliente;
use App\Models\Transaccion;

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
                    $lines = explode("\n", $fileContent);
                    $data = [];
                    if (count($lines) > 0) {
                        foreach ($lines as $key => $line) {
                            // Quitar comas y comillas de cada línea
                            $info = str_replace(['"'], '', $line);
                            $info = explode(',', $info);
                            if (count($info) > 1 && $key > 0) {
                                if ($info[3] == 'C') {
                                    $info[2] = str_replace(' ', '', $info[2]);
                                    preg_match('/-(\d+)/', $info[2], $matches);
                                    if (isset($matches[1])) {
                                        $cedula = $matches[1];
                                        $cedula = str_replace(' ', '', $cedula);
                                        $cliente = Cliente::where('cedula', $cedula)->first();
                                        if ($cliente) {
                                            $transaccion = Transaccion::where('cliente_id', $cliente->id)
                                            ->where('transaccion', $info[4])
                                            ->first();
                                            if (!$transaccion) {
                                                $nuevoSaldo = floatval($info[6]) + $cliente->valor;
                                                $cliente->update([
                                                    'valor' => $nuevoSaldo,
                                                ]);

                                                Transaccion::create([
                                                    'cliente_id' => $cliente->id,
                                                    'transaccion' => $info[4],
                                                    'valor' => $info[6],
                                                ]);
                                            }
                                        }
                                    }
                                }
                            }
                        }
                    }
                    $model::insert($data);
                    return  $model::all()->last();
                }) ->successNotification(
                    Notification::make()
                         ->success()
                         ->title('Importacion Realizada')
                         ->body('La importacion se realizo con exito.'),
                 ),
        
        ];
    }
}
