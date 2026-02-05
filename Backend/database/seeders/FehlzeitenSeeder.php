<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class FehlzeitenSeeder extends Seeder
{
    public function run(): void
    {
        $users = DB::table('users')->get();

        if ($users->isEmpty()) {
            return;
        }

        $fehlzeiten = [];

        foreach ($users as $user) {
            // Vergangene eingereichte Krankheit - 4 Tage
            $start1 = Carbon::parse('2025-11-24 00:00:00');
            $ende1 = Carbon::parse('2025-11-27 23:59:59');
            $fehlzeiten[] = [
                'mitarbeiter_id' => $user->id,
                'Kstart' => $start1,
                'Kende' => $ende1,
                'status' => 'eingereicht',
                'tage' => $start1->diffInDays($ende1),
            ];

            // Vergangene längere Krankheit - 7 Tage
            $start2 = Carbon::parse('2025-10-10 00:00:00');
            $ende2 = Carbon::parse('2025-10-16 23:59:59');
            $fehlzeiten[] = [
                'mitarbeiter_id' => $user->id,
                'Kstart' => $start2,
                'Kende' => $ende2,
                'status' => 'eingereicht',
                'tage' => $start2->diffInDays($ende2),
            ];

            // Aktuelle Krankheit (nicht eingereicht) - 4 Tage
            $start3 = Carbon::parse('2026-01-06 00:00:00');
            $ende3 = Carbon::parse('2026-01-09 23:59:59');
            $fehlzeiten[] = [
                'mitarbeiter_id' => $user->id,
                'Kstart' => $start3,
                'Kende' => $ende3,
                'status' => 'nicht eingereicht',
                'tage' => $start3->diffInDays($ende3),
            ];

            // Weitere vergangene Fehlzeit - 2 Tage
            $start4 = Carbon::parse('2025-09-10 00:00:00');
            $ende4 = Carbon::parse('2025-09-11 23:59:59');
            $fehlzeiten[] = [
                'mitarbeiter_id' => $user->id,
                'Kstart' => $start4,
                'Kende' => $ende4,
                'status' => 'eingereicht',
                'tage' => $start4->diffInDays($ende4),
            ];

            // Ältere Fehlzeit - 5 Tage
            $start5 = Carbon::parse('2025-07-12 00:00:00');
            $ende5 = Carbon::parse('2025-07-16 23:59:59');
            $fehlzeiten[] = [
                'mitarbeiter_id' => $user->id,
                'Kstart' => $start5,
                'Kende' => $ende5,
                'status' => 'eingereicht',
                'tage' => $start5->diffInDays($ende5),
            ];
        }

        DB::table('fehlzeiten')->insert($fehlzeiten);
    }
}
