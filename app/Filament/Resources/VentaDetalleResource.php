<?php

namespace App\Filament\Resources;

use Filament\Forms;
use Filament\Tables;
use App\Models\Producto;
use Filament\Forms\Form;
use Filament\Tables\Table;
use App\Models\VentaDetalle;
use App\Models\VentaEncabezado;
use Filament\Resources\Resource;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\TextInput;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Filament\Resources\VentaDetalleResource\Pages;
use App\Filament\Resources\VentaDetalleResource\RelationManagers;

class VentaDetalleResource extends Resource
{
    protected static ?string $model = VentaDetalle::class;

    protected static ?string $navigationGroup = 'Ventas';


    protected static ?int $navigationSort = 2;


    public static function getPluralNavigationLabel(): string
    {
        return 'Detalle de Ventas';
    }

    public static function getPluralLabel(): string
    {
        return 'Detalle de Ventas';
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Select::make('venta_encabezado_id')
                    ->label('Venta Encabezado')
                    ->options(VentaEncabezado::all()->pluck('client.nombres', 'id'))
                    ->searchable(),
                Select::make('producto_id')
                    ->label('Producto')
                    ->options(Producto::all()->pluck('nombre', 'id'))
                    ->searchable(),
                Section::make('')->schema([
                    TextInput::make('cantidad')
                        ->required()
                        ->prefix('$')
                        ->numeric(),
                    TextInput::make('precio')
                        ->required()
                        ->prefix('$')
                        ->numeric(),
                    TextInput::make('total')
                        ->required()
                        ->prefix('$')
                        ->numeric(),
                ])->columns(3),
                Toggle::make('estado')->default(true)->label('Estado'),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('saleHeader.client.nombres')
                    ->numeric()
                    ->label('Encabezado')
                    ->sortable(),
                Tables\Columns\TextColumn::make('product.nombre')
                    ->numeric()
                    ->label('Producto')
                    ->sortable()
                    ->searchable(),
                Tables\Columns\TextColumn::make('cantidad')
                    ->numeric()
                    ->prefix('$')
                    ->sortable(),
                Tables\Columns\TextColumn::make('precio')
                    ->numeric()
                    ->prefix('$')
                    ->sortable(),
                Tables\Columns\TextColumn::make('total')
                    ->numeric()
                    ->prefix('$')
                    ->sortable(),
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
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
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
            'index' => Pages\ListVentaDetalles::route('/'),
            // 'create' => Pages\CreateVentaDetalle::route('/create'),
            // 'edit' => Pages\EditVentaDetalle::route('/{record}/edit'),
        ];
    }
}
