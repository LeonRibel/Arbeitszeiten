<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class UrlaubSeeder extends Seeder
{
    public function run(): void
    {
        $users = DB::table('users')->get();

        if ($users->isEmpty()) {
            return;
        }

        $urlaubsantraege = [];

        foreach ($users as $user) {
            // Genehmigter Urlaub (vergangen) - 5 Tage
            $start1 = Carbon::parse('2025-11-10 00:00:00');
            $ende1 = Carbon::parse('2025-11-14 23:59:59');
            $urlaubsantraege[] = [
                'mitarbeiter_id' => $user->id,
                'start' => $start1,
                'ende' => $ende1,
                'status' => 'genehmigt',
                'tage' => $start1->diffInDays($ende1),
            ];

            // Genehmigter Urlaub (zukünftig) - 6 Tage
            $start2 = Carbon::parse('2026-02-07 00:00:00');
            $ende2 = Carbon::parse('2026-02-12 23:59:59');
            $urlaubsantraege[] = [
                'mitarbeiter_id' => $user->id,
                'start' => $start2,
                'ende' => $ende2,
                'status' => 'genehmigt',
                'tage' => $start2->diffInDays($ende2),
            ];

            // Angefragter Urlaub (wartend) - 8 Tage
            $start3 = Carbon::parse('2026-03-09 00:00:00');
            $ende3 = Carbon::parse('2026-03-16 23:59:59');
            $urlaubsantraege[] = [
                'mitarbeiter_id' => $user->id,
                'start' => $start3,
                'ende' => $ende3,
                'status' => 'angefragt',
                'tage' => $start3->diffInDays($ende3),
            ];

            // Abgelehnter Urlaub - 3 Tage
            $start4 = Carbon::parse('2026-01-18 00:00:00');
            $ende4 = Carbon::parse('2026-01-20 23:59:59');
            $urlaubsantraege[] = [
                'mitarbeiter_id' => $user->id,
                'start' => $start4,
                'ende' => $ende4,
                'status' => 'abgelehnt',
                'tage' => $start4->diffInDays($ende4),
            ];

            // Weiterer genehmigter Urlaub (vergangen) - 4 Tage
            $start5 = Carbon::parse('2025-09-10 00:00:00');
            $ende5 = Carbon::parse('2025-09-13 23:59:59');
            $urlaubsantraege[] = [
                'mitarbeiter_id' => $user->id,
                'start' => $start5,
                'ende' => $ende5,
                'status' => 'genehmigt',
                'tage' => $start5->diffInDays($ende5),
            ];
        }

        DB::table('urlaub')->insert($urlaubsantraege);
    }
}
