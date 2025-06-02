<!DOCTYPE html>
<html lang="nl">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>ResyFlowHub – Reserveringsagenda</title>

  <!-- Bootstrap 5 CSS -->
  <link
    href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css"
    rel="stylesheet"/>

  <!-- Bootstrap Icons -->
  <link
    href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css"
    rel="stylesheet"/>

  <style>
    body {
      display: flex;
      min-height: 100vh;
    }
    /* SIDEBAR */
    .sidebar {
      width: 250px;
      background: #0d6efd;
      color: #fff;
      flex-shrink: 0;
      padding: 1rem;
    }
    .sidebar .navbar-brand {
      color: #fff;
      font-weight: bold;
    }
    .sidebar .nav-link {
      color: #fff;
    }
    .sidebar .nav-link.active {
      background: rgba(255,255,255,0.1);
    }

    /* Kalender-cellen */
    .calendar-day {
      width: 2rem;
      height: 2rem;
      line-height: 2rem;
      margin: 0;
      cursor: pointer;
      border-radius: .25rem;
      text-align: center;
      font-size: .875rem;
      position: relative;
    }
    .other-month { color: #adb5bd; }
    .status-in_behandeling { 
      background: rgba(255,193,7,0.25); 
    }
    .status-bevestigd {
      background: rgba(25,135,84,0.8);
      color: #fff;
    }
    .status-geannuleerd {
      background: rgba(220,53,69,0.8);
      color: #fff;
    }
    .selected-day {
      outline: 2px solid #0d6efd;
      outline-offset: -2px;
    }
    
    /* Indicatoren voor meerdere reserveringen */
    .reservation-count {
      position: absolute;
      top: -5px;
      right: -5px;
      background: #0d6efd;
      color: white;
      border-radius: 50%;
      width: 16px;
      height: 16px;
      font-size: 10px;
      line-height: 16px;
      text-align: center;
    }
    
    /* Tijdslot stijlen */
    .time-slot {
      padding: 10px;
      border-radius: 5px;
      margin-bottom: 10px;
      border-left: 4px solid #ddd;
    }
    .time-slot.in_behandeling {
      border-left-color: #ffc107;
      background-color: rgba(255,193,7,0.1);
    }
    .time-slot.bevestigd {
      border-left-color: #198754;
      background-color: rgba(25,135,84,0.1);
    }
    .time-slot.geannuleerd {
      border-left-color: #dc3545;
      background-color: rgba(220,53,69,0.1);
    }
    
    /* Week view styling */
    .week-view {
      display: grid;
      grid-template-columns: repeat(auto-fill, minmax(250px, 1fr));
      gap: 15px;
    }
  </style>
</head>
<body>
  @include('layouts.app')

  <!-- MAIN CONTENT -->
  <div class="flex-grow-1">
    <div class="container-fluid py-4">
      <!-- Header -->
      <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
          <h1 class="h3 mb-1">Reserveringsagenda</h1>
          <p class="text-muted mb-0">Bekijk en beheer reserveringen per dag of week</p>
        </div>
        <div class="d-flex gap-2">
          <a href="{{ route('reservation.form') }}" class="btn btn-primary">
            <i class="bi bi-calendar-plus"></i> Nieuwe reservering
          </a>
          <div class="btn-group" role="group">
            <button type="button" class="btn btn-outline-secondary active" id="viewDay">Dag</button>
            <button type="button" class="btn btn-outline-secondary" id="viewWeek">Week</button>
          </div>
        </div>
      </div>

      <div class="row">
        <!-- Kalender Sidebar -->
        <div class="col-lg-3 mb-4">
          <div class="card">
            <div class="card-body p-2">
              <!-- Maand-navigatie -->
              <div class="d-flex justify-content-between align-items-center mb-2">
                <button class="btn btn-sm btn-light" id="prevMonth">&laquo;</button>
                <h6 class="mb-0" id="monthYear">—</h6>
                <button class="btn btn-sm btn-light" id="nextMonth">&raquo;</button>
              </div>
              <!-- Weekdagkoppen in exact hetzelfde grid -->
              <div
                class="d-grid gap-1 text-muted fw-medium small mb-2"
                style="grid-template-columns: repeat(7, 2rem); justify-items: center;">
                <div>Ma</div><div>Di</div><div>Wo</div><div>Do</div>
                <div>Vr</div><div>Za</div><div>Zo</div>
              </div>
              <!-- Dagen in CSS Grid -->
              <div
                id="calendarDays"
                class="d-grid gap-1"
                style="grid-template-columns: repeat(7, 2rem);">
              </div>
              <!-- Legenda -->
              <ul class="list-unstyled mt-3 mb-0 small">
                <li><span class="calendar-day status-in_behandeling">&nbsp;</span> In behandeling</li>
                <li><span class="calendar-day status-bevestigd">&nbsp;</span> Bevestigd</li>
                <li><span class="calendar-day status-geannuleerd">&nbsp;</span> Geannuleerd</li>
              </ul>
            </div>
          </div>
        </div>

        <!-- Detail Panel -->
        <div class="col-lg-9">
          <div class="card h-100">
            <div class="card-body d-flex flex-column">
              <h5 class="card-title mb-3" id="selectedDateHeader"></h5>
              
              <div id="dayReservations" class="flex-grow-1">
                <!-- Hier worden de reserveringen voor de geselecteerde dag getoond -->
                <p class="text-muted text-center py-5" id="noReservationsMessage">Geen reserveringen voor deze datum</p>
              </div>
            </div>
          </div>
        </div>
      </div><!-- /row -->
    </div><!-- /container -->
  </div><!-- /main -->

  <!-- Bootstrap JS -->
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

  <script>
    // Reserveringsgegevens (overgebracht vanuit controller)
    const calendarData = @json($calendarData ?? []);

    // Status mapping
    const statusLabels = {
      'in_behandeling': 'In behandeling',
      'bevestigd': 'Bevestigd',
      'geannuleerd': 'Geannuleerd'
    };

    // Weekdagen / maanden in NL
    const weekdays = ['zondag','maandag','dinsdag','woensdag','donderdag','vrijdag','zaterdag'];
    const shortWeekdays = ['zo','ma','di','wo','do','vr','za'];
    const months   = ['januari','februari','maart','april','mei','juni','juli',
                      'augustus','september','oktober','november','december'];

    // State
    let current  = new Date();  // Huidige maand
    let selected = new Date();  // Vandaag
    let viewMode = 'day';       // Weergavemodus: 'day' of 'week'

    const monthYearEl      = document.getElementById('monthYear');
    const daysContainer    = document.getElementById('calendarDays');
    const selectedHeader   = document.getElementById('selectedDateHeader');
    const dayReservations  = document.getElementById('dayReservations');
    const noReservationsMsg = document.getElementById('noReservationsMessage');
    const viewDayBtn       = document.getElementById('viewDay');
    const viewWeekBtn      = document.getElementById('viewWeek');

    function renderCalendar() {
      // toon "mei 2025"
      monthYearEl.textContent = months[current.getMonth()] + ' ' + current.getFullYear();

      daysContainer.innerHTML = '';
      // bepaal eerste dag (maandag=0…zondag=6)
      let firstDayIdx = new Date(current.getFullYear(), current.getMonth(), 1).getDay();
      firstDayIdx = firstDayIdx === 0 ? 6 : firstDayIdx - 1;
      const daysInPrev  = new Date(current.getFullYear(), current.getMonth(), 0).getDate();
      const daysInMonth = new Date(current.getFullYear(), current.getMonth()+1, 0).getDate();

      // voorloopdagen (vorige maand)
      for (let i = firstDayIdx - 1; i >= 0; i--) {
        const d = daysInPrev - i;
        addDay(new Date(current.getFullYear(), current.getMonth()-1, d), true);
      }
      // dagen huidige maand
      for (let d = 1; d <= daysInMonth; d++) {
        addDay(new Date(current.getFullYear(), current.getMonth(), d), false);
      }
      
      // Voeg naloopdagen toe tot we 42 dagen hebben (6 rijen van 7 dagen)
      // Dit zorgt voor een consistent formaat op alle apparaten
      const totalCells = daysContainer.childElementCount;
      if (totalCells < 42) {
        const remainingDays = 42 - totalCells;
        for (let d = 1; d <= remainingDays; d++) {
          addDay(new Date(current.getFullYear(), current.getMonth()+1, d), true);
        }
      }
    }

    function addDay(dateObj, otherMonth) {
      const y = dateObj.getFullYear(),
            m = String(dateObj.getMonth()+1).padStart(2,'0'),
            d = String(dateObj.getDate()).padStart(2,'0'),
            key = `${y}-${m}-${d}`;

      const cell = document.createElement('div');
      cell.className = 'calendar-day';
      cell.textContent = dateObj.getDate();

      if (otherMonth) cell.classList.add('other-month');
      
      // Voeg statusklasse toe op basis van reserveringsgegevens
      const dateKey = `${y}-${m}-${d}`;
      if (calendarData[dateKey]) {
        const data = calendarData[dateKey];
        
        // Bepaal de dominante status
        let dominantStatus = 'in_behandeling';
        if (data.statuses.bevestigd > 0) {
          dominantStatus = 'bevestigd';
        } else if (data.statuses.in_behandeling === 0 && data.statuses.geannuleerd > 0) {
          dominantStatus = 'geannuleerd';
        }
        
        cell.classList.add('status-' + dominantStatus);
        
        // Toon aantal reserveringen als er meer dan 1 zijn
        if (data.count > 1) {
          const badge = document.createElement('span');
          badge.className = 'reservation-count';
          badge.textContent = data.count;
          cell.appendChild(badge);
        }
      }
      
      if (dateObj.toDateString() === selected.toDateString()) {
        cell.classList.add('selected-day');
      }

      cell.addEventListener('click', () => {
        document.querySelectorAll('.selected-day').forEach(el => {
          el.classList.remove('selected-day');
        });
        cell.classList.add('selected-day');
        
        selected = new Date(dateObj);
        updateDetail();
      });

      daysContainer.appendChild(cell);
    }

    function updateDetail() {
      const wk = weekdays[selected.getDay()];
      const dy = selected.getDate();
      const mn = months[selected.getMonth()];
      const yr = selected.getFullYear();
      
      if (viewMode === 'day') {
        selectedHeader.textContent = `${wk} ${dy} ${mn} ${yr}`;
        showDayView();
      } else {
        // Week weergave
        const weekStart = new Date(selected);
        const dayOfWeek = selected.getDay() || 7; // zondag = 0, maak er 7 van
        weekStart.setDate(selected.getDate() - (dayOfWeek - 1)); // Ga naar maandag
        
        const weekEnd = new Date(weekStart);
        weekEnd.setDate(weekStart.getDate() + 6); // Ga naar zondag
        
        const startDay = weekStart.getDate();
        const startMonth = months[weekStart.getMonth()];
        const endDay = weekEnd.getDate();
        const endMonth = months[weekEnd.getMonth()];
        
        selectedHeader.textContent = `Week ${getWeekNumber(selected)}: ${startDay} ${startMonth} - ${endDay} ${endMonth} ${yr}`;
        showWeekView(weekStart);
      }
    }

    function showDayView() {
      // Formatteer datum als YYYY-MM-DD voor opzoeken in calendarData
      const y = selected.getFullYear(),
            m = String(selected.getMonth()+1).padStart(2,'0'),
            d = String(selected.getDate()).padStart(2,'0'),
            dateKey = `${y}-${m}-${d}`;
      
      // Wis de huidige inhoud
      dayReservations.innerHTML = '';
      
      // Controleer of er reserveringen zijn voor deze datum
      if (calendarData[dateKey] && calendarData[dateKey].reservations.length > 0) {
        noReservationsMsg.style.display = 'none';
        
        // Sorteer reserveringen op tijd
        const sortedReservations = [...calendarData[dateKey].reservations].sort((a, b) => {
          return a.time.localeCompare(b.time);
        });
        
        // Maak een tijdslot element voor elke reservering
        sortedReservations.forEach(reservation => {
          const timeSlot = document.createElement('div');
          timeSlot.className = `time-slot ${reservation.status}`;
          
          timeSlot.innerHTML = `
            <div class="d-flex justify-content-between align-items-start">
              <div>
                <span class="fw-bold">${reservation.time}</span> - ${reservation.name} (${reservation.people} personen)
                <div class="small text-muted">${reservation.reference}</div>
              </div>
              <span class="badge bg-${reservation.status === 'in_behandeling' ? 'warning' : 
                                      (reservation.status === 'bevestigd' ? 'success' : 'danger')}">
                ${statusLabels[reservation.status]}
              </span>
            </div>
            <div class="mt-2 d-flex justify-content-between align-items-center">
              <div class="small">
                <i class="bi bi-envelope"></i> ${reservation.email}<br>
                <i class="bi bi-telephone"></i> ${reservation.phone}
              </div>
              <div>
                <a href="/reservering/${reservation.id}/bewerken" class="btn btn-sm btn-outline-primary">
                  <i class="bi bi-pencil"></i> Bewerken
                </a>
              </div>
            </div>
          `;
          
          dayReservations.appendChild(timeSlot);
        });
      } else {
        noReservationsMsg.style.display = 'block';
        dayReservations.appendChild(noReservationsMsg);
      }
    }

    function showWeekView(weekStart) {
      // Wis de huidige inhoud
      dayReservations.innerHTML = '';
      
      // Maak een tabel voor de week
      const weekTable = document.createElement('div');
      weekTable.className = 'week-view';
      
      let hasReservations = false;
      
      // Loop door elke dag van de week (7 dagen vanaf weekStart)
      for (let i = 0; i < 7; i++) {
        const currentDate = new Date(weekStart);
        currentDate.setDate(weekStart.getDate() + i);
        
        const y = currentDate.getFullYear(),
              m = String(currentDate.getMonth()+1).padStart(2,'0'),
              d = String(currentDate.getDate()).padStart(2,'0'),
              dateKey = `${y}-${m}-${d}`;
        
        // Maak dagsectie
        const daySection = document.createElement('div');
        daySection.className = 'card mb-3';
        
        // Dagheader
        const dayHeader = document.createElement('div');
        dayHeader.className = `card-header ${currentDate.toDateString() === new Date().toDateString() ? 'bg-light' : ''}`;
        dayHeader.innerHTML = `
          <div class="d-flex justify-content-between align-items-center">
            <h6 class="mb-0">${shortWeekdays[currentDate.getDay()]} ${currentDate.getDate()} ${months[currentDate.getMonth()].substring(0, 3)}</h6>
            <span class="badge rounded-pill bg-secondary">${calendarData[dateKey]?.count || 0}</span>
          </div>
        `;
        daySection.appendChild(dayHeader);
        
        // Dag inhoud
        const dayContent = document.createElement('div');
        dayContent.className = 'card-body p-2';
        
        // Controleer of er reserveringen zijn voor deze datum
        if (calendarData[dateKey] && calendarData[dateKey].reservations.length > 0) {
          hasReservations = true;
          
          // Sorteer reserveringen op tijd
          const sortedReservations = [...calendarData[dateKey].reservations].sort((a, b) => {
            return a.time.localeCompare(b.time);
          });
          
          // Maak een beknopte reserveringsweergave voor elke reservering
          sortedReservations.forEach(reservation => {
            const resItem = document.createElement('div');
            resItem.className = `p-2 mb-2 rounded border-start border-3 border-${
              reservation.status === 'in_behandeling' ? 'warning' : 
              (reservation.status === 'bevestigd' ? 'success' : 'danger')
            }`;
            
            resItem.innerHTML = `
              <div class="d-flex justify-content-between">
                <span class="fw-medium">${reservation.time}</span>
                <span class="badge bg-${
                  reservation.status === 'in_behandeling' ? 'warning' : 
                  (reservation.status === 'bevestigd' ? 'success' : 'danger')
                } bg-opacity-75">${statusLabels[reservation.status]}</span>
              </div>
              <div>${reservation.name} - ${reservation.people} p.</div>
              <div class="d-flex justify-content-end mt-1">
                <a href="/reservering/${reservation.id}/bewerken" class="btn btn-sm btn-outline-primary py-0 px-2">
                  <i class="bi bi-pencil"></i>
                </a>
              </div>
            `;
            
            dayContent.appendChild(resItem);
          });
        } else {
          // Geen reserveringen voor deze dag
          const noRes = document.createElement('p');
          noRes.className = 'text-muted small text-center mb-0 py-2';
          noRes.textContent = 'Geen reserveringen';
          dayContent.appendChild(noRes);
        }
        
        daySection.appendChild(dayContent);
        weekTable.appendChild(daySection);
      }
      
      dayReservations.appendChild(weekTable);
      
      if (!hasReservations) {
        noReservationsMsg.style.display = 'block';
        dayReservations.appendChild(noReservationsMsg);
      } else {
        noReservationsMsg.style.display = 'none';
      }
    }

    // Bereken weeknummer voor een datum
    function getWeekNumber(d) {
      // Kopieer datum zodat we niet de originele wijzigen
      d = new Date(Date.UTC(d.getFullYear(), d.getMonth(), d.getDate()));
      // Zet naar voorafgaande donderdag
      d.setUTCDate(d.getUTCDate() + 4 - (d.getUTCDay() || 7));
      // Begin van het jaar
      const yearStart = new Date(Date.UTC(d.getUTCFullYear(), 0, 1));
      // Bereken weeknummer: Rond het aantal dagen sinds begin jaar / 7 naar boven
      return Math.ceil(((d - yearStart) / 86400000 + 1) / 7);
    }

    // Event Listeners voor knoppen
    viewDayBtn.addEventListener('click', function() {
      viewMode = 'day';
      viewDayBtn.classList.add('active');
      viewWeekBtn.classList.remove('active');
      updateDetail();
    });

    viewWeekBtn.addEventListener('click', function() {
      viewMode = 'week';
      viewWeekBtn.classList.add('active');
      viewDayBtn.classList.remove('active');
      updateDetail();
    });

    document.getElementById('prevMonth').addEventListener('click', () => {
      current.setMonth(current.getMonth() - 1);
      renderCalendar();
    });
    document.getElementById('nextMonth').addEventListener('click', () => {
      current.setMonth(current.getMonth() + 1);
      renderCalendar();
    });

    // initialisatie
    renderCalendar();
    updateDetail();
  </script>
</body>
</html>
