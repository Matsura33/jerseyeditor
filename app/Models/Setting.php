<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Setting extends Model
{
    use HasFactory;

    protected $fillable = [
        'openai_key',
        'getimg_key',
        'contest_start',
        'contest_end',
        'voting_end',
    ];

    protected $casts = [
        'contest_start' => 'datetime',
        'contest_end' => 'datetime',
        'voting_end' => 'datetime',
    ];
}
