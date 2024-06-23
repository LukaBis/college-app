<?php

namespace App\Filament\Resources\UserResource\Widgets;

use Filament\Widgets\Widget;

class StudentActivationRecordsWidget extends Widget
{
    protected static string $view = 'filament.resources.user-resource.widgets.student-activation-records-widget';

    public array $records;
}
