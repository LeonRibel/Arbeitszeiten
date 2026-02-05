<?php

namespace App\Http\Controllers;

use App\Models\Projekt;
use Illuminate\Http\Request;

class ProjektController extends Controller
{
    /**
     * Display a listing of all projects.
     */
    public function index(Request $request)
    {
        // Eager-load relation, damit JSON das Kunde-Objekt enthält
        $query = Projekt::with('kunde');

        if ($request->has('status')) {
            $query->where('status', $request->status);
        }

        if ($request->has('kunde')) {
            // Suche nach Firmenname in der Kunden-Tabelle (spalte heißt in deinem Frontend "firmenname")
            $query->whereHas('kunde', function ($q) use ($request) {
                $q->where('firmenname', 'like', '%' . $request->kunde . '%');
            });
        }

        $orderBy = $request->get('order_by', 'created_at');
        $orderDirection = $request->get('order_direction', 'desc');

        $projekte = $query->orderBy($orderBy, $orderDirection)->get();

        return response()->json($projekte);
    }

    /**
     * Store a newly created project.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'aufgabe' => 'required|string|max:255',
            'kunde_id' => 'required|exists:kunden,id',
            'status' => 'required|in:aktiv,abgeschlossen',
            'gesamt' => 'required|numeric|min:0',
        ]);

        $projekt = Projekt::create($validated);

        return response()->json($projekt->load('kunde'), 201);
    }

    /**
     * Display the specified project.
     */
    public function show(int $id)
    {
        $projekt = Projekt::findOrFail($id);

        return response()->json($projekt);
    }

    /**
     * Update the specified project.
     */
    public function update(Request $request, int $id)
    {
        $projekt = Projekt::findOrFail($id);

        $validated = $request->validate([
            'aufgabe' => 'sometimes|required|string|max:255',
            'kunde_id' => 'sometimes|required|exists:kunden,id',
            'status' => 'sometimes|required|in:aktiv,abgeschlossen',
            'gesamt' => 'sometimes|required|numeric|min:0',
        ]);

        $projekt->update($validated);

        return response()->json($projekt->load('kunde'));
    }

    /**
     * Remove the specified project.
     */
    public function destroy(int $id)
    {
        $projekt = Projekt::findOrFail($id);
        $projekt->delete();

        return response()->json(['message' => 'Projekt erfolgreich gelöscht'], 200);
    }
}