<?php

namespace App\Filament\Resources;

use App\Filament\Resources\EmployeeResource\Pages;
use App\Filament\Resources\EmployeeResource\RelationManagers;
use App\Models\Employee;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class EmployeeResource extends Resource
{
    protected static ?string $model = Employee::class;   

    protected static ?string $navigationGroup = 'Businesses';
    
    protected static ?string $navigationLabel = 'Employee';   

    protected static ?string $navigationIcon = 'heroicon-o-users';

    protected static ?int $navigationSort = 0;

    public static function form(Form $form): Form
    {
        return $form
        ->schema([
            Forms\Components\Group::make()
                ->schema([              

                    Forms\Components\Section::make('General')
                        ->schema([
                            Forms\Components\Select::make('user_id')
                                ->label('User ID')
                                ->relationship(name:'users', titleAttribute:'first_name')
                                ->required()
                                ->live(onBlur: true)
                                ->searchable(),
                            Forms\Components\Select::make('business_id')
                            ->label('Business Name')
                                ->relationship(name:'business', titleAttribute:'name')
                                ->required()
                                ->live(onBlur: true)
                                ->searchable()->preload(),
                            Forms\Components\Select::make('role_id')
                            ->label('Role Name')
                                ->relationship(name:'roles', titleAttribute:'name')
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
                                ->helperText('This employee will be hidden from all sales channels.')
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
                    ->label('id')
                    ->searchable()
                    ->sortable(),
                
                Tables\Columns\TextColumn::make('user.first_name')
                    ->label('Name')
                    ->searchable()
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
            'index' => Pages\ListEmployees::route('/'),
            'create' => Pages\CreateEmployee::route('/create'),
            'edit' => Pages\EditEmployee::route('/{record}/edit'),
        ];
    }
}
