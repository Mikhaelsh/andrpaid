<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class University extends Model
{
    protected $guarded = ["id"];

    public function province(){
        return $this->belongsTo(Province::class);
    }

    public function user(){
        return $this->belongsTo(User::class);
    }

    public function affiliations(){
        return $this->hasMany(Affiliation::class);
    }
}
