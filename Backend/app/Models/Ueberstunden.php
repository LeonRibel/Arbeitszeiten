<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Ueberstunden extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'ueberstunden';

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
        'user_id',
        'start',
        'ende',
        'stunden',
        'beschreibung'
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'start' => 'datetime',
        'ende' => 'datetime',
        'stunden' => 'float',
    ];

    /**
     * Get the user that owns the Ueberstunden.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
