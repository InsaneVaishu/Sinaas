<?php

namespace App\Filament\Resources;

use App\Filament\Resources\SubCategoriesResource\Pages;
use App\Filament\Resources\SubCategoriesResource\RelationManagers;
use App\Models\SubCategories;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class SubCategoriesResource extends Resource
{
    protected static ?string $model = SubCategories::class;

    protected static ?string $navigationGroup = 'Catalogue';
    protected static ?string $navigationLabel = 'Sub Categories';   
    protected static ?string $navigationIcon = 'heroicon-o-bolt';
    protected static ?int $navigationSort = 3;

    public static function form(Form $form): Form
    {
        return $form
        ->schema([
            Forms\Components\Group::make()
                ->schema([              

                    Forms\Components\Section::make('General')
                        ->schema([
                            Forms\Components\Select::make('subcategoryname_id')
                                ->label('Sub Category Name')
                                ->relationship(name:'subcategoryname', titleAttribute:'name')
                                ->required()
                                ->live(onBlur: true)
                                ->searchable(),     
                                
                            Forms\Components\Select::make('category_id')
                                ->label('Parent Category')
                                ->relationship(name:'category', titleAttribute:'products_names.name')
                                ->required()
                                ->live(onBlur: true)
                                ->searchable(),
                                        
                        ])
                        ->columns(2),
             
                    
                        Forms\Components\Section::make('Image')
                            ->schema([

                            Forms\Components\FileUpload::make('subcategory_image')
                                ->label('Sub Category Image')
                                ->image()
                                ->imageEditor(),
                        ]), 
                            
                        
                           
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
                    ->label('ID')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\ImageColumn::make('subcategory_image')
                    ->label('Image'),

                Tables\Columns\TextColumn::make('subcategoryname.name')
                    ->searchable()
                    ->sortable()
                    ->toggleable()
                    ->label('Name'),

                /*Tables\Columns\TextColumn::make('status')
                ->searchable()
                ->modifyQueryUsing(fn (Builder $query) => $query->where('status', true)->withoutGlobalScopes()),*/


                /*Tables\Columns\TextColumn::make('category_id')
                    ->getStateUsing(fn ($record): ?string => Category::find($record->products_names->first()?->category)?->name ?? null),*/


                Tables\Columns\IconColumn::make('status')->boolean(),



                /*Tables\Columns\TextColumn::make('category.products_names.name_en')
                    ->searchable()
                    ->sortable()
                    ->toggleable()
                    ->label('Parent Category'),*/
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
            'index' => Pages\ListSubCategories::route('/'),
            'create' => Pages\CreateSubCategories::route('/create'),
            'edit' => Pages\EditSubCategories::route('/{record}/edit'),
        ];
    }
}
