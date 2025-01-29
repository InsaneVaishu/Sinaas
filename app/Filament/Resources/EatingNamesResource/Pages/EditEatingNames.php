<?php

namespace App\Filament\Resources\EatingNamesResource\Pages;

use App\Filament\Resources\EatingNamesResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditEatingNames extends EditRecord
{
    protected static string $resource = EatingNamesResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
