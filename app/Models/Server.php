<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Server extends Model
{
    use HasFactory,SoftDeletes;

    protected $table = 'server_connections';

    protected $fillable = [
        'ip_address',
    ];
}