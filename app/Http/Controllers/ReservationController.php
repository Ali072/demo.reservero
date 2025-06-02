<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Reservation;
use App\Models\Customer;

class ReservationController extends Controller
{
    /**
     * Toon het reserveringsformulier
     */
    public function showForm()
    {
        return view('widget');
    }

    /**
     * Verwerk de reserveringsaanvraag
     */
    public function processReservation(Request $request)
    {
        // Valideer de invoer
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'phone' => 'required|string|max:20',
            'people' => 'required|integer|min:1',
            'date' => 'required|date',
            'time' => 'required',
            'special_requests' => 'nullable|string',
        ]);
        
        // Zoek bestaande klant of maak een nieuwe aan
        $customer = Customer::firstOrCreate(
            ['email' => $validated['email']],
            [
                'name' => $validated['name'],
                'phone' => $validated['phone'],
                'visit_count' => 0,
            ]
        );
        
        // Maak een nieuwe reservering aan
        $reservation = new Reservation($validated);
        $reservation->reference = Reservation::generateReference();
        $reservation->status = 'in_behandeling';
        $reservation->customer_id = $customer->id;
        $reservation->save();
        
        return redirect()->route('reservation.form')
            ->with('success', 'Reservering is aangevraagd! Je referentienummer is: ' . $reservation->reference);
    }

    /**
     * Toon het dashboard met alle reserveringen
     */
    public function dashboard()
    {
        $reservations = Reservation::orderBy('created_at', 'desc')->get();
        
        // Statistieken berekenen
        $stats = [
            'total' => $reservations->count(),
            'pending' => $reservations->where('status', 'in_behandeling')->count(),
            'confirmed' => $reservations->where('status', 'bevestigd')->count(),
            'cancelled' => $reservations->where('status', 'geannuleerd')->count(),
            'today' => $reservations->where('date', now()->toDateString())->count(),
        ];
        
        return view('dashboard', compact('reservations', 'stats'));
    }

    /**
     * Wijzig de status van een reservering
     */
    public function updateStatus(Request $request, Reservation $reservation)
    {
        $validated = $request->validate([
            'status' => 'required|in:in_behandeling,bevestigd,geannuleerd',
        ]);
        
        $reservation->status = $validated['status'];
        $reservation->save();
        
        return redirect()->route('dashboard')
            ->with('success', 'Reserveringsstatus is bijgewerkt.');
    }

    /**
     * Toon het formulier om een reservering te bewerken
     */
    public function edit(Reservation $reservation)
    {
        return view('reservation_edit', compact('reservation'));
    }

    /**
     * Update de reservering in de database
     */
    public function update(Request $request, Reservation $reservation)
    {
        // Valideer de invoer
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'phone' => 'required|string|max:20',
            'people' => 'required|integer|min:1',
            'date' => 'required|date',
            'time' => 'required',
            'special_requests' => 'nullable|string',
            'status' => 'required|in:in_behandeling,bevestigd,geannuleerd',
        ]);
        
        // Update de reservering
        $reservation->update($validated);
        
        return redirect()->route('calendar')
            ->with('success', 'Reservering is bijgewerkt!');
    }
}
