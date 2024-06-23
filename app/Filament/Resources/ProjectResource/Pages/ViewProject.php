<?php

namespace App\Filament\Resources\ProjectResource\Pages;

use App\Filament\Resources\ProjectResource;
use App\Filament\Resources\ProjectResource\Widgets\AssignToProjectWidget;
use Filament\Resources\Pages\ViewRecord;

class ViewProject extends ViewRecord
{
    protected static string $resource = ProjectResource::class;

    public function getHeaderWidgets(): array
    {
        return [
            AssignToProjectWidget::make([
                'studentId' => auth()->user()->id,
                'projectId' => $this->record->id,
            ]),
        ];
    }
}
