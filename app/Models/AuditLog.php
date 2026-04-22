<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AuditLog extends Model
{
    protected $table = 'audit_logs';

    public $timestamps = false;

    protected $fillable = [
        'user_id',
        'action',
        'target_type',
        'target_id',
        'description',
        'ip_address',
        'user_agent',
        'created_at',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
