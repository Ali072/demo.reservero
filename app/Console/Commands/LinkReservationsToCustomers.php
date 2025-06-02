<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Reservation;
use App\Models\Customer;

class LinkReservationsToCustomers extends Command
{
    protected $signature = 'reservations:link-customers';
    protected $description = 'Koppel bestaande reserveringen aan klanten';

    public function handle()
    {
        $reservations = Reservation::whereNull('customer_id')->get();
        $this->info("Bezig met koppelen van {$reservations->count()} reserveringen aan klanten...");

        $bar = $this->output->createProgressBar($reservations->count());
        $bar->start();

        foreach ($reservations as $reservation) {
            // Zoek een bestaande klant of maak een nieuwe aan
            $customer = Customer::firstOrCreate(
                ['email' => $reservation->email],
                [
                    'name' => $reservation->name,
                    'phone' => $reservation->phone,
                    'visit_count' => 0,
                ]
            );

            $reservation->customer_id = $customer->id;
            $reservation->save();

            $bar->advance();
        }

        $bar->finish();
        $this->newLine();
        $this->info('Alle reserveringen zijn succesvol gekoppeld aan klanten!');
    }
} 