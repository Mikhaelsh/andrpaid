<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ReportType extends Model
{
    protected $guarded = ["id"];

    public function reports(){
        return $this->hasMany(Report::class);
    }
}
