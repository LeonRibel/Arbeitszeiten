<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class ProjekteSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $projekte = [
            [
                'aufgabe' => 'Website Relaunch',
                'kunde' => 'Mustermann GmbH',
                'status' => 'aktiv',
                'gesamt' => 12500.00,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'aufgabe' => 'Mobile App Entwicklung',
                'kunde' => 'TechStart AG',
                'status' => 'aktiv',
                'gesamt' => 25000.00,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'aufgabe' => 'E-Commerce Platform',
                'kunde' => 'Shop24 GmbH',
                'status' => 'aktiv',
                'gesamt' => 45000.00,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'aufgabe' => 'CRM System Integration',
                'kunde' => 'Business Solutions Ltd',
                'status' => 'abgeschlossen',
                'gesamt' => 18500.00,
                'created_at' => now()->subMonths(2),
                'updated_at' => now()->subMonth(),
            ],
            [
                'aufgabe' => 'API Development',
                'kunde' => 'DataFlow Inc',
                'status' => 'aktiv',
                'gesamt' => 8750.00,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'aufgabe' => 'Dashboard Design',
                'kunde' => 'Analytics Pro',
                'status' => 'aktiv',
                'gesamt' => 6200.00,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'aufgabe' => 'Legacy System Migration',
                'kunde' => 'OldTech GmbH',
                'status' => 'abgeschlossen',
                'gesamt' => 32000.00,
                'created_at' => now()->subMonths(3),
                'updated_at' => now()->subMonths(1),
            ],
            [
                'aufgabe' => 'Security Audit',
                'kunde' => 'SecureNet AG',
                'status' => 'aktiv',
                'gesamt' => 5500.00,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'aufgabe' => 'Cloud Infrastructure Setup',
                'kunde' => 'CloudFirst GmbH',
                'status' => 'aktiv',
                'gesamt' => 15000.00,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'aufgabe' => 'Wartung & Support 2025',
                'kunde' => 'Mustermann GmbH',
                'status' => 'aktiv',
                'gesamt' => 9600.00,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ];

        DB::table('projekte')->insert($projekte);
    }
}
