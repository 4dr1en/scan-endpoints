<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Workspace extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'description',
    ];

    /**
     * Get the users of the workspace.
     */
    public function users()
    {
        return $this->belongsToMany(User::class)->using(UserWorkspace::class)->withPivot('role')->withTimestamps();
    }

    /**
     * Get the targets of the workspace.
     */
    public function targetsMonitored()
    {
        return $this->hasMany(TargetsMonitored::class);
    }
}