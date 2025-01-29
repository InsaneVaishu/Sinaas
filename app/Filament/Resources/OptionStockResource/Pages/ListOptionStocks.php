<?php

namespace App\Filament\Resources\OptionStockResource\Pages;

use App\Filament\Resources\OptionStockResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListOptionStocks extends ListRecords
{
    protected static string $resource = OptionStockResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
