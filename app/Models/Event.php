<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Event extends Model
{
    use HasFactory;

    protected $fillable = [
        'organizer_id',
        'title',
        'description',
        'venue',
        'start_date',
        'end_date',
        'image',
        'status',
        'total_tickets',
        'available_tickets',
        'price',
    ];
    
    protected $casts = [
        'start_date' => 'datetime',
        'end_date' => 'datetime',
    ];
    
    public function organizer()
    {
        return $this->belongsTo(Organizer::class);
    }
    
    public function tickets()
    {
        return $this->hasMany(Ticket::class);
    }
    
    public function transactions()
    {
        return $this->hasMany(Transaction::class);
    }

    public function ticketTypes()
    {
        return $this->hasMany(TicketType::class)->orderBy('sort_order');
    }

    // Obtenir le prix minimum parmi tous les types de billets
    public function getMinPriceAttribute()
    {
        return $this->ticketTypes()->where('is_active', true)->min('price') ?? 0;
    }

    // Obtenir le prix maximum parmi tous les types de billets
    public function getMaxPriceAttribute()
    {
        return $this->ticketTypes()->where('is_active', true)->max('price') ?? 0;
    }

    // Vérifier si l'événement a des billets disponibles
    public function hasAvailableTickets()
    {
        return $this->ticketTypes()->where('is_active', true)->where('available_quantity', '>', 0)->exists();
    }

    // Obtenir le nombre total de billets disponibles
    public function getTotalAvailableTicketsAttribute()
    {
        return $this->ticketTypes()->where('is_active', true)->sum('available_quantity');
    }
}
