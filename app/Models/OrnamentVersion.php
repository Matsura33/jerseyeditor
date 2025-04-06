<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrnamentVersion extends Model
{
    use HasFactory;

    protected $fillable = [
        'ornament_id',
        'name',
        'image_url',
        'active',
    ];

    protected $casts = [
        'active' => 'boolean',
    ];

    public function ornament()
    {
        return $this->belongsTo(Ornament::class);
    }

    public function userJerseyOrnaments()
    {
        return $this->hasMany(UserJerseyOrnament::class);
    }
}
