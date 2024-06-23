<?php

namespace App\Filament\Resources\UserResource\Pages;

use App\Filament\Resources\UserResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditUser extends EditRecord
{
    protected static string $resource = UserResource::class;

    public function getFooterWidgets(): array
    {
        $records = $this->record->activation_dates;

        if ($records === null) {
            $records = [];
        }

        return [
            UserResource\Widgets\StudentActivationRecordsWidget::make([
                'records' => $records,
            ]),
        ];
    }

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
