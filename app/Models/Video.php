<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Video extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'description',
        'added_by',
        'youtube_url',
        'cover_image',
        'featured_users',
    ];

    protected $casts = [
        'featured_users' => 'json',
    ];
}
