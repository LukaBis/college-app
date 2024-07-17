<?php

namespace App\Filament\Resources\CustomDeadlineResource\Pages;

use App\Filament\Resources\CustomDeadlineResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateCustomDeadline extends CreateRecord
{
    protected static string $resource = CustomDeadlineResource::class;
}
