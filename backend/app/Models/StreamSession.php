<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class StreamSession extends Model
{
    protected $fillable = [
        'user_id',
        'mode',
        'started_at',
        'ended_at',
        'duration_seconds',
    ];

    protected $casts = [
        'started_at' => 'datetime',
        'ended_at'   => 'datetime',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}