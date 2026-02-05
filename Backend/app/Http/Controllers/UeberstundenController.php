<?php

namespace App\Http\Controllers;

use App\Models\Arbeitszeit;
use Illuminate\Support\Facades\Auth;

class UeberstundenController extends Controller
{
    public function index()
    {
        $userId = Auth::id();
        if (!$userId) abort(401);

        $arbeitszeiten = Arbeitszeit::where('user_id', $userId)->orderBy('start')->get();

        $jahre = [];
        $ueberstundenProMonat = $ueberstundenProJahr = $wochen = [];

        foreach ($arbeitszeiten as $az) {
            $jahr = $az->start->format('Y');
            $monat = $az->start->format('m');
            $tag = $az->start->format('d');

            $jahre[$jahr]['monate'][$monat]['tage'][$tag]['arbeitszeiten'][] = [
                'start' => $az->start->format('Y-m-d H:i:s'),
                'ende' => $az->ende->format('Y-m-d H:i:s'),
                'tag' => $az->start->format('Y-m-d'),
            ];

            $stunden = ($az->ende->getTimestamp() - $az->start->getTimestamp()) / 3600;
            $ueberstunden = $stunden - 8;

            $ueberstundenProMonat["$jahr-$monat"] = ($ueberstundenProMonat["$jahr-$monat"] ?? 0) + $ueberstunden;
            $ueberstundenProJahr[$jahr] = ($ueberstundenProJahr[$jahr] ?? 0) + $ueberstunden;
            $wochen[$az->start->format('o-W')] = ($wochen[$az->start->format('o-W')] ?? 0) + $ueberstunden;
        }

        return response()->json([
            'arbeitszeiten' => ['jahre' => $jahre],
            'ueberstundenProMonat' => $ueberstundenProMonat,
            'ueberstundenProJahr' => $ueberstundenProJahr,
            'wochen' => $wochen,
        ]);
    }
}
