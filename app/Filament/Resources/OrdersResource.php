<?php

namespace App\Filament\Resources;

use Filament\Forms;
use Filament\Tables;
use App\Models\Orders;
use App\Models\Products;
use Filament\Forms\Form;
use Filament\Tables\Table;
use Filament\Resources\Resource;
use Illuminate\Database\Eloquent\Builder;
use App\Filament\Resources\OrdersResource\Pages;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Filament\Resources\OrdersResource\RelationManagers;

use Filament\Forms\Components\DateTimePicker;

class OrdersResource extends Resource
{
    protected static ?string $model = Orders::class;

    protected static ?string $navigationIcon = 'heroicon-o-shopping-bag';
    protected static ?string $navigationGroup = 'Orders';
    protected static ?string $navigationLabel = 'Orders List';
    protected static ?int $navigationSort = 1;
    protected static ?string $recordTitleAttribute = 'number';

    public static function form(Form $form): Form
    {
        return $form
        ->schema([
        
            Forms\Components\Group::make()
                ->schema([
                
                Forms\Components\Section::make('General')
                ->schema([ 

                    Forms\Components\Select::make('business_id')
                        ->label('Business Name')
                            ->relationship(name:'business', titleAttribute:'name')
                            ->required()
                            ->live(onBlur: true)
                            ->searchable()->preload(),
                    
                    Forms\Components\Select::make('user_id')
                        ->label('User Name')
                            ->relationship(name:'user', titleAttribute:'first_name')
                            ->required()
                            ->live(onBlur: true)
                            ->searchable(),
                                  
                    Forms\Components\Select::make('order_type')
                        ->label('Order Type')
                            ->options([
                                '1' => 'All',
                                '2' => 'Eat-in',
                                '3' => 'Pickup',
                                '4' => 'Delivery'
                            ]),

                    Forms\Components\Select::make('language_id')
                        ->label('Order Language')
                            ->relationship(name:'language', titleAttribute:'name')
                            ->required()
                            ->live(onBlur: true)
                            ->searchable()->preload(),

                    Forms\Components\TextInput::make('order_note')
                        ->label('Order Note')                        
                        ->live(onBlur: true),
                    
                    Forms\Components\Select::make('order_currency')
                        ->label('Order Currency')
                            ->relationship(name:'currency', titleAttribute:'code')
                            ->required()
                            ->live(onBlur: true)
                            ->searchable()->preload(),
                    
                    Forms\Components\TextInput::make('order_address')
                            ->label('Address')
                                ->live(onBlur: true),
                    
                    Forms\Components\TextInput::make('order_geo')
                            ->label('Geo Location')
                                ->live(onBlur: true),
                    
                    Forms\Components\TextInput::make('order_alert')
                                ->label('Order Alert')
                                    ->live(onBlur: true),
                    
                    Forms\Components\Select::make('payment_type')
                                    ->label('Payment Type')
                                        ->live(onBlur: true)
                                        ->options([
                                            '1' => 'Cash',
                                            '2' => 'UPI',
                                            '3' => 'Net banking',
                                            '4' => 'Check'
                                        ]),
                    
                    Forms\Components\Select::make('payment_status')
                                        ->label('Payment Status')
                                        ->live(onBlur: true)
                                        ->options([
                                            '1' => 'Paid',
                                            '2' => 'Un Paid',
                                        ]),
                    
                    Forms\Components\Select::make('order_status_id')
                                        ->label('Order Status')
                                            ->relationship(name:'status', titleAttribute:'name')
                                            ->required()
                                            ->live(onBlur: true)
                                            ->searchable()->preload(),

                    Forms\Components\TextInput::make('order_total')
                    ->readOnly()
                    //->hidden()
                    ->live(onBlur: true)
                    ->placeholder(function (Forms\Set $set, Forms\Get $get) {
                        $fields = $get('OrderProducts');
                        $sum = 0;
                        foreach($fields as $field){                            
                            $sum+=$field['total'];
                        }
                        $set('order_total',$sum);
                        return $sum;
                    }),

                    Forms\Components\DateTimePicker::make('order_time')->native(false)
                    

                    
                ])->columns(2),


                Forms\Components\Section::make('Product Items')
                    ->schema([

                    Forms\Components\Repeater::make('OrderProducts')
                            ->live()
                            /*->afterStateUpdated(function (Forms\Set $set, Forms\Get $get) {  
                                
                                $fields = $get('OrderProducts');
                                $sum = 0;
                                foreach($fields as $field){
                                    foreach ($field['total'] as $value){
                                        if ($value == ""){
                                            $value = 0;
                                        }
                                        $sum += $value;
                                    }
                                    $sum+=$field['total'];
                                }
                                $set('total_amount', $sum);
                            })   */                         
                            ->relationship()
                            ->reactive()
                            ->defaultItems(0)
                            ->hiddenLabel()
                            ->schema([

                                Forms\Components\Select::make('product_id')
                                    ->label('Product')
                                    //->relationship(name:'products', titleAttribute:'name')
                                    //->required()
                                    ->live(onBlur: true)
                                    

                                    ->options(Products::query()->leftJoin('products_names', 'products_names.id', '=', 'products.productname_id')->select('products_names.name AS name', 'products.id')->orderBy('products_names.name')->pluck('name', 'products.id'))
                                    ->required()
                                    ->reactive()
                                    ->distinct()
                                    ->searchable()
                                    ->afterStateUpdated(function (Forms\Set $set, Forms\Get $get, ?string $state) {                                   
                                        $info = Products::where('id', $state)->select('price', 'tax_id')->first();
                                        $set('price', $info->price);

                                        $price = $get('price');
                                        $quantity = $get('quantity');
                                        if($quantity==''){ $quantity = 1; $set('quantity', $quantity); }
                                        $total = $quantity*$price;
                                        $set('total', $total);
                                        $set('tax_id', $info->tax_id);
                                }),
                                
                                Forms\Components\Hidden::make('tax_id'),
                                Forms\Components\TextInput::make('price')->readOnly(),
                                Forms\Components\TextInput::make('quantity')->numeric()->afterStateUpdated(function (Forms\Set $set, Forms\Get $get, ?string $state) {                                            
                                    $price = $get('price');
                                    $total = $state*$price;
                                    $set('total', $total);
                                }),
                                Forms\Components\TextInput::make('total')->readOnly(),
                                //Forms\Components\Hidden::make('day')
                                //->default('Sunday')
                            ])->columns(2)
                            
                ]),                   



            ])->columnSpan(['lg' => 2]),


            Forms\Components\Group::make()
            ->schema([

                Forms\Components\Section::make()
                    ->schema([
                        Forms\Components\Placeholder::make('created_at')
                            ->label('Created at')
                            ->content(fn (Orders $record): ?string => $record->created_at?->diffForHumans()),

                        Forms\Components\Placeholder::make('updated_at')
                            ->label('Last modified at')
                            ->content(fn (Orders $record): ?string => $record->updated_at?->diffForHumans()),
                    ])
                    ->columnSpan(['lg' => 1])
                    ->hidden(fn (?Orders $record) => $record === null),


                Forms\Components\Section::make('Status')
                    ->schema([
                        Forms\Components\Toggle::make('status')
                            ->label('Status')
                            ->helperText('This Order will be hidden from all channels.')
                            ->default(true)                                
                    ]),
                
            ])
            ->columnSpan(['lg' => 1]),

            
        ])->columns(3);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('id')
                    ->searchable()
                    ->sortable()
                    ->toggleable()
                    ->label('Order ID'),

                Tables\Columns\TextColumn::make('user.first_name')
                    ->searchable()
                    ->toggleable()
                    ->label('Customer Name'),

                Tables\Columns\TextColumn::make('business.name')
                    ->searchable()
                    ->sortable()
                    ->toggleable()
                    ->label('Business'),
                
                    Tables\Columns\TextColumn::make('order_total')
                    ->searchable()
                    ->toggleable()
                    ->label('Total')
                    ->summarize([
                        Tables\Columns\Summarizers\Sum::make()
                            ->money(),
                    ]),

                    Tables\Columns\TextColumn::make('order_type')
                    ->searchable()
                    ->toggleable()
                    ->label('Order Type'),
                
                    Tables\Columns\TextColumn::make('status.name')
                    ->toggleable()
                    ->label('Order Status'),

               // Tables\Columns\IconColumn::make('status')->boolean()->sortable()->toggleable(),
            ])
            ->filters([
                //Tables\Filters\TrashedFilter::make(),
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

    public static function getWidgets(): array
    {
        return [
           // OrderStats::class,
        ];
    }

    public static function getNavigationBadge(): ?string
    {
        return (string) Orders::count();
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListOrders::route('/'),
            'create' => Pages\CreateOrders::route('/create'),
            'edit' => Pages\EditOrders::route('/{record}/edit'),
        ];
    }
}
