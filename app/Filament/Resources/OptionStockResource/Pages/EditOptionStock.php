<?php

namespace App\Filament\Resources\OptionStockResource\Pages;

use App\Filament\Resources\OptionStockResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditOptionStock extends EditRecord
{
    protected static string $resource = OptionStockResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
