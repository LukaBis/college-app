<?php

namespace App\Filament\Resources\ProjectResource\Pages;

use App\Filament\Resources\ProjectResource;
use App\Filament\Resources\ProjectResource\Widgets\AssignToProjectWidget;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditProject extends EditRecord
{
    protected static string $resource = ProjectResource::class;

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('edit', ['record' => $this->getRecord()]);
    }

    public function getHeaderWidgets(): array
    {
        $isStudent = auth()->user()->hasRole('Student');
        $projectSelected = false;
        $hasProjectInThisCourse = auth()->user()->projects()->where('course_id', $this->record->course_id)->count() > 0;
        $maxNumberOfStudents = $this->record->max_students <= $this->record->students()->count();

        if ($isStudent) {
            $projectSelected = auth()->user()->projects()->get()->pluck('id')->contains($this->record->id);
        }

        return [
            AssignToProjectWidget::make([
                'studentId' => auth()->user()->id,
                'projectId' => $this->record->id,
                'projectSelected' => $projectSelected,
                'displayWarning' => $hasProjectInThisCourse,
                'maxNumberOfStudents' => $maxNumberOfStudents,
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
