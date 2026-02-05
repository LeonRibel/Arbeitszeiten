<?php

namespace App\Nova\Filters;

use Illuminate\Http\Request;
use Laravel\Nova\Filters\Filter;
use App\Models\Kunde;

class Firmenfilter extends Filter
{
    public $name = 'Firmen';

    public function component()
    {
        return 'select-filter';
    }

    public function apply(Request $request, $query, $value)
    {
        return $query->where('id', $value);
    }

    public function options(Request $request)
    {

        return Kunde::orderBy('firmenname')->pluck('id', 'firmenname')->toArray();
    }
}
