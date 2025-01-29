<?php

namespace App\Filament\Resources\KitchenNamesResource\Pages;

use App\Filament\Resources\KitchenNamesResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditKitchenNames extends EditRecord
{
    protected static string $resource = KitchenNamesResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
