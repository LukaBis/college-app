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
        $isStudent = auth()->user()->hasRole('Student');
        $projectSelected = false;
        $hasProjectInThisCourse = auth()->user()->projects()->where('course_id', $this->record->course_id)->count() > 0;

        if ($isStudent) {
            $projectSelected = auth()->user()->projects()->get()->pluck('id')->contains($this->record->id);
        }

        return [
            AssignToProjectWidget::make([
                'studentId' => auth()->user()->id,
                'projectId' => $this->record->id,
                'projectSelected' => $projectSelected,
                'displayWarning' => $hasProjectInThisCourse,
            ]),
        ];
    }
}
