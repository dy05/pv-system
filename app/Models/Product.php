<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphOne;

class Product extends Model
{
    protected $fillable = [
        'user_id',
        'project_id',
        'name',
        'description',
        'status',
        'generated_at',
        'details',
    ];

    protected $casts = [
        'details' => 'array'
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

    /**
     * @return BelongsTo
     */
    public function project(): BelongsTo
    {
        return $this->belongsTo(Project::class);
    }
}
