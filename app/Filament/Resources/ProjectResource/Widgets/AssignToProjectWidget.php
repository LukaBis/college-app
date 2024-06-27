<?php

namespace App\Filament\Resources\ProjectResource\Widgets;

use Filament\Widgets\Widget;

class AssignToProjectWidget extends Widget
{
    protected static string $view = 'filament.resources.project-resource.widgets.assign-to-project-widget';

    public int $studentId;

    public int $projectId;

    public bool $projectSelected;

    /**
     * @var bool warning displays that student has already picked his project for given course and he can't choose anymore
     */
    public bool $displayWarning;

    public bool $maxNumberOfStudents;

    public static function canView(): bool
    {
        return auth()->user()->hasRole('Student');
    }

    public function render(): \Illuminate\Contracts\View\View
    {
        return view(static::$view);
    }
}
