<?php

namespace App\Nova\Concerns;

/**
 * Adds search support for session resources that matches the values as they
 * are displayed in the index table rather than the raw database values.
 *
 * Specifically it allows searching by:
 *  - Session Start / Session End formatted as "Y-m-d H:i:s A" (with AM/PM)
 *  - Total Session Time computed string e.g. "0:01:16 Mins" / "1:02:03 Hrs"
 *  - Recording URL
 *  - Primary key (id)
 */
trait SearchesSessionColumns
{
    /**
     * Apply the search query to the query.
     *
     * Signature must stay untyped to match Laravel\Nova\Resource::applySearch($query, $search).
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @param  string  $search
     * @return \Illuminate\Database\Eloquent\Builder
     */
    protected static function applySearch($query, $search)
    {
        $like = '%' . $search . '%';

        // Absolute difference, in seconds, between the two timestamps.
        $diff = 'ABS(TIMESTAMPDIFF(SECOND, session_start, session_end))';

        // Rebuild the exact string produced by the model's
        // getTotalSessionTimeAttribute() accessor so the search matches
        // what the user actually sees in the table.
        $totalTimeExpression = "CASE
            WHEN FLOOR({$diff} / 3600) > 0 THEN CONCAT(
                FLOOR({$diff} / 3600), ':',
                LPAD(FLOOR(MOD({$diff}, 3600) / 60), 2, '0'), ':',
                LPAD(MOD({$diff}, 60), 2, '0'), ' Hrs'
            )
            ELSE CONCAT(
                '0:',
                LPAD(FLOOR(MOD({$diff}, 3600) / 60), 2, '0'), ':',
                LPAD(MOD({$diff}, 60), 2, '0'), ' Mins'
            )
        END";

        return $query->where(function ($query) use ($like, $totalTimeExpression) {
            $query->where('id', 'like', $like)
                ->orWhere('recording_url', 'like', $like)
                // Raw stored datetime values (e.g. "2026-06-30 07:45:49").
                ->orWhere('session_start', 'like', $like)
                ->orWhere('session_end', 'like', $like)
                // Formatted datetime values matching the displayed "Y-m-d H:i:s A".
                ->orWhereRaw("DATE_FORMAT(session_start, '%Y-%m-%d %H:%i:%s %p') LIKE ?", [$like])
                ->orWhereRaw("DATE_FORMAT(session_end, '%Y-%m-%d %H:%i:%s %p') LIKE ?", [$like])
                // Computed Total Session Time string.
                ->orWhereRaw("{$totalTimeExpression} LIKE ?", [$like]);
        });
    }
}
