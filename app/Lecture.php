<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Lecture extends Model
{
    protected $fillable = [
        'title', 'univ', 'gra', 'dep', 'number', 'date',
    ];
}
