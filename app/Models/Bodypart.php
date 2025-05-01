<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * Class Bodypart
 *
 * @package App\Models
 *
 * @property int $id
 * @property int $element_id
 * @property string $name
 * @property \Illuminate\Support\Carbon $created_at
 * @property \Illuminate\Support\Carbon $updated_at
 *
 * @property-read \App\Models\Element $element
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Question[] $questions
 */
class Bodypart extends Model
{
    use HasFactory;

    /**
     * Define the relationship between the Bodypart and Element models.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function element()
    {
        return $this->belongsTo(Element::class);
    }

    /**
     * Define the relationship between the Bodypart and Question models.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function questions()
    {
        return $this->hasMany(Question::class);
    }
}
