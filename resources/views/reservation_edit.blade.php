@extends('layouts.app')

@section('content')
<div class="container my-5">
    <div class="row justify-content-center">
        <div class="col-md-8">
            @if (session('success'))
                <div class="alert alert-success mb-4">
                    {{ session('success') }}
                </div>
            @endif
            
            <div class="card shadow">
                <div class="card-header bg-primary text-white">
                    <h2 class="mb-0">Reservering Bewerken</h2>
                </div>
                <div class="card-body">
                    <p class="text-muted mb-4">Bewerk de gegevens van deze reservering.</p>

                    <form method="POST" action="{{ route('reservation.update', $reservation) }}" novalidate>
                        @csrf
                        @method('PUT')
                        
                        <div class="mb-3">
                            <label for="name" class="form-label">Naam</label>
                            <input type="text" class="form-control @error('name') is-invalid @enderror" id="name" name="name" value="{{ old('name', $reservation->name) }}" required>
                            @error('name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="email" class="form-label">E-mail</label>
                            <input type="email" class="form-control @error('email') is-invalid @enderror" id="email" name="email" value="{{ old('email', $reservation->email) }}" required>
                            @error('email')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="phone" class="form-label">Telefoon</label>
                            <input type="text" class="form-control @error('phone') is-invalid @enderror" id="phone" name="phone" value="{{ old('phone', $reservation->phone) }}" required>
                            @error('phone')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="people" class="form-label">Aantal personen</label>
                            <input type="number" class="form-control @error('people') is-invalid @enderror" id="people" name="people" value="{{ old('people', $reservation->people) }}" min="1" required>
                            @error('people')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="date" class="form-label">Datum</label>
                            <input type="date" class="form-control @error('date') is-invalid @enderror" id="date" name="date" 
                                   value="{{ old('date', $reservation->date) }}" 
                                   min="{{ date('Y-m-d') }}" 
                                   required>
                            @error('date')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="time" class="form-label">Tijd</label>
                            <select class="form-select @error('time') is-invalid @enderror" id="time" name="time" required>
                                <option value="" selected disabled>Selecteer tijd</option>
                                <option value="17:00" {{ old('time', $reservation->time) == '17:00' ? 'selected' : '' }}>17:00</option>
                                <option value="17:30" {{ old('time', $reservation->time) == '17:30' ? 'selected' : '' }}>17:30</option>
                                <option value="18:00" {{ old('time', $reservation->time) == '18:00' ? 'selected' : '' }}>18:00</option>
                                <option value="18:30" {{ old('time', $reservation->time) == '18:30' ? 'selected' : '' }}>18:30</option>
                                <option value="19:00" {{ old('time', $reservation->time) == '19:00' ? 'selected' : '' }}>19:00</option>
                                <option value="19:30" {{ old('time', $reservation->time) == '19:30' ? 'selected' : '' }}>19:30</option>
                                <option value="20:00" {{ old('time', $reservation->time) == '20:00' ? 'selected' : '' }}>20:00</option>
                                <option value="20:30" {{ old('time', $reservation->time) == '20:30' ? 'selected' : '' }}>20:30</option>
                            </select>
                            @error('time')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="special_requests" class="form-label">Speciale verzoeken (optioneel)</label>
                            <textarea class="form-control @error('special_requests') is-invalid @enderror" id="special_requests" name="special_requests" rows="3">{{ old('special_requests', $reservation->special_requests) }}</textarea>
                            @error('special_requests')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="status" class="form-label">Status</label>
                            <select class="form-select @error('status') is-invalid @enderror" id="status" name="status" required>
                                <option value="in_behandeling" {{ old('status', $reservation->status) == 'in_behandeling' ? 'selected' : '' }}>In behandeling</option>
                                <option value="bevestigd" {{ old('status', $reservation->status) == 'bevestigd' ? 'selected' : '' }}>Bevestigd</option>
                                <option value="geannuleerd" {{ old('status', $reservation->status) == 'geannuleerd' ? 'selected' : '' }}>Geannuleerd</option>
                            </select>
                            @error('status')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="d-flex justify-content-between">
                            <a href="{{ route('calendar') }}" class="btn btn-outline-secondary">Terug naar kalender</a>
                            <button type="submit" class="btn btn-primary">Reservering bijwerken</button>
                        </div>
                    </form>
                </div>
            </div>
            
            <div class="mt-4 text-center">
                <p class="text-muted">Referentienummer: {{ $reservation->reference }}</p>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Als de huidige reserveringsdatum in het verleden ligt, deze bijwerken naar vandaag
        const dateInput = document.getElementById('date');
        const currentDate = new Date().toISOString().split('T')[0]; // Format: YYYY-MM-DD
        
        if (dateInput.value < currentDate) {
            dateInput.value = currentDate;
        }
    });
</script>
@endsection
