<?php

namespace App\Filament\Resources;

use App\Filament\Resources\CurrenciesResource\Pages;
use App\Filament\Resources\CurrenciesResource\RelationManagers;
use App\Models\Currencies;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class CurrenciesResource extends Resource
{
    protected static ?string $model = Currencies::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    protected static ?string $navigationGroup = 'Sinaas Settings';
    protected static ?string $navigationLabel = 'Currencies';
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
                        ->label('Currency Name')
                        ->required()
                        ->maxLength(255),
                    Forms\Components\TextInput::make('country_id')
                        ->label('Country')
                        ->required()
                        ->maxLength(255),    
                        
                    Forms\Components\TextInput::make('code')
                        ->label('Currency Code')
                        ->required()
                        ->maxLength(255), 
                    
                    Forms\Components\TextInput::make('value')
                        ->label('Currency Value')
                        ->required()
                        ->maxLength(255), 

                    Forms\Components\TextInput::make('right_symbol')
                        ->label('Currency Symbol Right')
                        ->maxLength(255), 

                    Forms\Components\TextInput::make('left_symbol')
                        ->label('Currency Symbol Left')
                        ->maxLength(255), 



                ])->columns(2),

            ])->columnSpan(['lg' => 2]),


            Forms\Components\Group::make()
            ->schema([
                Forms\Components\Section::make('Status')
                    ->schema([
                        Forms\Components\Toggle::make('status')
                            ->label('Status')
                            ->helperText('This product will be hidden from all sales channels.')
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
                    ->label('Currency Name')
                    ->searchable()
                    ->sortable(),  

                Tables\Columns\TextColumn::make('code')
                    ->label('Currency Code')
                    ->searchable()
                    ->sortable(),               

                Tables\Columns\TextColumn::make('country_id')
                    ->searchable()
                    ->sortable()
                    ->toggleable()
                    ->label('Country'),

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
            'index' => Pages\ListCurrencies::route('/'),
            'create' => Pages\CreateCurrencies::route('/create'),
            'edit' => Pages\EditCurrencies::route('/{record}/edit'),
        ];
    }
}
