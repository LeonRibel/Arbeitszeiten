<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;


class MitarbeiterController extends Controller
{
    public function mitarbeiter(Request $request)
    {
        $spalten = ['id', 'Vorname', 'Nachname', 'email'];

        $order = in_array($request->get('order'), $spalten)
            ? $request->get('order')
            : 'id';

        $sort = in_array(strtolower($request->get('sort')), ['asc', 'desc'])
            ? strtolower($request->get('sort'))
            : 'asc';

        $seite = max((int) $request->get('seite', 1), 1);
        $limit = 10;
        $offset = ($seite - 1) * $limit;

        $arbeiter = DB::table('users')
            ->select('id', 'Vorname as vorname', 'Nachname as nachname', 'email')
            ->orderBy($order, $sort)
            ->limit($limit)
            ->offset($offset)
            ->get();
        
        $gesamtEintraege = DB::table('users')->count();
        $seitenAnzahl = (int) ceil($gesamtEintraege / $limit);

        return response()->json([
            'users' => $arbeiter,
            'order' => $order,
            'sort' => $sort,
            'seite' => $seite,
            'seitenAnzahl' => $seitenAnzahl,
            'gesamtEintraege' => $gesamtEintraege,
        ]);
    }
}