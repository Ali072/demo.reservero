@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <!-- Header Sectie -->
    <div class="mb-4 d-flex justify-content-between align-items-center">
        <div>
            <h1 class="h2">Dashboard</h1>
            <p class="text-muted">Overzicht van alle reserveringen en activiteiten</p>
        </div>
        <div class="text-end">
            <button class="btn btn-primary">
                <i class="fas fa-plus-circle me-2"></i>Nieuwe Reservering
            </button>
        </div>
    </div>

    <!-- Statistiek Kaarten -->
    <div class="row g-4 mb-4">
        <div class="col-12 col-sm-6 col-lg-3">
            <div class="card h-100 shadow-sm">
                <div class="card-body">
                    <h6 class="card-subtitle mb-2 text-muted">Totaal Reserveringen</h6>
                    <div class="d-flex align-items-center">
                        <h2 class="card-title mb-0">{{ $stats['total'] }}</h2>
                        <span class="badge bg-success ms-2">{{ $stats['today'] }} Vandaag</span>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-12 col-sm-6 col-lg-3">
            <div class="card h-100 shadow-sm">
                <div class="card-body">
                    <h6 class="card-subtitle mb-2 text-muted">In Behandeling</h6>
                    <div class="d-flex align-items-center">
                        <h2 class="card-title mb-0">{{ $stats['pending'] }}</h2>
                        <span class="badge bg-warning ms-2">Nieuw</span>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-12 col-sm-6 col-lg-3">
            <div class="card h-100 shadow-sm">
                <div class="card-body">
                    <h6 class="card-subtitle mb-2 text-muted">Bevestigd</h6>
                    <div class="d-flex align-items-center">
                        <h2 class="card-title mb-0">{{ $stats['confirmed'] }}</h2>
                        <span class="badge bg-success ms-2">Actief</span>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-12 col-sm-6 col-lg-3">
            <div class="card h-100 shadow-sm">
                <div class="card-body">
                    <h6 class="card-subtitle mb-2 text-muted">Geannuleerd</h6>
                    <div class="d-flex align-items-center">
                        <h2 class="card-title mb-0">{{ $stats['cancelled'] }}</h2>
                        <span class="badge bg-danger ms-2">Deze week</span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Filters & Zoeken -->
    <div class="card shadow-sm mb-4">
        <div class="card-header bg-white">
            <h5 class="card-title mb-0">Filter Reserveringen</h5>
        </div>
        <div class="card-body">
            <div class="row g-3">
                <div class="col-md-3">
                    <label for="nameFilter" class="form-label">Zoek op naam</label>
                    <input type="text" class="form-control" id="nameFilter" placeholder="Naam klant...">
                </div>
                <div class="col-md-3">
                    <label for="statusFilter" class="form-label">Status</label>
                    <select class="form-select" id="statusFilter">
                        <option value="">Alle statussen</option>
                        <option value="in_behandeling">In behandeling</option>
                        <option value="bevestigd">Bevestigd</option>
                        <option value="geannuleerd">Geannuleerd</option>
                    </select>
                </div>
                <div class="col-md-2">
                    <label for="dateFrom" class="form-label">Vanaf datum</label>
                    <input type="date" class="form-control" id="dateFrom">
                </div>
                <div class="col-md-2">
                    <label for="dateTo" class="form-label">Tot datum</label>
                    <input type="date" class="form-control" id="dateTo">
                </div>
                <div class="col-md-2 d-flex align-items-end">
                    <button class="btn btn-outline-secondary w-100" id="resetFilters">
                        <i class="fas fa-undo me-2"></i>Reset filters
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Weergave tabs -->
    <ul class="nav nav-tabs mb-4" id="viewTabs" role="tablist">
        <li class="nav-item" role="presentation">
            <button class="nav-link active" id="card-tab" data-bs-toggle="tab" data-bs-target="#card-view" type="button" role="tab" aria-controls="card-view" aria-selected="true">
                <i class="fas fa-th me-2"></i>Kaartweergave
            </button>
        </li>
        <li class="nav-item" role="presentation">
            <button class="nav-link" id="table-tab" data-bs-toggle="tab" data-bs-target="#table-view" type="button" role="tab" aria-controls="table-view" aria-selected="false">
                <i class="fas fa-list me-2"></i>Tabelweergave
            </button>
        </li>
    </ul>

    <!-- Tab inhoud -->
    <div class="tab-content" id="viewTabsContent">
        <!-- Kaartweergave -->
        <div class="tab-pane fade show active" id="card-view" role="tabpanel" aria-labelledby="card-tab">
            <div class="row g-4" id="reservationCards">
                @forelse($reservations as $reservation)
                <div class="col-md-6 col-lg-4 reservation-card" 
                     data-name="{{ strtolower($reservation->name) }}"
                     data-date="{{ $reservation->date }}"
                     data-status="{{ $reservation->status }}">
                    <div class="card shadow-sm h-100 reservation-item">
                        <div class="card-header 
                            @if($reservation->status === 'in_behandeling') bg-warning-subtle
                            @elseif($reservation->status === 'bevestigd') bg-success-subtle
                            @else bg-danger-subtle @endif
                            d-flex justify-content-between">
                            <h5 class="card-title mb-0">{{ $reservation->name }}</h5>
                            <span class="badge 
                                @if($reservation->status === 'in_behandeling') bg-warning
                                @elseif($reservation->status === 'bevestigd') bg-success
                                @else bg-danger @endif">
                                @if($reservation->status === 'in_behandeling') In Behandeling
                                @elseif($reservation->status === 'bevestigd') Bevestigd
                                @else Geannuleerd @endif
                            </span>
                        </div>
                        <div class="card-body">
                            <div class="d-flex justify-content-between mb-3">
                                <div>
                                    <p class="mb-0 text-muted"><i class="fas fa-calendar me-2"></i>{{ \Carbon\Carbon::parse($reservation->date)->format('d-m-Y') }}</p>
                                    <p class="mb-0 text-muted"><i class="fas fa-clock me-2"></i>{{ $reservation->time }}</p>
                                </div>
                                <div>
                                    <p class="mb-0 text-muted"><i class="fas fa-users me-2"></i>{{ $reservation->people }} personen</p>
                                    <p class="mb-0 text-muted"><i class="fas fa-hashtag me-2"></i>{{ $reservation->reference }}</p>
                                </div>
                            </div>
                            
                            <hr>
                            
                            <div class="mb-3">
                                <h6 class="card-subtitle">Contactgegevens</h6>
                                <p class="mb-0"><i class="fas fa-envelope me-2"></i>{{ $reservation->email }}</p>
                                <p class="mb-0"><i class="fas fa-phone me-2"></i>{{ $reservation->phone }}</p>
                            </div>
                            
                            @if($reservation->special_requests)
                            <div class="mb-3">
                                <h6 class="card-subtitle">Speciale verzoeken</h6>
                                <p class="small">{{ $reservation->special_requests }}</p>
                            </div>
                            @endif
                            
                            <div class="d-flex justify-content-between mt-3">
                                <small class="text-muted">Aangevraagd op {{ $reservation->created_at->format('d-m-Y H:i') }}</small>
                                <div class="dropdown">
                                    <button class="btn btn-sm btn-outline-primary dropdown-toggle" type="button" data-bs-toggle="dropdown">
                                        Status wijzigen
                                    </button>
                                    <ul class="dropdown-menu dropdown-menu-end">
                                        <li>
                                            <form action="{{ route('reservation.update.status', $reservation) }}" method="POST">
                                                @csrf
                                                @method('PUT')
                                                <input type="hidden" name="status" value="bevestigd">
                                                <button class="dropdown-item" type="submit">
                                                    <i class="fas fa-check-circle text-success me-2"></i>Bevestigen
                                                </button>
                                            </form>
                                        </li>
                                        <li>
                                            <form action="{{ route('reservation.update.status', $reservation) }}" method="POST">
                                                @csrf
                                                @method('PUT')
                                                <input type="hidden" name="status" value="in_behandeling">
                                                <button class="dropdown-item" type="submit">
                                                    <i class="fas fa-clock text-warning me-2"></i>In behandeling
                                                </button>
                                            </form>
                                        </li>
                                        <li><hr class="dropdown-divider"></li>
                                        <li>
                                            <form action="{{ route('reservation.update.status', $reservation) }}" method="POST">
                                                @csrf
                                                @method('PUT')
                                                <input type="hidden" name="status" value="geannuleerd">
                                                <button class="dropdown-item" type="submit">
                                                    <i class="fas fa-times-circle text-danger me-2"></i>Annuleren
                                                </button>
                                            </form>
                                        </li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                @empty
                <div class="col-12">
                    <div class="alert alert-info">
                        Geen reserveringen gevonden. Nieuwe reserveringen zullen hier verschijnen.
                    </div>
                </div>
                @endforelse
            </div>
        </div>
        
        <!-- Tabelweergave -->
        <div class="tab-pane fade" id="table-view" role="tabpanel" aria-labelledby="table-tab">
            <div class="card shadow-sm">
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-hover" id="reservationsTable">
                            <thead>
                                <tr>
                                    <th>Referentie</th>
                                    <th>Klant</th>
                                    <th>Contactgegevens</th>
                                    <th>Personen</th>
                                    <th>Datum & Tijd</th>
                                    <th>Status</th>
                                    <th>Acties</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($reservations as $reservation)
                                <tr class="reservation-row"
                                    data-name="{{ strtolower($reservation->name) }}"
                                    data-date="{{ $reservation->date }}"
                                    data-status="{{ $reservation->status }}">
                                    <td>{{ $reservation->reference }}</td>
                                    <td>{{ $reservation->name }}</td>
                                    <td>
                                        <div>{{ $reservation->email }}</div>
                                        <div>{{ $reservation->phone }}</div>
                                    </td>
                                    <td>{{ $reservation->people }}</td>
                                    <td>
                                        <div>{{ \Carbon\Carbon::parse($reservation->date)->format('d-m-Y') }}</div>
                                        <div>{{ $reservation->time }}</div>
                                    </td>
                                    <td>
                                        @if($reservation->status === 'in_behandeling')
                                            <span class="badge bg-warning">In Behandeling</span>
                                        @elseif($reservation->status === 'bevestigd')
                                            <span class="badge bg-success">Bevestigd</span>
                                        @else
                                            <span class="badge bg-danger">Geannuleerd</span>
                                        @endif
                                    </td>
                                    <td>
                                        <div class="dropdown">
                                            <button class="btn btn-sm btn-outline-primary dropdown-toggle" type="button" data-bs-toggle="dropdown">
                                                <i class="fas fa-edit"></i>
                                            </button>
                                            <ul class="dropdown-menu dropdown-menu-end">
                                                <li>
                                                    <form action="{{ route('reservation.update.status', $reservation) }}" method="POST">
                                                        @csrf
                                                        @method('PUT')
                                                        <input type="hidden" name="status" value="bevestigd">
                                                        <button class="dropdown-item" type="submit">Bevestigen</button>
                                                    </form>
                                                </li>
                                                <li>
                                                    <form action="{{ route('reservation.update.status', $reservation) }}" method="POST">
                                                        @csrf
                                                        @method('PUT')
                                                        <input type="hidden" name="status" value="in_behandeling">
                                                        <button class="dropdown-item" type="submit">In behandeling zetten</button>
                                                    </form>
                                                </li>
                                                <li><hr class="dropdown-divider"></li>
                                                <li>
                                                    <form action="{{ route('reservation.update.status', $reservation) }}" method="POST">
                                                        @csrf
                                                        @method('PUT')
                                                        <input type="hidden" name="status" value="geannuleerd">
                                                        <button class="dropdown-item" type="submit">Annuleren</button>
                                                    </form>
                                                </li>
                                            </ul>
                                        </div>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="7" class="text-center">Geen reserveringen gevonden</td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    /* Subtiele hover effecten */
    .card {
        transition: transform 0.2s ease-in-out, box-shadow 0.2s ease-in-out;
    }
    .card:hover {
        transform: translateY(-3px);
        box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.1) !important;
    }

    /* Tabel rij hover effect */
    .table tbody tr:hover {
        background-color: rgba(0,0,0,.03);
    }

    /* Statuskleur stijlen */
    .bg-warning-subtle {
        background-color: rgba(255, 193, 7, 0.15);
    }
    .bg-success-subtle {
        background-color: rgba(25, 135, 84, 0.15);
    }
    .bg-danger-subtle {
        background-color: rgba(220, 53, 69, 0.15);
    }

    /* Responsive aanpassingen */
    @media (max-width: 768px) {
        .main-content {
            margin-left: 0;
        }
    }
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const nameFilter = document.getElementById('nameFilter');
    const statusFilter = document.getElementById('statusFilter');
    const dateFrom = document.getElementById('dateFrom');
    const dateTo = document.getElementById('dateTo');
    const resetButton = document.getElementById('resetFilters');
    const reservationCards = document.querySelectorAll('.reservation-card');
    const reservationRows = document.querySelectorAll('.reservation-row');

    // Filter functie
    function filterReservations() {
        const nameValue = nameFilter.value.toLowerCase();
        const statusValue = statusFilter.value;
        const fromDate = dateFrom.value ? new Date(dateFrom.value) : null;
        const toDate = dateTo.value ? new Date(dateTo.value) : null;

        // Filter kaartweergave
        reservationCards.forEach(card => {
            const name = card.dataset.name;
            const status = card.dataset.status;
            const date = new Date(card.dataset.date);
            
            let showCard = true;

            // Filter op naam
            if (nameValue && !name.includes(nameValue)) {
                showCard = false;
            }

            // Filter op status
            if (statusValue && status !== statusValue) {
                showCard = false;
            }

            // Filter op datum range
            if (fromDate && date < fromDate) {
                showCard = false;
            }
            if (toDate && date > toDate) {
                showCard = false;
            }

            card.style.display = showCard ? '' : 'none';
        });

        // Filter tabelweergave
        reservationRows.forEach(row => {
            const name = row.dataset.name;
            const status = row.dataset.status;
            const date = new Date(row.dataset.date);
            
            let showRow = true;

            // Filter op naam
            if (nameValue && !name.includes(nameValue)) {
                showRow = false;
            }

            // Filter op status
            if (statusValue && status !== statusValue) {
                showRow = false;
            }

            // Filter op datum range
            if (fromDate && date < fromDate) {
                showRow = false;
            }
            if (toDate && date > toDate) {
                showRow = false;
            }

            row.style.display = showRow ? '' : 'none';
        });
    }

    // Event listeners
    nameFilter.addEventListener('input', filterReservations);
    statusFilter.addEventListener('change', filterReservations);
    dateFrom.addEventListener('change', filterReservations);
    dateTo.addEventListener('change', filterReservations);

    // Reset filters
    resetButton.addEventListener('click', function() {
        nameFilter.value = '';
        statusFilter.value = '';
        dateFrom.value = '';
        dateTo.value = '';
        filterReservations();
    });

    // Stel initiële datumwaarden in
    const today = new Date();
    const thirtyDaysAgo = new Date(today);
    thirtyDaysAgo.setDate(today.getDate() - 30);

    dateFrom.value = thirtyDaysAgo.toISOString().split('T')[0];
    dateTo.value = today.toISOString().split('T')[0];
    
    // Pas filters toe bij laden
    filterReservations();
});
</script>
@endsection