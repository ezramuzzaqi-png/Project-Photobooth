<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Template extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'layout_type',
        'frame_image',
        'background_image',
    ];

    public function photos()
    {
        return $this->hasMany(Photo::class);
    }
}
