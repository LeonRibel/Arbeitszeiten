<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class ArbeitszeitenSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $users = DB::table('users')->get();

        if ($users->isEmpty()) {
            return;
        }

        $arbeitszeiten = [];
        $aufgabenPool = [
            'Backend API entwickelt',
            'Frontend Komponenten erstellt',
            'Bug Fixes durchgeführt',
            'Datenbank Optimierung',
            'Code Review und Refactoring',
            'Testing und Debugging',
            'Dokumentation geschrieben',
            'Meeting und Planung',
        ];

        foreach ($users as $index => $user) {
            // User 1: KW 1 (30.12.2024 - 05.01.2026)
            if ($index === 0) {
                $arbeitszeiten[] = ['user_id' => $user->id, 'start' => '2025-12-30 08:00:00', 'ende' => '2025-12-30 16:30:00', 'aufgaben' => 'Backend API entwickelt', 'created_at' => now(), 'updated_at' => now()];
                $arbeitszeiten[] = ['user_id' => $user->id, 'start' => '2025-12-31 09:00:00', 'ende' => '2025-12-31 17:00:00', 'aufgaben' => 'Frontend Komponenten erstellt', 'created_at' => now(), 'updated_at' => now()];
                $arbeitszeiten[] = ['user_id' => $user->id, 'start' => '2026-01-02 08:30:00', 'ende' => '2026-01-02 12:00:00', 'aufgaben' => 'Bug Fixes durchgeführt', 'created_at' => now(), 'updated_at' => now()];
                $arbeitszeiten[] = ['user_id' => $user->id, 'start' => '2026-01-02 13:00:00', 'ende' => '2026-01-02 18:30:00', 'aufgaben' => 'Datenbank Migration erstellt', 'created_at' => now(), 'updated_at' => now()];
                $arbeitszeiten[] = ['user_id' => $user->id, 'start' => '2026-01-03 07:45:00', 'ende' => '2026-01-03 15:15:00', 'aufgaben' => 'API Endpoints implementiert', 'created_at' => now(), 'updated_at' => now()];
            }
            // User 2: KW 2 (06.01.2026 - 12.01.2026)
            elseif ($index === 1) {
                $arbeitszeiten[] = ['user_id' => $user->id, 'start' => '2026-01-06 08:00:00', 'ende' => '2026-01-06 16:00:00', 'aufgaben' => 'Code Review und Refactoring', 'created_at' => now(), 'updated_at' => now()];
                $arbeitszeiten[] = ['user_id' => $user->id, 'start' => '2026-01-07 09:30:00', 'ende' => '2026-01-07 17:30:00', 'aufgaben' => 'Testing und Debugging', 'created_at' => now(), 'updated_at' => now()];
                $arbeitszeiten[] = ['user_id' => $user->id, 'start' => '2026-01-08 08:00:00', 'ende' => '2026-01-08 16:30:00', 'aufgaben' => 'Dokumentation geschrieben', 'created_at' => now(), 'updated_at' => now()];
                $arbeitszeiten[] = ['user_id' => $user->id, 'start' => '2026-01-09 10:00:00', 'ende' => '2026-01-09 14:00:00', 'aufgaben' => 'Meeting und Planung', 'created_at' => now(), 'updated_at' => now()];
                $arbeitszeiten[] = ['user_id' => $user->id, 'start' => '2026-01-10 08:15:00', 'ende' => '2026-01-10 16:45:00', 'aufgaben' => 'Feature Implementation', 'created_at' => now(), 'updated_at' => now()];
            }
            // User 3: KW 3 (13.01.2026 - 19.01.2026)
            elseif ($index === 2) {
                $arbeitszeiten[] = ['user_id' => $user->id, 'start' => '2026-01-13 07:30:00', 'ende' => '2026-01-13 15:30:00', 'aufgaben' => 'UI/UX Verbesserungen', 'created_at' => now(), 'updated_at' => now()];
                $arbeitszeiten[] = ['user_id' => $user->id, 'start' => '2026-01-14 08:00:00', 'ende' => '2026-01-14 17:00:00', 'aufgaben' => 'Performance Optimierung', 'created_at' => now(), 'updated_at' => now()];
                $arbeitszeiten[] = ['user_id' => $user->id, 'start' => '2026-01-15 09:00:00', 'ende' => '2026-01-15 12:30:00', 'aufgaben' => 'Security Audit', 'created_at' => now(), 'updated_at' => now()];
                $arbeitszeiten[] = ['user_id' => $user->id, 'start' => '2026-01-15 13:30:00', 'ende' => '2026-01-15 18:00:00', 'aufgaben' => 'Deployment Vorbereitung', 'created_at' => now(), 'updated_at' => now()];
                $arbeitszeiten[] = ['user_id' => $user->id, 'start' => '2026-01-16 08:00:00', 'ende' => '2026-01-16 16:00:00', 'aufgaben' => 'Integration Testing', 'created_at' => now(), 'updated_at' => now()];
            }
        }

        DB::table('arbeitszeiten')->insert($arbeitszeiten);
    }
}
