<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PaperActivity extends Model
{
    protected $guarded = ["id"];

    public function paper(){
        return $this->belongsTo(Paper::class);
    }

    public function user(){
        return $this->belongsTo(User::class);
    }
}
