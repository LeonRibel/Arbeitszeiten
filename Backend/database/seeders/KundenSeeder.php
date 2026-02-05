<?php

namespace Database\Seeders;

use App\Models\Kunde;
use Illuminate\Database\Seeder;

class KundenSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $kunden = [
            [
                'firmenname' => 'TechnoSoft GmbH',
                'ansprechpartner' => 'Max Mustermann',
                'email' => 'max.mustermann@technosoft.de',
                'telefon' => '+49 30 123456789',
                'ort' => 'Berlin',
                'straße' => 'Hauptstraße',
                'hausnummer' => '42',
                'plz' => '10115',
                'land' => 'DE',
                'ust_id' => 'DE123456789',
                'handelsregister_id' => 'HRB 12345 B',
                'kundenart' => 'B2B',
            ],
            [
                'firmenname' => 'WebDesign Pro AG',
                'ansprechpartner' => 'Anna Schmidt',
                'email' => 'a.schmidt@webdesign-pro.de',
                'telefon' => '+49 89 987654321',
                'ort' => 'München',
                'straße' => 'Marienplatz',
                'hausnummer' => '15',
                'plz' => '80331',
                'land' => 'DE',
                'ust_id' => 'DE987654321',
                'handelsregister_id' => 'HRB 98765 M',
                'kundenart' => 'B2B',
            ],
            [
                'firmenname' => 'Innovate Solutions',
                'ansprechpartner' => 'Thomas Weber',
                'email' => 'thomas.weber@innovate-solutions.de',
                'telefon' => '+49 40 555123456',
                'ort' => 'Hamburg',
                'straße' => 'Reeperbahn',
                'hausnummer' => '88',
                'plz' => '20359',
                'land' => 'DE',
                'ust_id' => 'DE555123456',
                'handelsregister_id' => null,
                'kundenart' => 'B2B',
            ],
            [
                'firmenname' => 'Startup Ventures',
                'ansprechpartner' => 'Lisa Müller',
                'email' => 'lisa@startup-ventures.com',
                'telefon' => '+49 221 777888999',
                'ort' => 'Köln',
                'straße' => 'Domstraße',
                'hausnummer' => '3',
                'plz' => '50667',
                'land' => 'DE',
                'ust_id' => null,
                'handelsregister_id' => null,
                'kundenart' => 'B2B',
            ],
            [
                'firmenname' => 'Privatperson Meier',
                'ansprechpartner' => 'Klaus Meier',
                'email' => 'klaus.meier@gmail.com',
                'telefon' => '+49 711 234567890',
                'ort' => 'Stuttgart',
                'straße' => 'Königstraße',
                'hausnummer' => '27',
                'plz' => '70173',
                'land' => 'DE',
                'ust_id' => null,
                'handelsregister_id' => null,
                'kundenart' => 'B2C',
            ],
        ];

        foreach ($kunden as $kunde) {
            Kunde::create($kunde);
        }
    }
}
