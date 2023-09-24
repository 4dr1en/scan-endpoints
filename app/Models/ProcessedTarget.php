<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProcessedTarget extends Model
{
    use HasFactory;

    const UPDATED_AT = null;

        /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'targets_monitored_id',
        'response_code',
        'response_time'
    ];

    public function targetsMonitored()
    {
        return $this->belongsTo(TargetsMonitored::class);
    }
}
