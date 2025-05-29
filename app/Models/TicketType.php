<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TicketType extends Model
{
     use HasFactory;

    protected $fillable = [
        'event_id',
        'name',
        'description',
        'price',
        'total_quantity',
        'available_quantity',
        'sort_order',
        'is_active',
    ];

    protected $casts = [
        'price' => 'decimal:2',
        'is_active' => 'boolean',
    ];

    public function event()
    {
        return $this->belongsTo(Event::class);
    }

    public function tickets()
    {
        return $this->hasMany(Ticket::class);
    }

    // Vérifier si le type de billet est disponible
    public function isAvailable($quantity = 1)
    {
        return $this->is_active && $this->available_quantity >= $quantity;
    }

    // Calculer les billets vendus
    public function getSoldQuantityAttribute()
    {
        return $this->total_quantity - $this->available_quantity;
    }

    // Calculer le pourcentage de vente
    public function getSalePercentageAttribute()
    {
        if ($this->total_quantity === 0) {
            return 0;
        }
        return round(($this->sold_quantity / $this->total_quantity) * 100, 2);
    }
    // Mettre à jour la quantité disponible après une vente
    public function updateAvailableQuantity($quantity)
    {
        if ($this->available_quantity >= $quantity) {
            $this->available_quantity -= $quantity;
            $this->save();
            return true;
        }
        return false;
    }
}
