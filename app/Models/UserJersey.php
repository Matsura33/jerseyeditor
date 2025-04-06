<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserJersey extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'user_id',
        'jersey_id',
        'texture_url',
        'texture_size',
        'prompt',
        'ornaments_data'
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'ornaments_data' => 'array',
        'texture_size' => 'integer'
    ];

    /**
     * Get the user that owns the jersey.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the base jersey.
     */
    public function jersey()
    {
        return $this->belongsTo(Jersey::class);
    }

    /**
     * Get the ornaments for the user jersey.
     */
    public function ornaments()
    {
        return $this->belongsToMany(Ornament::class, 'user_jersey_ornaments')
            ->withPivot('position_x', 'position_y', 'ornament_version_id')
            ->withTimestamps();
    }

    /**
     * Get the ornament versions for the user jersey.
     */
    public function ornamentVersions()
    {
        return $this->belongsToMany(OrnamentVersion::class, 'user_jersey_ornaments')
            ->withPivot('position_x', 'position_y')
            ->withTimestamps();
    }
}
