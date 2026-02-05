<?php

namespace App\Nova\Filters;

use Illuminate\Http\Request;
use Laravel\Nova\Filters\Filter;

class StundenFilter extends Filter
{
    public $name = 'Stunden';

    public function component()
    {
        return 'select-filter';
    }

    public function apply(Request $request, $query, $value)
    {
        return match ($value) {
            'zu_wenig' => $query->whereRaw('TIMESTAMPDIFF(HOUR, start, ende) < 8'),
            'normal'   => $query->whereRaw('TIMESTAMPDIFF(HOUR, start, ende) BETWEEN 8 AND 10'),
            'zu_viel'  => $query->whereRaw('TIMESTAMPDIFF(HOUR, start, ende) > 10'),
            default    => $query,
        };
    }

    public function options(Request $request)
    {
        return [
            'Weniger als 8 Stunden' => 'zu_wenig',
            '8 bis 10 Stunden'     => 'normal',
            'Mehr als 10 Stunden'  => 'zu_viel',
        ];
    }
}
