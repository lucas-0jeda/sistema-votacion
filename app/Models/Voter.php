<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Voter extends Model
{
    use HasFactory;

    public $timestamps = false;

    protected $table = "voters";

    protected $fillable = [
        "name",
        "lastName",
        "document",
        "dob",
        "is_candidate"
    ];
}
