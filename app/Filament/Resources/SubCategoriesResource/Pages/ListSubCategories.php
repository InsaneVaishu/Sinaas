<?php

namespace App\Filament\Resources\SubCategoriesResource\Pages;

use App\Filament\Resources\SubCategoriesResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListSubCategories extends ListRecords
{
    protected static string $resource = SubCategoriesResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
