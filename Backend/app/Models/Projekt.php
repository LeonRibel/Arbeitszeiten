<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Projekt extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'projekte';

    /**
     * The primary key associated with the table.
     *
     * @var string
     */
    protected $primaryKey = 'id';

    /**
     * Indicates if the model should be timestamped.
     *
     * @var bool
     */
    public $timestamps = true;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<string>
     */
    protected $fillable = ['aufgabe', 'status', 'gesamt', 'kunde_id']; // kein 'kunde' mehr

    public function kunde()
    {
        return $this->belongsTo(Kunde::class, 'kunde_id');
    }

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'gesamt' => 'decimal:2',
    ];
}
