<?php

namespace App\Filament\Resources;

use Filament\Forms;
use Filament\Tables;
use App\Models\Cliente;
use Filament\Forms\Form;
use Filament\Tables\Table;
use Filament\Resources\Resource;
use Filament\Notifications\Notification;
use Illuminate\Database\Eloquent\Builder;
use App\Filament\Resources\ClienteResource\Pages;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Filament\Resources\ClienteResource\RelationManagers;

class ClienteResource extends Resource
{
    protected static ?string $model = Cliente::class;

    protected static ?string $navigationGroup = 'Clientes';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\FileUpload::make('archivo')
                    ->label('Archivo CSV')
                    ->required(),
            ])->columns(2);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('cedula')
                    ->searchable(),
                Tables\Columns\TextColumn::make('nombres')
                    ->searchable(),
                Tables\Columns\TextColumn::make('valor')
                    ->searchable(),
                Tables\Columns\TextColumn::make('cpl')
                    ->searchable(),
                Tables\Columns\TextColumn::make('pabellon')
                    ->searchable(),
                Tables\Columns\IconColumn::make('estado')
                    ->boolean(),
                Tables\Columns\TextColumn::make('created_at')
                    ->label('Fecha de Creación')
                    ->date()
                    ->sortable(),
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
                Tables\Actions\EditAction::make()->label('Editar')
                    ->form([
                        Forms\Components\Select::make('tipo_identificacion')
                        ->options([
                            '1' => 'Cédula',  
                            '2' => 'Pasaporte',
                        ])
                        ->required()
                        ->placeholder('Selecciona el tipo de identificación'),
                        Forms\Components\TextInput::make('cedula')
                            ->required()
                            ->maxLength(255),
                        Forms\Components\TextInput::make('nombres')
                            ->required()
                            ->maxLength(255),
                        Forms\Components\Select::make('genero')
                            ->options([
                                '1' => 'Masculino',  
                                '2' => 'Femenino',
                            ])
                            ->required()
                            ->placeholder('Selecciona el género'),

                        Forms\Components\Select::make('nacionalidad')
                            ->options([
                                'ECUATORIANA' => 'ECUATORIANA',  
                                'VENEZOLANA' => 'VENEZOLANA',
                                'COLOMBIANA' => 'COLOMBIANA',
                                'ALBANES' => 'ALBANES',
                                'SERBIA' => 'SERBIA',
                            ])
                            ->required()
                            ->placeholder('Selecciona la nacionalidad'),
                        Forms\Components\TextInput::make('valor')
                            ->required()
                            ->maxLength(255),
                        Forms\Components\TextInput::make('cpl')
                            ->maxLength(255),
                        Forms\Components\TextInput::make('pabellon')
                            ->maxLength(255),
                        Forms\Components\Toggle::make('estado')
                            ->required(),
                    ])->successNotification(
                        Notification::make()
                            ->success()
                            ->title('Cliente Actualizado')
                            ->body('El cliente se actualizo con exito.'),
                    ),
            ])
            ->headerActions([
                Tables\Actions\CreateAction::make()
                ->label('Nuevo')
                ->modalHeading('Crear Nuevo Cliente')
                ->modalButton('Crear')
                ->form([
                    Forms\Components\Select::make('tipo_identificacion')
                    ->options([
                        '1' => 'Cédula',	
                        '2' => 'Pasaporte',
                    ])
                    ->required()
                    ->label('Tipo de identificación')
                    ->placeholder('Selecciona el tipo de identificación'),
                    Forms\Components\TextInput::make('cedula')
                        ->label('Cédula')
                        ->unique('clientes', 'cedula')
                        ->validationMessages([
                            'unique' => 'La cédula ya está en uso.',
                            'required' => 'La cédula es requerida.',
                        ])
                        ->required(),
                    Forms\Components\TextInput::make('nombres')
                        ->label('Nombres')
                        ->unique('clientes', 'nombres')
                        ->validationMessages([
                            'unique' => 'El nombre ya está en uso.',
                            'required' => 'El nombre es requerido.',
                        ])
                        ->required(),
                    Forms\Components\Select::make('genero')
                    ->options([
                        '1' => 'Masculino',	
                        '2' => 'Femenino',
                    ])
                    ->required()
                    ->label('Género')	
                    ->placeholder('Selecciona el género'),
                    Forms\Components\Select::make('nacionalidad')
                    ->options([
                        'ECUATORIANA' => 'ECUATORIANA',	
                        'VENEZOLANA' => 'VENEZOLANA',
                        'COLOMBIANA' => 'COLOMBIANA',
                        'ALBANES' => 'ALBANES',
                        'SERBIA' => 'SERBIA',
                    ])
                    ->required()
                    ->label('Nacionalidad')	
                    ->placeholder('Selecciona la nacionalidad'),
                    Forms\Components\TextInput::make('nacionalidad')
                        ->label('Nacionalidad')
                        ->required(),
                    Forms\Components\TextInput::make('valor')
                        ->label('Valor')
                        ->required(),
                    Forms\Components\TextInput::make('cpl')
                        ->label('CPL')
                        ->required(),
                    Forms\Components\TextInput::make('pabellon')
                        ->label('Pabellón')
                        ->required(),
                    Forms\Components\Toggle::make('estado')
                        ->label('Estado')
                        ->default(true),
                ])
                ->successNotification(
                    Notification::make()
                        ->success()
                        ->title('Cliente Creado')
                        ->body('El cliente se creó con éxito.')
                ),
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
            'index' => Pages\ListClientes::route('/'),
            // 'create' => Pages\CreateCliente::route('/create'),
            // 'edit' => Pages\EditCliente::route('/{record}/edit'),
        ];
    }
}
