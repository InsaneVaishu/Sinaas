<?php

namespace App\Filament\Resources\EatingNamesResource\Pages;

use App\Filament\Resources\EatingNamesResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListEatingNames extends ListRecords
{
    protected static string $resource = EatingNamesResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
