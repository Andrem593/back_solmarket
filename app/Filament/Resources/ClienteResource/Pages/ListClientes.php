<?php

namespace App\Filament\Resources\ClienteResource\Pages;

use Filament\Actions;
use App\Models\Cliente;
use App\Models\Transaccion;
use Maatwebsite\Excel\Excel;
use App\Imports\ClientsImport;
use App\Exports\ClientesExport;
use Filament\Actions\ImportAction;
use Filament\Forms\Components\Select;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\ListRecords;
use App\Filament\Resources\ClienteResource;

class ListClientes extends ListRecords
{
    protected static string $resource = ClienteResource::class;

    protected function getHeaderActions(): array
    {
        $cpl = Cliente::select('cpl')->distinct()
        ->orderBy('cpl')
        ->get();
        $optionsCPL = [];
        foreach ($cpl as $key => $value) {
           if($value->cpl == null){
               $optionsCPL[0] = 'Sin CPL';
           }else{
                $optionsCPL[$value->cpl] = $value->cpl;
           }
        }
        
        return [
            Actions\CreateAction::make()
                ->label('Importar')
                ->icon('heroicon-o-arrow-up-tray')
                ->using(function (array $data, string $model, Excel $excel) {

                    $filePath = storage_path('app/public/' . urldecode($data['archivo']));
                    $excel->import(new ClientsImport, $filePath);
                    return  $model::all()->last();
                })->successNotification(
                    Notification::make()
                        ->success()
                        ->title('Importacion Realizada')
                        ->body('La importacion se realizo con exito.'),
                ),
            Actions\Action::make('generateReport')
                ->label('Generar Reporte CPL')
                ->modalHeading('Selecciona el tipo de CPL')
                ->form([
                    Select::make('cpl_type')
                        ->label('Tipo de CPL')
                        ->options($optionsCPL)
                        ->required(),
                ])
                ->action(function (Excel $excel, Array $data) {

                    $cpl = $data['cpl_type'];                    
                    return $excel->download(new ClientesExport($cpl), 'clientes-cpl.xlsx');
                }),

        ];
    }
}
