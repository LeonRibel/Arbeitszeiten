<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Arbeitszeit extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'arbeitszeiten';

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
        'start',
        'ende',
        'aufgaben',
        'user_id',
        'kunde_id'
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'start' => 'datetime',
        'ende' => 'datetime',
    ];

    /**
     * Get the user that owns the Arbeitszeit.
     */

    
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    /**
     * Get the kunde that owns the Arbeitszeit.
     */
    public function kunde(): BelongsTo
    {
        return $this->belongsTo(Kunde::class, 'kunde_id');
    }

    /**
     * Get the calculated hours between start and ende.
     */
    public function getStundenAttribute(): ?float
    {
        if (!$this->start || !$this->ende) {
            return null;
        }

        $diff = $this->start->diff($this->ende);
        return $diff->h + ($diff->i / 60);
    }
}
