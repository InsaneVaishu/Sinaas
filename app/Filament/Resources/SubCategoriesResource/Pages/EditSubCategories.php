<?php

namespace App\Filament\Resources\SubCategoriesResource\Pages;

use App\Filament\Resources\SubCategoriesResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditSubCategories extends EditRecord
{
    protected static string $resource = SubCategoriesResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
