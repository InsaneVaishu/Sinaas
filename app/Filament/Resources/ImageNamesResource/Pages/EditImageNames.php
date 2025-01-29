<?php

namespace App\Filament\Resources\ImageNamesResource\Pages;

use App\Filament\Resources\ImageNamesResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditImageNames extends EditRecord
{
    protected static string $resource = ImageNamesResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
