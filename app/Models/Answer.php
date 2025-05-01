<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * Class Answer
 *
 * @package App\Models
 *
 * @property int $id
 * @property int $user_id
 * @property int $result_id
 * @property string $content
 * @property \Illuminate\Support\Carbon $created_at
 * @property \Illuminate\Support\Carbon $updated_at
 *
 * @property-read \App\Models\User $user
 * @property-read \App\Models\Result $result
 */
class Answer extends Model
{
    use HasFactory;

    /**
     * Define the relationship between the Answer and User models.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Define the relationship between the Answer and Result models.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function result()
    {
        return $this->belongsTo(Result::class);
    }
}