<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Kunde extends Model
{
    
    protected $table = 'kunden';

    protected $fillable = [
        'firmenname',
        'ansprechpartner',
        'email',
        'ort',
        'straße',
        'land',
        'plz',
        'hausnummer',
        'ust_id',
        'handelsregister_id',
        'telefon',
        'kundenart',
    ];
}
