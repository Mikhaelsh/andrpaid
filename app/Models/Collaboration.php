<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Collaboration extends Model
{
    protected $guarded = ["id"];

    public function paper(){
        return $this->belongsTo(Paper::class);
    }

    public function lecturer(){
        return $this->belongsTo(Lecturer::class);
    }

    public function collaborationRequests(){
        return $this->hasMany(CollaborationRequest::class);
    }
}
