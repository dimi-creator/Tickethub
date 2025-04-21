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
        'total_tickets',
        'available_tickets',
        'price',
        'image',
        'status',
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
}
