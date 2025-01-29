<?php

namespace App\Filament\Resources\ProductsNamesResource\Pages;

use App\Filament\Resources\ProductsNamesResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditProductsNames extends EditRecord
{
    protected static string $resource = ProductsNamesResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
