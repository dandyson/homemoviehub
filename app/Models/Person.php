<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Person extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'family',
        'avatar',
    ];

    /**
     * Get the videos associated with the person.
     */ 
    public function videos(): BelongsToMany
    {
        return $this->belongsToMany(Video::class);
    }
}
