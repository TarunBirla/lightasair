@extends('layouts.vendor')
@section('title', 'Availability Calendar — ' . $rental->title)

@section('content')
<div class="page-header">
    <div>
        <h1 class="page-title"><i class="fas fa-calendar-alt"></i> Availability Calendar</h1>
        <p class="page-subtitle">{{ $rental->title }} — Manage blocked dates and view bookings</p>
    </div>
    <div class="header-actions">
        <a href="{{ route('vendor.rentals.show', $rental) }}" class="btn btn-outline">
            <i class="fas fa-arrow-left"></i> Back to Listing
        </a>
    </div>
</div>

{{-- Legend --}}
<div class="legend-row">
    <span class="legend-item legend-booked"><i class="fas fa-circle"></i> Booked</span>
    <span class="legend-item legend-blocked"><i class="fas fa-circle"></i> Blocked by you</span>
    <span class="legend-item legend-available"><i class="fas fa-circle"></i> Available</span>
    <span class="legend-item legend-today"><i class="fas fa-circle"></i> Today</span>
</div>

<div class="calendar-layout">

    {{-- Calendar --}}
    <div class="calendar-panel">
        <div id="calendar"></div>
    </div>

    {{-- Side panel --}}
    <div class="side-panel">

        {{-- Block a date --}}
        <div class="side-card">
            <h3 class="side-title"><i class="fas fa-ban"></i> Block a Date</h3>
            <form id="blockForm">
                @csrf
                <div class="form-group">
                    <label class="form-label">Date</label>
                    <input type="date" id="blockDate" name="date" class="form-control"
                           min="{{ now()->toDateString() }}" required>
                </div>
                <div class="form-group">
                    <label class="form-label">Reason (optional)</label>
                    <input type="text" id="blockReason" name="reason" class="form-control"
                           placeholder="e.g. Maintenance">
                </div>
                <button type="submit" class="btn btn-danger btn-full">
                    <i class="fas fa-ban"></i> Block Date
                </button>
            </form>
        </div>

        {{-- Unblock a date --}}
        <div class="side-card">
            <h3 class="side-title"><i class="fas fa-check-circle"></i> Unblock a Date</h3>
            <form id="unblockForm">
                @csrf
                <div class="form-group">
                    <label class="form-label">Date</label>
                    <input type="date" id="unblockDate" name="date" class="form-control" required>
                </div>
                <button type="submit" class="btn btn-success btn-full">
                    <i class="fas fa-unlock"></i> Unblock Date
                </button>
            </form>
        </div>

        {{-- Current blocked dates --}}
        <div class="side-card">
            <h3 class="side-title"><i class="fas fa-list"></i> Currently Blocked</h3>
            <div id="blockedList">
                @forelse($blockedDates as $d)
                <div class="blocked-item" data-date="{{ $d }}">
                    <span>{{ \Carbon\Carbon::parse($d)->format('d M Y') }}</span>
                    <button class="btn-unblock" onclick="quickUnblock('{{ $d }}')">
                        <i class="fas fa-times"></i>
                    </button>
                </div>
                @empty
                <p class="no-blocked">No dates blocked.</p>
                @endforelse
            </div>
        </div>

        {{-- Upcoming bookings --}}
        <div class="side-card">
            <h3 class="side-title"><i class="fas fa-bookmark"></i> Upcoming Bookings</h3>
            @forelse($bookedRanges as $range)
            <div class="booking-range">
                <span class="range-dot"></span>
                <span>{{ \Carbon\Carbon::parse($range['start'])->format('d M') }}
                      → {{ \Carbon\Carbon::parse($range['end'])->format('d M Y') }}</span>
            </div>
            @empty
            <p class="no-blocked">No upcoming bookings.</p>
            @endforelse
        </div>

    </div>
</div>

<div id="toast" class="toast"></div>
@endsection

