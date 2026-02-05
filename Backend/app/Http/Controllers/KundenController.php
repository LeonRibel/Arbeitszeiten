<?php

namespace App\Http\Controllers;

use App\Models\Kunde;
use Illuminate\Http\Request;

class KundenController extends Controller
{
    public function index(Request $request)
    {
        $query = Kunde::query();

        // Filter nach Kundenart (B2B/B2C)
        if ($request->has('kundenart')) {
            $query->where('kundenart', $request->kundenart);
        }

        // Suche nach Firmenname
        if ($request->has('firmenname')) {
            $query->where('firmenname', 'like', '%' . $request->firmenname . '%');
        }

        $orderBy = $request->get('order_by', 'firmenname');
        $orderDirection = $request->get('order_direction', 'asc');

        $kunden = $query->orderBy($orderBy, $orderDirection)->get();

        return response()->json($kunden);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'firmenname' => 'required|string|max:255',
            'ansprechpartner' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'telefon' => 'required|string|max:255',
            'ort' => 'required|string|max:255',
            'straße' => 'required|string|max:255',
            'hausnummer' => 'required|string|max:255',
            'plz' => 'required|string|max:10',
            'land' => 'required|string|max:2',
            'ust_id' => 'nullable|string|max:255',
            'handelsregister_id' => 'nullable|string|max:255',
            'kundenart' => 'required|in:B2B,B2C',
        ]);

        $kunde = Kunde::create($validated);

        return response()->json($kunde, 201);
    }

    public function show(int $id)
    {
        $kunde = Kunde::findOrFail($id);

        return response()->json($kunde);
    }

    public function update(Request $request, int $id)
    {
        $kunde = Kunde::findOrFail($id);

        $validated = $request->validate([
            'firmenname' => 'required|string|max:255',
            'ansprechpartner' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'telefon' => 'required|string|max:255',
            'ort' => 'required|string|max:255',
            'straße' => 'required|string|max:255',
            'hausnummer' => 'required|string|max:255',
            'plz' => 'required|string|max:10',
            'land' => 'required|string|max:2',
            'ust_id' => 'nullable|string|max:255',
            'handelsregister_id' => 'nullable|string|max:255',
            'kundenart' => 'required|in:B2B,B2C',
        ]);

        $kunde->update($validated);

        return response()->json($kunde);
    }

    public function destroy(int $id)
    {
        $kunde = Kunde::findOrFail($id);
        $kunde->delete();

        return response()->json(['message' => 'Kunde erfolgreich gelöscht'], 200);
    }
}
