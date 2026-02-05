<?php

namespace App\Nova;

use App\Nova\Filters\Firmenfilter;
use Illuminate\Contracts\Database\Eloquent\Builder;
use Laravel\Nova\Fields\ID;
use Laravel\Nova\Fields\BelongsTo;
use Laravel\Nova\Fields\Text;
use Laravel\Nova\Http\Requests\NovaRequest;


class Projekt extends Resource
{
    /**
     * The model the resource corresponds to.
     *
     * @var class-string<\App\Models\Projekt>
     */
    public static $model = \App\Models\Projekt::class;

    /**
     * The single value that should be used to represent the resource when being displayed.
     *
     * @var string
     */
    public static $title = 'projektname';

    /**
     * The columns that should be searched.
     *
     * @var array
     */
    public static $search = [
        'id',
        'projektnummer',
        'projektname',
        'kundenname',
    ];

    public static function label()
    {
        return 'Projekte';
    }

    public static function singularLabel()
    {
        return 'Projekte';
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
            ID::make('id')->sortable(),

            Text::make('aufgabe')
                ->sortable()
                ->rules('required', 'max:255'),

            BelongsTo::make('Kunde', 'kunde', \App\Nova\Kunde::class)
                ->sortable()
                ->rules('required'),

            Text::make('status')
                ->sortable()
                ->rules('required', 'max:255'),

            Text::make('gesamt')
                ->sortable()
                ->rules('required', 'max:255'),
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

            new Firmenfilter(),


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
