<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * Class McqAnswer
 *
 * @package App\Models
 *
 * @property int $id
 * @property int $question_id
 * @property string $content
 * @property bool $is_correct
 * @property \Illuminate\Support\Carbon $created_at
 * @property \Illuminate\Support\Carbon $updated_at
 *
 * @property-read \App\Models\Question $question
 */
class McqAnswer extends Model
{
    use HasFactory;

    /**
     * Define the relationship between the McqAnswer and Question models.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function question()
    {
        return $this->belongsTo(Question::class);
    }
}