@push('styles')
{{-- FullCalendar CSS --}}
<link href="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.10/index.global.min.css" rel="stylesheet">
<style>
.page-header{display:flex;align-items:flex-start;justify-content:space-between;margin-bottom:1.25rem;gap:1rem}
.header-actions{display:flex;gap:.5rem}
.legend-row{display:flex;gap:1.5rem;margin-bottom:1.25rem;flex-wrap:wrap}
.legend-item{display:flex;align-items:center;gap:.4rem;font-size:.8rem;font-weight:600}
.legend-booked i{color:#dc2626}
.legend-blocked i{color:#d97706}
.legend-available i{color:#16a34a}
.legend-today i{color:#1d4ed8}

.calendar-layout{display:grid;grid-template-columns:1fr 300px;gap:1.5rem;align-items:start}
@media(max-width:1024px){.calendar-layout{grid-template-columns:1fr}}

.calendar-panel{background:#fff;border-radius:1rem;padding:1.25rem;box-shadow:0 2px 8px rgba(0,0,0,.06)}

/* FullCalendar overrides */
.fc .fc-toolbar-title{font-size:1.1rem;font-weight:700}
.fc .fc-button-primary{background:#16a34a;border-color:#16a34a}
.fc .fc-button-primary:hover{background:#15803d;border-color:#15803d}
.fc .fc-daygrid-day.fc-day-today{background:#eff6ff}
.fc-event{border-radius:4px;font-size:.75rem;padding:1px 4px}
.booked-event{background:#dc2626!important;border-color:#dc2626!important;color:#fff!important}
.blocked-event{background:#d97706!important;border-color:#d97706!important;color:#fff!important}

.side-panel{display:flex;flex-direction:column;gap:1rem}
.side-card{background:#fff;border-radius:1rem;padding:1.25rem;box-shadow:0 2px 8px rgba(0,0,0,.06)}
.side-title{font-size:.95rem;font-weight:700;margin:0 0 1rem;display:flex;align-items:center;gap:.5rem}
.form-group{margin-bottom:.75rem}
.form-label{display:block;font-size:.8rem;font-weight:600;margin-bottom:.25rem;color:#374151}
.form-control{width:100%;padding:.45rem .65rem;border:1px solid #d1d5db;border-radius:.5rem;font-size:.875rem;box-sizing:border-box}
.form-control:focus{outline:none;border-color:#16a34a;box-shadow:0 0 0 3px rgba(22,163,74,.1)}
.btn-full{width:100%;padding:.55rem;border:none;border-radius:.5rem;font-weight:600;cursor:pointer;font-size:.875rem}
.btn-danger{background:#dc2626;color:#fff}
.btn-success{background:#16a34a;color:#fff}
.btn-outline{border:1px solid #d1d5db;background:transparent;color:#374151;padding:.5rem 1rem;border-radius:.5rem;cursor:pointer;text-decoration:none;display:inline-flex;align-items:center;gap:.4rem;font-size:.875rem}

.blocked-item{display:flex;justify-content:space-between;align-items:center;padding:.4rem .5rem;border-radius:.375rem;background:#fef9c3;margin-bottom:.4rem;font-size:.85rem}
.btn-unblock{background:none;border:none;cursor:pointer;color:#dc2626;font-size:.8rem}
.no-blocked{color:#9ca3af;font-size:.85rem;margin:0}

.booking-range{display:flex;align-items:center;gap:.5rem;padding:.4rem 0;font-size:.85rem;border-bottom:1px solid #f3f4f6}
.booking-range:last-child{border-bottom:none}
.range-dot{width:8px;height:8px;background:#dc2626;border-radius:50%;flex-shrink:0}

/* Toast */
.toast{position:fixed;bottom:1.5rem;right:1.5rem;background:#1f2937;color:#fff;padding:.75rem 1.25rem;border-radius:.75rem;font-size:.875rem;z-index:9999;opacity:0;transform:translateY(10px);transition:all .3s;pointer-events:none}
.toast.show{opacity:1;transform:translateY(0)}
.toast.success{background:#16a34a}
.toast.error{background:#dc2626}
</style>
@endpush

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.10/index.global.min.js"></script>
<script>
const BLOCK_URL   = "{{ route('vendor.rentals.block-date', $rental) }}";
const UNBLOCK_URL = "{{ route('vendor.rentals.unblock-date', $rental) }}";
const CSRF        = "{{ csrf_token() }}";

const bookedRanges = @json($bookedRanges);
const blockedDates = @json($blockedDates);

// Build FullCalendar events
const events = [];

bookedRanges.forEach(r => {
    // FullCalendar end is exclusive, add 1 day
    const endDate = new Date(r.end);
    endDate.setDate(endDate.getDate() + 1);
    events.push({
        title: 'Booked',
        start: r.start,
        end: endDate.toISOString().split('T')[0],
        classNames: ['booked-event'],
        display: 'background',
    });
});

blockedDates.forEach(d => {
    events.push({
        title: 'Blocked',
        start: d,
        end: d,
        classNames: ['blocked-event'],
        allDay: true,
    });
});

const calendarEl = document.getElementById('calendar');
const calendar = new FullCalendar.Calendar(calendarEl, {
    initialView: 'dayGridMonth',
    headerToolbar: {
        left: 'prev,next today',
        center: 'title',
        right: 'dayGridMonth,listMonth',
    },
    events: events,
    dateClick(info) {
        document.getElementById('blockDate').value = info.dateStr;
        document.getElementById('unblockDate').value = info.dateStr;
    },
});
calendar.render();

// Block form
document.getElementById('blockForm').addEventListener('submit', async function(e) {
    e.preventDefault();
    const date   = document.getElementById('blockDate').value;
    const reason = document.getElementById('blockReason').value;
    const res = await fetch(BLOCK_URL, {
        method: 'POST',
        headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': CSRF },
        body: JSON.stringify({ date, reason }),
    });
    const data = await res.json();
    if (data.status) {
        showToast('Date blocked successfully', 'success');
        addBlockedEvent(date);
        addBlockedToList(date);
        this.reset();
    } else {
        showToast('Failed to block date', 'error');
    }
});

// Unblock form
document.getElementById('unblockForm').addEventListener('submit', async function(e) {
    e.preventDefault();
    await doUnblock(document.getElementById('unblockDate').value);
    this.reset();
});

async function quickUnblock(date) {
    await doUnblock(date);
}

async function doUnblock(date) {
    const res = await fetch(UNBLOCK_URL, {
        method: 'POST',
        headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': CSRF },
        body: JSON.stringify({ date }),
    });
    const data = await res.json();
    if (data.status) {
        showToast('Date unblocked', 'success');
        removeBlockedEvent(date);
        removeBlockedFromList(date);
    } else {
        showToast('Failed to unblock date', 'error');
    }
}

function addBlockedEvent(date) {
    calendar.addEvent({ title: 'Blocked', start: date, end: date, classNames: ['blocked-event'], allDay: true });
}

function removeBlockedEvent(date) {
    calendar.getEvents().forEach(ev => {
        if (ev.startStr === date && ev.title === 'Blocked') ev.remove();
    });
}

function addBlockedToList(date) {
    const list = document.getElementById('blockedList');
    const noBlocked = list.querySelector('.no-blocked');
    if (noBlocked) noBlocked.remove();
    const div = document.createElement('div');
    div.className = 'blocked-item';
    div.dataset.date = date;
    const d = new Date(date);
    div.innerHTML = `<span>${d.toLocaleDateString('en-GB', {day:'2-digit',month:'short',year:'numeric'})}</span>
        <button class="btn-unblock" onclick="quickUnblock('${date}')"><i class="fas fa-times"></i></button>`;
    list.prepend(div);
}

function removeBlockedFromList(date) {
    const el = document.querySelector(`.blocked-item[data-date="${date}"]`);
    if (el) el.remove();
    if (!document.querySelectorAll('.blocked-item').length) {
        document.getElementById('blockedList').innerHTML = '<p class="no-blocked">No dates blocked.</p>';
    }
}

function showToast(msg, type = 'success') {
    const t = document.getElementById('toast');
    t.textContent = msg;
    t.className = 'toast show ' + type;
    setTimeout(() => { t.className = 'toast'; }, 3000);
}
</script>
@endpush
