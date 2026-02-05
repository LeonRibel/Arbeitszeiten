<?php

namespace App\Nova\Filters;

use Illuminate\Http\Request;
use Laravel\Nova\Filters\Filter;
use App\Models\User;

class Mitarbeiter_idFilter extends Filter
{
    public $name = 'Mitarbeiter';

    // Der Filtertyp
    public function component()
    {
        return 'select-filter';
    }

    public function apply(Request $request, $query, $value)
    {
        return $query->where('Mitarbeiter_id', $value);
    }

    public function options(Request $request)
    {

        return User::orderBy('vorname')->pluck('id', 'vorname')->toArray();
    }
}
