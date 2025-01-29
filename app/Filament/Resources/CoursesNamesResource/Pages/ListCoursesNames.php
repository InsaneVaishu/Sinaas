<?php

namespace App\Filament\Resources\CoursesNamesResource\Pages;

use App\Filament\Resources\CoursesNamesResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListCoursesNames extends ListRecords
{
    protected static string $resource = CoursesNamesResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
