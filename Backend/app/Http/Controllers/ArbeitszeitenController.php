<?php

namespace App\Http\Controllers;

use App\Models\Arbeitszeit;
use Illuminate\Http\Request;
use App\Http\Requests\ArbeitszeitenRequest;
use Illuminate\Support\Facades\Auth;


class ArbeitszeitenController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        // Sortierung
        $spalten = ['id', 'start', 'ende', 'aufgaben'];
        $order = in_array($request->get('order'), $spalten) ? $request->get('order') : 'id';
        $sort  = in_array($request->get('sort'), ['asc', 'desc']) ? $request->get('sort') : 'asc';

        // Kalenderwoche (Standard: aktuelle KW)
        $kw = $request->get('kw', date('W'));

        // Daten holen + KW filtern
        $user = Auth::user();
        $arbeitszeiten = Arbeitszeit::where('user_id', $user->id)
            ->whereRaw('WEEK(start, 1) = ?', [$kw])
            ->orderBy($order, $sort)
            ->get();

        // Wochentage
        $wochentage = [
            1 => 'Montag',
            2 => 'Dienstag',
            3 => 'Mittwoch',
            4 => 'Donnerstag',
            5 => 'Freitag',
            6 => 'Samstag',
            7 => 'Sonntag',
        ];

        $result = [];
        $letztesEnde = [];
        $gesamtSekunden = 0;

        foreach ($arbeitszeiten as $eintrag) {

            $tag = $wochentage[date('N', strtotime($eintrag->start))];

            $start = strtotime($eintrag->start);
            $ende  = $eintrag->ende ? strtotime($eintrag->ende) : time();

            // Dauer pro Eintrag
            $dauerSek = $ende - $start;
            $dauerStd = floor($dauerSek / 3600);
            $dauerMin = floor(($dauerSek % 3600) / 60);
            $dauer = ($dauerStd > 0 ? $dauerStd . 'h ' : '') . $dauerMin . 'min';

            // Pause
            if (isset($letztesEnde[$tag]) && $start > $letztesEnde[$tag]) {
                $pauseSek = $start - $letztesEnde[$tag];
                $pStd = floor($pauseSek / 3600);
                $pMin = floor(($pauseSek % 3600) / 60);

                $result[$tag][] = [
                    'ist_pause' => true,
                    'dauer' => ($pStd > 0 ? $pStd . 'h ' : '') . $pMin . 'min',
                ];
            }

            // Arbeitszeit-Eintrag
            $result[$tag][] = [
                'ist_pause' => false,
                'id' => $eintrag->id,
                'kunde_id' => $eintrag->kunde_id,
                'start' => $eintrag->start,
                'ende' => $eintrag->ende,
                'aufgaben' => $eintrag->aufgaben,
                'dauer' => $dauer,
            ];

            $letztesEnde[$tag] = $ende;

            // Gesamtzeit nur Mo–Fr
            if (in_array($tag, ['Montag', 'Dienstag', 'Mittwoch', 'Donnerstag', 'Freitag'])) {
                $gesamtSekunden += $dauerSek;
            }
        }

        // Gesamtzeit formatieren
        $gStd = floor($gesamtSekunden / 3600);
        $gMin = floor(($gesamtSekunden % 3600) / 60);
        $gesamtZeit = ($gStd > 0 ? $gStd . 'h ' : '') . $gMin . 'min';

        return response()->json([
            'kw' => (int)$kw,
            'tage' => $result,
            'gesamtzeit' => $gesamtZeit,
        ]);
    }


    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(ArbeitszeitenRequest $request)
    {
        $validated = $request->validated();

        $arbeitszeit = Arbeitszeit::create([
            'user_id' => Auth::id(),
            'start' => $validated['start'],
            'ende' => $validated['ende'],
            'aufgaben' => $validated['aufgaben'],
            'kunde_id' => $validated['kunde_id'] ?? null,
        ]);

        return response()->json([
            'message' => 'Arbeitszeit erfolgreich erstellt',
            'data' => $arbeitszeit
        ], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $arbeitszeit = Arbeitszeit::where('user_id', Auth::id())
            ->where('id', $id)
            ->first();

        if (!$arbeitszeit) {
            return response()->json([
                'message' => 'Arbeitszeit nicht gefunden.'
            ], 404);
        }

        return response()->json([
            'data' => $arbeitszeit
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $arbeitszeit = Arbeitszeit::where('user_id', Auth::id())
            ->where('id', $id)
            ->first();

        if (!$arbeitszeit) {
            return response()->json([
                'message' => 'Arbeitszeit nicht gefunden.'
            ], 404);
        }

        return response()->json($arbeitszeit);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(ArbeitszeitenRequest $request, string $id)
    {
        // Validierte Daten aus dem Request holen
        $validated = $request->validated();

        // Arbeitszeit nach ID suchen (nur eigene)
        $arbeitszeit = Arbeitszeit::where('user_id', Auth::id())
            ->where('id', $id)
            ->first();

        if (!$arbeitszeit) {
            return response()->json([
                'message' => 'Arbeitszeit nicht gefunden.'
            ], 404);
        }

        // Daten aktualisieren
        $arbeitszeit->update([
            'start' => $validated['start'],
            'ende' => $validated['ende'],
            'aufgaben' => $validated['aufgaben'],
            'kunde_id' => $validated['kunde_id'] ?? null,
        ]);

        return response()->json([
            'message' => 'Arbeitszeit erfolgreich aktualisiert.',
            'data' => $arbeitszeit
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $arbeitszeit = Arbeitszeit::where('user_id', Auth::id())
            ->where('id', $id)
            ->first();

        if (!$arbeitszeit) {
            return response()->json([
                'message' => 'Arbeitszeit nicht gefunden.'
            ], 404);
        }

        $arbeitszeit->delete();

        return response()->json([
            'message' => 'Arbeitszeit erfolgreich gelöscht.'
        ]);
    }

    public function startTimer(Request $request)
    {
        $arbeitszeit = Arbeitszeit::create([
            'user_id' => Auth::id(),
            'start' => now(),
            'ende' => null,
            'kunde_id' => $request->kunde_id ?? null,
            'aufgaben' => $request->aufgaben ?? '',

        ]);

        return response()->json([
            'message' => 'Timer gestartet',
            'data' => $arbeitszeit
        ], 201);
    }

    public function stopTimer(Request $request, string $id)
    {
        $arbeitszeit = Arbeitszeit::where('user_id', Auth::id())
            ->where('id', $id)
            ->whereNull('ende')
            ->first();

        if (!$arbeitszeit) {
            return response()->json([
                'message' => 'Laufender Timer nicht gefunden.'
            ], 404);
        }
        $arbeitszeit->update([
            'ende' => now(),
            'aufgaben' => $request->aufgaben ?? $arbeitszeit->aufgaben,

        ]);

        return response()->json([
            'message' => 'Timer gestoppt',
            'data' => $arbeitszeit
        ]);
    }
    public function getTimer()
    {
        $timer = Arbeitszeit::where('user_id', Auth::id())
            ->whereNull('ende')
            ->first();

        if (!$timer) {
            return response()->json([
                'message' => 'Kein laufender Timer'
            ], 404);
        }

        return response()->json([
            'data' => $timer
        ]);
    }
}