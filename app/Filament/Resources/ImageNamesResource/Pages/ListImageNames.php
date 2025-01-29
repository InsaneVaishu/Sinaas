<?php

namespace App\Filament\Resources\ImageNamesResource\Pages;

use App\Filament\Resources\ImageNamesResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListImageNames extends ListRecords
{
    protected static string $resource = ImageNamesResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
