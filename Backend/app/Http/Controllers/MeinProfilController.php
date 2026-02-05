<?php

namespace App\Http\Controllers;

use App\Models\Urlaub;
use App\Models\Fehlzeit;
use App\Enums\UrlaubsStatus;
use App\Enums\FehlzeitenStatus;
use Illuminate\Support\Facades\Auth;

class MeinProfilController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        $genommeneTage = Urlaub::where('mitarbeiter_id', $user->id)
            ->where('status', UrlaubsStatus::GENEHMIGT)
            ->sum('tage');

        $fehltage = Fehlzeit::where('mitarbeiter_id', $user->id)
            ->sum('tage');

        $urlaubstageGesamt = $user->urlaubstage;
        $resturlaub = $urlaubstageGesamt - $genommeneTage;

        return response()->json([
            'profil' => [[
                'id' => $user->id,
                'Vorname' => $user->vorname,
                'Nachname' => $user->nachname,
                'email' => $user->email,
                'username' => explode('@', $user->email)[0],
                'Urlaubstage' => $urlaubstageGesamt,
                'Tage' => $genommeneTage,
                'Resturlaub' => $resturlaub,
                'Fehltage' => $fehltage,
            ]]
        ]);
    }
}
