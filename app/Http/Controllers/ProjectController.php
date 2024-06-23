<?php

namespace App\Http\Controllers;

use App\Models\Project;
use App\Models\User;
use Illuminate\Http\Request;

class ProjectController extends Controller
{
    public function assignStudentToProject(Project $project, User $user)
    {
        return response('Success', 200);
    }
}
