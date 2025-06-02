<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Reservation;
use Carbon\Carbon;

class CalendarController extends Controller
{
    public function index()
    {
        // Haal alle reserveringen op
        $reservations = Reservation::all();
        
        // Verwerk reserveringen in een formaat dat gemakkelijk te gebruiken is in de view
        $calendarData = [];
        
        foreach ($reservations as $reservation) {
            $dateKey = $reservation->date;
            
            if (!isset($calendarData[$dateKey])) {
                $calendarData[$dateKey] = [
                    'count' => 0,
                    'statuses' => [
                        'in_behandeling' => 0,
                        'bevestigd' => 0,
                        'geannuleerd' => 0
                    ],
                    'reservations' => []
                ];
            }
            
            $calendarData[$dateKey]['count']++;
            $calendarData[$dateKey]['statuses'][$reservation->status]++;
            $calendarData[$dateKey]['reservations'][] = $reservation;
        }
        
        return view('calendar', compact('calendarData'));
    }
}
