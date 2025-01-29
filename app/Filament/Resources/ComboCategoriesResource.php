<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ComboCategoriesResource\Pages;
use App\Filament\Resources\ComboCategoriesResource\RelationManagers;
use App\Models\ComboCategories;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class ComboCategoriesResource extends Resource
{
    protected static ?string $model = ComboCategories::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';
    protected static ?string $navigationGroup = 'Combos';
    protected static ?string $navigationLabel = 'Combo Categories';
    protected static ?int $navigationSort = 6;

    public static function form(Form $form): Form
    {
        return $form
        ->schema([
            Forms\Components\Group::make()
                ->schema([              

                    Forms\Components\Section::make('General')
                        ->schema([
                            Forms\Components\Select::make('categoryname_id')
                                ->label('Category Name')
                                ->relationship(name:'categoryname',titleAttribute:'name',)
                                ->required()
                                ->live(onBlur: true)
                                ->searchable()->preload(),
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
                                ->helperText('This product will be hidden from all sales channels.')
                                ->default(true)                                
                        ]),
                    
                ])
                ->columnSpan(['lg' => 1]),
        ])
        ->columns(3);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('id')
                    ->searchable()
                    ->sortable()
                    ->toggleable()
                    ->label('Category ID'),
                    
                    Tables\Columns\TextColumn::make('categoryname.name')
                    ->searchable()
                    ->sortable()
                    ->toggleable()
                    ->label('Name'),         
                    
                Tables\Columns\IconColumn::make('status')->boolean()->sortable()->toggleable(),
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
            'index' => Pages\ListComboCategories::route('/'),
            'create' => Pages\CreateComboCategories::route('/create'),
            'edit' => Pages\EditComboCategories::route('/{record}/edit'),
        ];
    }
}
