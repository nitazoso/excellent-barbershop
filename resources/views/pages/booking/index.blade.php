@extends('layouts.app')

@section('title', 'Booking — Excellent Barbershop')

@section('content')
<section class="booking-page" id="booking-wizard">
    <span class="section-kicker">BOOKING ONLINE</span>
    <h1>Atur waktu untuk diri sendiri.</h1>

    <x-booking-stepper :current="1" />

    <form method="POST" action="{{ route('booking.store') }}" id="booking-form">
        @csrf

        {{-- Step 1: Pilih Barber --}}
        <div id="step-1" class="wizard-step" data-step="1">
            <h2>Pilih Kapster Favorit</h2>
            <div class="barber-selection">
                @forelse ($barbers as $barber)
                <button type="button" class="barber-card selectable" data-id="{{ $barber->id }}"
                    data-name="{{ $barber->name }}" onclick="selectBarber(this)">
                    <img src="{{ $barber->photo }}" alt="{{ $barber->name }}" loading="lazy">
                    <div>
                        <h3>{{ $barber->name }} @if($barber->id == 1) @endif</h3>
                        <p class="rating">★ 4.9 <small>(850+ ulasan)</small></p>
                        <p>{{ $barber->description }} · 10+ thn exp</p>
                    </div>
                    <div class="status-badge">Tersedia Hari Ini</div>
                </button>
                @empty
                <p>Belum ada barber yang tersedia.</p>
                @endforelse
            </div>
            <input type="hidden" name="barber_id" id="barber_id" required>

            <div class="wizard-nav">
                <a class="primary" href="#layanan"
                    onclick="alert('Pilih layanan terlebih dahulu dari halaman utama.')">Lihat Layanan →</a>
            </div>
        </div>

        {{-- Step 2: Pilih Jadwal --}}
        <div id="step-2" class="wizard-step hidden" data-step="2">
            <h2>Jadwal Waktu</h2>
            <div class="barber-header" id="barber-header"></div>

            <div class="calendar-strip">
                @php($today = today()->format('Y-m-d'))
                @foreach (range(0, 6) as $i)
                @php($date = today()->addDays($i))
                <button type="button" class="date-pill {{ $date === $today ? 'is-today' : '' }}"
                    onclick="selectDate(this, '{{ $date }}')" data-date="{{ $date }}">
                    <span>{{ $date->format('D') }}</span>
                    <strong>{{ $date->format('d') }}</strong>
                </button>
                @endforeach
            </div>

            <div class="session-block">
                <h3>Sesi siang (15.00 – 18.00 WIB)</h3>
                <div class="time-grid" id="sesi-sore">
                    <button type="button" class="time-slot" onclick="selectSlot(this, 1, '15:00')">15:00</button>
                    <button type="button" class="time-slot" onclick="selectSlot(this, 2, '15:30')">15:30</button>
                    <button type="button" class="time-slot" onclick="selectSlot(this, 3, '16:00')">16:00</button>
                    <button type="button" class="time-slot" onclick="selectSlot(this, 4, '16:30')">16:30</button>
                    <button type="button" class="time-slot" onclick="selectSlot(this, 5, '17:00')">17:00</button>
                    <button type="button" class="time-slot" onclick="selectSlot(this, 6, '17:30')">17:30</button>
                </div>
            </div>
            <div class="session-block">
                <h3>Waktu Istirahat (18.00 – 19.30 WIB)</h3>
                <div class="time-grid" id="sesi-istirahat">
                    <button type="button" class="time-slot is-booked" disabled>18:00</button>
                    <button type="button" class="time-slot is-booked" disabled>18:30</button>
                    <button type="button" class="time-slot is-booked" disabled>19:00</button>
                </div>
            </div>
            <div class="session-block">
                <h3>Sesi Malam (19.30 – 21.00 WIB)</h3>
                <div class="time-grid" id="sesi-malam">
                    <button type="button" class="time-slot" onclick="selectSlot(this, 7, '19:30')">19:30</button>
                    <button type="button" class="time-slot" onclick="selectSlot(this, 8, '20:00')">20:00</button>
                    <button type="button" class="time-slot" onclick="selectSlot(this, 9, '20:30')">20:30</button>
                    <button type="button" class="time-slot" onclick="selectSlot(this, 10, '21:00')">21:00</button>
                </div>
            </div>

            <input type="hidden" name="schedule_id" id="schedule_id" required>
            <input type="hidden" name="service_id" value="1" id="service_id">
            <input type="hidden" name="date" id="date">

            <div class="wizard-nav">
                <button type="button" onclick="prevStep(1)">← Kembali</button>
                <button type="button" class="primary" onclick="confirmBooking()">Konfirmasi & Tiket →</button>
            </div>
        </div>

        {{-- Step 3: Konfirmasi Pesanan --}}
        <div id="step-3" class="wizard-step hidden" data-step="3">
            <h2>Konfirmasi Pemesanan</h2>
            <p style="margin-top:-8px; margin-bottom:16px; color:#666; font-size:14px;">Pesanan data dikirimkan ke admin dan akan dikonfirmasi apabila statusnya sudah dikirimkan dengan lengkap ke WhatsApp Anda.</p>

            <div class="info-card">
                <h3>Informasi Pelanggan</h3>
                <label for="name">Nama Lengkap <span>*</span></label>
                <input id="name" name="name" type="text" required maxlength="255" placeholder="Raka Pratama">

                <label for="phone">Nomor WhatsApp Aktif <span>*</span></label>
                <input id="phone" name="phone" type="tel" required maxlength="20" placeholder="+62 812-3456-7890">

                <label for="notes">Catatan (opsional)</label>
                <textarea id="notes" name="notes" rows="3"
                    placeholder="Permintaan khusus, alergi, atau preferensi."></textarea>
            </div>

            <div class="summary-card">
                <h3>Ringkasan Reservasi</h3>
                <div id="summary-barber"></div>
                <div id="summary-schedule"></div>
                <div id="summary-service"></div>
                <div class="policy">
                    <p><strong>Kebijakan Kedatangan & Pembatalan</strong></p>
                    <p>• Harap tiba di cabang 10 menit sebelum sesi dimulai untuk persiapan dan konsultasi awal.</p>
                    <p>• Tidak dapat dibatalkan maksimal 15 menit sebelum jadwal.</p>
                    <p>• Lewat dari batas waktu, reservasi dialihkan pada antrean berikutnya.</p>
                    <p>• Penjadwulan ulang gratis dapat dilakukan maksimal 1 jam sebelum waktu appointment melalui
                        WhatsApp.</p>
                </div>
            </div>

            <div class="wizard-nav">
                <button type="button" onclick="prevStep(2)">← Kembali</button>
                <button type="submit" class="primary">Konfirmasi & Tiket →</button>
            </div>
        </div>
    </form>
