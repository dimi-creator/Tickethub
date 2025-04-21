<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Ticket extends Model
{
    use HasFactory;

    protected $fillable = [
        'ticket_number',
        'event_id',
        'user_id',
        'attendee_name',
        'attendee_email',
        'attendee_phone',
        'status',
        'paid_at',
    ];
    
    protected $casts = [
        'paid_at' => 'datetime',
    ];
    
    public function event()
    {
        return $this->belongsTo(Event::class);
    }
    
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}

