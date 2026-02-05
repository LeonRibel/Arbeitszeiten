<?php

namespace App\Models;

use App\Enums\UrlaubsStatus;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Urlaub extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'urlaub';

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
    public $timestamps = false;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<string>
     */
    protected $fillable = [
        'mitarbeiter_id',
        'start',
        'ende',
        'status',
        'tage',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'start' => 'datetime',
        'ende' => 'datetime',
        'status' => UrlaubsStatus::class,
    ];

    /**
     * Get the user that owns the Urlaub.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'mitarbeiter_id');
    }
}
