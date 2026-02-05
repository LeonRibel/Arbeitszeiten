<?php

namespace App\Nova\Filters;

use Illuminate\Http\Request;
use Laravel\Nova\Filters\Filter;

class DatumFilter extends Filter
{
    public $component = 'select-filter';
    public $name = 'Kalenderwoche';

    public function apply(Request $request, $query, $value)
    {
        return match ($value) {
            'diese_woche' => $query->whereRaw('WEEK(start, 1) = WEEK(NOW(), 1)'),
            'letzte_woche' => $query->whereRaw('WEEK(start, 1) = WEEK(NOW(), 1) - 1'),
            'dieser_monat' => $query->whereRaw('MONTH(start) = MONTH(NOW())'),
            'letzter_monat' => $query->whereRaw('MONTH(start) = MONTH(NOW()) - 1'),
            'vorlezter_monat' => $query->whereRaw('MONTH(start) = MONTH(NOW()) - 2'),

            default => $query,
        };
    }

    public function options(Request $request)
    {
        return [
            'Diese Woche' => 'diese_woche',
            'Letzte Woche' => 'letzte_woche',
            'Dieser Monat' => 'dieser_monat',
            'Letzter Monat' => 'letzter_monat',
            'Vorlezter monat' => 'vorlezter_monat'
        ];
    }
}
