@extends('layouts.app')

@section('content')
<div class="container py-4">
    <!-- Header -->
    <div class="mb-4">
        <h1 class="fw-bold">Instellingen</h1>
        <p class="text-secondary">Configureer uw reserveringssysteem</p>
    </div>

    <!-- Tabs -->
    <div>
        <ul class="nav nav-tabs mb-4" id="settingsTabs" role="tablist">
            <li class="nav-item" role="presentation">
                <button class="nav-link active" id="general-tab" data-bs-toggle="tab" data-bs-target="#general" type="button" role="tab" aria-controls="general" aria-selected="true">Algemeen</button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link" id="working-hours-tab" data-bs-toggle="tab" data-bs-target="#working-hours" type="button" role="tab" aria-controls="working-hours" aria-selected="false">Openingstijden</button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link" id="services-tab" data-bs-toggle="tab" data-bs-target="#services" type="button" role="tab" aria-controls="services" aria-selected="false">Diensten</button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link" id="notifications-tab" data-bs-toggle="tab" data-bs-target="#notifications" type="button" role="tab" aria-controls="notifications" aria-selected="false">Notificaties</button>
            </li>
        </ul>

        <div class="tab-content" id="settingsTabsContent">
            <!-- General Settings Tab -->
            <div class="tab-pane fade show active" id="general" role="tabpanel" aria-labelledby="general-tab">
                <div class="card shadow-sm">
                    <div class="card-body">
                        <h2 class="card-title">Algemene Instellingen</h2>
                        <p class="text-secondary mb-4">Basisinformatie over uw bedrijf</p>
                        
                        <form>
                            <div class="mb-3">
                                <label for="business_name" class="form-label">Bedrijfsnaam</label>
                                <input type="text" class="form-control" id="business_name" value="Voorbeeld Bedrijf">
                            </div>
                            
                            <div class="mb-3">
                                <label for="business_type" class="form-label">Bedrijfstype</label>
                                <select class="form-select" id="business_type">
                                    <option selected>Restaurant</option>
                                    <option>Salon</option>
                                    <option>Spa</option>
                                    <option>Medische Praktijk</option>
                                    <option>Anders</option>
                                </select>
                            </div>
                            
                            <div class="mb-3">
                                <label for="address" class="form-label">Adres</label>
                                <textarea class="form-control" id="address" rows="3">Hoofdstraat 123, Stad, 1234 AB</textarea>
                            </div>
                            
                            <div class="mb-3">
                                <label for="email" class="form-label">E-mail</label>
                                <input type="email" class="form-control" id="email" value="contact@voorbeeldbedrijf.nl">
                            </div>
                            
                            <div class="mb-3">
                                <label for="phone" class="form-label">Telefoon</label>
                                <input type="text" class="form-control" id="phone" value="06-12345678">
                            </div>
                            
                            <div class="mb-3">
                                <label class="form-label">Logo</label>
                                <div class="d-flex align-items-center">
                                    <div class="rounded-circle bg-light d-flex justify-content-center align-items-center" style="width: 64px; height: 64px;">
                                        <i class="bi bi-person-fill fs-1 text-secondary"></i>
                                    </div>
                                    <div class="ms-3">
                                        <label for="logo-upload" class="btn btn-outline-secondary">
                                            <span>Uploaden</span>
                                            <input id="logo-upload" type="file" class="d-none">
                                        </label>
                                    </div>
                                </div>
                                <small class="text-secondary d-block mt-2">Aanbevolen grootte: 512x512px. PNG of JPG formaat.</small>
                            </div>
                            
                            <button type="button" class="btn btn-primary" onclick="console.log('Algemene instellingen opslaan...')">
                                Wijzigingen Opslaan
                            </button>
                        </form>
                    </div>
                </div>
            </div>

            <!-- Working Hours Tab -->
            <div class="tab-pane fade" id="working-hours" role="tabpanel" aria-labelledby="working-hours-tab">
                <div class="card shadow-sm">
                    <div class="card-body">
                        <h2 class="card-title">Openingstijden</h2>
                        <p class="text-secondary mb-4">Stel uw openingstijden in voor elke dag van de week</p>
                        
                        <form>
                            <div class="working-hours">
                                <!-- Monday -->
                                <div class="row py-2 border-bottom">
                                    <div class="col-md-2 mb-2 mb-md-0">
                                        <span class="fw-medium">Maandag:</span>
                                    </div>
                                    <div class="col-md-3 mb-2 mb-md-0">
                                        <div class="form-check form-switch">
                                            <input class="form-check-input" type="checkbox" id="monday-toggle" checked onchange="toggleDay('monday')">
                                            <label class="form-check-label" for="monday-toggle" id="monday-status">Geopend</label>
                                        </div>
                                    </div>
                                    <div class="col-md-7" id="monday-hours">
                                        <div class="d-flex flex-wrap gap-2 align-items-center">
                                            <input type="time" class="form-control" style="width: auto;" value="09:00">
                                            <span class="text-secondary">tot</span>
                                            <input type="time" class="form-control" style="width: auto;" value="18:00">
                                        </div>
                                    </div>
                                </div>
                                
                                <!-- Tuesday -->
                                <div class="row py-2 border-bottom">
                                    <div class="col-md-2 mb-2 mb-md-0">
                                        <span class="fw-medium">Dinsdag:</span>
                                    </div>
                                    <div class="col-md-3 mb-2 mb-md-0">
                                        <div class="form-check form-switch">
                                            <input class="form-check-input" type="checkbox" id="tuesday-toggle" checked onchange="toggleDay('tuesday')">
                                            <label class="form-check-label" for="tuesday-toggle" id="tuesday-status">Geopend</label>
                                        </div>
                                    </div>
                                    <div class="col-md-7" id="tuesday-hours">
                                        <div class="d-flex flex-wrap gap-2 align-items-center">
                                            <input type="time" class="form-control" style="width: auto;" value="09:00">
                                            <span class="text-secondary">tot</span>
                                            <input type="time" class="form-control" style="width: auto;" value="18:00">
                                        </div>
                                    </div>
                                </div>
                                
                                <!-- Wednesday -->
                                <div class="row py-2 border-bottom">
                                    <div class="col-md-2 mb-2 mb-md-0">
                                        <span class="fw-medium">Woensdag:</span>
                                    </div>
                                    <div class="col-md-3 mb-2 mb-md-0">
                                        <div class="form-check form-switch">
                                            <input class="form-check-input" type="checkbox" id="wednesday-toggle" checked onchange="toggleDay('wednesday')">
                                            <label class="form-check-label" for="wednesday-toggle" id="wednesday-status">Geopend</label>
                                        </div>
                                    </div>
                                    <div class="col-md-7" id="wednesday-hours">
                                        <div class="d-flex flex-wrap gap-2 align-items-center">
                                            <input type="time" class="form-control" style="width: auto;" value="09:00">
                                            <span class="text-secondary">tot</span>
                                            <input type="time" class="form-control" style="width: auto;" value="18:00">
                                        </div>
                                    </div>
                                </div>
                                
                                <!-- Thursday -->
                                <div class="row py-2 border-bottom">
                                    <div class="col-md-2 mb-2 mb-md-0">
                                        <span class="fw-medium">Donderdag:</span>
                                    </div>
                                    <div class="col-md-3 mb-2 mb-md-0">
                                        <div class="form-check form-switch">
                                            <input class="form-check-input" type="checkbox" id="thursday-toggle" checked onchange="toggleDay('thursday')">
                                            <label class="form-check-label" for="thursday-toggle" id="thursday-status">Geopend</label>
                                        </div>
                                    </div>
                                    <div class="col-md-7" id="thursday-hours">
                                        <div class="d-flex flex-wrap gap-2 align-items-center">
                                            <input type="time" class="form-control" style="width: auto;" value="09:00">
                                            <span class="text-secondary">tot</span>
                                            <input type="time" class="form-control" style="width: auto;" value="18:00">
                                        </div>
                                    </div>
                                </div>
                                
                                <!-- Friday -->
                                <div class="row py-2 border-bottom">
                                    <div class="col-md-2 mb-2 mb-md-0">
                                        <span class="fw-medium">Vrijdag:</span>
                                    </div>
                                    <div class="col-md-3 mb-2 mb-md-0">
                                        <div class="form-check form-switch">
                                            <input class="form-check-input" type="checkbox" id="friday-toggle" checked onchange="toggleDay('friday')">
                                            <label class="form-check-label" for="friday-toggle" id="friday-status">Geopend</label>
                                        </div>
                                    </div>
                                    <div class="col-md-7" id="friday-hours">
                                        <div class="d-flex flex-wrap gap-2 align-items-center">
                                            <input type="time" class="form-control" style="width: auto;" value="09:00">
                                            <span class="text-secondary">tot</span>
                                            <input type="time" class="form-control" style="width: auto;" value="18:00">
                                        </div>
                                    </div>
                                </div>
                                
                                <!-- Saturday -->
                                <div class="row py-2 border-bottom">
                                    <div class="col-md-2 mb-2 mb-md-0">
                                        <span class="fw-medium">Zaterdag:</span>
                                    </div>
                                    <div class="col-md-3 mb-2 mb-md-0">
                                        <div class="form-check form-switch">
                                            <input class="form-check-input" type="checkbox" id="saturday-toggle" onchange="toggleDay('saturday')">
                                            <label class="form-check-label" for="saturday-toggle" id="saturday-status">Gesloten</label>
                                        </div>
                                    </div>
                                    <div class="col-md-7 d-none" id="saturday-hours">
                                        <div class="d-flex flex-wrap gap-2 align-items-center">
                                            <input type="time" class="form-control" style="width: auto;" value="10:00">
                                            <span class="text-secondary">tot</span>
                                            <input type="time" class="form-control" style="width: auto;" value="15:00">
                                        </div>
                                    </div>
                                    <div class="col-md-7" id="saturday-closed">
                                        <span class="text-secondary">Gesloten</span>
                                    </div>
                                </div>
                                
                                <!-- Sunday -->
                                <div class="row py-2 border-bottom">
                                    <div class="col-md-2 mb-2 mb-md-0">
                                        <span class="fw-medium">Zondag:</span>
                                    </div>
                                    <div class="col-md-3 mb-2 mb-md-0">
                                        <div class="form-check form-switch">
                                            <input class="form-check-input" type="checkbox" id="sunday-toggle" onchange="toggleDay('sunday')">
                                            <label class="form-check-label" for="sunday-toggle" id="sunday-status">Gesloten</label>
                                        </div>
                                    </div>
                                    <div class="col-md-7 d-none" id="sunday-hours">
                                        <div class="d-flex flex-wrap gap-2 align-items-center">
                                            <input type="time" class="form-control" style="width: auto;" value="10:00">
                                            <span class="text-secondary">tot</span>
                                            <input type="time" class="form-control" style="width: auto;" value="15:00">
                                        </div>
                                    </div>
                                    <div class="col-md-7" id="sunday-closed">
                                        <span class="text-secondary">Gesloten</span>
                                    </div>
                                </div>
                            </div>
                            
                            <button type="button" class="btn btn-primary mt-4" onclick="console.log('Openingstijden opslaan...')">
                                Wijzigingen Opslaan
                            </button>
                        </form>
                    </div>
                </div>
            </div>

            <!-- Services Tab -->
            <div class="tab-pane fade" id="services" role="tabpanel" aria-labelledby="services-tab">
                <div class="card shadow-sm">
                    <div class="card-body">
                        <h2 class="card-title">Diensten</h2>
                        <p class="text-secondary mb-4">Beheer de diensten die u aanbiedt</p>
                        
                        <div class="table-responsive">
                            <table class="table" id="services-table">
                                <thead>
                                    <tr>
                                        <th>Dienst Naam</th>
                                        <th>Duur (min)</th>
                                        <th>Prijs (€)</th>
                                        <th>Acties</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td><input type="text" class="form-control" value="Standaard Dienst"></td>
                                        <td><input type="number" class="form-control" value="60"></td>
                                        <td><input type="number" class="form-control" value="50"></td>
                                        <td><button class="btn btn-link text-danger p-0" onclick="removeService(this)">Verwijderen</button></td>
                                    </tr>
                                    <tr>
                                        <td><input type="text" class="form-control" value="Premium Dienst"></td>
                                        <td><input type="number" class="form-control" value="90"></td>
                                        <td><input type="number" class="form-control" value="75"></td>
                                        <td><button class="btn btn-link text-danger p-0" onclick="removeService(this)">Verwijderen</button></td>
                                    </tr>
                                    <tr>
                                        <td><input type="text" class="form-control" value="Express Dienst"></td>
                                        <td><input type="number" class="form-control" value="30"></td>
                                        <td><input type="number" class="form-control" value="35"></td>
                                        <td><button class="btn btn-link text-danger p-0" onclick="removeService(this)">Verwijderen</button></td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                        
                        <div class="d-flex justify-content-between mt-3">
                            <button type="button" class="btn btn-outline-primary" onclick="addService()">
                                <i class="bi bi-plus"></i> Dienst Toevoegen
                            </button>
                            
                            <button type="button" class="btn btn-primary" onclick="console.log('Diensten opslaan...')">
                                Wijzigingen Opslaan
                            </button>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Notifications Tab -->
            <div class="tab-pane fade" id="notifications" role="tabpanel" aria-labelledby="notifications-tab">
                <div class="card shadow-sm">
                    <div class="card-body">
                        <h2 class="card-title">Notificatie Instellingen</h2>
                        <p class="text-secondary mb-4">Configureer hoe en wanneer notificaties worden verzonden</p>
                        
                        <form>
                            <div class="mb-3">
                                <div class="form-check form-switch">
                                    <input class="form-check-input" type="checkbox" id="email-new" checked>
                                    <label class="form-check-label" for="email-new">E-mailnotificatie bij nieuwe reservering</label>
                                </div>
                            </div>
                            
                            <div class="mb-3">
                                <div class="form-check form-switch">
                                    <input class="form-check-input" type="checkbox" id="email-cancel" checked>
                                    <label class="form-check-label" for="email-cancel">E-mailnotificatie bij annulering</label>
                                </div>
                            </div>
                            
                            <div class="mb-3">
                                <div class="form-check form-switch">
                                    <input class="form-check-input" type="checkbox" id="send-reminders" checked onchange="toggleReminders()">
                                    <label class="form-check-label" for="send-reminders">Stuur herinneringen naar klanten</label>
                                </div>
                            </div>
                            
                            <div id="reminders-section" class="ms-4 mb-3">
                                <div class="mb-3">
                                    <label for="reminder-hours" class="form-label">Herinnering uren voor afspraak</label>
                                    <input type="number" class="form-control" id="reminder-hours" value="24" style="max-width: 200px;">
                                </div>
                                
                                <div class="mb-3">
                                    <label for="reminder-template" class="form-label">Herinneringssjabloon</label>
                                    <textarea class="form-control" id="reminder-template" rows="6">Hallo {customer_name},

