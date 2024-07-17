<?php

namespace App\Filament\Resources\CustomDeadlineResource\Pages;

use App\Filament\Resources\CustomDeadlineResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListCustomDeadlines extends ListRecords
{
    protected static string $resource = CustomDeadlineResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
