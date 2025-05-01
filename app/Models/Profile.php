<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Notifications\Notifiable;
class Profile extends Model
{
    //
    use HasFactory, Notifiable;
    protected $fillable = [
        'first_name',
        'last_name',
        'email',
        'date_of_birth',
        'place_of_birth',
        'religion',
        'occupation',
        'country',
        'city',
        'user_id',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
