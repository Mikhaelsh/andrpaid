<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PaperType extends Model
{
    protected $guarded = ["id"];

    public function papers(){
        return $this->hasMany(Paper::class);
    }
}
