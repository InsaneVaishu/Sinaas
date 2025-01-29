<?php

namespace App\Filament\Resources;

use Filament\Forms;
use Filament\Tables;
use App\Models\Combo;
use Filament\Forms\Get;
use Filament\Forms\Set;
use App\Models\Products;
use Filament\Forms\Form;
use Filament\Tables\Table;
use Filament\Resources\Resource;
use App\Models\ComboSubCategories;
use Illuminate\Support\Collection;
use Illuminate\Database\Eloquent\Builder;
use App\Filament\Resources\ComboResource\Pages;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Filament\Resources\ComboResource\RelationManagers;

class ComboResource extends Resource
{
    protected static ?string $model = Combo::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';
    protected static ?string $navigationGroup = 'Combos';
    protected static ?string $navigationLabel = 'Product Combo';
    protected static ?int $navigationSort = 6;

    public static function form(Form $form): Form
    {
        return $form
        ->schema([
            Forms\Components\Group::make()
                ->schema([              

                    Forms\Components\Section::make('General')
                        ->schema([
                            Forms\Components\Select::make('product_id')
                                ->label('Combo for Product')
                                ->relationship(name:'products',titleAttribute:'name',)
                                ->required()
                                ->live(onBlur: true)
                                ->searchable(),   
                                
                            Forms\Components\Select::make('maincategory_id')
                                ->label('Combo Category')
                                ->relationship(name:'combocategories',titleAttribute:'name')
                                ->required()
                                ->live()
                                ->afterStateUpdated(fn (Set $set) => $set('category_id', null))
                                ->searchable()->preload(),
                            
                            Forms\Components\Select::make('category_id')
                                ->label('Combo Sub Category')
                                ->options(fn (Get $get): Collection => ComboSubCategories::query()
                                    ->leftJoin('products_names', 'products_names.id', '=', 'combo_sub_categories.subcategoryname_id')
                                    ->where('category_id', $get('maincategory_id'))
                                    ->pluck('products_names.name', 'combo_sub_categories.id'))
                                ->required()
                                ->live()
                                ->searchable()->preload(),
                                                        
                        ])
                        ->columns(2),                               
                    


                        Forms\Components\Section::make('General')
                        ->schema([
                            Forms\Components\TextInput::make('min')
                                ->label('Min')
                                ->required()->numeric()
                                ->minValue(0)
                                ->live(onBlur: true),
                            Forms\Components\TextInput::make('max')
                                ->label('Max')
                                ->required()->numeric()
                                ->minValue(0)
                                ->live(onBlur: true),                               
                                  
                            
                        ])
                        ->columns(2),



                        Forms\Components\Section::make()
                            ->schema([                              
                                        
                            Forms\Components\Repeater::make('ProductsCombos') 
                                ->relationship()
                                ->reactive()
                               // ->defaultItems(0)
                                ->schema([                  
                                    
                                    Forms\Components\Select::make('combo_product_id')
                                    ->label('Combo Products')
                                   // ->relationship(name:'stocks', titleAttribute:'name')
                                    ->options(Products::query()->leftJoin('products_names', 'products_names.id', '=', 'products.productname_id')->select('products_names.name AS name', 'products.id')->orderBy('products_names.name')->pluck('products_names.name', 'products.id'))
                                    ->required()
                                    ->reactive()
                                    ->distinct()
                                    ->searchable()
                                    ->live()
                                    ->disableOptionsWhenSelectedInSiblingRepeaterItems()
                                    /*->afterStateUpdated(function (Forms\Set $set, ?string $state) {
                                        //$set('../../stock', $state);
                                    })*/
                                    
                                    ->preload(),

                                   
                                    
                                    Forms\Components\TextInput::make('price')->label('Price')->numeric()
                                    ->minValue(0)
                                    ->readOnly(fn(callable $get) => $get('included') == 1),

                                    Forms\Components\Toggle::make('included')
                                    //->afterStateUpdated(fn (Set $set) => $set('price', null))
                                    ->afterStateUpdated(function (Forms\Set $set, ?string $state) {
                                        if($state=='1'){
                                            $set('price', '0');
                                       }
                                    })
                                    ->default(true)

                            ])->columns(3)  
                                    
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
                    ->searchable()
                    ->sortable()
                    ->toggleable()
                    ->label('Category ID'),
                    
                Tables\Columns\TextColumn::make('product.name')
                    ->searchable()
                    ->sortable()
                    ->toggleable()
                    ->label('Product'),
                  
                /*Tables\Columns\TextColumn::make('category.name')
                    ->searchable()
                    ->sortable()
                    ->toggleable()
                    ->label('Category Name'),   */
                
                /*Tables\Columns\TextColumn::make('product_id')
                    ->getStateUsing(fn ($record): ?string => Products::find($record->products_names->first()?->productname_id)?->name ?? null),        */                       
                    
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
            'index' => Pages\ListCombos::route('/'),
            'create' => Pages\CreateCombo::route('/create'),
            'edit' => Pages\EditCombo::route('/{record}/edit'),
        ];
    }
}
