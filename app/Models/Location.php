<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Location extends Model
{
    use HasFactory;

    protected $fillable = [
        'location',
        'lat',
        'lng',
    ];

    protected $casts = [
        'lat' => 'float',
        'lng' => 'float',
    ];

    /**
     * Get the Videos associated with the location
     */ 
    public function videos()
    {
        return $this->belongsToMany(Video::class)
            ->where('user_id', auth()->id());
    }
}
