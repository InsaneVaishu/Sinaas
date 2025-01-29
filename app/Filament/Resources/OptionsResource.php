<?php

namespace App\Filament\Resources;

use Filament\Forms;
use Filament\Tables;
use App\Models\Options;
use App\Models\Stocks;
use Filament\Forms\Form;
use Filament\Tables\Table;
use Filament\Resources\Resource;
use Illuminate\Database\Eloquent\Builder;
use App\Filament\Resources\OptionsResource\Pages;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Filament\Resources\OptionsResource\RelationManagers;



class OptionsResource extends Resource
{
    protected static ?string $model = Options::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';
    protected static ?string $navigationGroup = 'Catalogue';
    protected static ?string $navigationLabel = 'Options';
    protected static ?int $navigationSort = 5;

    public static function form(Form $form): Form
    {
        return $form
        ->schema([
            Forms\Components\Group::make()
                ->schema([              

                    Forms\Components\Section::make('General')
                        ->schema([
                            Forms\Components\Select::make('optionname_id')
                                ->label('Option Name')
                                ->relationship(name:'optionname',titleAttribute:'name',)
                                ->required()
                                ->live(onBlur: true)
                                ->searchable()->preload(),

                            Forms\Components\Select::make('business_id')
                                ->relationship(name:'business', titleAttribute:'name')
                                ->required()
                                ->live(onBlur: true)
                                ->searchable()->preload(),
                                //->hiddenOn(BusinessesRelationManager::class),
                            
                            
                        ])
                        ->columns(2),
                               
                    


                        Forms\Components\Section::make('General')
                        ->schema([
                            Forms\Components\TextInput::make('option_min')
                                ->label('Option Min')
                                ->required()->numeric()
                                ->minValue(1)
                                ->live(onBlur: true),
                            Forms\Components\TextInput::make('option_max')
                                ->label('Option Max')
                                ->required()->numeric()
                                ->minValue(1)
                                ->live(onBlur: true),   
                                
                                
                            Forms\Components\Toggle::make('stock_deduction')
                                ->label('Stock Deduction')
                                ->helperText('If on the stock will be deducted')
                                ->default(true)->live(),
                                
                            Forms\Components\TextInput::make('price')
                                ->label('Price')
                                ->live(onBlur: true)->visible(fn(\Filament\Forms\Get $get):bool => $get('stock_deduction'))
                                ->default('0')->numeric()
                                ->minValue(1),                                 
                            
                                
                            

                            
                        ])
                        ->columns(2),



                        Forms\Components\Section::make()
                            ->schema([                              
                                        
                            Forms\Components\Repeater::make('optionsStocks') 
                                ->relationship()
                                ->reactive()
                               // ->defaultItems(0)
                                ->schema([                  
                                    
                                    Forms\Components\Select::make('stock_id')
                                    ->label('Stocks')
                                   // ->relationship(name:'stocks', titleAttribute:'name')
                                    ->options(Stocks::query()->leftJoin('inventories', 'inventories.id', '=', 'stocks.inventory_id')->leftJoin('inventory_names', 'inventory_names.id', '=', 'inventories.inventoryname_id')->select('inventory_names.name AS name', 'stocks.id')->orderBy('inventory_names.name')->pluck('name', 'stocks.id'))
                                    ->required()
                                    ->reactive()
                                    ->distinct()
                                    ->searchable()
                                    ->live()
                                    ->disableOptionsWhenSelectedInSiblingRepeaterItems()
                                    ->afterStateUpdated(function (Forms\Set $set, ?string $state) {
                                        //$set('../../stock', $state);
                                    })
                                    /*->afterStateUpdated(function (Forms\Get $get, ?int $state,$livewire) {
                                        $livewire->js("alert($get('stock_id');)");
                                        //$livewire->js('console.log(\'xx\')');
                                    })*/
                                    //->afterStateUpdated(fn ($state, Forms\Set $set) => $set('stock', Product::find($state)?->price ?? 0))
                                    ->preload(),

                                   // Forms\Components\TextInput::make('unit')->label('Stock Detect'),
                                    
                                    Forms\Components\TextInput::make('stock_deduction')->label('Stock Detect')->suffix(static function (Forms\Get $get, $state){
                                        if(!empty($get('stock_id'))){
                                            $stock_id = $get('stock_id');
                                        }else{
                                            $stock_id = 1;
                                        }
                                        

                                         $stock = Stocks::leftjoin('units', 'units.id', '=', 'stocks.unit_id')->where('stocks.id', $stock_id)->select('units.code AS code')->first('code');

                                         return $stock->code;

                                    })->numeric()
                                    ->minValue(1),
                                    Forms\Components\TextInput::make('stock_price')->numeric()
                                    ->minValue(1)

                            ])->columns(3)    
                            
                            

                            /*->allowHtml()
                                    ->options([
                                     'tailwind' => '<span class="text-blue-500">Tailwind</span>',
                                        'alpine' => '<span class="text-green-500">Alpine</span>',
                                        'laravel' => '<span class="text-red-500">Laravel</span>',
                                        'livewire' => '<span class="text-pink-500">Livewire</span>',
                                ]),*/
                                        
                                    
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
                    ->label('Customize ID'),

                Tables\Columns\TextColumn::make('optionname.name')
                    ->searchable()
                    ->toggleable()
                    ->label('Option Name'),

                /*Tables\Columns\TextColumn::make('products.name')
                    ->searchable()
                    ->sortable()
                    ->toggleable()
                    ->label('Product'),*/
                
                    Tables\Columns\TextColumn::make('option_max')
                    ->searchable()
                    ->toggleable()
                    ->label('Option Max'),

                    Tables\Columns\TextColumn::make('option_min')
                    ->searchable()
                    ->toggleable()
                    ->label('Option Mim'),

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
            'index' => Pages\ListOptions::route('/'),
            'create' => Pages\CreateOptions::route('/create'),
            'edit' => Pages\EditOptions::route('/{record}/edit'),
        ];
    }
}
