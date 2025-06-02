@extends('layouts.app')

@section('content')
<div class="content-wrapper">
    <!-- Pagina kop met "Nieuwe klant" knop -->
    <div class="page-header mb-4 d-flex justify-content-between align-items-center">
        <h1 class="h3">Klantenbeheer</h1>
        <button class="btn btn-primary rounded-pill shadow-sm fw-medium px-4 py-2 d-flex align-items-center" data-bs-toggle="modal" data-bs-target="#newCustomerModal">
            <i class="bi bi-plus-circle fs-5 me-2"></i> Nieuwe klant
        </button>
    </div>

    <!-- Fout- en succesmeldingen -->
    @if ($errors->any())
        <div class="alert alert-danger">
            <ul class="mb-0">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    @if (session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif

    <!-- Zoek en lijst -->
    <div class="card mb-4">
        <div class="card-header">
            <form method="GET" action="{{ route('customers.index') }}" class="d-flex align-items-center">
                <div class="input-group">
                    <span class="input-group-text bg-light"><i class="bi bi-search"></i></span>
                    <input type="text" name="search" class="form-control" placeholder="Zoek klanten..." value="{{ request('search') }}">
                </div>
                <button type="submit" class="btn btn-outline-primary ms-2 px-3 fw-medium rounded-pill">
                    <i class="bi bi-search me-1"></i> Zoeken
                </button>
            </form>
        </div>
        <div class="card-body p-0">
            <table class="table table-hover mb-0">
                <thead>
                    <tr>
                        <th>Naam</th>
                        <th>Contact</th>
                        <th>Reserveringen</th>
                        <th>Laatste reservering</th>
                        <th>Acties</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($customers as $customer)
                        <tr>
                            <td>{{ $customer->name }}</td>
                            <td>
                                <div>{{ $customer->email }}</div>
                                <small class="text-muted">{{ $customer->phone }}</small>
                            </td>
                            <td>{{ $customer->reservations_count }}</td>
                            <td>{{ $customer->last_reservation ? \Carbon\Carbon::parse($customer->last_reservation)->format('d-m-Y') : '—' }}</td>
                            <td>
                                <div class="d-flex gap-2">
                                    <button type="button" class="btn btn-sm btn-soft-primary rounded-pill px-3 view-btn" data-id="{{ $customer->id }}">
                                        <i class="bi bi-eye me-1"></i> Bekijken
                                    </button>
                                    <button type="button" class="btn btn-sm btn-soft-success rounded-pill px-3 edit-btn" data-id="{{ $customer->id }}">
                                        <i class="bi bi-pencil-square me-1"></i> Bewerken
                                    </button>
                                    <form action="{{ route('customers.destroy', $customer->id) }}" method="POST" onsubmit="return confirm('Weet je zeker dat je deze klant wilt verwijderen?')" style="display:inline;">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-soft-danger rounded-pill px-3">
                                            <i class="bi bi-trash me-1"></i> Verwijderen
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="5" class="text-center text-muted">Geen klanten gevonden.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <!-- Grafieken onder tabel -->
    <div class="row mt-4">
        <div class="col-md-6">
            <div class="card shadow-sm h-100">
                <div class="card-header bg-light py-2">
                    <h5 class="card-title mb-0 fs-6">Reserveringsfrequentie per klant</h5>
                </div>
                <div class="card-body p-3">
                    <canvas id="reservationsChart" height="200"></canvas>
                </div>
            </div>
        </div>  
        <div class="col-md-6">
            <div class="card shadow-sm h-100">
                <div class="card-header bg-light py-2">
                    <h5 class="card-title mb-0 fs-6">Groepsgrootte per reservering</h5>
                </div>
                <div class="card-body p-3">
                    <canvas id="groupSizeChart" height="200"></canvas>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modals -->
<!-- Nieuwe klant Modal -->
<div class="modal fade" id="newCustomerModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header"><h5 class="modal-title">Nieuwe klant toevoegen</h5><button type="button" class="btn-close" data-bs-dismiss="modal"></button></div>
      <form action="{{ route('customers.store') }}" method="POST">
        @csrf
        <div class="modal-body">
          <div class="mb-3"><label class="form-label">Naam</label><input type="text" name="name" class="form-control" required></div>
          <div class="mb-3"><label class="form-label">E-mail</label><input type="email" name="email" class="form-control"></div>
          <div class="mb-3"><label class="form-label">Telefoon</label><input type="text" name="phone" class="form-control"></div>
        </div>
        <div class="modal-footer">
            <button type="button" class="btn btn-light rounded-pill px-4" data-bs-dismiss="modal">Annuleren</button>
            <button type="submit" class="btn btn-primary rounded-pill px-4 fw-medium">Opslaan</button>
        </div>
      </form>
    </div>
  </div>
</div>

<!-- Bekijken Modal -->
<div class="modal fade" id="viewCustomerModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header"><h5 class="modal-title">Klantgegevens</h5><button type="button" class="btn-close" data-bs-dismiss="modal"></button></div>
      <div class="modal-body" id="viewCustomerContent"></div>
      <div class="modal-footer">
        <button type="button" class="btn btn-light rounded-pill px-4" data-bs-dismiss="modal">Sluiten</button>
      </div>
    </div>
  </div>
</div>

<!-- Bewerk Modal -->
<div class="modal fade" id="editCustomerModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog">
    <form id="editCustomerForm" method="POST" class="modal-content">
      @csrf
      @method('PUT')
      <div class="modal-header"><h5 class="modal-title">Klant bewerken</h5><button type="button" class="btn-close" data-bs-dismiss="modal"></button></div>
      <div class="modal-body">
        <input type="hidden" id="editCustomerId" name="id">
        <div class="mb-3"><label class="form-label">Naam</label><input type="text" name="name" id="editName" class="form-control" required></div>
        <div class="mb-3"><label class="form-label">E-mail</label><input type="email" name="email" id="editEmail" class="form-control"></div>
        <div class="mb-3"><label class="form-label">Telefoon</label><input type="text" name="phone" id="editPhone" class="form-control"></div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-light rounded-pill px-4" data-bs-dismiss="modal">Annuleren</button>
        <button type="submit" class="btn btn-primary rounded-pill px-4 fw-medium">Bijwerken</button>
      </div>
    </form>
  </div>
</div>
@endsection

@section('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
  document.addEventListener('DOMContentLoaded', function() {
    const customers = @json($customers);
    const reservationsByMonth = @json($reservationsByMonth);

    // Reserveringsfrequentie grafiek
    const reservationsCtx = document.getElementById('reservationsChart').getContext('2d');
    const freqCats = [
      {min:0,max:0,label:'Geen'},
      {min:1,max:1,label:'1'},
      {min:2,max:3,label:'2-3'},
      {min:4,max:6,label:'4-6'},
      {min:7,max:100,label:'7+'}
    ];
    
    new Chart(reservationsCtx, {
      type: 'bar', 
      data: {
        labels: freqCats.map(c => c.label),
        datasets: [{
          label: 'Klanten',
          data: freqCats.map(c => customers.filter(x => x.reservations_count >= c.min && x.reservations_count <= c.max).length),
          backgroundColor: 'rgba(54, 162, 235, 0.6)',
          borderColor: 'rgba(54, 162, 235, 1)',
          borderWidth: 1
        }]
      },
      options: {
        responsive: true,
        maintainAspectRatio: false,
        plugins: {
          legend: { display: false },
          tooltip: {
            displayColors: false,
            callbacks: {
              title: function(tooltipItems) {
                const idx = tooltipItems[0].dataIndex;
                return freqCats[idx].label + ' reserveringen';
              },
              label: function(context) {
                return context.raw + ' klanten';
              }
            }
          }
        },
        scales: {
          y: {
            beginAtZero: true,
            ticks: { precision: 0 },
            grid: { display: false }
          },
          x: {
            grid: { display: false }
          }
        }
      }
    });

    // Groepsgrootte grafiek
    const groupSizeCtx = document.getElementById('groupSizeChart').getContext('2d');
    const groupSizes = ['1-2', '3-4', '5-6', '7-8', '9+'];
    
    // Voorbeelddata voor groepsgroottes (in een echte applicatie zou je deze uit de database halen)
    const groupSizeData = [15, 28, 22, 12, 5]; 
    
    new Chart(groupSizeCtx, {
      type: 'pie',
      data: {
        labels: groupSizes,
        datasets: [{
          data: groupSizeData,
          backgroundColor: [
            'rgba(255, 99, 132, 0.7)',
            'rgba(54, 162, 235, 0.7)',
            'rgba(255, 206, 86, 0.7)',
            'rgba(75, 192, 192, 0.7)',
            'rgba(153, 102, 255, 0.7)'
          ],
          borderColor: [
            'rgba(255, 99, 132, 1)',
            'rgba(54, 162, 235, 1)',
            'rgba(255, 206, 86, 1)',
            'rgba(75, 192, 192, 1)',
            'rgba(153, 102, 255, 1)'
          ],
          borderWidth: 1
        }]
      },
      options: {
        responsive: true,
        maintainAspectRatio: false,
        plugins: {
          legend: {
            position: 'right',
            labels: {
              padding: 15,
              usePointStyle: true,
              pointStyle: 'circle'
            }
          },
          tooltip: {
            callbacks: {
              label: function(context) {
                const percent = Math.round((context.raw / context.dataset.data.reduce((a, b) => a + b, 0)) * 100);
                return `${context.raw} reserveringen (${percent}%)`;
              }
            }
          }
        }
      }
    });

    // Bekijken
    document.querySelectorAll('.view-btn').forEach(btn=>btn.addEventListener('click',async()=>{
      const id=btn.dataset.id;const res=await fetch(`/customers/${id}`);const c=await res.json();
      document.getElementById('viewCustomerContent').innerHTML=
        `<p><strong>Naam:</strong> ${c.name}</p>
         <p><strong>E-mail:</strong> ${c.email||'—'}</p>
         <p><strong>Telefoon:</strong> ${c.phone||'—'}</p>
         <p><strong>Aantal reserveringen:</strong> ${c.reservations_count||'0'}</p>
         <p><strong>Laatste reservering:</strong> ${c.last_reservation ? new Date(c.last_reservation).toLocaleDateString('nl-NL') : '—'}</p>`;
      new bootstrap.Modal(document.getElementById('viewCustomerModal')).show();
    }));

    // Bewerken
    document.querySelectorAll('.edit-btn').forEach(btn=>btn.addEventListener('click',async()=>{
      const id=btn.dataset.id;const res=await fetch(`/customers/${id}`);const c=await res.json();
      document.getElementById('editCustomerId').value=c.id;
      document.getElementById('editName').value=c.name;
      document.getElementById('editEmail').value=c.email;
      document.getElementById('editPhone').value=c.phone;
      const form=document.getElementById('editCustomerForm');form.action=`/customers/${c.id}`;
      new bootstrap.Modal(document.getElementById('editCustomerModal')).show();
    }));
  });
</script>

<style>
/* Voeg deze stijlen toe aan de head sectie of in je CSS bestand */
.btn-soft-primary {
    color: #0d6efd;
    background-color: rgba(13, 110, 253, 0.1);
    border-color: transparent;
}
.btn-soft-primary:hover {
    color: white;
    background-color: #0d6efd;
}

.btn-soft-success {
    color: #198754;
    background-color: rgba(25, 135, 84, 0.1);
    border-color: transparent;
}
.btn-soft-success:hover {
    color: white;
    background-color: #198754;
}

.btn-soft-danger {
    color: #dc3545;
    background-color: rgba(220, 53, 69, 0.1);
    border-color: transparent;
}
.btn-soft-danger:hover {
    color: white;
    background-color: #dc3545;
}
</style>
@endsection
