<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PaperReference extends Model
{
    use HasFactory;

    protected $guarded = [];

    protected $casts = [
        'key_points' => 'array', 
        'is_analyzed' => 'boolean',
    ];

    public function paper()
    {
        return $this->belongsTo(Paper::class);
    }
}
