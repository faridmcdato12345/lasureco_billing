<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AuditTrail extends Model
{
    use HasFactory;

    protected $table = 'audit_trails';
    protected $primaryKey = 'id';
    // public $timestamps = false;
    protected $fillable = [
        'id',
        'created_at',
        'updated_at',
        'at_old_value',
        'at_new_value',
        'at_action',
        'at_table',
        'at_auditable',
        'user_id',
        'cm_id',
    ];
}
