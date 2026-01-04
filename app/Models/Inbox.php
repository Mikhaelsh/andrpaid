<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Inbox extends Model
{
    use HasFactory, HasUuids;

    protected $guarded = ["id"];

    public function uniqueIds()
    {
        return ['inboxId'];
    }

    public function fromUser(){
        return $this->belongsTo(User::class, 'from_user_id');
    }

    public function toUser(){
        return $this->belongsTo(User::class, 'to_user_id');
    }
}
