<?php

namespace App\Nova;

use Illuminate\Http\Request;
use Laravel\Nova\Fields\ID;
use Carbon\Carbon;
use Laravel\Nova\Fields\Image;
use Laravel\Nova\Fields\Text;
use Laravel\Nova\Fields\File;
use Laravel\Nova\Http\Requests\NovaRequest;
use Laravel\Nova\Fields\BelongsTo as FieldsBelongsTo;
use Illuminate\Support\Facades\Storage;
use Laravel\Nova\Fields\Textarea;
use App\Nova\Concerns\SearchesSessionColumns;
class UserSession extends Resource
{
    use SearchesSessionColumns;

    /**
     * The model the resource corresponds to.
     *
     * @var class-string<\App\Models\UserSession>
     */
    public static $model = \App\Models\UserSession::class;

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
    public static $search = [
        'id',
        'session_start',
        'session_end',
        'total_session_time',
        'recording_url',
    ];

    /**
     * Get the fields displayed by the resource.
     *
     * @param  \Laravel\Nova\Http\Requests\NovaRequest  $request
     * @return array
     */
    public function fields(NovaRequest $request)
    {
        return [
            /*FieldsBelongsTo::make('User', 'user', \App\Nova\User::class)
                ->display('name') // Assuming your User model has a 'name' field
                ->sortable(),*/
            Text::make('Session Start', 'session_start')
                ->resolveUsing(function ($value) {
                    return $value ? Carbon::parse($value)->format('Y-m-d H:i:s A') : 'N/A';
                })
                ->readonly(),

            Text::make('Session End', 'session_end')
                ->resolveUsing(function ($value) {
                    return $value ? Carbon::parse($value)->format('Y-m-d H:i:s A') : 'N/A';
                })
                ->readonly(),

            Text::make('Total Session Time', function () {
                return $this->total_session_time ?? 'N/A';
            }),
            /*Text::make('Audio Preview', function () {
                return $this->audio_file
                    ? '<audio controls><source src="' . url('storage/' . $this->audio_file) . '" type="audio/mpeg"></audio>'
                    : 'No audio available';
            })->onlyOnIndex()->asHtml(),*/
            Image::make(__('Image 1'), 'image_1')
                //->disk('public')
                //->sortable()
                ->readonly(), // Read-only,

            Image::make(__('Image 2'), 'image_2')
                //->disk('public')
                //->sortable()
                ->readonly(), // Read-only,

            File::make('Audio', 'audio')
                ->disk('public')
                //->path('audio-files')
                ->storeAs(function (Request $request) {
                    //return time() . '.' . $request->audio->getClientOriginalExtension();
                })
                //->prunable()
                ->download(function ($value, $disk) {
                    if ($value && Storage::disk($disk)->exists($value)) {
                        return Storage::disk($disk)->url($value);
                    }
                    return null;
                })->readonly(),

            // (optional) don't show on list page
            // allows delete with the resource
            //File::make(__('Image2'), 'image_2')->rules('max:255')->sortable()->readonly(), // Read-only,
            Text::make(__('Recording'), 'recording_url')
                ->rules('max:255')
                ->sortable()
                ->withMeta([
                    'extraAttributes' => [
                        'placeholder' => 'Save your recording link here'
                    ]
                ]),

            Textarea::make(__('Notes'), 'notes')
                ->sortable()
                ->withMeta([
                    'placeholder' => 'Add your notes here'
                ]),

                Text::make('Resume Session', function () {
                    // Only show the Resume Session button if is_complete is 0
                    if ($this->is_complete == 0) {
                        return '<a href="' . config('app.url') . '/start-session/' . auth()->user()->id . '/' . $this->id . '" 
                            class="shrink-0 h-9 px-4 focus:outline-none ring-primary-200 dark:ring-gray-600 focus:ring text-white dark:text-gray-800 
                            inline-flex items-center font-bold shadow rounded bg-primary-500 hover:bg-primary-400 active:bg-primary-600 text-sm" 
                            target="_blank">
                            Resume Session
                        </a>';
                    }
                    
                    return ''; // Show nothing if session is complete
                })->asHtml()->onlyOnIndex(),
            /*FieldsBelongsTo::make('Practitioner', 'practitioner', \App\Nova\User::class)->hideWhenCreating()->sortable()->readonly(),
            */ 
            ];
    }

    public static function availableForNavigation(Request $request)
    {
        return false; // Hides the resource from the sidebar
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
        return [];
    }

    public static function authorizedToCreate(Request $request)
    {
        // return !auth()->check() || !auth()->user()->is_premium_member();
        return false;
    }


    public function authorizedToDelete(Request $request)
    {
        return false;
    }

    /**
     * Remove Replicate button
     */
    public function authorizedToReplicate(Request $request)
    {
        return false;
    }

    public function title()
    {
         
        return $this->user ? $this->user->name : 'Session';
    }

    public static function resourceTitle(NovaRequest $request, $resource)
    {
        // return 'Update Session Details: ' . $resource->user->name;
        return 'Update Session Details:';
    }

    public static function label()
    {
        return 'Sessions';
    }

    public static function singularLabel()
    {
        $request = app(NovaRequest::class);

        if ($request->route('resourceId')) {
            return 'Session';
        }else{
        
            return 'Session';
        }
    }

    
    public static function indexQuery(NovaRequest $request, $query)
    {
        if(auth()->user()->is_admin()){
            return $query;
        }else if(auth()->user()->is_premium_member() || auth()->user()->is_free_member()){
            return $query->where('user_id',auth()->id())->where('is_complete',0);
        }
        else{
            return $query->whereRelation('user',function($query){
                $query->where('practitioner_id',auth()->id());
            });
        }
    }

        
    public static function emptyResourceMessage(NovaRequest $request)
    {
        return 'No Sessions Available';
    }

}
