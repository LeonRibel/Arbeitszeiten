<?php

namespace App\Http\Controllers;

use App\Models\Urlaub;
use App\Enums\UrlaubsStatus;
use Illuminate\Http\Request;
use App\Http\Requests\UrlaubRequest;
use Illuminate\Support\Facades\Auth;
use DateTime;


class UrlaubController extends Controller
{
    public function index(Request $request)
    {
        $spalten = ['id', 'start', 'ende', 'status', 'tage'];
        $order = in_array($request->get('order'), $spalten) ? $request->get('order') : 'id';
        $sort  = in_array($request->get('sort'), ['asc', 'desc']) ? $request->get('sort') : 'asc';

        $urlaube = Urlaub::where('mitarbeiter_id', Auth::id())
            ->orderBy($order, $sort)
            ->paginate(10);

        foreach ($urlaube as $eintrag) {
            $start = new DateTime($eintrag->start);
            $ende  = new DateTime($eintrag->ende);
            $eintrag->tage = $start->diff($ende)->days + 1;
        }

        $urlaubsanspruchGesamt = Auth::user()->urlaubstage;
        $urlaubsGeplant = $urlaubsanspruchGesamt;

        foreach ($urlaube as $urlaub) {
            if (
                $urlaub->status === UrlaubsStatus::ANGEFRAGT ||
                $urlaub->status === UrlaubsStatus::GENEHMIGT
            ) {
                $urlaubsGeplant -= $urlaub->tage;
            }

            if ($urlaub->status === UrlaubsStatus::GENEHMIGT) {
                $urlaubsanspruchGesamt -= $urlaub->tage;
            }
        }

        return response()->json([
            'urlaube' => $urlaube,
            'urlaubsanspruchGesamt' => $urlaubsanspruchGesamt,
            'urlaubsGeplant' => $urlaubsGeplant,
        ]);
    }

    public function store(UrlaubRequest $request)
    {
        $validated = $request->validated();

        $start = new DateTime($validated['start']);
        $ende = new DateTime($validated['ende']);
        $tage = $start->diff($ende)->days + 1;

        $urlaub = Urlaub::create([
            'mitarbeiter_id' => Auth::id(),
            'start' => $validated['start'],
            'ende' => $validated['ende'],
            'status' => UrlaubsStatus::ANGEFRAGT,
            'tage' => $tage,
        ]);

        return response()->json([
            'message' => 'Urlaub erfolgreich angefragt.',
            'urlaub' => $urlaub
        ], 201); // 201 = Created
    }

    public function show(string $id)
    {
        $urlaub = Urlaub::where('mitarbeiter_id', Auth::id())
            ->where('id', $id)
            ->firstOrFail();

        return response()->json($urlaub);
    }

    public function update(UrlaubRequest $request, string $id)
    {
        $urlaub = Urlaub::where('mitarbeiter_id', Auth::id())
            ->where('id', $id)
            ->firstOrFail();

        $validated = $request->validated();

        $start = new DateTime($validated['start']);
        $ende = new DateTime($validated['ende']);
        $tage = $start->diff($ende)->days + 1;

        $urlaub->update([
            'start' => $validated['start'],
            'ende' => $validated['ende'],
            'tage' => $tage,
        ]);

        return response()->json($urlaub);
    }

    public function destroy(string $id)
    {
        $urlaub = Urlaub::find($id);

        if(!$urlaub) {
            return response()->json([
                'message' => 'Urlaub nicht gefunden.'
            ], 404);
        }
        
    }



    public function genehmigen(Urlaub $urlaub)
    {
        $urlaub->update([
            'status' => UrlaubsStatus::GENEHMIGT,
        ]);

        return response()->json([
            'message' => 'Urlaub genehmigt.',
            'urlaub' => $urlaub
        ], 200);
    }

    public function ablehnen(Urlaub $urlaub)
    {
        $urlaub->update([
            'status' => UrlaubsStatus::ABGELEHNT,
        ]);

        return response()->json([
            'message' => 'Urlaub abgelehnt.',
            'urlaub' => $urlaub
        ], 200);
    }
}
