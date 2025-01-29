<?php

namespace App\Filament\Resources\CuisinesResource\Pages;

use App\Filament\Resources\CuisinesResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditCuisines extends EditRecord
{
    protected static string $resource = CuisinesResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
