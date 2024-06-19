<?php

namespace App\Filament\Resources\ProjectResource\Widgets;

use Filament\Widgets\Widget;

class AssignToProjectWidget extends Widget
{
    protected static string $view = 'filament.resources.project-resource.widgets.assign-to-project-widget';

    public function render(): \Illuminate\Contracts\View\View
    {
        return view(static::$view);
    }
}
