<?php

namespace App\Filament\Resources;

use App\Filament\Resources\UserResource\Pages;
use App\Filament\Resources\UserResource\RelationManagers;
use App\Models\User;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class UserResource extends Resource
{
    protected static ?string $model = User::class;

    protected static ?string $slug = '/admin';
    protected static ?string $navigationGroup = 'Sinaas Settings';
    protected static ?string $navigationIcon = 'heroicon-s-user-circle';
    protected static ?string $navigationLabel = 'Admin';    
    protected static ?int $navigationSort = 9;

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
                ->email()
                ->required()
                ->maxLength(255),
                Forms\Components\TextInput::make('phone')
                ->maxLength(15),
                Forms\Components\Select::make('gender')
                ->options([
                    '1' => 'Male',
                    '2' => 'Female'
                ])->required(),
                Forms\Components\DatePicker::make('date_of_birth')
                ->required()
                ->maxDate(now()),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                //
                Tables\Columns\TextColumn::make('name')
                    ->searchable()
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
            'index' => Pages\ListUsers::route('/'),
            'create' => Pages\CreateUser::route('/create'),
            'edit' => Pages\EditUser::route('/{record}/edit'),
        ];
    }
}
