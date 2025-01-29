<?php

namespace App\Filament\Resources\ComboSubCategoriesResource\Pages;

use App\Filament\Resources\ComboSubCategoriesResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditComboSubCategories extends EditRecord
{
    protected static string $resource = ComboSubCategoriesResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
