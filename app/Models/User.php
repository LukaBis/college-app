<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Althinect\FilamentSpatieRolesPermissions\Concerns\HasSuperAdmin;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Collection;
use Laravel\Sanctum\HasApiTokens;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, HasRoles, HasSuperAdmin, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'surname',
        'email',
        'password',
        'jmbag',
        'active',
        'team_lead',
        'activation_dates',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
        'activation_dates' => 'array',
    ];

    public bool $processingUpdate = false;

    protected static function boot()
    {
        parent::boot();

        static::updated(function ($student) {
            // Check if the 'active' field is being set to false
            if ($student->isDirty('active') && $student->active === false) {
                $student->withoutEvents(function () use ($student) {
                    $student->deactivation_date = Carbon::now()->setTimezone('Europe/Zagreb');
                    $student->save();
                });
            }
        });
    }

    /* these are courses that this user (course admin) manages */
    public function course(): BelongsToMany
    {
        return $this->belongsToMany(Course::class, 'admin_course', 'user_id', 'course_id')->withPivot(['course_id', 'user_id']);
    }

    /* this is course that this user (student) is attending */
    public function attendingCourse(): BelongsToMany
    {
        return $this->belongsToMany(Course::class, 'course_user', 'user_id', 'course_id');
    }

    public function projects(): BelongsToMany
    {
        return $this->belongsToMany(Project::class, 'project_user', 'user_id', 'project_id')->withPivot('team_lead');
    }

    public function customDeadlines(): BelongsToMany
    {
        return $this->belongsToMany(CustomDeadline::class, 'custom_deadline_user', 'user_id', 'custom_deadline_id');
    }

    /**
     * This method checks if this user is part of at least one
     * of the projects given in projects collection param.
     */
    public function attendsProjects(Collection $projects): bool
    {
        $thisUserProjects = $this->projects()->get();
        $intersection = $thisUserProjects->intersect($projects);

        return $intersection->isNotEmpty();
    }

    public function getFullNameAttribute()
    {
        return "{$this->name} {$this->surname}";
    }

    public function pointsInValuationTerm(ValuationTerm $valuationTerm): int
    {
        $deactivationDate = Carbon::parse($this->deactivation_date);
        $termDeadlineDate = Carbon::parse($valuationTerm->term);

        // if student is inactive for this term the return 0
        if (($this->active === 0) && $deactivationDate->isBefore($termDeadlineDate)) {
            return 0;
        }

        $totalPoints = 0;

        $valuationTerm->valuations()
            ->where([
                ['rated_student_id', '=', $this->id],
                ['self_evaluation', '=', false],
            ])
            ->get()
            ->each(function ($valuation) use (&$totalPoints) {
                $totalPoints += $valuation?->valuation ? $valuation->totalPoints() : 0;
            });

        return $totalPoints;
    }

    public function sumOfAllPointsInValuationTerm(ValuationTerm $valuationTerm): int
    {
        $course = $valuationTerm->course;
        $deadline = Carbon::parse($valuationTerm->term);
        $inactiveStudentIds = User::where([
            ['active', '=', false],
            ['deactivation_date', '<', $deadline],
        ])
            ->get()
            ->pluck('id')
            ->toArray();

        $students = $this->projects()
            ->where('course_id', '=', $course->id)
            ->get()
            ->first()
            ->students()
            ->whereNotIn('users.id', $inactiveStudentIds)
            ->get();

        $sum = 0;

        $students->each(function ($student) use (&$sum, $valuationTerm) {
            $sum += $student->pointsInValuationTerm($valuationTerm);
        });

        return $sum;
    }

    /*
     * This is not always the same because tem points can vary between
     * valuation terms due to student inactivity
     */
    public function teamPoints(ValuationTerm $valuationTerm): int
    {
        $course = $valuationTerm->course;
        $project = $this->projects()->where('course_id', '=', $course->id)->get()->first();
        $students = $project->students()->get();
        $numberOfActiveStudents = $this->getActiveStudentsCount($students, $valuationTerm);

        return $project->given_points * $numberOfActiveStudents;
    }

    public function finalValuationTermPoints(ValuationTerm $valuationTerm)
    {
        // check if student has any project, if not return 0
        if ($this->projects()->count() === 0) {
            return 0;
        }

        $teamPoints = $this->teamPoints($valuationTerm);
        $sumOfAllStudentsPoints = $this->sumOfAllPointsInValuationTerm($valuationTerm);
        $thisStudentPoints = $this->pointsInValuationTerm($valuationTerm);

        if ($sumOfAllStudentsPoints === 0) {
            return 0;
        }

        return ($teamPoints / $sumOfAllStudentsPoints) * $thisStudentPoints;
    }

    public function getFinalPointsOfAllValuationTerms(Course $course)
    {
        // check if student has any project, if not return 0
        if ($this->projects()->count() === 0) {
            return 0;
        }

        if (! $this->active) {
            return 0;
        }

        if (! $this->attendingCourse->contains($course)) {
            throw new \Exception('Student does not attend this course');
        }

        $totalPoints = 0;
        $count = 0;
        $validationTerms = $course->valuationTerms;
        $negativePoints = 0;
        $negativePointsFromCustomDeadlines = 0;

        $validationTerms->each(function ($validationTerm) use (&$totalPoints, &$count, $course, &$negativePoints) {
            $totalPoints += $this->finalValuationTermPoints($validationTerm);
            $negativePoints += $this->valuationTermNegativePoints($validationTerm, $course);
            $count += 1;
        });

        $course->customDeadlines->each(function ($customDeadline) use (&$negativePointsFromCustomDeadlines) {
            $negativePointsFromCustomDeadlines += $this->negativePointsFromCustomDeadline($customDeadline);
        });

        if ($count === 0) {
            return 0;
        }

        return ($totalPoints / $count) - $negativePoints - $negativePointsFromCustomDeadlines;
    }

    private function getActiveStudentsCount(Collection $students, ValuationTerm $valuationTerm): int
    {
        $activeStudent = $students->filter(function ($student) use ($valuationTerm) {
            if ($student->active) {
                return true;
            }

            $deactivationDate = Carbon::parse($student->deactivation_date);
            $termDeadlineDate = Carbon::parse($valuationTerm->term);

            if (($student->active === 0) && $deactivationDate->isBefore($termDeadlineDate)) {
                return false;
            }

            return true;
        });

        return $activeStudent->count();
    }

    /**
     * User has his/her valuations that belong to some valuation term. If even one of those
     * valuations is created after the valuation term deadline, then user gets negative points.
     *
     * @param ValuationTerm $valuationTerm
     * @return int
     */
    public function valuationTermNegativePoints(ValuationTerm $valuationTerm, Course $course): int
    {
        if (! $this->attendingCourse->contains($course)) {
            throw new \Exception('Student does not attend this course');
        }

        // for each valuation where this user is evaluator and where valuation belongs to
        // given valuation term, check if each valuation is created before the deadline
        $valuations = Valuation::where('student_evaluator_id', '=', $this->id)
            ->where('valuation_term_id', '=', $valuationTerm->id)
            ->get();

        $deadline = Carbon::parse($valuationTerm->term);

        foreach ($valuations as $valuation) {
            if (! $valuation->created_at->isBefore($deadline)) {
                return $valuationTerm->negative_points;
            }
        }

        return 0;
    }

    public function negativePointsFromCustomDeadline(CustomDeadline $customDeadline): int
    {
        $exists = CustomDeadlineUser::where('custom_deadline_id', '=', $customDeadline->id)
            ->where('user_id', '=', $this->id)
            ->where('applied', '=', true)
            ->exists();

        if ($exists) {
            return $customDeadline->negative_points;
        }

        return 0;
    }
}
