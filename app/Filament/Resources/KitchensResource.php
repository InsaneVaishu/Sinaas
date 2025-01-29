<?php

namespace App\Filament\Resources;

use App\Filament\Resources\KitchensResource\Pages;
use App\Filament\Resources\KitchensResource\RelationManagers;
use App\Models\Kitchens;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class KitchensResource extends Resource
{
    protected static ?string $model = Kitchens::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';
    protected static ?string $navigationGroup = 'Catalogue';
    protected static ?string $navigationLabel = 'Kitchens';
    protected static ?int $navigationSort = 5;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([                           
                Forms\Components\Group::make()
                ->schema([        

                    Forms\Components\Section::make('General')
                        ->schema([
                            Forms\Components\Select::make('kitchenname_id')
                                ->label('Kitchen Name ID')
                                ->relationship(name:'kitchennames', titleAttribute:'name_en')
                                ->required()
                                ->live(onBlur: true)
                                ->searchable(),  
                                                           
                        ])
                        ->columns(2),
                ])
                ->columnSpan(['lg' => 2]),                                  


            Forms\Components\Group::make()
                ->schema([
                    Forms\Components\Section::make('Status')
                        ->schema([
                            Forms\Components\Toggle::make('status')
                                ->label('Status')
                                ->helperText('This inventory will be hidden from all sales channels.')
                                ->default(true)                                
                        ]),              
                    
                ])
                ->columnSpan(['lg' => 1]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('id')
                    ->label('ID')
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('kitchenname.name')
                    ->searchable()
                    ->sortable()
                    ->toggleable()
                    ->label('Name'),

                

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
            'index' => Pages\ListKitchens::route('/'),
            'create' => Pages\CreateKitchens::route('/create'),
            'edit' => Pages\EditKitchens::route('/{record}/edit'),
        ];
    }
}