</section>
@endsection

@push('styles')
<style>
    .wizard-step {
        display: none;
        animation: fadeIn .35s ease;
    }

    .wizard-step:not(.hidden) {
        display: block;
    }

    @keyframes fadeIn {
        from {
            opacity: 0;
            transform: translateY(6px);
        }

        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    .barber-selection {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(260px, 1fr));
        gap: 14px;
        margin: 24px 0;
    }

    .barber-card.selectable {
        cursor: pointer;
        border-width: 2px;
        transition: all .15s;
        text-align: left;
        padding: 0;
    }

    .barber-card.selectable:hover,
    .barber-card.selectable.is-selected {
        border-color: var(--red);
        box-shadow: 0 6px 18px rgba(0, 0, 0, .06);
    }

    .barber-card.selectable img {
        width: 100%;
        aspect-ratio: 4/5;
        object-fit: cover;
        border-radius: 8px 8px 0 0;
        display: block;
    }

    .barber-card.selectable h3 {
        font-size: 16px;
        font-weight: 700;
        letter-spacing: -.02em;
        margin: 10px 14px 4px;
    }

    .barber-card.selectable p {
        font-size: 12px;
        color: var(--muted);
        margin: 0 14px 6px;
        line-height: 1.45;
    }

    .barber-card.selectable p.rating {
        color: #bfa04a;
        font-weight: 700;
        font-size: 13px;
    }

    .badge {
        background: var(--red);
        color: #fff;
        font-size: 9px;
        font-weight: 700;
        letter-spacing: .05em;
        padding: 2px 5px;
        border-radius: 3px;
        margin-left: 6px;
        vertical-align: middle;
    }

    .status-badge {
        font-size: 10px;
        font-weight: 700;
        color: #5a8a4a;
        letter-spacing: .04em;
        margin: 8px 14px 0;
    }

    .calendar-strip {
        display: flex;
        gap: 6px;
        overflow-x: auto;
        padding-bottom: 6px;
        margin: 18px 0;
    }

    .date-pill {
        background: #f5f3ee;
        border: 1px solid var(--line);
        border-radius: 8px;
        padding: 10px 12px;
        text-align: center;
        min-width: 60px;
        font-family: var(--display);
        font-size: 11px;
        color: #514239;
        cursor: pointer;
        transition: .15s;
    }

    .date-pill:hover,
    .date-pill.is-today {
        border-color: var(--red);
        background: #fff;
    }

    .date-pill.is-today {
        background: #ffeae1;
    }

    .date-pill span {
        font-size: 9px;
        text-transform: uppercase;
        letter-spacing: .08em;
        color: #7b7168;
    }

    .date-pill strong {
        display: block;
        font-size: 18px;
        font-weight: 800;
    }

    .session-block {
        background: #faf9f6;
        border: 1px solid var(--line);
        border-radius: 10px;
        padding: 16px 18px;
        margin: 14px 0;
    }

    .session-block h3 {
        font-size: 13px;
        font-weight: 700;
        letter-spacing: .02em;
        margin: 0 0 10px;
        color: #3a3229;
    }

    .time-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(84px, 1fr));
        gap: 8px;
    }

    .time-slot {
        background: #fff;
        border: 1px solid var(--line);
        border-radius: 6px;
        padding: 10px 4px;
        font-size: 12px;
        font-weight: 600;
        text-align: center;
        cursor: pointer;
        transition: .15s;
    }

    .time-slot:hover {
        border-color: var(--red);
        color: var(--red);
    }

    .time-slot.is-selected {
        background: var(--red);
        border-color: var(--red);
        color: #fff;
    }

    .time-slot.is-booked {
        background: #ecece8;
        color: #b5aca3;
        border-color: #ecece8;
        cursor: default;
    }

    .barber-header {
        display: flex;
        align-items: center;
        gap: 12px;
        padding: 14px 16px;
        background: #fff;
        border: 1px solid var(--line);
        border-radius: 8px;
        margin-bottom: 14px;
    }

    .barber-header img {
        width: 48px;
        height: 48px;
        border-radius: 50%;
        object-fit: cover;
    }

    .barber-header h3 {
        font-size: 15px;
        font-weight: 700;
        margin: 0;
    }

    .barber-header span {
        font-size: 11px;
        color: var(--muted);
    }

    .info-card,
    .summary-card {
        background: #fff;
        border: 1px solid var(--line);
        border-radius: 10px;
        padding: 18px;
        margin: 14px 0;
    }

    .info-card h3,
    .summary-card h3 {
        font-size: 14px;
        font-weight: 700;
        letter-spacing: .02em;
        margin: 0 0 10px;
    }

    .info-card label,
    .summary-card label {
        display: block;
        font-size: 11px;
        font-weight: 700;
        letter-spacing: .04em;
        margin: 8px 0 4px;
        color: #3a3229;
    }

    .info-card input,
    .info-card textarea {
        width: 100%;
        padding: 10px 12px;
        border: 1px solid var(--line);
        border-radius: 6px;
        font-family: inherit;
        font-size: 14px;
        background: #fdfcfb;
    }

    .info-card input:focus,
    .info-card textarea:focus {
        border-color: var(--red);
        outline: none;
    }

    .policy {
        font-size: 11px;
        line-height: 1.65;
        color: #665a50;
        border-top: 1px dashed var(--line);
        margin-top: 12px;
        padding-top: 10px;
    }

    .policy p {
        margin: 4px 0;
    }

    .wizard-nav {
        display: flex;
        justify-content: space-between;
        align-items: center;
        gap: 12px;
        margin-top: 22px;
        padding-top: 14px;
        border-top: 1px solid var(--line);
    }

    .wizard-nav button,
    .wizard-nav a {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        padding: 10px 20px;
        font-size: 11px;
        font-weight: 700;
        letter-spacing: .03em;
        border-radius: 6px;
        text-decoration: none;
        border: 1px solid var(--red);
        color: var(--red);
        background: #fff;
        cursor: pointer;
        transition: .15s;
    }

    .wizard-nav button:hover,
    .wizard-nav a:hover {
        background: var(--red);
        color: #fff;
    }

    .wizard-nav button[type="submit"],
    .wizard-nav .primary {
        background: var(--red);
        color: #fff;
        border-color: var(--red);
    }

    .hidden {
        display: none !important;
    }
