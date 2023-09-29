<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\Pivot;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class UserWorkspace extends Pivot
{
    use HasFactory;

    protected $table = 'user_workspace';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'role',
    ];

    /**
     * Get the user of the user_workspace.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the workspace of the user_workspace.
     */
    public function workspace()
    {
        return $this->belongsTo(Workspace::class);
    }
}