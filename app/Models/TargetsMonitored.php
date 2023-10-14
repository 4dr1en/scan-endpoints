<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TargetsMonitored extends Model
{
    use HasFactory;


    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'user_id',
        'name',
        'description',
        'protocol',
        'path',
        'port',
        'interval',
        'status',
    ];

    public function processedTargets()
    {
        return $this->hasMany(
            ProcessedTarget::class
        );
    }

    public function workspace()
    {
        return $this->belongsTo(
            Workspace::class
        );
    }
}