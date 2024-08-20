<?php

namespace App\Filament\Resources\VentaEncabezadoResource\Pages;

use App\Filament\Resources\VentaEncabezadoResource;
use Filament\Actions;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Pages\ManageRelatedRecords;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Filament\Tables\Actions\ButtonAction;
use Filament\Tables\Contracts\HasTable;

class ManageDetalleVentas extends ManageRelatedRecords
{

    protected static bool $isLazy = false;

    protected static string $resource = VentaEncabezadoResource::class;

    protected static string $relationship = 'salesDetail';

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    protected static ?string $title = 'Lista de detalle de Venta';

    public static function getNavigationLabel(): string
    {
        return 'Lista de detalle de Venta';
    }


    public function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('product.nombre')->label('Producto')->searchable()->sortable(),
                Tables\Columns\TextColumn::make('cantidad'),
                Tables\Columns\TextColumn::make('precio')->prefix('$'),
                Tables\Columns\TextColumn::make('total')->prefix('$'),
            ])
            ->filters([
                Tables\Filters\TrashedFilter::make()
            ])
            ->headerActions([
                // Tables\Actions\CreateAction::make(),
                // Tables\Actions\AssociateAction::make(),
                ButtonAction::make('print')
                    ->label('Imprimir')
                    ->icon('heroicon-o-printer')
                    ->color('primary')
                    ->action(fn (HasTable $livewire) => $this->printTable($livewire))

            ])
            ->actions([
               
            ])
            ->bulkActions([
               
            ])
            ->modifyQueryUsing(fn (Builder $query) => $query->withoutGlobalScopes([
                SoftDeletingScope::class,
            ]));
    }


    public  function printTable($record)
    {       
        $id = $record->record->id;

        return redirect()->route('ventas.print', ['id' => $id]);
    }

    public static function printTable2($record)
    {       
        $id = $record->id;

        return redirect()->route('ventas.print', ['id' => $id]);
    }
}
