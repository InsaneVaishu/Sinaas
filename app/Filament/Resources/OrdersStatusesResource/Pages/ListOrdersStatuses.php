<?php

namespace App\Filament\Resources\OrdersStatusesResource\Pages;

use App\Filament\Resources\OrdersStatusesResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListOrdersStatuses extends ListRecords
{
    protected static string $resource = OrdersStatusesResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
