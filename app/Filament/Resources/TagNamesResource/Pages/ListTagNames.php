<?php

namespace App\Filament\Resources\TagNamesResource\Pages;

use App\Filament\Resources\TagNamesResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListTagNames extends ListRecords
{
    protected static string $resource = TagNamesResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
