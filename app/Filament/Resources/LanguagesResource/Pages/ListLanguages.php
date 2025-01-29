<?php

namespace App\Filament\Resources\LanguagesResource\Pages;

use App\Filament\Resources\LanguagesResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListLanguages extends ListRecords
{
    protected static string $resource = LanguagesResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
