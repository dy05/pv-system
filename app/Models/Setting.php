<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class Setting extends Model
{
    protected $fillable = [
        'latitude',
        'longitude',
        'orientation',
        'power_peak',
        'area',
        'inclination',
        'details',
        'user_id',
        'elementable_type',
        'elementable_id',
    ];

    /**
     * @return MorphTo
     */
    public function elementable(): MorphTo
    {
        return $this->morphTo('elementable');
    }

    /**
     * @return BelongsTo
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
