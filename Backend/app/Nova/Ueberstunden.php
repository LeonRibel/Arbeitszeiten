<?php

namespace App\Nova;

use Illuminate\Http\Request;
use Laravel\Nova\Fields\Date;
use Laravel\Nova\Fields\ID;
use Laravel\Nova\Fields\Text;
use Laravel\Nova\Fields\BelongsTo;
use Laravel\Nova\Http\Requests\NovaRequest;
use App\Nova\Filters\StundenFilter;
use App\Nova\Filters\MitarbeiterFilter;



class Ueberstunden extends Resource
{
    public static $model = \App\Models\Arbeitszeit::class;
    public static $title = 'name';
    public static $search = ['id', 'vorname', 'email'];

    public static function label()
    {
        return 'Überstunden';
    }
    public static function singularLabel()
    {
        return 'Überstunden';
    }

    public function fields(NovaRequest $request): array
    {
        return [
            ID::make()->sortable(),

            BelongsTo::make('Vorname', 'user', \App\Nova\User::class)
                ->sortable()
                ->rules('required'),

            Date::make('start')
                ->sortable()
                ->rules('required'),

            Text::make('stunden', function () {
                $value = $this->stunden;
                if ($value < 8) {
                    $color = 'orange';
                } elseif ($value >= 8 && $value <= 10) {
                    $color = 'green';
                } else {
                    $color = 'red';
                }
                return "<span style='color: {$color}; font-weight: bold;'>{$value}</span>";
            })->asHtml()
                ->exceptOnForms()
                ->sortable(),

            Text::make('Aufgaben', 'aufgaben')
                ->sortable()
                ->rules('required', 'max:255'),
        ];
    }

    public function filters(NovaRequest $request): array
    {
        return [
            new StundenFilter(),
            new MitarbeiterFilter(),
        ];
    }

    public function cards(NovaRequest $request): array
    {
        return [];
    }
    public function lenses(NovaRequest $request): array
    {
        return [];
    }
    public function actions(NovaRequest $request): array
    {
        return [];
    }
}
