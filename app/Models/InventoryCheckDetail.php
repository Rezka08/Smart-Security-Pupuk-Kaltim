<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class InventoryCheckDetail extends Model
{
    use HasFactory;

    protected $fillable = [
        'check_id',
        'item_id',
        'condition',
        'remarks',
    ];

    // Relationships
    public function inventoryCheck()
    {
        return $this->belongsTo(InventoryCheck::class, 'check_id');
    }

    public function inventoryItem()
    {
        return $this->belongsTo(InventoryItem::class, 'item_id');
    }

    public function maintenanceTicket()
    {
        return $this->hasOne(MaintenanceTicket::class, 'source_inv_detail_id');
    }
}