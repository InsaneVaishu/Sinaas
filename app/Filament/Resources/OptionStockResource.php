<?php

namespace App\Filament\Resources;

use App\Filament\Resources\OptionStockResource\Pages;
use App\Filament\Resources\OptionStockResource\RelationManagers;
use App\Models\OptionStock;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class OptionStockResource extends Resource
{
    protected static ?string $model = OptionStock::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';
    protected static ?string $navigationGroup = 'Catalogue';
    protected static ?string $navigationLabel = 'Options Stock';
    protected static ?int $navigationSort = 6;

    public static function form(Form $form): Form
    {
        return $form
        ->schema([
            Forms\Components\Group::make()
                ->schema([              

                    Forms\Components\Section::make('General')
                        ->schema([                            

                            Forms\Components\Select::make('stock_id')
                            ->label('Stock')
                                ->relationship(name:'stock', titleAttribute:'name',modifyQueryUsing: fn (Builder $query, callable $get) => $query->select('inventory_names.name','stocks.id')->orderBy('stocks.id'))
                                ->required()
                                ->live(onBlur: true)
                                ->searchable(),                            
                        ])
                        ->columns(2),
             
                                       


                        Forms\Components\Section::make('Stocks')
                        ->schema([
                            Forms\Components\TextInput::make('stock_price')
                                ->label('Price')
                                ->required()
                                ->live(onBlur: true),
                            
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


                Tables\Columns\TextColumn::make('stock_price')
                    ->searchable()
                    ->toggleable()
                    ->label('Price'),
                
                Tables\Columns\IconColumn::make('stock_deduction')->boolean()->sortable()->toggleable(),

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
            'index' => Pages\ListOptionStocks::route('/'),
            'create' => Pages\CreateOptionStock::route('/create'),
            'edit' => Pages\EditOptionStock::route('/{record}/edit'),
        ];
    }
}
