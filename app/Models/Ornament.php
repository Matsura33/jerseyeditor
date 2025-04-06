<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Ornament extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'active'
    ];

    protected $casts = [
        'active' => 'boolean'
    ];

    public function versions()
    {
        return $this->hasMany(OrnamentVersion::class);
    }

    public function jerseys()
    {
        return $this->belongsToMany(Jersey::class);
    }

    public function userJerseys()
    {
        return $this->belongsToMany(UserJersey::class, 'user_jersey_ornaments')
            ->withPivot('position_x', 'position_y', 'ornament_version_id')
            ->withTimestamps();
    }
}
