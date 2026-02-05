<?php

namespace App\Models;

use App\Enums\FehlzeitenStatus;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Fehlzeit extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'fehlzeiten';

    /**
     * The primary key associated with the table.
     *
     * @var string
     */
    protected $primaryKey = 'fehlzeiten_id';

    /**
     * Indicates if the model should be timestamped.
     *
     * @var bool
     */
    public $timestamps = false;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<string>
     */
    protected $fillable = [
        'mitarbeiter_id',
        'Kstart',
        'Kende',
        'krankheit_start',
        'krankheit_ende',
        'status',
        'tage',
        'attest',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'Kstart' => 'datetime',
        'Kende' => 'datetime',
        'status' => FehlzeitenStatus::class,
    ];


    /**
     * Get the user that owns the Fehlzeit.
     */

    
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'mitarbeiter_id');
    }

    /**
     * Accessor für krankheit_start (für Kompatibilität)
     */
    public function getKrankheitStartAttribute()
    {
        return $this->Kstart;
    }

    /**
     * Accessor für krankheit_ende (für Kompatibilität)
     */
    public function getKrankheitEndeAttribute()
    {
        return $this->Kende;
    }

    /**
     * Mutator für krankheit_start (für Kompatibilität)
     */
    public function setKrankheitStartAttribute($value)
    {
        $this->attributes['Kstart'] = $value;
    }

    /**
     * Mutator für krankheit_ende (für Kompatibilität)
     */
    public function setKrankheitEndeAttribute($value)
    {
        $this->attributes['Kende'] = $value;
    }
}
