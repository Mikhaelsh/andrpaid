<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Lecturer extends Model
{
    use HasFactory;

    protected $guarded = ["id"];

    public function province(){
        return $this->belongsTo(Province::class);
    }

    public function user(){
        return $this->belongsTo(User::class);
    }

    public function papers(){
        return $this->hasMany(Paper::class);
    }

    public function affiliation(){
        return $this->hasOne(Affiliation::class);
    }

    public function researchFields(){
        return $this->belongsToMany(ResearchField::class);
    }
}
