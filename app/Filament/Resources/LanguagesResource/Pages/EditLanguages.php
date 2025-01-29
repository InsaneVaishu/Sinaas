<?php

namespace App\Filament\Resources\LanguagesResource\Pages;

use App\Filament\Resources\LanguagesResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditLanguages extends EditRecord
{
    protected static string $resource = LanguagesResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
