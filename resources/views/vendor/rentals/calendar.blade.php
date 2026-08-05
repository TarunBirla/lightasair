@extends('layouts.vendor')

@section('page-title', 'Equipment Availability Calendar')
@section('breadcrumb', 'Vendor / Rentals / Calendar — ' . $rental->title)

@section('content')

<style>
.cal-container { margin-bottom: 2rem; }

.legend-row {
    display: flex;
    gap: 1rem;
    margin-bottom: 1.5rem;
    flex-wrap: wrap;
    align-items: center;
}
.legend-pill {
    background: #fff;
    border: 1px solid #E8E6DF;
    border-radius: 30px;
    padding: .45rem 1rem;
    font-size: .82rem;
    font-weight: 700;
    display: inline-flex;
    align-items: center;
    gap: .5rem;
    color: #333;
    box-shadow: 0 2px 6px rgba(0,0,0,.02);
}
.legend-dot { width: 10px; height: 10px; border-radius: 50%; display: inline-block; }
.dot-booked { background: #dc2626; }
.dot-blocked { background: #d97706; }
.dot-available { background: #16a34a; }
.dot-today { background: #2563eb; }

.calendar-layout {
    display: grid;
    grid-template-columns: 1fr 340px;
    gap: 1.5rem;
    align-items: start;
}
@media(max-width:1024px) {
    .calendar-layout { grid-template-columns: 1fr; }
}

.calendar-panel {
    background: #fff;
    border-radius: 16px;
    padding: 1.5rem;
    border: 1px solid #E8E6DF;
    box-shadow: 0 4px 16px rgba(0,0,0,.03);
}

/* FullCalendar Custom Theme Overrides */
.fc { font-family: inherit; }
.fc .fc-toolbar-title { font-size: 1.25rem; font-weight: 800; color: #111; }
.fc .fc-button-primary {
    background: #111111 !important;
    border-color: #111111 !important;
    color: var(--brand, #FFC700) !important;
    font-weight: 800 !important;
    border-radius: 8px !important;
    padding: .4rem .9rem !important;
    text-transform: capitalize !important;
    box-shadow: none !important;
}
.fc .fc-button-primary:hover {
    background: #333333 !important;
    border-color: #333333 !important;
}
.fc .fc-button-primary:disabled {
    background: #e5e7eb !important;
    border-color: #e5e7eb !important;
    color: #9ca3af !important;
}
.fc .fc-daygrid-day.fc-day-today { background: #fff8e1 !important; }
.fc-theme-standard td, .fc-theme-standard th { border-color: #f0efe8 !important; }
.fc-col-header-cell-cushion { color: #888; font-weight: 700; font-size: .78rem; text-transform: uppercase; padding: .6rem 0 !important; }

.fc-event { border-radius: 6px; font-size: .75rem; padding: 2px 6px; font-weight: 700; }
.booked-event { background: #fee2e2 !important; border-color: #fca5a5 !important; color: #991b1b !important; }
.blocked-event { background: #fff3cd !important; border-color: #ffe082 !important; color: #856404 !important; }

.side-panel { display: flex; flex-direction: column; gap: 1.2rem; }
.side-card {
    background: #fff;
    border-radius: 16px;
    padding: 1.3rem;
    border: 1px solid #E8E6DF;
    box-shadow: 0 4px 16px rgba(0,0,0,.03);
}
.side-title {
    font-size: .95rem;
    font-weight: 800;
    color: #111;
    margin: 0 0 1rem;
    display: flex;
    align-items: center;
    gap: .5rem;
}

.f-label { font-size: .75rem; font-weight: 700; text-transform: uppercase; color: #666; margin-bottom: .3rem; display: block; }
.f-input { width: 100%; padding: .55rem .8rem; border: 1px solid #E8E6DF; border-radius: 8px; font-size: .88rem; font-family: inherit; margin-bottom: .8rem; }
.f-input:focus { outline: none; border-color: var(--brand, #FFC700); }

.btn-brand-block {
    width: 100%;
    padding: .6rem;
    background: #111;
    color: var(--brand, #FFC700);
    border: none;
    border-radius: 8px;
    font-weight: 800;
    font-size: .88rem;
    cursor: pointer;
    display: flex;
    align-items: center;
    justify-content: center;
    gap: .4rem;
    transition: all .2s;
}
.btn-brand-block:hover { background: #333; }

.btn-unblock-btn {
    width: 100%;
    padding: .6rem;
    background: #f3f4f6;
    color: #374151;
    border: 1px solid #e5e7eb;
    border-radius: 8px;
    font-weight: 700;
    font-size: .88rem;
    cursor: pointer;
    display: flex;
    align-items: center;
    justify-content: center;
    gap: .4rem;
    transition: all .2s;
}
.btn-unblock-btn:hover { background: #e5e7eb; }

.blocked-item {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: .5rem .75rem;
    border-radius: 8px;
    background: #fff8e1;
    border: 1px solid #ffe082;
    margin-bottom: .5rem;
    font-size: .85rem;
    font-weight: 700;
    color: #856404;
}
.btn-unblock-icon {
    background: none;
    border: none;
    cursor: pointer;
    color: #dc2626;
    font-size: .85rem;
    padding: 0;
}
.no-blocked { color: #888; font-size: .85rem; margin: 0; text-align: center; padding: .5rem 0; }

.booking-range {
    display: flex;
    align-items: center;
    gap: .6rem;
    padding: .5rem 0;
    font-size: .85rem;
    border-bottom: 1px solid #f5f4f0;
}
.booking-range:last-child { border-bottom: none; }
.range-dot { width: 8px; height: 8px; background: #dc2626; border-radius: 50%; flex-shrink: 0; }

/* Toast Notification */
.toast-notice {
    position: fixed;
    bottom: 2rem;
    right: 2rem;
    background: #111;
    color: #fff;
    padding: .85rem 1.4rem;
    border-radius: 12px;
    font-weight: 700;
    font-size: .88rem;
    z-index: 9999;
    opacity: 0;
    transform: translateY(15px);
    transition: all .3s;
    pointer-events: none;
    box-shadow: 0 10px 30px rgba(0,0,0,.3);
}
.toast-notice.show { opacity: 1; transform: translateY(0); }
.toast-notice.success { border-left: 4px solid #16a34a; }
.toast-notice.error { border-left: 4px solid #dc2626; }
</style>

{{-- Page Header --}}
<div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-2">
    <div>
        <h2 style="font-size:1.5rem;font-weight:800;margin:0;color:#111;">
            <i class="fa-solid fa-calendar-days text-warning me-2"></i> Availability Calendar
        </h2>
        <div style="font-size:.85rem;color:#888;margin-top:.2rem;">
            Listing: <strong>{{ $rental->title }}</strong> — Manage blocked dates & view customer bookings
        </div>
    </div>
    <a href="{{ route('vendor.rentals.show', $rental->id) }}" class="btn btn-outline-secondary" style="border-radius:10px;font-weight:700;font-size:.85rem;">
        <i class="fa-solid fa-arrow-left me-1"></i> Back to Listing
    </a>
</div>

{{-- Color Legend --}}
<div class="legend-row">
    <div class="legend-pill"><span class="legend-dot dot-available"></span> Available</div>
    <div class="legend-pill"><span class="legend-dot dot-booked"></span> Customer Booked</div>
    <div class="legend-pill"><span class="legend-dot dot-blocked"></span> Blocked by Vendor</div>
    <div class="legend-pill"><span class="legend-dot dot-today"></span> Today</div>
</div>

<div class="calendar-layout cal-container">

    {{-- Calendar Main Grid --}}
    <div class="calendar-panel">
        <div id="calendar"></div>
    </div>

    {{-- Side Panel Tools --}}
    <div class="side-panel">

        {{-- Block Date Card --}}
        <div class="side-card">
            <h4 class="side-title"><i class="fa-solid fa-ban text-warning"></i> Block Date</h4>
            <form id="blockForm">
                @csrf
                <div>
                    <label class="f-label">Date to Block</label>
                    <input type="date" id="blockDate" name="date" class="f-input" min="{{ now()->toDateString() }}" required>
                </div>
                <div>
                    <label class="f-label">Reason (Optional)</label>
                    <input type="text" id="blockReason" name="reason" class="f-input" placeholder="e.g. Maintenance / Personal use">
                </div>
                <button type="submit" class="btn-brand-block">
                    <i class="fa-solid fa-lock"></i> Block Selected Date
                </button>
            </form>
        </div>

        {{-- Unblock Date Card --}}
        <div class="side-card">
            <h4 class="side-title"><i class="fa-solid fa-unlock text-success"></i> Quick Unblock</h4>
            <form id="unblockForm">
                @csrf
                <div>
                    <label class="f-label">Date to Unblock</label>
                    <input type="date" id="unblockDate" name="date" class="f-input" required>
                </div>
                <button type="submit" class="btn-unblock-btn">
                    <i class="fa-solid fa-key"></i> Unblock Date
                </button>
            </form>
        </div>

        {{-- Currently Blocked Dates List --}}
        <div class="side-card">
            <h4 class="side-title"><i class="fa-solid fa-list-check text-warning"></i> Blocked Dates List</h4>
            <div id="blockedList">
                @forelse($blockedDates as $d)
                <div class="blocked-item" data-date="{{ $d }}">
                    <span>{{ \Carbon\Carbon::parse($d)->format('d M Y') }}</span>
                    <button class="btn-unblock-icon" onclick="quickUnblock('{{ $d }}')" title="Unblock Date">
                        <i class="fa-solid fa-circle-xmark"></i>
                    </button>
                </div>
                @empty
                <p class="no-blocked">No dates currently blocked.</p>
                @endforelse
            </div>
        </div>

        {{-- Upcoming Bookings --}}
        <div class="side-card">
            <h4 class="side-title"><i class="fa-solid fa-calendar-check text-primary"></i> Customer Bookings</h4>
            @forelse($bookedRanges as $range)
            <div class="booking-range">
                <span class="range-dot"></span>
                <span style="font-weight:700;color:#111;">
                    {{ \Carbon\Carbon::parse($range['start'])->format('d M') }}
                    → {{ \Carbon\Carbon::parse($range['end'])->format('d M Y') }}
                </span>
            </div>
            @empty
            <p class="no-blocked">No customer bookings yet.</p>
            @endforelse
        </div>

    </div>
</div>

<div id="toastNotice" class="toast-notice"></div>

@endsection

@push('styles')
<link href="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.10/index.global.min.css" rel="stylesheet">
@endpush

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.10/index.global.min.js"></script>
<script>
const BLOCK_URL   = "{{ route('vendor.rentals.block-date', $rental->id) }}";
const UNBLOCK_URL = "{{ route('vendor.rentals.unblock-date', $rental->id) }}";
const CSRF        = "{{ csrf_token() }}";

const bookedRanges = @json($bookedRanges);
const blockedDates = @json($blockedDates);

// Build FullCalendar events
const events = [];

bookedRanges.forEach(r => {
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

// Block form submit
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

// Unblock form submit
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
        showToast('Date unblocked successfully', 'success');
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
        <button class="btn-unblock-icon" onclick="quickUnblock('${date}')"><i class="fa-solid fa-circle-xmark"></i></button>`;
    list.prepend(div);
}

function removeBlockedFromList(date) {
    const el = document.querySelector(`.blocked-item[data-date="${date}"]`);
    if (el) el.remove();
    if (!document.querySelectorAll('.blocked-item').length) {
        document.getElementById('blockedList').innerHTML = '<p class="no-blocked">No dates currently blocked.</p>';
    }
}

function showToast(msg, type = 'success') {
    const t = document.getElementById('toastNotice');
    t.textContent = msg;
    t.className = 'toast-notice show ' + type;
    setTimeout(() => { t.className = 'toast-notice'; }, 3000);
}
</script>
@endpush
