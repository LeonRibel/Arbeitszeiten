<?php

namespace App\Nova;

use Illuminate\Contracts\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Laravel\Nova\Auth\PasswordValidationRules;
use Laravel\Nova\Fields\DateTime;
use Laravel\Nova\Fields\ID;
use Laravel\Nova\Fields\Password;
use Laravel\Nova\Fields\Text;
use Laravel\Nova\Http\Requests\NovaRequest;
use Laravel\Nova\Fields\BelongsTo;
use App\Nova\Filters\MitarbeiterFilter;
use App\Nova\Filters\DatumFilter;





class Arbeitszeit extends Resource
{
    use PasswordValidationRules;

    /**
     * The model the resource corresponds to.
     *
     * @var class-string<\App\Models\User>
     */
    public static $model = \App\Models\Arbeitszeit::class;

    /**
     * The single value that should be used to represent the resource when being displayed.
     *
     * @var string
     */
    public static $title = 'name';

    /**
     * The columns that should be searched.
     *
     * @var array
     */
    public static $search = [
        'id',
        'vorname',
        'email',
        'kundenname',
    ];
    public static function label()
    {
        return 'Arbeitszeiten';
    }

    public static function singularLabel()
    {
        return 'Arbeitszeiten';
    }

    public static function indexQuery(NovaRequest $request, Builder $query): Builder
    {
        if ($request->user()->is_admin) {
            return $query;
        }

        return $query;
    }
    /**
     * Get the fields displayed by the resource.
     *
     * @return array<int, \Laravel\Nova\Fields\Field|\Laravel\Nova\Panel|\Laravel\Nova\ResourceTool|\Illuminate\Http\Resources\MergeValue>
     */
    public function fields(NovaRequest $request): array
    {
        return [
            ID::make()->sortable(),


            BelongsTo::make('vorname', 'user', \App\Nova\User::class)
                ->sortable()
                ->onlyOnIndex()
                ->rules('required', 'max:255'),

            BelongsTo::make('Kunde', 'kunde', \App\Nova\Kunde::class)
                ->sortable()
                ->nullable(),

            DateTime::make('start')
                ->sortable()
                ->rules('required', 'max:255'),

            Datetime::make('ende')
                ->sortable()
                ->rules('required', 'max:255'),

            Text::make('aufgaben')
                ->sortable()
                ->rules('required', 'text', 'max:254')
                ->creationRules('unique:users,email')
                ->updateRules('unique:users,email,{{resourceId}}'),

        ];
    }

    /**
     * Get the cards available for the request.
     *
     * @return array<int, \Laravel\Nova\Card>
     */
    public function cards(NovaRequest $request): array
    {
        return [];
    }

    /**
     * Get the filters available for the resource.
     *
     * @return array<int, \Laravel\Nova\Filters\Filter>
     */
    public function filters(NovaRequest $request): array
    {
        return [

            new MitarbeiterFilter(),
            new DatumFilter(),

        ];
    }

    /**
     * Get the lenses available for the resource.
     *
     * @return array<int, \Laravel\Nova\Lenses\Lens>
     */
    public function lenses(NovaRequest $request): array
    {
        return [];
    }

    /**
     * Get the actions available for the resource.
     *
     * @return array<int, \Laravel\Nova\Actions\Action>
     */
    public function actions(NovaRequest $request): array
    {
        return [];
    }
}
