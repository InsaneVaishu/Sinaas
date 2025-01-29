<?php

namespace App\Filament\Resources;

use App\Filament\Resources\CustomizeResource\Pages;
use App\Filament\Resources\CustomizeResource\RelationManagers;
use App\Models\Customize;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class CustomizeResource extends Resource
{
    protected static ?string $model = Customize::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';
    protected static ?string $navigationGroup = 'Catalogue';
    protected static ?string $navigationLabel = 'Customize';
    protected static ?int $navigationSort = 7;

    public static function form(Form $form): Form
    {
        return $form
        ->schema([
            Forms\Components\Group::make()
                ->schema([              

                    Forms\Components\Section::make('General')
                        ->schema([
                            Forms\Components\Select::make('product_id')
                                ->label('Product')
                                ->relationship(name:'product',titleAttribute:'name',                               
                                modifyQueryUsing: fn (Builder $query, callable $get) => $query->select('products_names.name','products.id')->orderBy('products.id')) 
                                ->required()
                                ->live(onBlur: true)
                                ->searchable()->preload(),

                            Forms\Components\Select::make('stock_id')
                            ->label('Stock')
                                ->relationship(name:'stock', titleAttribute:'name',modifyQueryUsing: fn (Builder $query, callable $get) => $query->select('inventory_names.name','stocks.id')->orderBy('stocks.id'))
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
             
                                       


                        Forms\Components\Section::make('Stocks')
                        ->schema([
                            Forms\Components\TextInput::make('price')
                                ->label('Price')
                                ->required()
                                ->live(onBlur: true),
                            Forms\Components\TextInput::make('max')
                                ->label('Maximum')
                                ->live(onBlur: true),
                            Forms\Components\Toggle::make('default_extra')
                                ->label('Default Extra')
                                ->helperText('Make this as default extra on the product')
                                ->default(true),
                            Forms\Components\Toggle::make('stock_deduction')
                                ->label('Stock Deduction')
                                ->helperText('If on the stock will be deducted')
                                ->default(true),
                            
                                
                            
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
                    ->sortable()
                    ->toggleable()
                    ->label('Customize ID'),

                Tables\Columns\TextColumn::make('stock.name')
                    ->searchable()
                    ->toggleable()
                    ->label('Stock'),


                Tables\Columns\TextColumn::make('price')
                    ->searchable()
                    ->toggleable()
                    ->label('Price'),
                
                Tables\Columns\TextColumn::make('max')
                    ->searchable()
                    ->toggleable()
                    ->label('Max'),

                /*Tables\Columns\TextColumn::make('products.name')
                    ->searchable()
                    ->toggleable()
                    ->label('Product'),*/

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
            'index' => Pages\ListCustomizes::route('/'),
            'create' => Pages\CreateCustomize::route('/create'),
            'edit' => Pages\EditCustomize::route('/{record}/edit'),
        ];
    }
}
