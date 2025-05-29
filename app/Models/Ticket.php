<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;



class Ticket extends Model
{
    use HasFactory;

    protected $fillable = [
        'ticket_number',
        'event_id',
        'ticket_type_id',
        'user_id',
        'attendee_name',
        'qr_code',
        'attendee_email',
        'attendee_phone',
        'unit_price',
        'status',
        'paid_at',
    ];
    
    protected $casts = [
        'paid_at' => 'datetime',
        'unit_price' => 'decimal:2',
    ];
    
    public function event()
    {
        return $this->belongsTo(Event::class);
    }
    
    public function ticketType()
    {
        return $this->belongsTo(TicketType::class);
    }
    
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}

