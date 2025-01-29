<?php

namespace App\Filament\Resources;

use Filament\Forms;
use Filament\Forms\Form;
use Filament\Tables;
use Filament\Tables\Table;
use App\Models\Categories;
use Filament\Resources\Resource;
use Filament\Forms\Components\FileUpload;
use Illuminate\Database\Eloquent\Builder;
use App\Filament\Resources\CategoriesResource\Pages;
use Illuminate\Database\Eloquent\SoftDeletingScope;


class CategoriesResource extends Resource
{
    protected static ?string $model = Categories::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    protected static ?string $navigationGroup = 'Catalogue';
    protected static ?string $navigationLabel = 'Categories';
    protected static ?int $navigationSort = 2;
  
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
                                ->relationship(name:'productnames', titleAttribute:'name_en')
                                ->required()
                                ->live(onBlur: true)
                                ->searchable(),     
                                
                            Forms\Components\Select::make('business_id')
                                ->relationship(name:'businesses', titleAttribute:'name')
                                ->required()
                                ->live(onBlur: true)
                                ->searchable(),
                                //->hiddenOn(BusinessesRelationManager::class),
                                        
                        ])->columns(2),
             
                    
                    Forms\Components\Section::make('Images')
                        ->schema([


                            Forms\Components\FileUpload::make('category_image')
                                ->label('Category Image')
                                ->image()
                                ->directory('categories')
                                ->imageEditor(), 


                            Forms\Components\FileUpload::make('categorybg_image')
                                ->label('Category BG Image')
                                ->image()
                                ->directory('categories_bg')
                                ->imageEditor(), 
                                
                                
                        ])
                        ->columns(2), 
                            
                        
                           
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
        ])
        ->columns(3);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('id')
                    ->label('ID')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\ImageColumn::make('category_image')
                    ->label('Image'),

                Tables\Columns\TextColumn::make('categoryname.name')
                    ->searchable()
                    ->sortable()
                    ->toggleable()
                    ->label('Name'),

                Tables\Columns\TextColumn::make('business.name')
                    ->searchable()
                    ->sortable()
                    ->toggleable()
                    ->label('Business'),
                
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
            'index' => Pages\ListCategories::route('/'),
            'create' => Pages\CreateCategories::route('/create'),
            'edit' => Pages\EditCategories::route('/{record}/edit'),
        ];
    }
}
