<?php

namespace App\Models;

use Carbon\Carbon;

use Illuminate\Database\Eloquent\Model;

class UserSession extends Model
{
    //

    protected $fillable = [
        'user_id',
        'notes',
        'audio',
        'image',
        'practitioner_id',
        'session_start',
        'state',
        'type',
        'recording_url',
        'image_1',
        'image_2',
        'session_end',
        'total_session_time',
        'audio_enabled',
        'healing_type',
        'gender',
        'voice_recording_enabled',
        'created_at',
        'updated_at'
    ];
    protected $casts = [
        'session_start' => 'datetime',
        'session_end' => 'datetime',
    ];
    public static function boot()
    {
        parent::boot();

        // Creating event to set the practitioner_id before saving the model.
        static::creating(function ($usersession) {
            $usersession->setPractitionerId();
        });
    }

    public function practitioner()
    {
        return $this->belongsTo(User::class, 'practitioner_id');
    }

    public function user()
    {
    return $this->belongsTo(User::class, 'user_id');
    }
    protected function setPractitionerId()
    {
        // Check if there is an authenticated user before setting the practitioner_id.
        if (auth()->check()) {
            $this->practitioner_id = auth()->user()->id;
        }
    }

    public function getTotalSessionTimeAttribute()
    {
        if (!$this->session_start || !$this->session_end) {
            return null;
        }

        $diffInSeconds = $this->session_start->diffInSeconds($this->session_end);

        $hours = floor($diffInSeconds / 3600);
        $minutes = floor(($diffInSeconds % 3600) / 60);
        $seconds = $diffInSeconds % 60;

        if ($hours > 0) {
            return sprintf('%d:%02d:%02d Hrs', $hours, $minutes, $seconds);
        } else {
            return sprintf('0:%02d:%02d Mins', $minutes, $seconds);
        }
    }
}
