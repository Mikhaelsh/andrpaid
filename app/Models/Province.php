<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Province extends Model
{
    protected $guarded = ["id"];

    public function lecturers(){
        return $this->hasMany(Lecturer::class);
    }

    public function universities(){
        return $this->hasMany(University::class);
    }
}
