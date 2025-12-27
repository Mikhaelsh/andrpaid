<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;

class Paper extends Model
{
    use HasUuids;

    protected $guarded = ["id"];

    protected $with = ["paperStars"];

    public function uniqueIds()
    {
        return ['paperId'];
    }

    public function paperType(){
        return $this->belongsTo(PaperType::class);
    }

    public function researchFields(){
        return $this->belongsToMany(ResearchField::class);
    }

    public function lecturer(){
        return $this->belongsTo(Lecturer::class);
    }

    public function paperStars(){
        return $this->hasMany(PaperStar::class);
    }
}
