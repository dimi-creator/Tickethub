<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;


class Organizer extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'company_name',
        'description',
        'website',
        'logo',
    ];
    
    public function user()
    {
        return $this->belongsTo(User::class);
    }
    
    public function events()
    {
        return $this->hasMany(Event::class);
    }
}
