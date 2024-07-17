<?php

namespace App\Filament\Resources\CustomDeadlineResource\Pages;

use App\Filament\Resources\CustomDeadlineResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditCustomDeadline extends EditRecord
{
    protected static string $resource = CustomDeadlineResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\ViewAction::make(),
            Actions\DeleteAction::make(),
        ];
    }
}
