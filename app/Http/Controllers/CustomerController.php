<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CustomerController extends Controller
{
    /**
     * Toon een lijst van de klanten.
     */
    public function index(Request $request)
    {
        $search = $request->input('search');
    
        $customers = Customer::when($search, function($query, $search) {
            $query->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%")
                  ->orWhere('phone', 'like', "%{$search}%");
        })
        ->withCount('reservations')
        ->with(['latestReservation' => function($query) {
            $query->orderBy('date', 'desc');
        }])
        ->get();
    
        // Voeg laatste reserveringsdatum toe aan elk klantobject
        $customers->each(function($customer) {
            $customer->last_reservation = $customer->latestReservation ? $customer->latestReservation->date : null;
        });
    
        // Haal reserveringen per maand op
        $reservationsByMonth = DB::table('reservations')
            ->selectRaw('MONTH(date) as month, COUNT(*) as count')
            ->whereYear('date', date('Y'))
            ->groupBy('month')
            ->get()
            ->pluck('count', 'month')
            ->toArray();
    
        // Vul ontbrekende maanden in
        for ($i = 1; $i <= 12; $i++) {
            if (!isset($reservationsByMonth[$i])) {
                $reservationsByMonth[$i] = 0;
            }
        }
        ksort($reservationsByMonth);

        // Haal groepsgrootte gegevens op
        $groupSizeData = DB::table('reservations')
            ->selectRaw('
                SUM(CASE WHEN people BETWEEN 1 AND 2 THEN 1 ELSE 0 END) as size_1_2,
                SUM(CASE WHEN people BETWEEN 3 AND 4 THEN 1 ELSE 0 END) as size_3_4,
                SUM(CASE WHEN people BETWEEN 5 AND 6 THEN 1 ELSE 0 END) as size_5_6,
                SUM(CASE WHEN people BETWEEN 7 AND 8 THEN 1 ELSE 0 END) as size_7_8,
                SUM(CASE WHEN people >= 9 THEN 1 ELSE 0 END) as size_9_plus
            ')
            ->first();
        
        $groupSizes = [
            $groupSizeData->size_1_2 ?? 0,
            $groupSizeData->size_3_4 ?? 0,
            $groupSizeData->size_5_6 ?? 0,
            $groupSizeData->size_7_8 ?? 0,
            $groupSizeData->size_9_plus ?? 0
        ];

        return view('customers', compact('customers', 'reservationsByMonth', 'groupSizes'));
    }
    

    /**
     * Sla een nieuwe klant op in de database.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'nullable|email|max:255',
            'phone' => 'nullable|string|max:20',
        ]);

        Customer::create($validated);

        return redirect()->route('customers.index')->with('success', 'Klant succesvol toegevoegd.');
    }

    /**
     * Toon de gegevens van één klant.
     */
    public function show($id)
    {
        $customer = Customer::withCount('reservations')
            ->with(['latestReservation' => function($query) {
                $query->orderBy('date', 'desc');
            }])
            ->findOrFail($id);
        
        // Voeg laatste reserveringsdatum toe
        $customer->last_reservation = $customer->latestReservation ? $customer->latestReservation->date : null;
        
        return response()->json($customer);
    }

    /**
     * Update een bestaande klant.
     */
    public function update(Request $request, $id)
    {
        $customer = Customer::findOrFail($id);

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'nullable|email|max:255',
            'phone' => 'nullable|string|max:20',
        ]);

        $customer->update($validated);

        return redirect()->route('customers.index')->with('success', 'Klant succesvol bijgewerkt.');
    }

    /**
     * Verwijder een klant uit de database.
     */
    public function destroy($id)
    {
        $customer = Customer::findOrFail($id);
        $customer->delete();

        return redirect()->route('customers.index')->with('success', 'Klant succesvol verwijderd.');
    }
}
