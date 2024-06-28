<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Althinect\FilamentSpatieRolesPermissions\Concerns\HasSuperAdmin;
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

    /**
     * This method checks if this user is part of at least one
     * of the projects given in projects collection param.
     *
     * @param Collection $projects
     * @return bool
     */
    public function attendsProjects(Collection $projects): bool
    {
        $thisUserProjects = $this->projects()->get();
        $intersection = $thisUserProjects->intersect($projects);

        return $intersection->isNotEmpty();
    }
}
