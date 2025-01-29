<?php

namespace App\Filament\Resources;

use Filament\Forms;
use Filament\Tables;
use Filament\Forms\Form;
use App\Models\Languages;

use Filament\Tables\Table;
use App\Models\Description;
use Filament\Resources\Resource;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Filament\Resources\DescriptionResource\Pages;
use App\Filament\Resources\DescriptionResource\RelationManagers;

class DescriptionResource extends Resource
{
    protected static ?string $model = Description::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';
    protected static ?string $navigationGroup = 'Names';
    protected static ?string $navigationLabel = 'Description';
    protected static ?int $navigationSort = 6;

    public static function form(Form $form): Form
    {
        $schema = [];
        //$allLanguages = languages::status(1)->get();
        $allLanguages = Languages::orderBy('id')->get();
        foreach($allLanguages as $lang)
        {
            $schema[] =  Forms\Components\Textarea::make('description_'.$lang->code)
                                            ->label($lang->name.' Language')
                                            ->required()
                                            ->live(onBlur: true);                            
                
        }  

        return $form
            ->schema([

                Forms\Components\Textarea::make('description')
                    ->label('Description')
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
                    ->label('ID')
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('description')
                    ->label('Name')
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('description_en')
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
            'index' => Pages\ListDescriptions::route('/'),
            'create' => Pages\CreateDescription::route('/create'),
            'edit' => Pages\EditDescription::route('/{record}/edit'),
        ];
    }
}
