@extends('layouts.app')

@section('content')
<div class="container">
    <!-- Paginaheader - responsive met stackende elementen op mobiel -->
    <div class="row mb-4">
        <div class="col-md-8 mb-3 mb-md-0">
            <h1 class="h3">Gebruikers</h1>
            <p class="text-muted mb-0">Beheer systeemgebruikers en hun rollen</p>
        </div>
        <div class="col-md-4 d-flex justify-content-start justify-content-md-end align-items-center">
            <a href="{{ route('users.create') }}" class="btn btn-primary">
                <i class="bi bi-plus-circle me-1"></i>Gebruiker Toevoegen
            </a>
        </div>
    </div>

    <!-- Gebruikerskaart met verbeterde responsive layout -->
    <div class="card shadow-sm">
        <div class="card-header bg-white py-3">
            <div class="d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center">
                <h5 class="mb-3 mb-md-0">Gebruikerslijst</h5>
                <form action="{{ route('users.index') }}" method="GET" class="w-100 w-md-auto">
                    <div class="input-group">
                        <input type="text" class="form-control" name="search" placeholder="Zoek gebruikers..." value="{{ $search ?? '' }}">
                        <button class="btn btn-outline-secondary d-flex align-items-center" type="submit">
                            <i class="bi bi-search me-1"></i><span class="d-none d-sm-inline">Zoeken</span>
                        </button>
                    </div>
                </form>
            </div>
        </div>
        
        <div class="card-body">
            @if (session('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Sluiten"></button>
                </div>
            @endif

            <div class="table-responsive">
                <table class="table table-hover align-middle">
                    <thead>
                        <tr>
                            <th scope="col">Naam</th>
                            <th scope="col">E-mail</th>
                            <th scope="col">Rol</th>
                            <th scope="col">Acties</th>
                        </tr>
                    </thead>
                    <tbody>
                        @php
                            // Fake data voor demonstratie
                            if (!isset($users) || $users->isEmpty()) {
                                $fakeUsers = [
                                    (object)[
                                        'id' => 1,
                                        'name' => 'Jan de Vries',
                                        'email' => 'jan@voorbeeld.nl',
                                        'roles' => collect([(object)['name' => 'Beheerder']])
                                    ],
                                    (object)[
                                        'id' => 2,
                                        'name' => 'Marieke Jansen',
                                        'email' => 'marieke@voorbeeld.nl',
                                        'roles' => collect([(object)['name' => 'Manager']])
                                    ],
                                    (object)[
                                        'id' => 3,
                                        'name' => 'Robert Bakker',
                                        'email' => 'robert@voorbeeld.nl',
                                        'roles' => collect([(object)['name' => 'Medewerker']])
                                    ],
                                    (object)[
                                        'id' => 4,
                                        'name' => 'Emma Visser',
                                        'email' => 'emma@voorbeeld.nl',
                                        'roles' => collect([(object)['name' => 'Manager']])
                                    ],
                                    (object)[
                                        'id' => 5,
                                        'name' => 'Michael de Boer',
                                        'email' => 'michael@voorbeeld.nl',
                                        'roles' => collect([])
                                    ],
                                ];
                                
                                // Als er een zoekterm is, filteren we de nep gebruikers
                                if (isset($search) && !empty($search)) {
                                    $fakeUsers = array_filter($fakeUsers, function($user) use ($search) {
                                        return stripos($user->name, $search) !== false || 
                                               stripos($user->email, $search) !== false;
                                    });
                                }
                                
                                $users = collect($fakeUsers);
                            }
                        @endphp

                        @forelse ($users as $user)
                            <tr>
                                <td>
                                    <!-- Gebruikersnaam op mobiel en desktop anders tonen -->
                                    <div class="d-flex align-items-center">
                                        <div class="avatar-placeholder rounded-circle bg-light text-primary d-flex align-items-center justify-content-center me-2">
                                            <span>{{ strtoupper(substr($user->name, 0, 1)) }}</span>
                                        </div>
                                        <div>
                                            <div>{{ $user->name }}</div>
                                            <!-- E-mail alleen tonen op mobiel, op grotere schermen staat het in een aparte kolom -->
                                            <small class="text-muted d-inline d-md-none">{{ $user->email }}</small>
                                        </div>
                                    </div>
                                </td>
                                <td class="d-none d-md-table-cell">{{ $user->email }}</td>
                                <td>
                                    @if ($user->roles->isNotEmpty())
                                        @php
                                            $role = $user->roles->first()->name;
                                            $badgeClass = match($role) {
                                                'Beheerder' => 'bg-primary',
                                                'Manager' => 'bg-success',
                                                'Medewerker' => 'bg-light text-dark border',
                                                default => 'bg-secondary'
                                            };
                                        @endphp
                                        <span class="badge {{ $badgeClass }}">{{ $role }}</span>
                                    @else
                                        <span class="badge bg-secondary">Geen rol</span>
                                    @endif
                                </td>
                                <td>
                                    <!-- Actieknoppen geoptimaliseerd voor mobiel en desktop -->
                                    <div class="d-flex gap-2">
                                        <a href="{{ route('users.edit', $user->id) }}" class="btn btn-sm btn-outline-secondary d-flex align-items-center">
                                            <i class="bi bi-pencil-square"></i>
                                            <span class="d-none d-md-inline ms-1">Bewerken</span>
                                        </a>
                                        <form action="{{ route('users.destroy', $user->id) }}" method="POST">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-outline-danger d-flex align-items-center" 
                                                    onclick="return confirm('Weet je zeker dat je deze gebruiker wilt verwijderen?')">
                                                <i class="bi bi-trash"></i>
                                                <span class="d-none d-md-inline ms-1">Verwijderen</span>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="text-center py-4">
                                    <div class="d-flex flex-column align-items-center">
                                        <i class="bi bi-people text-muted mb-2" style="font-size: 2rem;"></i>
                                        <p class="mb-0">Geen gebruikers gevonden</p>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
    /* Verbeterde responsive styling */
    .avatar-placeholder {
        width: 36px;
        height: 36px;
        font-weight: bold;
    }
    
    /* Zorg dat de tabel netjes blijft op alle schermgroottes */
    .table-responsive {
        border: 0;
        margin-bottom: 0;
    }
    
    /* Tabel hover effecten verbeteren */
    .table-hover tbody tr:hover {
        background-color: rgba(0,0,0,.02);
    }
    
    /* Zorgen dat formulieren goed werken op alle schermgroottes */
    @media (max-width: 767.98px) {
        .input-group {
            width: 100%;
        }
        
        /* Kleinere padding in tabelcellen op mobiel */
        .table td, .table th {
            padding: 0.75rem 0.5rem;
        }
        
        /* Acties kolom wat breder op mobiel */
        .table td:last-child {
            min-width: 110px;
        }
    }
    
    /* Voeg Bootstrap Icons toe als deze nog niet in je project zitten */
    @import url("https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css");
</style>
@endpush