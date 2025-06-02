<!DOCTYPE html>
<html lang="nl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reservering maken</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    <style>
        body {
            background-color: #f8f9fa;
            padding-top: 20px;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }
        .card {
            border-radius: 15px;
            overflow: hidden;
            box-shadow: 0 10px 20px rgba(0,0,0,0.1);
            border: none;
        }
        .card-header {
            background-color: #0d6efd;
            color: white;
            padding: 1.8rem;
            position: relative;
        }
        .card-header::after {
            content: '';
            position: absolute;
            bottom: -15px;
            left: 0;
            right: 0;
            height: 15px;
            background: linear-gradient(to bottom, rgba(13, 110, 253, 0.2), transparent);
        }
        .btn-primary {
            background-color: #0d6efd;
            border-color: #0d6efd;
            padding: 10px 25px;
            border-radius: 8px;
            font-weight: 600;
            transition: all 0.3s;
        }
        .btn-primary:hover {
            background-color: #0b5ed7;
            border-color: #0a58ca;
            transform: translateY(-2px);
            box-shadow: 0 4px 8px rgba(0,0,0,0.1);
        }
        .form-control, .form-select {
            border-radius: 8px;
            padding: 12px 15px;
            border: 1px solid #ced4da;
            transition: border-color 0.3s;
        }
        .form-control:focus, .form-select:focus {
            box-shadow: 0 0 0 0.25rem rgba(13, 110, 253, 0.25);
        }
        .input-group {
            margin-bottom: 1.5rem;
        }
        .input-group-text {
            background-color: #e9ecef;
            border-radius: 8px 0 0 8px;
            border: 1px solid #ced4da;
            border-right: none;
        }
        .section-title {
            font-size: 1.2rem;
            font-weight: 600;
            color: #0d6efd;
            margin-bottom: 1.5rem;
            padding-bottom: 0.5rem;
            border-bottom: 2px solid #e9ecef;
        }
        .success-animation {
            animation: fadeIn 1s;
        }
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(-20px); }
            to { opacity: 1; transform: translateY(0); }
        }
        .form-hint {
            font-size: 0.85rem;
            color: #6c757d;
            margin-top: 0.25rem;
        }
    </style>
