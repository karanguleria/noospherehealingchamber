<?php

namespace App\Nova;

use App\Models\User;
use App\Nova\Actions\DownloadResult;
use App\Nova\Actions\ShowResults;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Laravel\Nova\Fields\ID;
use Laravel\Nova\Fields\Text;
use Laravel\Nova\Fields\DateTime;
use Laravel\Nova\Fields\HasMany;
use Laravel\Nova\Fields\BelongsTo as FieldsBelongsTo;
use Laravel\Nova\Http\Requests\NovaRequest;
use Laravel\Nova\Query\Search\SearchableRelation;

class Result extends Resource
{
    /**
     * The model the resource corresponds to.
     *
     * @var class-string<\App\Models\Result>
     */
    public static $model = \App\Models\Result::class;

    /**
     * The single value that should be used to represent the resource when being displayed.
     *
     * @var string
     */
    public static $title = 'id';

    /**
     * The columns that should be searched.
     *
     * @var array
     */
    
    // public static $search = [
    //     'id',
    //     'user_id',
    //     'Excess',
    //     'Balance',
    //     'Insufficiency'
    // ];
    /**
 * Get the searchable columns for the resource.
 *
 * @return array
 */
public static function searchableColumns()
{
    return ['id', new SearchableRelation('user', 'name'),'Excess','Balance','Insufficiency'];
}

    public static function availableForNavigation(Request $request)
    {
        return false; // Hides the resource from the sidebar
    }


    /**
     * Get the fields displayed by the resource.
     *
     * @param  \Laravel\Nova\Http\Requests\NovaRequest  $request
     * @return array
     */
    public function fields(NovaRequest $request)
    {
        return [
            ID::make()->sortable(),
            FieldsBelongsTo::make('User')->sortable(),
            Text::make('Excess')->sortable(),
            Text::make('Balance')->sortable(),
            Text::make('Insufficiency')->sortable(),
            DateTime::make('Created At')->sortable(),
            HasMany::make('Answers'),
        ];
    }

    /**
     * Get the cards available for the request.
     *
     * @param  \Laravel\Nova\Http\Requests\NovaRequest  $request
     * @return array
     */
    public function cards(NovaRequest $request)
    {
        return [];
    }

    /**
     * Get the filters available for the resource.
     *
     * @param  \Laravel\Nova\Http\Requests\NovaRequest  $request
     * @return array
     */
    public function filters(NovaRequest $request)
    {
        return [];
    }

    /**
     * Get the lenses available for the resource.
     *
     * @param  \Laravel\Nova\Http\Requests\NovaRequest  $request
     * @return array
     */
    public function lenses(NovaRequest $request)
    {
        return [];
    }

    /**
     * Get the actions available for the resource.
     *
     * @param  \Laravel\Nova\Http\Requests\NovaRequest  $request
     * @return array
     */
    public function actions(NovaRequest $request)
    {
        return [
            (new ShowResults)->onlyOnTableRow()->canRun(function(){
                return true;
            }),
            (new DownloadResult)->onlyOnTableRow()->canRun(function(){
                return true;
            }),
        ];
    }
     /**
     * Build an "index" query for the given resource.
     *
     * @param  \Laravel\Nova\Http\Requests\NovaRequest  $request
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public static function indexQuery(NovaRequest $request, $query)
    {
        if(auth()->user()->is_admin()){
            return $query;
        }else{
            return $query->whereRelation('user',function($query){
                $query->where('practitioner_id',auth()->id());
            });
    }
    }
}
