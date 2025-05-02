<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Support\Facades\Hash;

/**
 * Class User
 *
 * @package App\Models
 *
 * @property int $id
 * @property string $name
 * @property string $email
 * @property string $password
 * @property int $type_id
 * @property int $practitioner_id
 * @property \Illuminate\Support\Carbon $email_verified_at
 * @property string $remember_token
 * @property \Illuminate\Support\Carbon $created_at
 * @property \Illuminate\Support\Carbon $updated_at
 *
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Result[] $results
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Answer[] $answers
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Invitation[] $invitations
 * @property-read \App\Models\User $practitioner
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\User[] $users
 */
class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'type_id',
        'first_name',
        'last_name',
        'date_of_birth',
        'country',
        'wellness_goals',
        'company_name',
        'practitioner_id',
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
        'date_of_birth' => 'datetime',
        
    ];

    /**
     * Define the relationship between the User and Result models.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function results()
    {
        return $this->hasMany(Result::class);
    }

    /**
     * Define the relationship between the User and Answer models.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function answers()
    {
        return $this->hasMany(Answer::class);
    }

    /**
     * Define the relationship between the User and Invitation models.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function invitations()
    {
        return $this->hasMany(Invitation::class, 'practitioner_id');
    }

    /**
     * Define the relationship between the User and another User model as a practitioner.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function practitioner()
    {
        return $this->belongsTo(User::class, 'practitioner_id', 'id');
    }

    /**
     * Define the relationship between the User and multiple User models as users of the practitioner.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function users()
    {
        return $this->hasMany(User::class, 'practitioner_id', 'id');
    }

    /**
     * Check if the user is an admin based on the type_id attribute.
     *
     * @return bool
     */
    public function is_admin()
    {
        return $this->type_id == 3;
    }

    /**
     * Check if the user is a practitioner based on the type_id attribute.
     *
     * @return bool
     */
    public function is_practitioner()
    {
        return $this->type_id == 2;
    }


    public function usersession()
    {
        return $this->hasMany(UserSession::class, 'user_id', 'id');
    } 

    public function profile(): HasOne
    {
        return $this->hasOne(Profile::class);
    }
    
    protected static function booted()
    {
        static::saving(function ($user) {
            // Check if the password is empty
            if (empty($user->password)) {
                // Set a static password if not provided
                $user->password = Hash::make('noosphere@123'); // Example static password
            }

            if (empty($user->type_id)) {
                // Set a static type id if not provided
                $user->type_id = 1; // Example static type id
            }
        });
    }
}
