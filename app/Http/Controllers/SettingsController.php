<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class SettingsController extends Controller
{
    /**
     * Toon de instellingen pagina.
     *
     * @return \Illuminate\View\View
     */
    public function index($tab = 'general')
    {
        // Controleer of de tab geldig is
        $validTabs = ['general', 'working-hours', 'services', 'notifications'];
        $activeTab = in_array($tab, $validTabs) ? $tab : 'general';
        
        return view('settings', [
            'activeTab' => $activeTab
        ]);
    }

    /**
     * Update de instellingen.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(Request $request)
    {
        // Hier kun je de verwerking van de formuliergegevens implementeren
        // Voor deze demo slaan we de gegevens niet op
        
        return redirect()->back()->with('success', 'Instellingen succesvol bijgewerkt!');
    }
}