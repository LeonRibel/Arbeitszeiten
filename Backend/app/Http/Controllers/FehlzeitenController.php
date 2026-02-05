<?php

namespace App\Http\Controllers;

use App\Models\Fehlzeit;
use App\Enums\FehlzeitenStatus;
use Illuminate\Http\Request;
use App\Http\Requests\FehlzeitenRequest;
use Illuminate\Support\Facades\Auth;
use DateTime;


class FehlzeitenController extends Controller
{

    private array $monate = [
        1  => 'Januar',
        2  => 'Februar',
        3  => 'März',
        4  => 'April',
        5  => 'Mai',
        6  => 'Juni',
        7  => 'Juli',
        8  => 'August',
        9  => 'September',
        10 => 'Oktober',
        11 => 'November',
        12 => 'Dezember',
    ];

    public function index(Request $request)
    {
        $user = Auth::user();
        $fehlzeiten = Fehlzeit::where('mitarbeiter_id', $user->id)->get();

        $fehlzeitenProMonat = [];

        foreach ($fehlzeiten as $eintrag) {
            $Kstart = new DateTime($eintrag->Kstart);
            $Kende  = new DateTime($eintrag->Kende);

            $tage = $Kstart->diff($Kende)->days + 1;
            $eintrag->tage = $tage;

            $monat = (int) $Kstart->format('n');

            if (!isset($fehlzeitenProMonat[$monat])) {
                $fehlzeitenProMonat[$monat] = [
                    'name' => $this->monate[$monat],
                    'tage' => 0
                ];
            }

            $fehlzeitenProMonat[$monat]['tage'] += $tage;
        }

        return response()->json([
            'fehlzeiten' => $fehlzeiten,
            'fehlzeitenProMonat' => $fehlzeitenProMonat,
        ]);
    }

    public function monat(int $monat)
    {
        if ($monat < 1 || $monat > 12) {
            abort(404);
        }

        $user = Auth::user();
        $fehlzeiten = Fehlzeit::where('mitarbeiter_id', $user->id)
            ->whereMonth('Kstart', $monat)
            ->get();

        foreach ($fehlzeiten as $eintrag) {
            $Kstart = new DateTime($eintrag->Kstart);
            $Kende  = new DateTime($eintrag->Kende);
            $eintrag->tage = $Kstart->diff($Kende)->days + 1;
        }

        return response()->json([
            'monat' => $this->monate[$monat],
            'fehlzeiten' => $fehlzeiten,
        ]);
    }
    public function store(FehlzeitenRequest $request)
    {
        $data = $request->validated();
        $data['mitarbeiter_id'] = $request->user()->id;
        $data['status']  = FehlzeitenStatus::OFFEN;

        // Berechne die Tage
        $start = new DateTime($data['krankheit_start']);
        $ende  = new DateTime($data['krankheit_ende']);
        $data['tage'] = $start->diff($ende)->days + 1;

        $fehlzeit = Fehlzeit::create($data);

        return response()->json($fehlzeit, 201);
    }

    public function show(int $id)
    {
        $fehlzeit = Fehlzeit::where('mitarbeiter_id', Auth::id())
            ->where('fehlzeiten_id', $id)
            ->firstOrFail();

        return response()->json($fehlzeit);
    }

    public function update(FehlzeitenRequest $request, int $id)
    {
        $fehlzeit = Fehlzeit::where('mitarbeiter_id', Auth::id())
            ->where('fehlzeiten_id', $id)
            ->firstOrFail();

        $validated = $request->validated();

        // Berechne die Tage
        $start = new DateTime($validated['krankheit_start']);
        $ende  = new DateTime($validated['krankheit_ende']);
        $validated['tage'] = $start->diff($ende)->days + 1;

        $fehlzeit->update($validated);

        return response()->json($fehlzeit);
    }

    public function upload(FehlzeitenRequest $request, int $id)
    {
        $fehlzeit = Fehlzeit::findOrFail($id);

        $datei = $request->file('datei');
        $name  = $fehlzeit->id . '.' . $datei->getClientOriginalExtension();
        $pfad  = $datei->storeAs('fehlzeiten', $name);

        $fehlzeit->update([
            'attest' => $pfad,
            'status' => FehlzeitenStatus::GENEHMIGT,
        ]);

        return response()->json(['path' => $pfad]);
    }
}
