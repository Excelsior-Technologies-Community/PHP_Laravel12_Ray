<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Activity extends Model
{
    protected $fillable = [
        'student_name',
        'action',
        'details'
    ];

    protected $casts = [
        'details' => 'array'
    ];
}