</head>
<body>
    <div class="container my-5">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="mb-3">
                    <a href="javascript:history.back()" class="btn btn-outline-secondary">
                        <i class="bi bi-arrow-left me-1"></i> Terug naar vorige pagina
                    </a>
                </div>
                
                @if (session('success'))
                    <div class="alert alert-success mb-4 success-animation">
                        <i class="bi bi-check-circle-fill me-2"></i> {{ session('success') }}
                    </div>
                @endif
                
                <div class="card shadow">
                    <div class="card-header bg-primary text-white">
                        <h2 class="mb-0"><i class="bi bi-calendar-check me-2"></i> Maak een Reservering</h2>
                        <p class="mb-0 mt-2">Reserveer een tafel bij ons restaurant</p>
                    </div>
                    <div class="card-body p-4">
                        <p class="text-muted mb-4">Vul het onderstaande formulier in om een reservering aan te vragen. Wij nemen contact met je op voor bevestiging.</p>

                        <form method="POST" action="{{ route('reservation.process') }}" novalidate>
                            @csrf
                            
                            <div class="section-title">Persoonlijke gegevens</div>
                            
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="name" class="form-label">Naam</label>
                                    <div class="input-group">
                                        <span class="input-group-text"><i class="bi bi-person"></i></span>
                                        <input type="text" class="form-control @error('name') is-invalid @enderror" id="name" name="name" value="{{ old('name') }}" required>
                                        @error('name')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @else
                                            <div class="invalid-feedback">Vul je naam in.</div>
                                        @enderror
                                    </div>
                                </div>

                                <div class="col-md-6 mb-3">
                                    <label for="phone" class="form-label">Telefoon</label>
                                    <div class="input-group">
                                        <span class="input-group-text"><i class="bi bi-telephone"></i></span>
                                        <input type="text" class="form-control @error('phone') is-invalid @enderror" id="phone" name="phone" value="{{ old('phone') }}" required>
                                        @error('phone')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @else
                                            <div class="invalid-feedback">Vul je telefoonnummer in.</div>
                                        @enderror
                                    </div>
                                </div>
                            </div>

                            <div class="mb-4">
                                <label for="email" class="form-label">E-mail</label>
                                <div class="input-group">
                                    <span class="input-group-text"><i class="bi bi-envelope"></i></span>
                                    <input type="email" class="form-control @error('email') is-invalid @enderror" id="email" name="email" value="{{ old('email') }}" required>
                                    @error('email')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @else
                                        <div class="invalid-feedback">Vul een geldig e-mailadres in.</div>
                                    @enderror
                                </div>
                                <div class="form-hint">We sturen je een bevestiging per e-mail</div>
                            </div>

                            <div class="section-title mt-4">Reserveringsdetails</div>
                            
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="date" class="form-label">Datum</label>
                                    <div class="input-group">
                                        <span class="input-group-text"><i class="bi bi-calendar"></i></span>
                                        <input type="date" class="form-control @error('date') is-invalid @enderror" id="date" name="date" value="{{ old('date', date('Y-m-d')) }}" required>
                                        @error('date')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @else
                                            <div class="invalid-feedback">Kies een datum.</div>
                                        @enderror
                                    </div>
                                </div>

                                <div class="col-md-6 mb-3">
                                    <label for="time" class="form-label">Tijd</label>
                                    <div class="input-group">
                                        <span class="input-group-text"><i class="bi bi-clock"></i></span>
                                        <select class="form-select @error('time') is-invalid @enderror" id="time" name="time" required>
                                            <option value="" selected disabled>Selecteer tijd</option>
                                            <option value="17:00" {{ old('time') == '17:00' ? 'selected' : '' }}>17:00</option>
                                            <option value="17:30" {{ old('time') == '17:30' ? 'selected' : '' }}>17:30</option>
                                            <option value="18:00" {{ old('time') == '18:00' ? 'selected' : '' }}>18:00</option>
                                            <option value="18:30" {{ old('time') == '18:30' ? 'selected' : '' }}>18:30</option>
                                            <option value="19:00" {{ old('time') == '19:00' ? 'selected' : '' }}>19:00</option>
                                            <option value="19:30" {{ old('time') == '19:30' ? 'selected' : '' }}>19:30</option>
                                            <option value="20:00" {{ old('time') == '20:00' ? 'selected' : '' }}>20:00</option>
                                            <option value="20:30" {{ old('time') == '20:30' ? 'selected' : '' }}>20:30</option>
                                        </select>
                                        @error('time')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @else
                                            <div class="invalid-feedback">Kies een tijd.</div>
                                        @enderror
                                    </div>
                                </div>
                            </div>

                            <div class="mb-4">
                                <label for="people" class="form-label">Aantal personen</label>
                                <div class="input-group">
                                    <span class="input-group-text"><i class="bi bi-people"></i></span>
                                    <input type="number" class="form-control @error('people') is-invalid @enderror" id="people" name="people" value="{{ old('people', 2) }}" min="1" max="10" required>
                                    @error('people')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @else
                                        <div class="invalid-feedback">Vul het aantal personen in (maximaal 10).</div>
                                    @enderror
                                </div>
                                <div class="form-hint">Voor groepen groter dan 10 personen, neem telefonisch contact met ons op</div>
                            </div>

                            <div class="mb-4">
                                <label for="special_requests" class="form-label">Speciale verzoeken (optioneel)</label>
                                <div class="input-group">
                                    <span class="input-group-text"><i class="bi bi-chat-left-text"></i></span>
                                    <textarea class="form-control @error('special_requests') is-invalid @enderror" id="special_requests" name="special_requests" rows="3" placeholder="Allergie, dieetwensen of andere verzoeken...">{{ old('special_requests') }}</textarea>
                                    @error('special_requests')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="d-flex justify-content-between align-items-center mt-4 pt-3 border-top">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" id="terms" name="terms" required>
                                    <label class="form-check-label" for="terms">
                                        Ik ga akkoord met de voorwaarden
                                    </label>
                                    <div class="invalid-feedback">Je moet akkoord gaan met de voorwaarden.</div>
                                </div>
                                <div>
                                    <button type="reset" class="btn btn-outline-secondary me-2">
                                        <i class="bi bi-x-circle me-1"></i> Formulier leegmaken
                                    </button>
                                    <button type="submit" class="btn btn-primary">
                                        <i class="bi bi-check-circle me-1"></i> Reservering aanvragen
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
                
                <div class="text-center mt-4 text-muted">
                    <small>Heb je hulp nodig? Bel ons op 020-1234567 of mail naar info@restaurant.nl</small>
                </div>
            </div>
        </div>
    </div>

    <!-- Bootstrap JS Bundle met Popper -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    
    <!-- Formulier validatie script -->
    <script>
    (() => {
        'use strict'

        // Datumvalidatie - Stel minimale datum in op vandaag
        const dateInput = document.getElementById('date');
        const today = new Date().toISOString().split('T')[0];
        dateInput.setAttribute('min', today);

        // Fetch alle formulieren
        const forms = document.querySelectorAll('form')

        // Loop over de formulieren en voorkom submission bij ongeldige invoer
        Array.from(forms).forEach(form => {
            form.addEventListener('submit', event => {
                if (!form.checkValidity()) {
                    event.preventDefault()
                    event.stopPropagation()
                }

                form.classList.add('was-validated')
            }, false)
        })
    })()
    </script>
</body>
</html>
