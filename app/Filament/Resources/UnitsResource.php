<?php

namespace App\Filament\Resources;

use App\Filament\Resources\UnitsResource\Pages;
use App\Filament\Resources\UnitsResource\RelationManagers;
use App\Models\Units;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class UnitsResource extends Resource
{
    protected static ?string $model = Units::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    protected static ?string $navigationGroup = 'Sinaas Settings';
    protected static ?string $navigationLabel = 'Units';
    protected static ?int $navigationSort = 7;

    public static function form(Form $form): Form
    {
        return $form
        ->schema([
        
            Forms\Components\Group::make()
                ->schema([
                
                Forms\Components\Section::make('General')
                ->schema([

                    Forms\Components\TextInput::make('name')
                        ->label('Unit Name')
                        ->required()
                        ->maxLength(255),
                     
                        
                    Forms\Components\TextInput::make('code')
                        ->label('Unit Code')
                        ->required()
                        ->maxLength(255), 
                    
                    Forms\Components\TextInput::make('value')
                        ->label('Unit Value')
                        ->required()
                        ->maxLength(255), 

                    Forms\Components\TextInput::make('symbol')
                        ->label('Unit Symbol')
                        ->maxLength(255),                   



                ])->columns(2),

            ])->columnSpan(['lg' => 2]),


            Forms\Components\Group::make()
            ->schema([
                Forms\Components\Section::make('Status')
                    ->schema([
                        Forms\Components\Toggle::make('status')
                            ->label('Status')
                            ->helperText('This unit will be hidden from all sales channels.')
                            ->default(true)                                
                    ]),
                
            ])
            ->columnSpan(['lg' => 1]),

            
        ])->columns(3);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('id')
                    ->label('ID')
                    ->searchable()
                    ->sortable(),    
               
                Tables\Columns\TextColumn::make('name')
                    ->label('Unit Name')
                    ->searchable()
                    ->sortable(),  

                Tables\Columns\TextColumn::make('code')
                    ->label('Unit Code')
                    ->searchable()
                    ->sortable(),         
            

                Tables\Columns\IconColumn::make('status')->boolean(),
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
            'index' => Pages\ListUnits::route('/'),
            'create' => Pages\CreateUnits::route('/create'),
            'edit' => Pages\EditUnits::route('/{record}/edit'),
        ];
    }
}
