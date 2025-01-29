<?php

namespace App\Filament\Resources;

use App\Filament\Resources\OrdersStatusesResource\Pages;
use App\Filament\Resources\OrdersStatusesResource\RelationManagers;
use App\Models\OrderStatuses;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class OrdersStatusesResource extends Resource
{
    protected static ?string $model = OrderStatuses::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';
    protected static ?string $navigationGroup = 'Orders';
    protected static ?string $navigationLabel = 'Orders Statuses';
    protected static ?int $navigationSort = 2;

    public static function form(Form $form): Form
    {
        return $form
        ->schema([
        
            Forms\Components\Group::make()
                ->schema([
                
                Forms\Components\Section::make('General')
                ->schema([

                    Forms\Components\TextInput::make('name')
                        ->label('Status Name')
                        ->required()
                        ->maxLength(255),
                    Forms\Components\TextInput::make('language_id')
                        ->label('Language')
                        ->required()
                        ->maxLength(3),                          
                                   
                    
                ])->columns(2),

            ])->columnSpan(['lg' => 2]),


            Forms\Components\Group::make()
            ->schema([
                Forms\Components\Section::make('Status')
                    ->schema([
                        Forms\Components\Toggle::make('status')
                            ->label('Status')
                            ->helperText('This status will be hidden from all sales channels.')
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
                    ->label('ID')
                    ->searchable()
                    ->sortable(),    
               
                Tables\Columns\TextColumn::make('name')
                    ->label('Status Name')
                    ->searchable()
                    ->sortable(),  

                Tables\Columns\TextColumn::make('lanuage_id')
                    ->label('Language')
                    ->sortable(),              

                

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
            'index' => Pages\ListOrdersStatuses::route('/'),
            'create' => Pages\CreateOrdersStatuses::route('/create'),
            'edit' => Pages\EditOrdersStatuses::route('/{record}/edit'),
        ];
    }
}
