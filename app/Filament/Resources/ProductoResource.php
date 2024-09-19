<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ProductoResource\Pages;
use App\Filament\Resources\ProductoResource\RelationManagers;
use App\Models\Producto;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class ProductoResource extends Resource
{
    protected static ?string $model = Producto::class;

    protected static ?string $navigationGroup = 'Productos';



    public static function form(Form $form): Form
    {
        return $form
            ->schema(
                function ($record) {                  
                    return [
                        Forms\Components\TextInput::make('nombre')
                            ->required()
                            ->unique(Producto::class, 'nombre', $record) 
                            ->validationMessages([
                                'unique' => 'El nombre del producto ya existe',
                            ])
                            ->maxLength(255),
                        Forms\Components\TextInput::make('descripcion')
                            ->required()
                            ->maxLength(255),
                        Forms\Components\TextInput::make('stock')
                            ->required()
                            ->numeric()
                            ->maxLength(255),
                        Forms\Components\TextInput::make('precio')
                            ->numeric()
                            ->prefix('$')
                            ->maxValue(42949672.95),
                        Forms\Components\Select::make('bodega')
                            ->options([
                                'PRINCIPAL' => 'Bodega Principal',
                                'SECUNDARIA' => 'Bodega Secundaria',
                            ])
                            ->required()
                            ->label('Selecciona la Bodega'),
                        Forms\Components\Toggle::make('estado')
                            ->required()->default(true),
                    ];
                }
            );
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('nombre')
                    ->searchable(),
                Tables\Columns\TextColumn::make('descripcion')
                    ->searchable(),
                Tables\Columns\TextColumn::make('stock')
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('precio')
                    ->prefix('$')
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('bodega')
                    ->searchable(),
                Tables\Columns\IconColumn::make('estado')
                    ->boolean(),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('deleted_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make()->label('Editar'),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListProductos::route('/'),
            // 'create' => Pages\CreateProducto::route('/create'),
            // 'edit' => Pages\EditProducto::route('/{record}/edit'),
        ];
    }
}
