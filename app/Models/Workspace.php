<?php

namespace App\Models;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

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
        return $this->belongsToMany(User::class)
            ->using(UserWorkspace::class)
            ->withPivot('role')
            ->withTimestamps();
    }

    /**
     * Get the targets of the workspace.
     */
    public function targetsMonitoreds()
    {
        return $this->hasMany(TargetsMonitored::class);
    }
}