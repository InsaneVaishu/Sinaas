<?php

namespace App\Filament\Resources;

use Filament\Forms; 
use Filament\Tables;
use App\Models\Access;
use App\Models\Skills;
use App\Models\Staffs;
use Filament\Forms\Form;
use Filament\Tables\Table;
use Filament\Resources\Resource;
use Filament\Forms\Components\Tabs;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Checkbox;
use Filament\Forms\Components\Fieldset;
use Filament\Forms\Components\TextInput;
use Illuminate\Database\Eloquent\Builder;
use Filament\Forms\Components\CheckboxList;
use Filament\Forms\Components\ToggleButtons;
use App\Filament\Resources\StaffsResource\Pages;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Filament\Resources\StaffsResource\RelationManagers;


class StaffsResource extends Resource
{
    protected static ?string $model = Staffs::class;

    // protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';
    protected static ?string $navigationGroup = 'Businesses';
    protected static ?string $navigationLabel = 'Staffs';   
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
                                Forms\Components\TextInput::make('first_name')->required()
                                ->maxLength(255),
                                Forms\Components\TextInput::make('last_name')
                                ->maxLength(255),
                                Forms\Components\TextInput::make('email')
                                ->label('Email address')
                                ->email()
                                ->required()
                                ->maxLength(255),
                                Forms\Components\TextInput::make('phone')
                                ->maxLength(15),                                                   
                            
                            ])
                            ->columns(2),                      
                             
                        

                        

                        
                        Forms\Components\Section::make('General')
                            ->schema([
                                Forms\Components\Select::make('gender')
                                ->options([
                                    '1' => 'Male',
                                    '2' => 'Female'
                                ]),
                                Forms\Components\DatePicker::make('date_of_birth')
                                ->maxDate(now()),  
                                    
                                    Forms\Components\FileUpload::make('image')
                                    ->image()
                                    ->directory('business')
                                    ->imageEditor(),

                                    
                            ])
                            ->columns(2),  
                                
                            Forms\Components\Section::make('Skills & Accesses')
                            ->schema([
                                Tabs::make('Tabs')
                                    ->tabs([
                                        Tabs\Tab::make('Staff Skills')
                                        ->schema([       
                                            
                                            /*Fieldset::make('User Info')
                                            ->relationship('access')
                                            ->columns(2)
                                            ->schema([
                                                Forms\Components\TextInput::make("app_cashier")                                                
                                            ]),*/
                                            
                                            Forms\Components\Select::make('skills')
                                                ->label('Skills')
                                                ->relationship(name: 'skills',
                                                titleAttribute: 'name')
                                                ->multiple()->preload(),
                                            
                                        ]),
                                        Tabs\Tab::make('Staff Access')
                                        ->schema([
                                      

                                            Fieldset::make('Access')->label('')
                                            ->relationship('access')
                                            ->schema([

                                                //Forms\Components\Checkbox::make('app_cashier')->label('Cashier'),
                                                Section::make('App')->columns(2)->schema([
                                                Checkbox::make('app_cashier')->label('Cashier'),
                                                Checkbox::make('app_waiter')->label('Waiter'),
                                                Checkbox::make('app_driverr')->label('Driver')
                                                ]),

                                                
                                                Section::make('Payments')->columns(2)->schema([
                                                Checkbox::make('pay_payment')->label('Payment'),
                                                Checkbox::make('pay_cash_payment')->label('Cash Payment'),
                                                Checkbox::make('pay_refund')->label('Refund'),
                                                Checkbox::make('pay_discount')->label('Discount')
                                                ]),

                                                Section::make('Dashboard')->columns(2)->schema([
                                                Checkbox::make('das_menu')->label('Menu'),
                                                Toggle::make('das_menu_edit')->label('Edit'),
                                                Checkbox::make('das_orders')->label('Orders'),
                                                Toggle::make('das_orders_edit')->label('Edit'),
                                                Checkbox::make('das_staff')->label('Staff'),
                                                Toggle::make('das_staff_edit')->label('Edit'),
                                                Checkbox::make('das_system')->label('System'),
                                                Toggle::make('das_system_edit')->label('Edit'),
                                                Checkbox::make('das_shop')->label('Shop'),
                                                Toggle::make('das_shop_edit')->label('Edit'),
                                                ]), 

                                            ])->columns(2),

                                            /*Fieldset::make('Payments')
                                            ->relationship('payaccess')
                                            ->schema([
                                                
                                            ])->columns(2),
                                            Fieldset::make('Dashboard')
                                            ->relationship('dasaccess')
                                            ->schema([
                                                
                                            ])->columns(2),*/
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
                Tables\Columns\TextColumn::make('first_name','last_name')
                    ->searchable()
                    ->label('Name')
                    ->sortable(),
                Tables\Columns\TextColumn::make('email')
                    ->label('Email address')
                    ->searchable()
                    ->sortable(),
                //Tables\Columns\TextColumn::make('country')
                    //->getStateUsing(fn ($record): ?string => Country::find($record->addresses->first()?//->country)?->name ?? null),
                /*Tables\Columns\TextColumn::make('type')
                    ->searchable()
                    ->sortable(),*/

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
            'index' => Pages\ListStaffs::route('/'),
            'create' => Pages\CreateStaffs::route('/create'),
            'edit' => Pages\EditStaffs::route('/{record}/edit'),
        ];
    }
}
