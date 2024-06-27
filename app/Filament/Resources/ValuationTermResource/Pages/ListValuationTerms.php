<?php

namespace App\Filament\Resources\ValuationTermResource\Pages;

use App\Filament\Resources\ValuationTermResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListValuationTerms extends ListRecords
{
    protected static string $resource = ValuationTermResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
