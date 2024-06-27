<?php

namespace App\Filament\Resources\ValuationTermResource\Pages;

use App\Filament\Resources\ValuationTermResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditValuationTerm extends EditRecord
{
    protected static string $resource = ValuationTermResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
