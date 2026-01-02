<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable, HasUuids;

    /**
     * The attributes that can not be filled.
     *
     * @var list<string>
     */
    protected $guarded = [
        'id'
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'latest_password_updated_at' => 'datetime',
        ];
    }

    public function uniqueIds()
    {
        return ['profileId'];
    }

    public function isLecturer(){
        return $this->lecturer !== null;
    }

    public function isUniversity(){
        return $this->university !== null;
    }

    public function isAdmin(){
        return $this->id === 1;
    }

    public function lecturer(){
        return $this->hasOne(Lecturer::class);
    }

    public function university(){
        return $this->hasOne(University::class);
    }

    public function paperStars(){
        return $this->hasMany(PaperStar::class);
    }
}
