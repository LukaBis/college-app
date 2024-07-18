<?php

namespace App\Http\Controllers;

use App\Exports\ValuationsExport;
use App\Models\Course;
use Maatwebsite\Excel\Facades\Excel;

class ValuationsController extends Controller
{
    public function csvExport(Course $course)
    {
        return Excel::download(new ValuationsExport($course), 'valuations.csv');
    }
}
