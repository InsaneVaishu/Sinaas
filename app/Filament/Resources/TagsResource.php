<?php

namespace App\Filament\Resources;

use App\Filament\Resources\TagsResource\Pages;
use App\Filament\Resources\TagsResource\RelationManagers;
use App\Models\Tags;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class TagsResource extends Resource
{
    protected static ?string $model = Tags::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';
    protected static ?string $navigationGroup = 'Catalogue';
    protected static ?string $navigationLabel = 'Tags';
    protected static ?int $navigationSort = 8;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([                           
                Forms\Components\Group::make()
                ->schema([        

                    Forms\Components\Section::make('General')
                        ->schema([
                            Forms\Components\Select::make('tagname_id')
                                ->label('Tag Name ID')
                                ->relationship(name:'tagname', titleAttribute:'name_en')
                                ->required()
                                ->live(onBlur: true)
                                ->searchable(),  
                                                                                    
                                        
                            Forms\Components\FileUpload::make('tag_image')
                                ->image()
                                ->directory('tags')
                                ->imageEditor(), 
                        
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
                                ->helperText('This tag will be hidden from all sales channels.')
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

                    Tables\Columns\TextColumn::make('tagname.name')
                    ->searchable()
                    ->sortable()
                    ->toggleable()
                    ->label('Name'),

                Tables\Columns\ImageColumn::make('tag_image')
                    ->label('Image'),
                //Tables\Columns\TextColumn::make('name_id')
                    //->label('Name'),

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
            'index' => Pages\ListTags::route('/'),
            'create' => Pages\CreateTags::route('/create'),
            'edit' => Pages\EditTags::route('/{record}/edit'),
        ];
    }
}
