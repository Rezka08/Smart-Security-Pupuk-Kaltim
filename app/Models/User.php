<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'username',
        'password',
        'full_name',
        'role',
        'is_active',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    // Relationships
    public function cctvLogs()
    {
        return $this->hasMany(CctvLog::class);
    }

    public function inventoryChecks()
    {
        return $this->hasMany(InventoryCheck::class);
    }

    public function reportedTickets()
    {
        return $this->hasMany(MaintenanceTicket::class, 'reporter_id');
    }

    public function assignedTickets()
    {
        return $this->hasMany(MaintenanceTicket::class, 'technician_id');
    }

    // Helper methods
    public function isAdmin()
    {
        return $this->role === 'admin';
    }

    public function isSecurity()
    {
        return $this->role === 'security';
    }

    public function isMaintenance()
    {
        return $this->role === 'maintenance';
    }
}