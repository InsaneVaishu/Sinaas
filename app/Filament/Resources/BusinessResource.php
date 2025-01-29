<?php

namespace App\Filament\Resources;

use Filament\Forms;
use Filament\Tables;
use App\Models\Business;
use Filament\Forms\Form;
use Filament\Tables\Table;

//use Filament\Forms\Components\SpatieMediaLibraryFileUpload;

use Filament\Resources\Resource;
use Filament\Forms\Components\Tabs;

use Filament\Tables\Filters\Filter;

use Illuminate\Database\Eloquent\Model;
use Filament\Notifications\Notification;
use Illuminate\Database\Eloquent\Builder;
use App\Filament\Resources\BusinessResource\Pages;

class BusinessResource extends Resource
{
    protected static ?string $model = Business::class;
    // protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';
    protected static ?string $navigationGroup = 'Businesses';
    protected static ?string $navigationLabel = 'Business';   
    protected static ?string $navigationIcon = 'heroicon-o-bolt';
    protected static ?int $navigationSort = 0;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Group::make()
                    ->schema([              

                        Forms\Components\Section::make('Contacts')
                            ->schema([
                                Forms\Components\TextInput::make('name')
                                    ->label('Business Name')
                                    ->required()
                                    ->maxLength(150)
                                    ->live(onBlur: true),                                
                                    Forms\Components\TextInput::make('email')
                                    ->label('Business Email')
                                    ->required()
                                    ->maxLength(100)
                                    ->live(onBlur: true),                       
                                    Forms\Components\TextInput::make('phone')
                                    ->label('Business Phone')
                                    ->required()
                                    ->maxLength(15)
                                    ->live(onBlur: true),

                                    Forms\Components\TextInput::make('website')
                                    ->label('Business Website')
                                    ->maxLength(100)
                                    ->live(onBlur: true)                        
                            
                            ])
                            ->columns(2),


                            Forms\Components\Section::make('Address')
                            ->schema([                                   
                                
                                Forms\Components\Textarea::make('address')
                                ->label('Business Address')
                                    ->columnSpan('full'),                                
                                Forms\Components\Textarea::make('billing_address')
                                ->label('Billing Business')
                                    ->columnSpan('full'),                       
                                Forms\Components\Textarea::make('shipping_address')
                                ->label('Shipping Business')
                                    ->columnSpan('full'),
                            ])
                            ->columns(2),
                             
                        

                        /*Forms\Components\Section::make('Images')
                            ->schema([
                                SpatieMediaLibraryFileUpload::make('media')
                                    ->collection('product-images')
                                    ->multiple()
                                    ->maxFiles(5)
                                    ->hiddenLabel(),
                            ])
                            ->collapsible(),*/

                        
                        Forms\Components\Section::make('General')
                            ->schema([
                                Forms\Components\TextInput::make('time_zone')
                                    ->helperText('Time zone of the Business.')
                                    ->maxLength(15),
                                Forms\Components\TextInput::make('currency')
                                    ->helperText('Currency of the Business.')
                                    ->maxLength(5),    
                                    
                                    Forms\Components\FileUpload::make('image')
                                    ->image()
                                    ->disk('local')
                                    ->directory('business')
                                    ->imageEditor()
                                    ->moveFiles(),
                            ])
                            ->columns(2),  
                                
                            Forms\Components\Section::make('Opening Hours')
                            ->schema([
                                Tabs::make('Tabs')
                                    ->tabs([
                                        Tabs\Tab::make('Monday')
                                        ->schema([ 
                                            Forms\Components\Repeater::make('businessWorkingMonday') 
                                                ->relationship()
                                                ->reactive()
                                                ->defaultItems(0)
                                                ->schema([
                                                    Forms\Components\TextInput::make('open_from'),
                                                    Forms\Components\TextInput::make('open_to'),
                                                    Forms\Components\Hidden::make('day')
                                                    ->default('Monday')
                                                ])->columns(2)
                                        ]),
                                        Tabs\Tab::make('Tuesday')
                                            ->schema([
                                                Forms\Components\Repeater::make('businessWorkingTuesday')
                                                ->relationship()
                                                ->reactive()
                                                ->defaultItems(0)
                                                ->schema([
                                                    Forms\Components\TextInput::make('open_from'),
                                                    Forms\Components\TextInput::make('open_to'),
                                                    Forms\Components\Hidden::make('day')
                                                    ->default('Tuesday')
                                                ])->columns(2)
                                            ]),
                                        Tabs\Tab::make('Wednesday')
                                            ->schema([
                                                Forms\Components\Repeater::make('businessWorkingWednesday')
                                                ->relationship()
                                                ->reactive()
                                                ->defaultItems(0)
                                                ->schema([
                                                    Forms\Components\TextInput::make('open_from'),
                                                    Forms\Components\TextInput::make('open_to'),
                                                    Forms\Components\Hidden::make('day')
                                                    ->default('Wednesday')
                                                ])->columns(2)
                                            ]),
                                            Tabs\Tab::make('Thursday')
                                            ->schema([
                                                Forms\Components\Repeater::make('businessWorkingThursday')
                                                ->relationship()
                                                ->reactive()
                                                ->defaultItems(0)
                                                ->schema([
                                                    Forms\Components\TextInput::make('open_from'),
                                                    Forms\Components\TextInput::make('open_to'),
                                                    Forms\Components\Hidden::make('day')
                                                    ->default('Thursday')
                                                ])->columns(2)
                                            ]),
                                            Tabs\Tab::make('Friday')
                                            ->schema([
                                                Forms\Components\Repeater::make('businessWorkingFriday')
                                                ->relationship()
                                                ->reactive()
                                                ->defaultItems(0)
                                                ->schema([
                                                    Forms\Components\TextInput::make('open_from'),
                                                    Forms\Components\TextInput::make('open_to'),
                                                    Forms\Components\Hidden::make('day')
                                                    ->default('Friday')
                                                ])->columns(2)
                                            ]),
                                            Tabs\Tab::make('Saturday')
                                            ->schema([
                                                Forms\Components\Repeater::make('businessWorkingSaturday')
                                                ->relationship()
                                                ->reactive()
                                                ->defaultItems(0)
                                                ->schema([
                                                    Forms\Components\TextInput::make('open_from'),
                                                    Forms\Components\TextInput::make('open_to'),
                                                    Forms\Components\Hidden::make('day')
                                                    ->default('Saturday')
                                                ])->columns(2)
                                            ]),
                                            Tabs\Tab::make('Sunday')
                                            ->schema([
                                                Forms\Components\Repeater::make('businessWorkingSunday')
                                                ->relationship()
                                                ->reactive()
                                                ->defaultItems(0)
                                                ->schema([
                                                    Forms\Components\TextInput::make('open_from'),
                                                    Forms\Components\TextInput::make('open_to'),
                                                    Forms\Components\Hidden::make('day')
                                                    ->default('Sunday')
                                                ])->columns(2)
                                            ]),
                                    ])
                                    ->activeTab(1)
                            ]),


                            Forms\Components\Section::make('Delivery Hours')
                            ->schema([
                                Tabs::make('Tabs')
                                    ->tabs([
                                        Tabs\Tab::make('Monday')
                                        ->schema([ 
                                            Forms\Components\Repeater::make('businessDeliveryMonday')
                                                ->relationship()
                                                ->reactive()
                                                ->defaultItems(0)
                                                ->schema([
                                                    Forms\Components\TextInput::make('open_from'),
                                                    Forms\Components\TextInput::make('open_to'),
                                                    Forms\Components\Hidden::make('day')
                                                    ->default('Monday')
                                                ])->columns(2)
                                        ]),
                                        Tabs\Tab::make('Tuesday')
                                            ->schema([
                                                Forms\Components\Repeater::make('businessDeliveryTuesday')
                                                ->relationship()
                                                ->reactive()
                                                ->defaultItems(0)
                                                ->schema([
                                                    Forms\Components\TextInput::make('open_from'),
                                                    Forms\Components\TextInput::make('open_to'),
                                                    Forms\Components\Hidden::make('day')
                                                    ->default('Tuesday')
                                                ])->columns(2)
                                            ]),
                                        Tabs\Tab::make('Wednesday')
                                            ->schema([
                                                Forms\Components\Repeater::make('businessDeliveryWednesday')
                                                ->relationship()
                                                ->reactive()
                                                ->defaultItems(0)
                                                ->schema([
                                                    Forms\Components\TextInput::make('open_from'),
                                                    Forms\Components\TextInput::make('open_to'),
                                                    Forms\Components\Hidden::make('day')
                                                    ->default('Wednesday')
                                                ])->columns(2)
                                            ]),
                                            Tabs\Tab::make('Thursday')
                                            ->schema([
                                                Forms\Components\Repeater::make('businessDeliveryThursday')
                                                ->relationship()
                                                ->reactive()
                                                ->defaultItems(0)
                                                ->schema([
                                                    Forms\Components\TextInput::make('open_from'),
                                                    Forms\Components\TextInput::make('open_to'),
                                                    Forms\Components\Hidden::make('day')
                                                    ->default('Thursday')
                                                ])->columns(2)
                                            ]),
                                            Tabs\Tab::make('Friday')
                                            ->schema([
                                                Forms\Components\Repeater::make('businessDeliveryFriday')
                                                ->relationship()
                                                ->reactive()
                                                ->defaultItems(0)
                                                ->schema([
                                                    Forms\Components\TextInput::make('open_from'),
                                                    Forms\Components\TextInput::make('open_to'),
                                                    Forms\Components\Hidden::make('day')
                                                    ->default('Friday')
                                                ])->columns(2)
                                            ]),
                                            Tabs\Tab::make('Saturday')
                                            ->schema([
                                                Forms\Components\Repeater::make('businessDeliverySaturday')
                                                ->relationship()
                                                ->reactive()
                                                ->defaultItems(0)
                                                ->schema([
                                                    Forms\Components\TextInput::make('open_from'),
                                                    Forms\Components\TextInput::make('open_to'),
                                                    Forms\Components\Hidden::make('day')
                                                    ->default('Saturday')
                                                ])->columns(2)
                                            ]),
                                            Tabs\Tab::make('Sunday')
                                            ->schema([
                                                Forms\Components\Repeater::make('businessDeliverySunday')
                                                ->relationship()
                                                ->reactive()
                                                ->defaultItems(0)
                                                ->schema([
                                                    Forms\Components\TextInput::make('open_from'),
                                                    Forms\Components\TextInput::make('open_to'),
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
                                    ->helperText('This business will be hidden from all sales channels.')
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
        return $table
            ->columns([

                Tables\Columns\TextColumn::make('id')
                    ->label('id')
                    ->searchable()
                    ->sortable(),
                
                Tables\Columns\TextColumn::make('name')
                    ->label('Name')
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('email')
                    ->searchable()
                    ->toggleable(),

                Tables\Columns\TextColumn::make('phone')                    
                    ->sortable()
                    ->searchable()
                    ->toggleable(),

                Tables\Columns\IconColumn::make('status')->boolean(),

                
            ])
            ->filters([
                
            ], layout: Tables\Enums\FiltersLayout::AboveContentCollapsible)
            ->actions([
                Tables\Actions\EditAction::make(),
            ])
            ->groupedBulkActions([
                Tables\Actions\DeleteBulkAction::make()
                    ->action(function () {
                        Notification::make()
                            ->title('Now, now, don\'t be cheeky, leave some records for others to play with!')
                            ->warning()
                            ->send();
                    }),
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
            'index' => Pages\ListBusinesses::route('/'),
            'create' => Pages\CreateBusiness::route('/create'),
            'edit' => Pages\EditBusiness::route('/{record}/edit'),
        ];
    }


    public static function getGloballySearchableAttributes(): array
    {
        return ['name'];
    }
}
