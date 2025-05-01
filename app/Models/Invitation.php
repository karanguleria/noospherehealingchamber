<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * Class Invitation
 *
 * @package App\Models
 *
 * @property int $id
 * @property int $practitioner_id
 * @property string $other_attributes
 * @property \Illuminate\Support\Carbon $created_at
 * @property \Illuminate\Support\Carbon $updated_at
 *
 * @property-read \App\Models\User $practitioner
 */
class Invitation extends Model
{
    use HasFactory;

    /**
     * Boot method to handle model events.
     */
    public static function boot()
    {
        parent::boot();

        // Creating event to set the practitioner_id before saving the model.
        static::creating(function ($invitation) {
            $invitation->setPractitionerId();
        });
    }

    /**
     * Define the relationship between the Invitation and User models.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function practitioner()
    {
        return $this->belongsTo(User::class, 'practitioner_id');
    }

    /**
     * Set the practitioner_id attribute based on the authenticated user.
     */
    protected function setPractitionerId()
    {
        // Check if there is an authenticated user before setting the practitioner_id.
        if (auth()->check()) {
            $this->practitioner_id = auth()->user()->id;
        }
    }
}
