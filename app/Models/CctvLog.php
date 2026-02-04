<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CctvLog extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'shift',
        'log_time',
        'camera_location',
        'incident_description',
        'action_taken',
        'status',
        'evidence_photo_url',
    ];

    protected $casts = [
        'log_time' => 'datetime',
    ];

    // Relationships
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function maintenanceTicket()
    {
        return $this->hasOne(MaintenanceTicket::class, 'source_cctv_log_id');
    }
}