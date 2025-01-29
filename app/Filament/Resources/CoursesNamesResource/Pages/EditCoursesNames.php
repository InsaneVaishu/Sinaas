<?php

namespace App\Filament\Resources\CoursesNamesResource\Pages;

use App\Filament\Resources\CoursesNamesResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditCoursesNames extends EditRecord
{
    protected static string $resource = CoursesNamesResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
