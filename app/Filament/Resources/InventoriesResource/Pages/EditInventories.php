<?php

namespace App\Filament\Resources\InventoriesResource\Pages;

use App\Filament\Resources\InventoriesResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditInventories extends EditRecord
{
    protected static string $resource = InventoriesResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
