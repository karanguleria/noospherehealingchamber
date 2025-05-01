<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * Class Element
 *
 * @package App\Models
 *
 * @property int $id
 * @property string $name
 * @property \Illuminate\Support\Carbon $created_at
 * @property \Illuminate\Support\Carbon $updated_at
 *
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Bodypart[] $bodyparts
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Question[] $questions
 */
class Element extends Model
{
    use HasFactory;

    /**
     * Define the relationship between the Element and Bodypart models.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany<Bodypart>
     */
    public function bodyparts()
    {
        return $this->hasMany(Bodypart::class);
    }

    /**
     * Define the relationship between the Element and Question models.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany<Question>
     */
    public function questions()
    {
        return $this->hasMany(Question::class);
    }
}
