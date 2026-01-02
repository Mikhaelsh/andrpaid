<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Paper extends Model
{
    use HasFactory, HasUuids;

    protected $guarded = ["id"];
    protected $casts = [
        'references_data' => 'array', 
        'openCollaboration' => 'boolean',
        'themes' => 'array',
        'lit_review_finalized' => 'boolean',
        'methodology_finalized' => 'boolean',
    ];

    protected $with = ["paperStars"];

    public function uniqueIds()
    {
        return ['paperId'];
    }

    public function paperType(){
        return $this->belongsTo(PaperType::class);
    }

    public function researchFields(){
        return $this->belongsToMany(ResearchField::class);
    }

    public function lecturer(){
        return $this->belongsTo(Lecturer::class);
    }

    public function paperStars(){
        return $this->hasMany(PaperStar::class);
    }

    public function collaborations(){
        return $this->hasMany(Collaboration::class);
    }

    public function collaborationRequests(){
        return $this->hasMany(CollaborationRequest::class);
    }

    public function references()
    {
        return $this->hasMany(PaperReference::class);
    }
}
