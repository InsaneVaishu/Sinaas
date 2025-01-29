<?php

namespace App\Filament\Resources;

use App\Filament\Resources\CuisinesResource\Pages;
use App\Filament\Resources\CuisinesResource\RelationManagers;
use App\Models\Cuisines;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class CuisinesResource extends Resource
{
    protected static ?string $model = Cuisines::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';
    protected static ?string $navigationGroup = 'Catalogue';
    protected static ?string $navigationLabel = 'Cuisines';
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
                        ->label('Common Name')
                        ->required()
                        ->maxLength(255),
                    Forms\Components\TextInput::make('name_en')
                        ->label('Name English')
                        ->required()
                        ->maxLength(255),    
                        
                    Forms\Components\TextInput::make('name_es')
                        ->label('Name Spanish')
                        ->required()
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
                    ->label('Common Name')
                    ->searchable()
                    ->sortable(),  

                Tables\Columns\TextColumn::make('name_en')
                    ->label('English')
                    ->sortable(),               

                Tables\Columns\TextColumn::make('name_es')
                ->label('English')
                    ->searchable(),

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
            'index' => Pages\ListCuisines::route('/'),
            'create' => Pages\CreateCuisines::route('/create'),
            'edit' => Pages\EditCuisines::route('/{record}/edit'),
        ];
    }
}
