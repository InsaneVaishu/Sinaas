<?php

namespace App\Filament\Resources;

use Filament\Forms;
use Filament\Tables;
use App\Models\Products;
use Filament\Forms\Form;
use Filament\Tables\Table;
use Filament\Resources\Resource;
use Filament\Forms\Components\Tabs;
use Filament\Forms\Components\FileUpload;
use Illuminate\Database\Eloquent\Builder;
use App\Filament\Resources\ProductsResource\Pages;
use Illuminate\Database\Eloquent\SoftDeletingScope;


class ProductsResource extends Resource
{
    protected static ?string $model = Products::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';
    protected static ?string $navigationGroup = 'Catalogue';
    protected static ?string $navigationLabel = 'Products';
    protected static ?int $navigationSort = 1;


    public static function form(Form $form): Form
    {
        
        return $form
            ->schema([
                Forms\Components\Group::make()
                    ->schema([              

                        Forms\Components\Section::make('General')
                            ->schema([
                                Forms\Components\Select::make('productname_id')
                                    ->label('Product Name ID')
                                    ->relationship(name:'productname', titleAttribute:'name_en')
                                    ->required()
                                    ->live(onBlur: true)
                                    ->searchable()->preload(),     
                                    
                                    Forms\Components\Select::make('business_id')
                                    ->relationship(name:'business', titleAttribute:'name')
                                    ->required()
                                    ->live(onBlur: true)
                                    ->searchable()->preload(),
                                    //->hiddenOn(BusinessesRelationManager::class),


                                    Forms\Components\Select::make('description_id')
                                    ->relationship(name:'description', titleAttribute:'description')
                                    ->required()
                                    ->live(onBlur: true)
                                    ->searchable()->preload(),

                                           
                                    Forms\Components\FileUpload::make('image')
                                    ->image()
                                    ->directory('products')
                                    ->imageEditor(), 
                            
                            ])
                            ->columns(2),

                       
                        
                        Forms\Components\Section::make('Price & Tax')
                            ->schema([


                                Forms\Components\TextInput::make('price')
                                    ->label('Product Price')
                                    ->maxLength(100)
                                    ->live(onBlur: true),                               
                                    
                                Forms\Components\Select::make('tax_id')
                                    ->relationship(name:'tax', titleAttribute:'name')
                                    ->required()
                                    ->live(onBlur: true)
                                    ->searchable(),
                                    
                                    
                            ])
                            ->columns(2), 

 

                            Forms\Components\Section::make('Relations')
                            ->schema([   

                                /*Forms\Components\Select::make('inventories')
                                    ->label('Stocks')
                                    ->relationship(name:'inventorynames', titleAttribute:'name')
                                    ->live(onBlur: true)
                                    ->multiple()
                                    ->reactive()
                                    ->searchable(), 

                                Forms\Components\Select::make('kitchens')
                                    ->label('Kitchens')
                                    ->relationship(name:'kitchennames', titleAttribute:'name')
                                    ->multiple()
                                    ->live(onBlur: true)
                                    ->searchable(),*/
                                
                                
                                Forms\Components\Select::make('categories')
                                    ->label('Product Categories')
                                    ->relationship(name: 'categories',
                                    titleAttribute: 'products_names.name',
                                    modifyQueryUsing: fn (Builder $query, callable $get) => $query->leftJoin('products_names', 'products_names.id', '=', 'categories.categoryname_id')->select('products_names.name', 'categories.id')->orderBy('products_names.name'))
                                    ->multiple()->preload(),

                                    //->where('product_categories.product_id', $get('id'))

                                Forms\Components\Select::make('kitchens')
                                    ->label('Product Kitchens')
                                    ->relationship(name: 'kitchens',
                                    titleAttribute: 'kitchen_names.name',
                                    modifyQueryUsing: fn (Builder $query, callable $get) => $query->leftJoin('kitchen_names', 'kitchen_names.id', '=', 'kitchens.kitchenname_id')->select('kitchen_names.name', 'kitchens.id')->orderBy('kitchen_names.name'))
                                    ->multiple()->preload(),


                                Forms\Components\Select::make('tags')
                                    ->label('Product Tags')
                                    ->relationship(name: 'tags',
                                    titleAttribute: 'products_names.name',
                                    modifyQueryUsing: fn (Builder $query, callable $get) => $query->leftJoin('products_names', 'products_names.id', '=', 'tags.tagname_id')->select('products_names.name', 'tags.id')->orderBy('products_names.name'))
                                    ->multiple()->preload(),

                                Forms\Components\Select::make('stocks')
                                    ->label('Product Stocks')
                                    ->relationship(name: 'stocks',
                                    titleAttribute: 'inventory_names.name',
                                    modifyQueryUsing: fn (Builder $query, callable $get) => $query->leftJoin('inventories', 'inventories.id', '=', 'stocks.inventory_id')->leftJoin('inventory_names', 'inventory_names.id', '=', 'inventories.inventoryname_id')->select('inventory_names.name', 'stocks.id')->orderBy('inventory_names.name'))
                                    ->multiple()->preload(),

                                //->where('product_kitchens.product_id', $get('id'))

                                /*Forms\Components\Select::make('inventories')
                                    ->label('Product Inventories')
                                    ->relationship(name: 'inventories',
                                    titleAttribute: 'products_names.name',
                                    modifyQueryUsing: fn (Builder $query, callable $get) => $query->leftJoin('products_names', 'products_names.id', '=', 'categories.categoryname_id')->select('products_names.name', 'categories.id')->orderBy('products_names.name')->where('product_categories.product_id', $get('id')),
                                    )
                                    ->multiple(),*/                                    
                            ])
                            ->columns(2),



                            Forms\Components\Section::make('Advanced Price')
                            ->schema([
                                Tabs::make('Tabs')
                                    ->tabs([
                                        Tabs\Tab::make('All')
                                        ->schema([ 
                                            Forms\Components\Repeater::make('productpriceAll') 
                                                ->relationship()
                                                ->reactive()
                                                ->defaultItems(0)
                                                ->schema([

                                                    Forms\Components\Select::make('price_type')
                                                    ->required()
                                                    ->live(onBlur: true)
                                                    ->options([
                                                        '1' => 'All',
                                                        '2' => 'Eat-in',
                                                        '3' => 'Take Away',
                                                        '4' => 'Delivery'
                                                    ]),
                                                    Forms\Components\TextInput::make('price')
                                                    ->maxLength(100)
                                                    ->live(onBlur: true),
                                                    Forms\Components\TimePicker::make('start_time')->seconds(false),
                                                    Forms\Components\TimePicker::make('end_time')->seconds(false),
                                                    Forms\Components\Hidden::make('day')
                                                    ->default('Monday')
                                                ])->columns(2)
                                        ]),
                                        Tabs\Tab::make('Monday')
                                        ->schema([ 
                                            Forms\Components\Repeater::make('productpriceMonday') 
                                                ->relationship()
                                                ->reactive()
                                                ->defaultItems(0)
                                                ->schema([
                                                    Forms\Components\Select::make('price_type')
                                                    ->required()
                                                    ->live(onBlur: true)
                                                    ->options([
                                                        '1' => 'All',
                                                        '2' => 'Eat-in',
                                                        '3' => 'Take Away',
                                                        '4' => 'Delivery'
                                                    ]),
                                                    Forms\Components\TextInput::make('price')
                                                    ->maxLength(100)
                                                    ->live(onBlur: true),
                                                    Forms\Components\TimePicker::make('start_time')->seconds(false),
                                                    Forms\Components\TimePicker::make('end_time')->seconds(false),
                                                    Forms\Components\Hidden::make('day')
                                                    ->default('Monday')
                                                ])->columns(2)
                                        ]),
                                        Tabs\Tab::make('Tuesday')
                                            ->schema([
                                                Forms\Components\Repeater::make('productpriceTuesday')
                                                ->relationship()
                                                ->reactive()
                                                ->defaultItems(0)
                                                ->schema([
                                                    Forms\Components\Select::make('price_type')
                                                    ->required()
                                                    ->live(onBlur: true)
                                                    ->options([
                                                        '1' => 'All',
                                                        '2' => 'Eat-in',
                                                        '3' => 'Take Away',
                                                        '4' => 'Delivery'
                                                    ]),
                                                    Forms\Components\TextInput::make('price')
                                                    ->maxLength(100)
                                                    ->live(onBlur: true),
                                                    Forms\Components\TimePicker::make('start_time')->seconds(false),
                                                    Forms\Components\TimePicker::make('end_time')->seconds(false),
                                                    Forms\Components\Hidden::make('day')
                                                    ->default('Tuesday')
                                                ])->columns(2)
                                            ]),
                                        Tabs\Tab::make('Wednesday')
                                            ->schema([
                                                Forms\Components\Repeater::make('productpriceWednesday')
                                                ->relationship()
                                                ->reactive()
                                                ->defaultItems(0)
                                                ->schema([
                                                    Forms\Components\Select::make('price_type')
                                                    ->required()
                                                    ->live(onBlur: true)
                                                    ->options([
                                                        '1' => 'All',
                                                        '2' => 'Eat-in',
                                                        '3' => 'Take Away',
                                                        '4' => 'Delivery'
                                                    ]),
                                                    Forms\Components\TextInput::make('price')
                                                    ->maxLength(100)
                                                    ->live(onBlur: true),
                                                    Forms\Components\TimePicker::make('start_time')->seconds(false),
                                                    Forms\Components\TimePicker::make('end_time')->seconds(false),
                                                    Forms\Components\Hidden::make('day')
                                                    ->default('Wednesday')
                                                ])->columns(2)
                                            ]),
                                            Tabs\Tab::make('Thursday')
                                            ->schema([
                                                Forms\Components\Repeater::make('productpriceThursday')
                                                ->relationship()
                                                ->reactive()
                                                ->defaultItems(0)
                                                ->schema([
                                                    Forms\Components\Select::make('price_type')
                                                    ->required()
                                                    ->live(onBlur: true)
                                                    ->options([
                                                        '1' => 'All',
                                                        '2' => 'Eat-in',
                                                        '3' => 'Take Away',
                                                        '4' => 'Delivery'
                                                    ]),
                                                    Forms\Components\TextInput::make('price')
                                                    ->maxLength(100)
                                                    ->live(onBlur: true),
                                                    Forms\Components\TimePicker::make('start_time')->seconds(false),
                                                    Forms\Components\TimePicker::make('end_time')->seconds(false),
                                                    Forms\Components\Hidden::make('day')
                                                    ->default('Thursday')
                                                ])->columns(2)
                                            ]),
                                            Tabs\Tab::make('Friday')
                                            ->schema([
                                                Forms\Components\Repeater::make('productpriceFriday')
                                                ->relationship()
                                                ->reactive()
                                                ->defaultItems(0)
                                                ->schema([
                                                    Forms\Components\Select::make('price_type')
                                                    ->required()
                                                    ->live(onBlur: true)
                                                    ->options([
                                                        '1' => 'All',
                                                        '2' => 'Eat-in',
                                                        '3' => 'Take Away',
                                                        '4' => 'Delivery'
                                                    ]),
                                                    Forms\Components\TextInput::make('price')
                                                    ->maxLength(100)
                                                    ->live(onBlur: true),
                                                    Forms\Components\TimePicker::make('start_time')->seconds(false),
                                                    Forms\Components\TimePicker::make('end_time')->seconds(false),
                                                    Forms\Components\Hidden::make('day')
                                                    ->default('Friday')
                                                ])->columns(2)
                                            ]),
                                            Tabs\Tab::make('Saturday')
                                            ->schema([
                                                Forms\Components\Repeater::make('productpriceSaturday')
                                                ->relationship()
                                                ->reactive()
                                                ->defaultItems(0)
                                                ->schema([
                                                    Forms\Components\Select::make('price_type')
                                                    ->required()
                                                    ->live(onBlur: true)
                                                    ->options([
                                                        '1' => 'All',
                                                        '2' => 'Eat-in',
                                                        '3' => 'Take Away',
                                                        '4' => 'Delivery'
                                                    ]),
                                                    Forms\Components\TextInput::make('price')
                                                    ->maxLength(100)
                                                    ->live(onBlur: true),
                                                    Forms\Components\TimePicker::make('start_time')->seconds(false),
                                                    Forms\Components\TimePicker::make('end_time')->seconds(false),
                                                    Forms\Components\Hidden::make('day')
                                                    ->default('Saturday')
                                                ])->columns(2)
                                            ]),
                                            Tabs\Tab::make('Sunday')
                                            ->schema([
                                                Forms\Components\Repeater::make('productpriceSunday')
                                                ->relationship()
                                                ->reactive()
                                                ->defaultItems(0)
                                                ->schema([
                                                    Forms\Components\Select::make('price_type')
                                                    ->required()
                                                    ->live(onBlur: true)
                                                    ->options([
                                                        '1' => 'All',
                                                        '2' => 'Eat-in',
                                                        '3' => 'Take Away',
                                                        '4' => 'Delivery'
                                                    ]),
                                                    Forms\Components\TextInput::make('price')
                                                    ->maxLength(100)
                                                    ->live(onBlur: true),
                                                    Forms\Components\TimePicker::make('start_time')->seconds(false),
                                                    Forms\Components\TimePicker::make('end_time')->seconds(false),
                                                    Forms\Components\Hidden::make('day')
                                                    ->default('Sunday')
                                                ])->columns(2)
                                            ]),
                                    ])
                                    ->activeTab(1)
                            ]),


                            Forms\Components\Section::make('Advanced Status')
                            ->schema([
                                Tabs::make('Tabs')
                                    ->tabs([
                                        Tabs\Tab::make('All')
                                        ->schema([ 
                                            Forms\Components\Repeater::make('productstatusAll') 
                                                ->relationship()
                                                ->reactive()
                                                ->defaultItems(0)
                                                ->schema([
                                                    Forms\Components\TimePicker::make('start_time')->seconds(false),
                                                    Forms\Components\TimePicker::make('end_time')->seconds(false),
                                                    Forms\Components\Hidden::make('day')
                                                    ->default('Monday')
                                                ])->columns(2)
                                        ]),
                                        Tabs\Tab::make('Monday')
                                        ->schema([ 
                                            Forms\Components\Repeater::make('productstatusMonday') 
                                                ->relationship()
                                                ->reactive()
                                                ->defaultItems(0)
                                                ->schema([
                                                    Forms\Components\TimePicker::make('start_time')->seconds(false),
                                                    Forms\Components\TimePicker::make('end_time')->seconds(false),
                                                    Forms\Components\Hidden::make('day')
                                                    ->default('Monday')
                                                ])->columns(2)
                                        ]),
                                        Tabs\Tab::make('Tuesday')
                                            ->schema([
                                                Forms\Components\Repeater::make('productstatusTuesday')
                                                ->relationship()
                                                ->reactive()
                                                ->defaultItems(0)
                                                ->schema([
                                                    Forms\Components\TimePicker::make('start_time')->seconds(false),
                                                    Forms\Components\TimePicker::make('end_time')->seconds(false),
                                                    Forms\Components\Hidden::make('day')
                                                    ->default('Tuesday')
                                                ])->columns(2)
                                            ]),
                                        Tabs\Tab::make('Wednesday')
                                            ->schema([
                                                Forms\Components\Repeater::make('productstatusWednesday')
                                                ->relationship()
                                                ->reactive()
                                                ->defaultItems(0)
                                                ->schema([
                                                    Forms\Components\TimePicker::make('start_time')->seconds(false),
                                                    Forms\Components\TimePicker::make('end_time')->seconds(false),
                                                    Forms\Components\Hidden::make('day')
                                                    ->default('Wednesday')
                                                ])->columns(2)
                                            ]),
                                            Tabs\Tab::make('Thursday')
                                            ->schema([
                                                Forms\Components\Repeater::make('productstatusThursday')
                                                ->relationship()
                                                ->reactive()
                                                ->defaultItems(0)
                                                ->schema([
                                                    Forms\Components\TimePicker::make('start_time')->seconds(false),
                                                    Forms\Components\TimePicker::make('end_time')->seconds(false),
                                                    Forms\Components\Hidden::make('day')
                                                    ->default('Thursday')
                                                ])->columns(2)
                                            ]),
                                            Tabs\Tab::make('Friday')
                                            ->schema([
                                                Forms\Components\Repeater::make('productstatusFriday')
                                                ->relationship()
                                                ->reactive()
                                                ->defaultItems(0)
                                                ->schema([
                                                    Forms\Components\TimePicker::make('start_time')->seconds(false),
                                                    Forms\Components\TimePicker::make('end_time')->seconds(false),
                                                    Forms\Components\Hidden::make('day')
                                                    ->default('Friday')
                                                ])->columns(2)
                                            ]),
                                            Tabs\Tab::make('Saturday')
                                            ->schema([
                                                Forms\Components\Repeater::make('productstatusSaturday')
                                                ->relationship()
                                                ->reactive()
                                                ->defaultItems(0)
                                                ->schema([
                                                    Forms\Components\TimePicker::make('start_time')->seconds(false),
                                                    Forms\Components\TimePicker::make('end_time')->seconds(false),
                                                    Forms\Components\Hidden::make('day')
                                                    ->default('Saturday')
                                                ])->columns(2)
                                            ]),
                                            Tabs\Tab::make('Sunday')
                                            ->schema([
                                                Forms\Components\Repeater::make('productstatusSunday')
                                                ->relationship()
                                                ->reactive()
                                                ->defaultItems(0)
                                                ->schema([
                                                    Forms\Components\TimePicker::make('start_time')->seconds(false),
                                                    Forms\Components\TimePicker::make('end_time')->seconds(false),
                                                    Forms\Components\Hidden::make('day')
                                                    ->default('Sunday')
                                                ])->columns(2)
                                            ]),
                                    ])
                                    ->activeTab(1)
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

                        /*Forms\Components\Section::make('Associations')
                            ->schema([
                                Forms\Components\Select::make('shop_brand_id')
                                    ->relationship('brand', 'name')
                                    ->searchable()
                                    ->hiddenOn(ProductsRelationManager::class),

                                Forms\Components\Select::make('categories')
                                    ->relationship('categories', 'name')
                                    ->multiple()
                                    ->required(),
                            ]),*/
                    ])
                    ->columnSpan(['lg' => 1]),
            ])
            ->columns(3);
    }

    public static function table(Table $table): Table
    {

        //->select('products.name_id AS productnames_id')
        return $table
            //->modifyQueryUsing(fn (Builder $query) => $query->select('products.name_id AS productname_id')->where('status', true))
            ->columns([
                Tables\Columns\TextColumn::make('id')
                    ->label('ID')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\ImageColumn::make('image')
                    ->label('Image'),
                //Tables\Columns\TextColumn::make('name_id')
                    //->label('Name'),


                Tables\Columns\TextColumn::make('price')
                    ->label('Price')
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('productname.name')
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

                    /*Tables\Columns\TextColumn::make('name_id')
                    ->label('name'),
                    Tables\Columns\TextColumn::make('business_id')
                    ->label('Business'),*/
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
            'index' => Pages\ListProducts::route('/'),
            'create' => Pages\CreateProducts::route('/create'),
            'edit' => Pages\EditProducts::route('/{record}/edit'),
        ];
    }
}
