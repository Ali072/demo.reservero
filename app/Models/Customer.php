<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Customer extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'email',
        'phone',
        'visit_count',
        'last_visit',
    ];

    /**
     * Relatie met alle reserveringen van deze klant
     */
    public function reservations()
    {
        return $this->hasMany(Reservation::class);
    }

    /**
     * Relatie met de meest recente reservering
     */
    public function latestReservation()
    {
        return $this->hasOne(Reservation::class)->latest('date');
    }
}
