<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class InventoryItem extends Model
{
    use HasFactory;

    protected $fillable = [
        'item_name',
        'category',
        'description',
    ];

    // Relationships
    public function checkDetails()
    {
        return $this->hasMany(InventoryCheckDetail::class, 'item_id');
    }
}