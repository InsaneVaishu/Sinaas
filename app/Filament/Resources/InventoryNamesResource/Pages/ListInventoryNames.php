<?php

namespace App\Filament\Resources\InventoryNamesResource\Pages;

use App\Filament\Resources\InventoryNamesResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListInventoryNames extends ListRecords
{
    protected static string $resource = InventoryNamesResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
