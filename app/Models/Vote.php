<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Vote extends Model
{
    protected $table = "votes";

    public $timestamps = false;
    
    protected $fillable = [
        "name",
        "candidate_voted_id",
        "candidate_id",
        "date"
    ];
}
