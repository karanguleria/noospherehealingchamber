<?php

namespace App\Nova;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Crypt;
use Laravel\Nova\Fields\ID;
use Laravel\Nova\Fields\Text;
use Laravel\Nova\Http\Requests\NovaRequest;
use Laravel\Nova\Fields\BelongsTo as FieldsBelongsTo;
use Quant\Invitaion\Invitaion as InvitationHome;

class Invitation extends Resource
{
    
    /**
     * The model the resource corresponds to.
     *
     * @var class-string<\App\Models\Invitation>
     */
    public static $model = \App\Models\Invitation::class;

    /**
     * The single value that should be used to represent the resource when being displayed.
     *
     * @var string
     */
    public static $title = 'email';

    /**
     * The columns that should be searched.
     *
     * @var array
     */
    public static $search = [
        'id',
    ];

    /**
     * Get the fields displayed by the resource.
     *
     * @param  \Laravel\Nova\Http\Requests\NovaRequest  $request
     * @return array
     */

    public static function availableForNavigation(Request $request)
    {
        //echo $request->user()->type_id;
        if($request->user()->type_id == 3){
            return true;
        } else{
            return false;

        }
         // Hides the resource from the sidebar
    }

    public function fields(NovaRequest $request)
    {
        return [
            ID::make()->sortable(),
            Text::make(__('Full Name'), 'name')->rules('required', 'max:255')->sortable(),
            Text::make(__('Email Address'), 'email')->rules('required', 'email', 'max:255')->sortable(),
            FieldsBelongsTo::make('Practitioner', 'practitioner', \App\Nova\User::class)->hideWhenCreating()->sortable(),
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
        return [
            (new InvitationHome())->withMeta(['id'=>Crypt::encrypt(auth()->id())])
        ];
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
        return [];
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
        if(auth()->user()->is_admin())
        {
           return $query->where('state', 1);
        }else{
            return $query->where('practitioner_id', $request->user()->id)->where('state', 1);
        }
    }
    public static function createButtonLabel()
    {
        return __('Send :resource', ['resource' => static::singularLabel()]);
    }


    
    // public static function label()
    // {
    //     return 'Send & Invite Another';
    // }

    // public static function singularLabel()
    // {
    //     return 'Send & Invite Another';
    // }
    /*public static function availableForNavigation(Request $request)
    {
        return $request->user()->role_id === 1;
    }*/
    
}
