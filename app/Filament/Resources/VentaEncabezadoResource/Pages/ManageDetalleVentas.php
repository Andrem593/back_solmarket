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
            ])
            ->actions([
               
            ])
            ->bulkActions([
               
            ])
            ->modifyQueryUsing(fn (Builder $query) => $query->withoutGlobalScopes([
                SoftDeletingScope::class,
            ]));
    }
}
