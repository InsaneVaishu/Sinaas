<?php

namespace App\Filament\Resources;

use App\Filament\Resources\CustomerResource\Pages;
use App\Filament\Resources\CustomerResource\RelationManagers;
use App\Models\Customer;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

use Filament\Forms\Components\FileUpload;


class CustomerResource extends Resource
{
    protected static ?string $model = Customer::class;

    // protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    protected static ?string $slug = '/users';
    protected static ?string $navigationGroup = 'Sinaas Settings';
    protected static ?string $navigationLabel = 'Users';
    protected static ?string $navigationIcon = 'heroicon-s-users';   
    protected static ?int $navigationSort = 8;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                //
                Forms\Components\TextInput::make('first_name')->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('last_name')
                    ->maxLength(255),
                Forms\Components\TextInput::make('email')
                    ->label('Email address')
                    ->required()
                    ->email()
                    ->maxLength(255)
                    ->unique(ignoreRecord: true),
                Forms\Components\TextInput::make('phone')->required()
                    ->maxLength(15),  
                    
                    Forms\Components\TextInput::make('password')
                    ->password()
                    ->autocomplete(false),    
                    
                
                Forms\Components\Textarea::make('address')
                    ->label('Address'),

                Forms\Components\DatePicker::make('date_of_birth')
                    ->maxDate(now()),                

                Forms\Components\FileUpload::make('image')
                    ->image()
                    ->imageEditor(), 
                
                    
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([

                Tables\Columns\ImageColumn::make('image')
                    ->label('Image'),

                Tables\Columns\TextColumn::make('first_name','last_name')
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
            'index' => Pages\ListCustomers::route('/'),
            'create' => Pages\CreateCustomer::route('/create'),
            'edit' => Pages\EditCustomer::route('/{record}/edit'),
        ];
    }


    public static function getGloballySearchableAttributes(): array
    {
        return ['first_name', 'last_name', 'email'];
    }
}
