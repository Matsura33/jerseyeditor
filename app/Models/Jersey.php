<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Jersey extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'description',
        'layer_base',
        'layer_shadow',
        'active',
    ];

    protected $casts = [
        'active' => 'boolean',
    ];

    public function ornaments()
    {
        return $this->belongsToMany(Ornament::class, 'jersey_ornaments')
            ->withPivot('position_x', 'position_y')
            ->withTimestamps();
    }

    public function userJerseys()
    {
        return $this->hasMany(UserJersey::class);
    }
}
