<?php

namespace App\Filament\Resources\OrdersStatusesResource\Pages;

use App\Filament\Resources\OrdersStatusesResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditOrdersStatuses extends EditRecord
{
    protected static string $resource = OrdersStatusesResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
