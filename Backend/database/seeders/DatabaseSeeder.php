<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();

        User::factory()->create([
            'vorname' => 'Test',
            'nachname' => 'User',
            'email' => 'test@example.com',
            'password' => 'password',
            'urlaubstage' => 25,
        ]);

        // Weitere Beispiel-User mit verschiedenen Urlaubstagen
        User::factory()->create([
            'vorname' => 'Max',
            'nachname' => 'Mustermann',
            'email' => 'max@example.com',
            'password' => 'password',
            'urlaubstage' => 30,
        ]);

        User::factory()->create([
            'vorname' => 'Anna',
            'nachname' => 'Schmidt',
            'email' => 'anna@example.com',
            'password' => 'password',
            'urlaubstage' => 28,
        ]);

        // Seed Arbeitszeiten mit Beispieldaten
        $this->call([
            ArbeitszeitenSeeder::class,
            UrlaubSeeder::class,
            FehlzeitenSeeder::class,
            ProjekteSeeder::class,
        ]);
    }
}
