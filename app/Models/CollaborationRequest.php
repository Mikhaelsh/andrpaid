<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CollaborationRequest extends Model
{
    protected $guarded = ["id"];

    public function fromLecturer(){
        return $this->belongsTo(Lecturer::class, 'from_lecturer_id');
    }

    public function toLecturer(){
        return $this->belongsTo(Lecturer::class, 'to_lecturer_id');
    }

    public function collaboration(){
        return $this->belongsTo(collaboration::class);
    }

    public function paper(){
        return $this->belongsTo(Paper::class);
    }
}
