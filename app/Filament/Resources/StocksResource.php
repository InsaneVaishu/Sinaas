<?php

namespace App\Filament\Resources;

use App\Filament\Resources\StocksResource\Pages;
use App\Filament\Resources\StocksResource\RelationManagers;
use App\Models\Stocks;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class StocksResource extends Resource
{
    protected static ?string $model = Stocks::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';
    protected static ?string $navigationGroup = 'Catalogue';
    protected static ?string $navigationLabel = 'Stocks';
    protected static ?int $navigationSort = 4;

    public static function form(Form $form): Form
    {
        return $form
        ->schema([
            Forms\Components\Group::make()
                ->schema([              

                    Forms\Components\Section::make('General')
                        ->schema([
                            Forms\Components\Select::make('inventory_id')
                                ->label('Inventory')
                                ->relationship(name:'inventory', titleAttribute:'name',                               
                                modifyQueryUsing: fn (Builder $query, callable $get) => $query->select('inventory_names.name','inventories.id')->orderBy('inventories.id'))
                                ->required()
                                ->live(onBlur: true)
                                ->searchable()->preload(),
                            Forms\Components\Select::make('business_id')
                            ->label('Business Name')
                                ->relationship(name:'business', titleAttribute:'name')
                                ->required()
                                ->live(onBlur: true)
                                ->searchable()->preload(),
                            
                        ])
                        ->columns(2),
             
                    
                    Forms\Components\Section::make('Images')
                        ->schema([
                            Forms\Components\FileUpload::make('image')
                                ->label('Stock Image')
                                ->image()
                                ->imageEditor(),
                                
                        ])
                        ->columns(1), 


                        Forms\Components\Section::make('General')
                        ->schema([
                            Forms\Components\TextInput::make('buy_price')
                                ->label('Buy Price')
                                ->required()
                                ->live(onBlur: true),

                            Forms\Components\TextInput::make('quantity_alert')
                                ->label('Quantity Alert')
                                ->required()
                                ->live(onBlur: true),

                            Forms\Components\TextInput::make('quantity')
                                ->label('Quantity')
                                ->required()
                                ->live(onBlur: true),

                            Forms\Components\Select::make('unit_id')
                                ->label('Unit Name')
                                    ->relationship(name:'unit', titleAttribute:'name')
                                    ->required()
                                    ->live(onBlur: true)
                                    ->searchable()->preload(),


                            
                                
                            
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
        return $table /*->modifyQueryUsing(function (Builder $query) {
            return $query->whereHas(
                
            ); 
        })*/
            ->columns([

                Tables\Columns\TextColumn::make('id')
                    ->searchable()
                    ->sortable()
                    ->toggleable()
                    ->label('Inventory ID'),

                Tables\Columns\TextColumn::make('inventorynames.name')
                    ->searchable()
                    ->sortable()
                    ->toggleable()
                    ->label('Inventory'),

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
            'index' => Pages\ListStocks::route('/'),
            'create' => Pages\CreateStocks::route('/create'),
            'edit' => Pages\EditStocks::route('/{record}/edit'),
        ];
    }
}
