<?php

namespace App\Filament\Resources\ProductsNamesResource\Pages;

use App\Filament\Resources\ProductsNamesResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListProductsNames extends ListRecords
{
    protected static string $resource = ProductsNamesResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
