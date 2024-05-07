<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Sec_right extends Model
{
    use HasFactory;
    
    public $timestamps = false;
    protected $fillable = ['right_name','right_description'];
}
