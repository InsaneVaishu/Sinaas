<?php

namespace App\Filament\Resources;

use App\Filament\Resources\InventoriesResource\Pages;
use App\Filament\Resources\InventoriesResource\RelationManagers;
use App\Models\Inventories;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class InventoriesResource extends Resource
{
    protected static ?string $model = Inventories::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';
    protected static ?string $navigationGroup = 'Catalogue';
    protected static ?string $navigationLabel = 'Inventory';
    protected static ?int $navigationSort = 5;


    public static function form(Form $form): Form
    {
        return $form
            ->schema([                           
                Forms\Components\Group::make()
                ->schema([        

                    Forms\Components\Section::make('General')
                        ->schema([
                            Forms\Components\Select::make('inventoryname_id')
                                ->label('Inventory Name ID')
                                ->relationship(name:'inventoryname', titleAttribute:'name_en')
                                ->required()
                                ->live(onBlur: true)
                                ->preload()
                                ->searchable(),  
                                                                                    
                                        
                            Forms\Components\FileUpload::make('inventory_image')
                                ->image()
                                ->directory('inventories')
                                ->imageEditor(), 
                        
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
                                ->helperText('This inventory will be hidden from all sales channels.')
                                ->default(true)                                
                        ]),              
                    
                ])
                ->columnSpan(['lg' => 1]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('id')
                    ->label('ID')
                    ->searchable()
                    ->sortable(),

                    Tables\Columns\TextColumn::make('inventoryname.name')
                    ->searchable()
                    ->sortable()
                    ->toggleable()
                    ->label('Name'),

                Tables\Columns\ImageColumn::make('inventory_image')
                    ->label('Image'),
                    
                //Tables\Columns\TextColumn::make('name_id')
                    //->label('Name'),

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
            'index' => Pages\ListInventories::route('/'),
            'create' => Pages\CreateInventories::route('/create'),
            'edit' => Pages\EditInventories::route('/{record}/edit'),
        ];
    }
}
