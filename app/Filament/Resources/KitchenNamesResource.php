<?php

namespace App\Filament\Resources;

use Filament\Forms;
use Filament\Tables;
use Filament\Forms\Form;
use App\Models\Languages;
use Filament\Tables\Table;
use App\Models\KitchenNames;
use Filament\Resources\Resource;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Filament\Resources\KitchenNamesResource\Pages;
use App\Filament\Resources\KitchenNamesResource\RelationManagers;

class KitchenNamesResource extends Resource
{
    protected static ?string $model = KitchenNames::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';
    protected static ?string $navigationGroup = 'Names';
    protected static ?string $navigationLabel = 'Kitchen Names';
    protected static ?int $navigationSort = 7;

    public static function form(Form $form): Form
    {
        $schema = [];
        //$allLanguages = languages::status(1)->get();
        $allLanguages = Languages::orderBy('id')->get();
        foreach($allLanguages as $lang)
        {
            $schema[] =  Forms\Components\TextInput::make('name_'.$lang->code)
                                            ->label($lang->name.' Language')
                                            ->required()
                                            ->live(onBlur: true);
        }  

        return $form
            ->schema([
                Forms\Components\TextInput::make('name')
                    ->label('Product Name')
                    ->required()
                    ->live(onBlur: true), 

                Forms\Components\Section::make('Languages Input')
                    ->schema($schema)
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('id')
                    ->label('ID'),

                 Tables\Columns\TextColumn::make('name')
                    ->searchable()
                    ->sortable()
                    ->toggleable(),
                    Tables\Columns\TextColumn::make('name_en')
                    ->label('English')
                    ->searchable()
                    ->sortable(),
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
            'index' => Pages\ListKitchenNames::route('/'),
            'create' => Pages\CreateKitchenNames::route('/create'),
            'edit' => Pages\EditKitchenNames::route('/{record}/edit'),
        ];
    }
}
