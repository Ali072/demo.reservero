<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Reservation extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'email',
        'phone',
        'people',
        'date',
        'time',
        'special_requests',
        'status',
    ];

    /**
     * Genereer een unieke referentiecode
     */
    public static function generateReference()
    {
        $chars = 'ABCDEFGHJKLMNPQRSTUVWXYZ';
        $numbers = '23456789';
        do {
            $reference = '';
            for ($i = 0; $i < 3; $i++) {
                $reference .= $chars[rand(0, strlen($chars) - 1)];
            }
            $reference .= '-';
            for ($i = 0; $i < 4; $i++) {
                $reference .= $numbers[rand(0, strlen($numbers) - 1)];
            }
        } while (self::where('reference', $reference)->exists());
        
        return $reference;
    }

    /**
     * Relatie met de klant van deze reservering
     */
    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }
}