Dit is een herinnering dat u een afspraak heeft gepland op {appointment_date} om {appointment_time}.

We kijken ernaar uit u te zien!

{business_name}</textarea>
                                    <small class="text-secondary d-block mt-2">
                                        Beschikbare variabelen: {customer_name}, {appointment_date}, {appointment_time}, {service_name}, {business_name}
                                    </small>
                                </div>
                            </div>
                            
                            <button type="button" class="btn btn-primary" onclick="console.log('Notificatie-instellingen opslaan...')">
                                Wijzigingen Opslaan
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    // Toggle working hours visibility
    function toggleDay(day) {
        const toggle = document.getElementById(`${day}-toggle`);
        const status = document.getElementById(`${day}-status`);
        const hours = document.getElementById(`${day}-hours`);
        const closed = document.getElementById(`${day}-closed`);
        
        if (toggle.checked) {
            status.textContent = 'Geopend';
            hours.classList.remove('d-none');
            if (closed) closed.classList.add('d-none');
        } else {
            status.textContent = 'Gesloten';
            hours.classList.add('d-none');
            if (closed) closed.classList.remove('d-none');
        }
    }
    
    // Toggle reminders section
    function toggleReminders() {
        const remindersSection = document.getElementById('reminders-section');
        const sendReminders = document.getElementById('send-reminders');
        
        if (sendReminders.checked) {
            remindersSection.classList.remove('d-none');
        } else {
            remindersSection.classList.add('d-none');
        }
    }
    
    // Add service
    function addService() {
        const table = document.getElementById('services-table').getElementsByTagName('tbody')[0];
        const newRow = table.insertRow();
        
        newRow.innerHTML = `
            <td><input type="text" class="form-control" value="Nieuwe Dienst"></td>
            <td><input type="number" class="form-control" value="60"></td>
            <td><input type="number" class="form-control" value="50"></td>
            <td><button class="btn btn-link text-danger p-0" onclick="removeService(this)">Verwijderen</button></td>
        `;
    }
    
    // Remove service
    function removeService(button) {
        const row = button.closest('tr');
        console.log('Dienst verwijderen:', row.cells[0].querySelector('input').value);
        row.remove();
    }
</script>
@endsection
