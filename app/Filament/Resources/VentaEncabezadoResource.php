<?php

namespace App\Filament\Resources;

use Filament\Forms;
use Filament\Tables;
use App\Models\Cliente;
use Filament\Forms\Form;
use Filament\Tables\Table;
use App\Models\VentaEncabezado;
use Filament\Resources\Resource;
use Filament\Resources\Pages\Page;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\DatePicker;
use Filament\Pages\SubNavigationPosition;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Filament\Resources\VentaEncabezadoResource\Pages;

class VentaEncabezadoResource extends Resource
{
    protected static ?string $model = VentaEncabezado::class;


    protected static ?string $navigationGroup = 'Ventas';

    protected static ?int $navigationSort = 1;

    public static function getPluralNavigationLabel(): string
    {
        return 'Ventas';
    }

    public static function getPluralLabel(): string
    {
        return 'Ventas';
    }

    protected static SubNavigationPosition $subNavigationPosition = SubNavigationPosition::End;


    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Select::make('cliente_id')
                    ->label('Cliente')
                    ->options(Cliente::all()->pluck('nombres', 'id'))
                    ->searchable(),
                DatePicker::make('fecha')
                    ->required(),
                Section::make('')->schema([
                    TextInput::make('saldo_actual')
                        ->required()
                        ->prefix('$')
                        ->numeric(),
                    TextInput::make('saldo')
                        ->required()
                        ->prefix('$')
                        ->numeric(),
                    TextInput::make('subtotal')
                        ->required()
                        ->prefix('$')
                        ->numeric(),
                    TextInput::make('iva')
                        ->required()
                        ->prefix('$')
                        ->numeric(),
                    TextInput::make('total')
                        ->required()
                        ->prefix('$')
                        ->numeric(),
                ])->columns(5),
                Toggle::make('estado')->default(true)->label('Estado'),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('user.name')
                    ->numeric()
                    ->label('Usuario')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('client.nombres')
                    ->numeric()
                    ->label('Cliente')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('saldo_actual')
                    ->numeric()
                    ->prefix('$')
                    ->sortable(),
                Tables\Columns\TextColumn::make('saldo')
                    ->numeric()
                    ->prefix('$')
                    ->sortable(),
                Tables\Columns\TextColumn::make('subtotal')
                    ->numeric()
                    ->prefix('$')
                    ->sortable(),
                Tables\Columns\TextColumn::make('iva')
                    ->numeric()
                    ->prefix('$')
                    ->sortable(),
                Tables\Columns\TextColumn::make('total')
                    ->numeric()
                    ->prefix('$')
                    ->sortable(),
                Tables\Columns\TextColumn::make('fecha')
                    ->date()
                    ->toggleable(isToggledHiddenByDefault: true)
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
            'index' => Pages\ListVentaEncabezados::route('/'),
            // 'create' => Pages\CreateVentaEncabezado::route('/create'),
            'edit' => Pages\EditVentaEncabezado::route('/{record}/edit'),
            // 'detalle' => Pages\ManageListDetalleVentas::route('/{record}/detalle'),
        ];
    }

    public static function getRecordSubNavigation(Page $page): array
    {
        return $page->generateNavigationItems([
            // ...
            Pages\EditVentaEncabezado::class,
            // Pages\ManageListDetalleVentas::class,
        ]);
    }
}
