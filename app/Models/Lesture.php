<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Lesture extends Model
{
    protected $table = 'lecture'; 
    protected $fillable = ['lecture_name'];
}
