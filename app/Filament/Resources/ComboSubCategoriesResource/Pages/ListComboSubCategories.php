<?php

namespace App\Filament\Resources\ComboSubCategoriesResource\Pages;

use App\Filament\Resources\ComboSubCategoriesResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListComboSubCategories extends ListRecords
{
    protected static string $resource = ComboSubCategoriesResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
