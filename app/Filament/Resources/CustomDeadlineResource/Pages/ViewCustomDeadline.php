<?php

namespace App\Filament\Resources\CustomDeadlineResource\Pages;

use App\Filament\Resources\CustomDeadlineResource;
use Filament\Actions;
use Filament\Resources\Pages\ViewRecord;

class ViewCustomDeadline extends ViewRecord
{
    protected static string $resource = CustomDeadlineResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\EditAction::make(),
        ];
    }
}
