<?php

namespace App\Filament\Resources;

use Filament\Forms;
use Filament\Tables;
use Filament\Forms\Form;
use App\Models\Languages;
use App\Models\ImageNames;
use Filament\Tables\Table;
use Filament\Resources\Resource;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Filament\Resources\ImageNamesResource\Pages;
use App\Filament\Resources\ImageNamesResource\RelationManagers;

class ImageNamesResource extends Resource
{
    protected static ?string $model = ImageNames::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';
    protected static ?string $navigationGroup = 'Names';
    protected static ?string $navigationLabel = 'Image Names';
    protected static ?int $navigationSort = 7;

    public static function form(Form $form): Form
    {
        $schema = [];
        //$allLanguages = languages::status(1)->get();
        /*$allLanguages = Languages::orderBy('id')->get();
        foreach($allLanguages as $lang)
        {
            $schema[] =  Forms\Components\TextInput::make('name_'.$lang->code)
                    ->label($lang->name.' Language')
                    ->required()
                    ->live(onBlur: true);
        }*/

        return $form
            ->schema([
                Forms\Components\TextInput::make('name')
                    ->label('Image Name')
                    ->required()
                    ->live(onBlur: true),

                Forms\Components\Select::make('tags')
                    ->label('Product Name ID')
                    ->relationship(name:'productnames', titleAttribute:'name_en')
                    ->required()
                    ->live(onBlur: true)
                    ->searchable()
                    ->multiple(),  

                Forms\Components\FileUpload::make('image')
                    ->image()
                    ->imageEditor(),       
                                   
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\ImageColumn::make('image')
                    ->label('Image'),
                //Tables\Columns\TextColumn::make('name_id')
                    //->label('Name'),

                Tables\Columns\TextColumn::make('name')
                    ->searchable()
                    ->sortable()
                    ->toggleable()
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
            'index' => Pages\ListImageNames::route('/'),
            'create' => Pages\CreateImageNames::route('/create'),
            'edit' => Pages\EditImageNames::route('/{record}/edit'),
        ];
    }
}
