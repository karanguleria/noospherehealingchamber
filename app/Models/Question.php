<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * Class Question
 *
 * @package App\Models
 *
 * @property int $id
 * @property int $bodypart_id
 * @property int $element_id
 * @property string $text
 * @property \Illuminate\Support\Carbon $created_at
 * @property \Illuminate\Support\Carbon $updated_at
 *
 * @property-read \App\Models\Bodypart $bodypart
 * @property-read \App\Models\Element $element
 */
class Question extends Model
{
    use HasFactory;

    /**
     * Define the relationship between the Question and Bodypart models.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function bodypart()
    {
        return $this->belongsTo(Bodypart::class, 'bodypart_id');
    }

    /**
     * Define the relationship between the Question and Element models.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function element()
    {
        return $this->belongsTo(Element::class, 'element_id');
    }
}
