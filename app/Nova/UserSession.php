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
class UserSession extends Resource
{
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
        return true; // Disables the "Create Session" button
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
         
        return $this->user ? $this->user->name : 'Client Session';
    }

    public static function resourceTitle(NovaRequest $request, $resource)
    {
        return 'Update Client Session: ' . $resource->user->name;
    }

    public static function label()
    {
        return 'Client Sessions';
    }

    public static function singularLabel()
    {
        return 'Client Session';
    }
}
