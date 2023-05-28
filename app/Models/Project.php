<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphOne;

class Project extends Model
{
    protected $fillable = [
        'user_id',
        'description',
        'name',
        'started_at',
    ];

    protected $with = [
        'setting'
    ];

    /**
     * @return MorphOne
     */
    public function setting(): MorphOne
    {
        return $this->morphOne(Setting::class, 'elementable');
    }

    /**
     * @return BelongsTo
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
