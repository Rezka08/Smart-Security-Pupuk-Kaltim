<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MaintenanceTicket extends Model
{
    use HasFactory;

    protected $fillable = [
        'ticket_code',
        'reporter_id',
        'technician_id',
        'source_cctv_log_id',
        'source_inv_detail_id',
        'issue_description',
        'status',
        'resolution_notes',
    ];

    // Relationships
    public function reporter()
    {
        return $this->belongsTo(User::class, 'reporter_id');
    }

    public function technician()
    {
        return $this->belongsTo(User::class, 'technician_id');
    }

    public function sourceCctvLog()
    {
        return $this->belongsTo(CctvLog::class, 'source_cctv_log_id');
    }

    public function sourceInventoryDetail()
    {
        return $this->belongsTo(InventoryCheckDetail::class, 'source_inv_detail_id');
    }

    // Helper method to generate ticket code
    public static function generateTicketCode()
    {
        $prefix = 'TKT';
        $date = now()->format('Ymd');
        $lastTicket = self::whereDate('created_at', now())->latest()->first();
        
        $number = $lastTicket ? (int)substr($lastTicket->ticket_code, -4) + 1 : 1;
        
        return $prefix . $date . str_pad($number, 4, '0', STR_PAD_LEFT);
    }
}