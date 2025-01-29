<?php

namespace App\Filament\Resources\ComboCategoriesResource\Pages;

use App\Filament\Resources\ComboCategoriesResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditComboCategories extends EditRecord
{
    protected static string $resource = ComboCategoriesResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
