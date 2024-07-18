<?php

namespace App\Filament\Resources\CourseResource\Widgets;

use Filament\Widgets\Widget;
use Illuminate\Database\Eloquent\Model;

class CsvExportWidget extends Widget
{
    protected static string $view = 'filament.resources.course-resource.widgets.csv-export-widget';

    public ?Model $record = null;
}
