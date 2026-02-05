<?php

namespace App\Nova;

use Illuminate\Contracts\Database\Eloquent\Builder;
use Laravel\Nova\Fields\ID;
use Laravel\Nova\Fields\Text;
use Laravel\Nova\Http\Requests\NovaRequest;
use App\Nova\Filters\Firmenfilter;


class Kunde extends Resource
{
    /**
     * The model the resource corresponds to.
     *
     * @var class-string<\App\Models\Kunde>
     */
    public static $model = \App\Models\Kunde::class;

    /**
     * The single value that should be used to represent the resource when being displayed.
     *
     * @var string
     */
    public static $title = 'firmenname';

    /**
     * The columns that should be searched.
     *
     * @var array
     */
    public static $search = [
        'id',
        'firmenname',
        'email',
        'ansprechpartner',
    ];

    public static function label()
    {
        return 'Kunden';
    }

    public static function singularLabel()
    {
        return 'Kunden';
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

            Text::make('ansprechpartner')
                ->sortable()
                ->rules('required', 'max:255'),

            Text::make('firmenname')
                ->sortable()
                ->rules('required', 'max:255'),

            Text::make('email')
                ->sortable()
                ->rules('required', 'email', 'max:255'),

            Text::make('ort')
                ->sortable()
                ->rules('required', 'max:255'),

            Text::make('straße')
                ->sortable()
                ->rules('required', 'max:255'),

            Text::make('land')
                ->sortable()
                ->rules('required', 'max:255'),

            Text::make('plz')
                ->sortable()
                ->rules('required', 'max:255'),

            Text::make('hausnummer')
                ->sortable()
                ->rules('required', 'max:255'),

            Text::make('ust_id')
                ->sortable()
                ->rules('nullable', 'max:255'),

            Text::make('handelsregister_id')
                ->sortable()
                ->rules('nullable', 'max:255'),

            Text::make('telefon')
                ->sortable()
                ->rules('required', 'max:255'),

            Text::make('kundenart')
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