</style>
@endpush

@push('scripts')
<script>
    let selectedBarber = null;
    let selectedSchedule = null;
    let selectedDate = null;

    function selectBarber(el) {
        document.querySelectorAll('.barber-card.selectable').forEach(b => b.classList.remove('is-selected'));
        el.classList.add('is-selected');
        selectedBarber = {
            id: el.dataset.id,
            name: el.dataset.name,
            photo: el.querySelector('img').src
        };
        document.getElementById('barber_id').value = el.dataset.id;
        document.getElementById('service_id').value = document.querySelector('input[name="service_id"]')?.value || '1';
        document.getElementById('step-1').classList.add('hidden');
        document.getElementById('step-2').classList.remove('hidden');
        updateStepIndicator(2);
        renderBarberHeader();
        loadSchedules();
    }

    function renderBarberHeader() {
        if (!selectedBarber) return;
        document.getElementById('barber-header').innerHTML = `
        <img src="${selectedBarber.photo}" alt="${selectedBarber.name}">
        <div>
            <p>Barber yang dipilih</p>
        </div>
    `;
    }

    function selectDate(el, dateStr) {
        document.querySelectorAll('.date-pill').forEach(d => d.classList.remove('is-today'));
        el.classList.add('is-today');
        selectedDate = dateStr;
        document.getElementById('date').value = dateStr;
        loadSchedules();
    }

    function loadSchedules() {
        if (!selectedBarber || !selectedDate) return;
        fetch(`/api/booking/schedules?barber_id=${selectedBarber.id}&date=${selectedDate}`)
            .then(r => r.ok ? r.json() : [])
            .then(slots => {
                if (!slots || slots.length === 0) {
                    // Fallback: generate static slots when no API data
                    const base = [{
                            id: 1,
                            start_time: '15:00',
                            is_available: true
                        },
                        {
                            id: 2,
                            start_time: '15:30',
                            is_available: true
                        },
                        {
                            id: 3,
                            start_time: '16:00',
                            is_available: true
                        },
                        {
                            id: 4,
                            start_time: '16:30',
                            is_available: true
                        },
                        {
                            id: 5,
                            start_time: '17:00',
                            is_available: true
                        },
                        {
                            id: 6,
                            start_time: '17:30',
                            is_available: true
                        },
                    ];
                    document.getElementById('sesi-sore').innerHTML = base.map(s => slotHtml(s)).join('');
                    document.getElementById('sesi-istirahat').innerHTML = [{
                            id: 11,
                            start_time: '18:00',
                            is_available: false
                        },
                        {
                            id: 12,
                            start_time: '18:30',
                            is_available: false
                        },
                        {
                            id: 13,
                            start_time: '19:00',
                            is_available: false
                        },
                    ].map(s => slotHtml(s)).join('');
                    document.getElementById('sesi-malam').innerHTML = [{
                            id: 7,
                            start_time: '19:30',
                            is_available: true
                        },
                        {
                            id: 8,
                            start_time: '20:00',
                            is_available: true
                        },
                        {
                            id: 9,
                            start_time: '20:30',
                            is_available: true
                        },
                        {
                            id: 10,
                            start_time: '21:00',
                            is_available: true
                        },
                    ].map(s => slotHtml(s)).join('');
                    return;
                }
                const sore = slots.filter(s => s.start_time < '18:00');
                const istirahat = slots.filter(s => s.start_time >= '18:00' && s.start_time < '19:30');
                const malam = slots.filter(s => s.start_time >= '19:30');
                document.getElementById('sesi-sore').innerHTML = sore.map(s => slotHtml(s)).join('');
                document.getElementById('sesi-istirahat').innerHTML = istirahat.map(s => slotHtml(s)).join('');
                document.getElementById('sesi-malam').innerHTML = malam.map(s => slotHtml(s)).join('');
            })
            .catch(() => {
                const base = [{
                        id: 1,
                        start_time: '15:00',
                        is_available: true
                    },
                    {
                        id: 2,
                        start_time: '15:30',
                        is_available: true
                    },
                    {
                        id: 3,
                        start_time: '16:00',
                        is_available: true
                    },
                    {
                        id: 4,
                        start_time: '16:30',
                        is_available: true
                    },
                    {
                        id: 5,
                        start_time: '17:00',
                        is_available: true
                    },
                    {
                        id: 6,
                        start_time: '17:30',
                        is_available: true
                    },
                ];
                document.getElementById('sesi-sore').innerHTML = base.map(s => slotHtml(s)).join('');
                document.getElementById('sesi-istirahat').innerHTML = [{
                        id: 11,
                        start_time: '18:00',
                        is_available: false
                    },
                    {
                        id: 12,
                        start_time: '18:30',
                        is_available: false
                    },
                    {
                        id: 13,
                        start_time: '19:00',
                        is_available: false
                    },
                ].map(s => slotHtml(s)).join('');
                document.getElementById('sesi-malam').innerHTML = [{
                        id: 7,
                        start_time: '19:30',
                        is_available: true
                    },
                    {
                        id: 8,
                        start_time: '20:00',
                        is_available: true
                    },
                    {
                        id: 9,
                        start_time: '20:30',
                        is_available: true
                    },
                    {
                        id: 10,
                        start_time: '21:00',
                        is_available: true
                    },
                ].map(s => slotHtml(s)).join('');
            });
    }

    function slotHtml(s) {
        const rest = s.start_time >= '18:00' && s.start_time < '19:30';
        const booked = !s.is_available || rest;
        return `<button type="button" class="time-slot ${booked ? 'is-booked' : ''}" onclick="selectSlot(this, ${s.id}, '${s.start_time}')">${s.start_time}</button>`;
    }

    function selectSlot(el, scheduleId, timeStr) {
        document.querySelectorAll('.time-slot').forEach(s => s.classList.remove('is-selected'));
        if (el.classList.contains('is-booked')) return;
        el.classList.add('is-selected');
        selectedSchedule = scheduleId;
        document.getElementById('schedule_id').value = scheduleId;
    }

    function confirmBooking() {
        if (!selectedSchedule) {
            alert('Pilih jam dan hari terlebih dahulu.');
            return;
        }
        document.getElementById('step-2').classList.add('hidden');
        document.getElementById('step-3').classList.remove('hidden');
        updateStepIndicator(3);
        renderSummary();
    }

    function renderSummary() {
        const barberName = selectedBarber ? selectedBarber.name : '-';
        const timeText = document.querySelector('.time-slot.is-selected') ? document.querySelector('.time-slot.is-selected')
            .innerText : '-';
        document.getElementById('summary-barber').innerHTML = `<p><strong>Barber</strong> ${barberName}</p>`;
        document.getElementById('summary-schedule').innerHTML =
            `<p><strong>Jadwal</strong> ${selectedDate}, ${timeText}</p>`;
        document.getElementById('summary-service').innerHTML =
            `<p><strong>Layanan</strong> Skin Fade, Classic Scissor & Beard · 30 Menit</p>`;
    }

    function prevStep(step) {
        document.getElementById('step-' + step).classList.remove('hidden');
        document.getElementById('step-' + (step + 1)).classList.add('hidden');
        updateStepIndicator(step);
        if (step === 1) {
            selectedBarber = null;
            selectedSchedule = null;
            selectedDate = null;
            document.getElementById('barber_id').value = '';
            document.getElementById('schedule_id').value = '';
            document.querySelectorAll('.barber-card.selectable').forEach(b => b.classList.remove('is-selected'));
        }
    }

    function updateStepIndicator(n) {
        document.querySelectorAll('.booking-stepper li').forEach(li => {
            const step = parseInt(li.querySelector('span').innerText);
            li.classList.remove('is-current', 'is-complete');
            li.setAttribute('aria-current', 'false');
            if (step < n) li.classList.add('is-complete');
            if (step === n) {
                li.classList.add('is-current');
                li.setAttribute('aria-current', 'step');
            }
        });
    }

    // Auto-select first barber and first available date
    window.addEventListener('load', () => {
        document.querySelectorAll('.date-pill').forEach((d, i) => {
            if (i === 0) d.classList.add('is-today');
            selectedDate = d.dataset.date;
            document.getElementById('date').value = d.dataset.date;
        });
        setTimeout(() => loadSchedules(), 300);
    });
</script>
@endpush