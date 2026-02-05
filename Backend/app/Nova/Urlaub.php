<?php

namespace App\Nova;

use Illuminate\Contracts\Database\Eloquent\Builder;
use Laravel\Nova\Fields\BelongsTo;
use Laravel\Nova\Fields\DateTime;
use Laravel\Nova\Fields\ID;
use Laravel\Nova\Fields\Number;
use Laravel\Nova\Fields\Select;
use Laravel\Nova\Http\Requests\NovaRequest;
use App\Nova\Filters\Mitarbeiter_idFilter;
use App\Nova\Filters\DatumFilter;



class Urlaub extends Resource
{
    /**
     * The model the resource corresponds to.
     *
     * @var class-string<\App\Models\Fehlzeit>
     */
    public static $model = \App\Models\Urlaub::class;

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
        'mitarbeiter_id',
    ];

    public static function label()
    {
        return 'Urlaub';
    }

    public static function singularLabel()
    {
        return 'Urlaub';
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

            BelongsTo::make('Mitarbeiter', 'user', \App\Nova\User::class)
                ->sortable()
                ->rules('required'),

            DateTime::make('start')
                ->sortable()
                ->rules('required'),

            DateTime::make('ende')
                ->sortable()
                ->rules('required'),

            Number::make('tage')
                ->sortable()
                ->rules('required', 'integer'),

            Select::make('status')
                ->options([
                    'genehmigt' => 'genehmigt',
                    'abgelehnt' => 'abgelehnt',
                ])
                ->displayUsingLabels()
                ->sortable()
                ->rules('required', 'in:genehmigt,abgelehnt'),
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

            new Mitarbeiter_idFilter(),
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
