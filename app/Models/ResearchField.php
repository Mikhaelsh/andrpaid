<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ResearchField extends Model
{
    protected $guarded = ["id"];

    public function papers(){
        return $this->belongsToMany(Paper::class);
    }

    public function lecturers(){
        return $this->belongsToMany(Lecturer::class);
    }
}
