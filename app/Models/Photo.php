<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Photo extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'visitor_name',
        'visitor_social',
        'template_id',
        'result_image_path',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function template()
    {
        return $this->belongsTo(Template::class);
    }

    /**
     * Public URL untuk preview photostrip.
     */
    public function resultUrl(): string
    {
        return asset('storage/' . ltrim($this->result_image_path, '/'));
    }
}
