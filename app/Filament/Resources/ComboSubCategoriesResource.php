<?php

namespace App\Filament\Resources;

use Filament\Forms;
use Filament\Tables;
use Filament\Forms\Form;
use Filament\Tables\Table;
use App\Models\ComboCategories;
use Filament\Resources\Resource;
use App\Models\ComboSubCategories;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Filament\Resources\ComboSubCategoriesResource\Pages;
use App\Filament\Resources\ComboSubCategoriesResource\RelationManagers;

class ComboSubCategoriesResource extends Resource
{
    protected static ?string $model = ComboSubCategories::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';
    protected static ?string $navigationGroup = 'Combos';
    protected static ?string $navigationLabel = 'Combo Sub Categories';
    protected static ?int $navigationSort = 6;

    public static function form(Form $form): Form
    {
        return $form
        ->schema([
            Forms\Components\Group::make()
                ->schema([              

                    Forms\Components\Section::make('General')
                        ->schema([
                            Forms\Components\Select::make('subcategoryname_id')
                                ->label('Category Name')
                                ->relationship(name:'subcategoryname',titleAttribute:'name',)
                                ->required()
                                ->live(onBlur: true)
                                ->searchable()->preload(),

                            Forms\Components\Select::make('category_id')
                            ->label('Combo Categories')
                            ->relationship(name: 'category',
                            titleAttribute: 'products_names.name',
                            modifyQueryUsing: fn (Builder $query, callable $get) => $query->leftJoin('products_names', 'products_names.id', '=', 'combo_categories.categoryname_id')->select('products_names.name', 'combo_categories.id')->orderBy('products_names.name'))
                            ->preload(),
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
                    ->searchable()
                    ->sortable()
                    ->toggleable()
                    ->label('Category ID'),
                    
                Tables\Columns\TextColumn::make('subcategoryname.name')
                    ->searchable()
                    ->sortable()
                    ->toggleable()
                    ->label('Name'),
                  
                /*Tables\Columns\TextColumn::make('category.name')
                    ->searchable()
                    ->sortable()
                    ->toggleable()
                    ->label('Category Name'),   */
                
                    /*Tables\Columns\TextColumn::make('category_id')
                    ->getStateUsing(fn ($record): ?string => ComboCategories::find($record->products_names->first()?->combo_category)?->name ?? null),  */                               
                    
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
            'index' => Pages\ListComboSubCategories::route('/'),
            'create' => Pages\CreateComboSubCategories::route('/create'),
            'edit' => Pages\EditComboSubCategories::route('/{record}/edit'),
        ];
    }
}
