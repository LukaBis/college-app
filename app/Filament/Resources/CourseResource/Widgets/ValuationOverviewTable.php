<?php

namespace App\Filament\Resources\CourseResource\Widgets;

use Filament\Widgets\Widget;
use Illuminate\Database\Eloquent\Model;

class ValuationOverviewTable extends Widget
{
    protected static string $view = 'filament.resources.course-resource.widgets.valuation-overview-table';

    protected int | string | array $columnSpan = 'full';

    public ?Model $record = null;
}
