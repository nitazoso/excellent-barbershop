@extends('layouts.app')

@section('title', 'Jadwal Waktu — Excellent Barbershop')

@section('content')
<section class="booking-page">
    <span class="section-kicker">BOOKING ONLINE — LANGKAH 2</span>
    <h1>Jadwal Waktu</h1>

    <div class="barber-header">
        <img src="{{ $barber->photo }}" alt="{{ $barber->name }}" style="width:48px;height:48px;border-radius:50%;object-fit:cover;">
        <div>
            <h3>{{ $barber->name }}</h3>
            <p style="font-size:11px;color:#665a50;margin:0;">Barber yang dipilih</p>
        </div>
    </div>

    <div style="background:#fff;border:1px solid var(--line);border-radius:10px;padding:10px 12px;margin:10px 0;">
        <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:10px;">
            <a href="{{ route('booking.schedule', ['barber_id' => $barber->id, 'date' => $dateObj->copy()->subMonth()->format('Y-m-d')]) }}" style="padding:4px 8px;font-size:14px;color:var(--red);text-decoration:none;font-weight:700;border:1px solid var(--line);border-radius:6px;background:#fff;">←</a>
            <strong style="font-family:var(--display);font-size:16px;letter-spacing:-.02em;">{{ $dateObj->format('F Y') }}</strong>
            <a href="{{ route('booking.schedule', ['barber_id' => $barber->id, 'date' => $dateObj->copy()->addMonth()->format('Y-m-d')]) }}" style="padding:4px 8px;font-size:14px;color:var(--red);text-decoration:none;font-weight:700;border:1px solid var(--line);border-radius:6px;background:#fff;">→</a>
        </div>
        <div style="display:grid;grid-template-columns:repeat(7,1fr);gap:6px;text-align:center;font-size:10px;font-weight:700;color:#665a50;text-transform:uppercase;letter-spacing:.05em;margin-bottom:6px;">
            <span>Min</span><span>Sen</span><span>Sel</span><span>Rab</span><span>Kam</span><span>Jum</span><span>Sab</span>
        </div>
        <div style="display:grid;grid-template-columns:repeat(7,1fr);gap:6px;">
            @php
                $year = $dateObj->year;
                $month = $dateObj->month;
                $daysInMonth = $dateObj->daysInMonth;
                $startDay = (int) $dateObj->copy()->startOfMonth()->format('w');
            @endphp
            @for ($i = 0; $i < $startDay; $i++)
                <div></div>
            @endfor
            @for ($d = 1; $d <= $daysInMonth; $d++)
                @php $dt = $dateObj->copy()->day($d); @endphp
                <a href="{{ route('booking.schedule', ['barber_id' => $barber->id, 'date' => $dt->format('Y-m-d')]) }}" class="date-pill {{ $dt->format('Y-m-d') == $dateObj->format('Y-m-d') ? 'is-selected' : '' }} {{ $dt->format('Y-m-d') == today()->format('Y-m-d') ? 'is-today' : '' }}" style="{{ $dt->format('Y-m-d') == $dateObj->format('Y-m-d') ? 'background:var(--red);border-color:var(--red);color:#fff;' : 'background:#fdfcfb;border:1px solid var(--line);color:#3a3229;' }} border-radius:8px;padding:8px 2px;text-align:center;font-family:var(--display);font-size:12px;font-weight:700;text-decoration:none;transition:.15s;display:block;min-height:36px;line-height:20px;">
                    {{ $d }}
                </a>
            @endfor
        </div>
    </div>

    <form method="GET" action="{{ route('booking.confirm') }}" id="schedule-form">
        <input type="hidden" name="barber_id" value="{{ $barber->id }}">
        <input type="hidden" name="date" id="date-field" value="{{ $dateObj->format('Y-m-d') }}">

        <div class="session-block" style="background:#faf9f6;border:1px solid var(--line);border-radius:10px;padding:16px 18px;margin:14px 0;">
            <h3 style="font-size:13px;font-weight:700;letter-spacing:.02em;margin:0 0 10px;color:#3a3229;">Sesi siang (15.00 – 18.00 WIB)</h3>
            <div class="time-grid" id="sesi-sore"></div>
        </div>
        <div class="session-block" style="background:#faf9f6;border:1px solid var(--line);border-radius:10px;padding:16px 18px;margin:14px 0;">
            <h3 style="font-size:13px;font-weight:700;letter-spacing:.02em;margin:0 0 10px;color:#3a3229;">Waktu Istirahat (18.00 – 19.30 WIB)</h3>
            <div class="time-grid" id="sesi-istirahat"></div>
        </div>
        <div class="session-block" style="background:#faf9f6;border:1px solid var(--line);border-radius:10px;padding:16px 18px;margin:14px 0;">
            <h3 style="font-size:13px;font-weight:700;letter-spacing:.02em;margin:0 0 10px;color:#3a3229;">Sesi Malam (19.30 – 21.00 WIB)</h3>
            <div class="time-grid" id="sesi-malam"></div>
        </div>

        <input type="hidden" name="schedule_id" id="schedule_id" required>
        <input type="hidden" name="service_id" value="1">

        <div style="display:flex;justify-content:space-between;align-items:center;gap:12px;margin-top:22px;padding-top:14px;border-top:1px solid var(--line);">
            <a href="{{ route('booking.index') }}" style="padding:10px 20px;font-size:11px;font-weight:700;letter-spacing:.03em;border-radius:6px;text-decoration:none;border:1px solid var(--red);color:var(--red);background:#fff;">← Kembali</a>
            <button type="submit" class="primary" style="padding:10px 20px;font-size:11px;font-weight:700;letter-spacing:.03em;border-radius:6px;border:1px solid var(--red);background:var(--red);color:#fff;cursor:pointer;">Lanjut Pilih Jadwal & Jam →</button>
        </div>
    </form>
</section>

@push('scripts')
<script>
    let selectedSlot = null;
    function slotHtml(s) {
        const rest = s.start_time >= '18:00' && s.start_time < '19:30';
        const booked = !s.is_available || rest;
        return `<button type="button" class="time-slot ${booked ? 'is-booked' : ''}" onclick="selectSlot(this, ${s.id}, '${s.start_time}')" ${booked ? 'disabled' : ''}>${s.start_time}</button>`;
    }
    function selectSlot(el, scheduleId, timeStr) {
        if (el.classList.contains('is-booked')) return;
        document.querySelectorAll('.time-slot').forEach(s => { if(!s.classList.contains('is-booked')) { s.style.background = '#fff'; s.style.borderColor = 'var(--line)'; s.style.color = '#3a3229'; } });
        el.style.background = 'var(--red)';
        el.style.borderColor = 'var(--red)';
        el.style.color = '#fff';
        selectedSlot = scheduleId;
        document.getElementById('schedule_id').value = scheduleId;
    }
    function loadSchedules() {
        const now = new Date();
        const currentHour = now.getHours() + now.getMinutes()/60;
        const isToday = '{{ $dateObj->format('Y-m-d') }}' === new Date().toISOString().split('T')[0];
        fetch(`/api/booking/schedules?barber_id={{ $barber->id }}&date={{ $dateObj->format('Y-m-d') }}`)
            .then(r => r.ok ? r.json() : [])
            .then(slots => {
                if (!slots || !slots.length) {
                    const fallback = [
                        {id:1,start_time:'15:00',is_available:true},{id:2,start_time:'15:30',is_available:true},
                        {id:3,start_time:'16:00',is_available:true},{id:4,start_time:'16:30',is_available:true},
                        {id:5,start_time:'17:00',is_available:true},{id:6,start_time:'17:30',is_available:true},
                        {id:7,start_time:'19:30',is_available:true},{id:8,start_time:'20:00',is_available:true},
                        {id:9,start_time:'20:30',is_available:true},{id:10,start_time:'21:00',is_available:true}
                    ];
                    const processed = fallback.map(s => {
                        if (isToday) {
                            const [h, m] = s.start_time.split(':').map(Number);
                            if (h + m/60 < currentHour) s.is_available = false;
                        }
                        return s;
                    });
                    const sore = processed.filter(s => s.start_time < '18:00');
                    const istirahat = fallback.filter(s => s.start_time >= '18:00' && s.start_time < '19:30');
                    const malam = fallback.filter(s => s.start_time >= '19:30');
                    document.getElementById('sesi-sore').innerHTML = sore.map(slotHtml).join('');
                    document.getElementById('sesi-istirahat').innerHTML = processed.filter(s => s.start_time >= '18:00' && s.start_time < '19:30').map(s => `<button type="button" disabled class="time-slot is-booked" style="background:#ecece8;color:#b5aca3;border-color:#ecece8;cursor:default;border-radius:6px;padding:10px 4px;font-size:12px;font-weight:600;text-align:center;">${s.start_time}</button>`).join('');
                    document.getElementById('sesi-malam').innerHTML = processed.filter(s => s.start_time >= '19:30').map(slotHtml).join('');
                    return;
                }
                const processed = slots.map(s => {
                    if (isToday) {
                        const [h, m] = s.start_time.split(':').map(Number);
                        if (h + m/60 < currentHour) s.is_available = false;
                    }
                    return s;
                });
                const sore = processed.filter(s => s.start_time < '18:00');
                const istirahat = processed.filter(s => s.start_time >= '18:00' && s.start_time < '19:30');
                const malam = processed.filter(s => s.start_time >= '19:30');
                document.getElementById('sesi-sore').innerHTML = sore.map(slotHtml).join('');
                document.getElementById('sesi-istirahat').innerHTML = istirahat.map(s => `<button type="button" disabled class="time-slot is-booked" style="background:#ecece8;color:#b5aca3;border-color:#ecece8;cursor:default;border-radius:6px;padding:10px 4px;font-size:12px;font-weight:600;text-align:center;">${s.start_time}</button>`).join('');
                document.getElementById('sesi-malam').innerHTML = malam.map(slotHtml).join('');
            })
            .catch(() => {
                const base = [{id:1,start_time:'15:00',is_available:true},{id:2,start_time:'15:30',is_available:true},{id:3,start_time:'16:00',is_available:true},{id:4,start_time:'16:30',is_available:true},{id:5,start_time:'17:00',is_available:true},{id:6,start_time:'17:30',is_available:true}];
                document.getElementById('sesi-sore').innerHTML = base.map(slotHtml).join('');
                document.getElementById('sesi-istirahat').innerHTML = [{start_time:'18:00'},{start_time:'18:30'},{start_time:'19:00'}].map(s => `<button disabled class="time-slot is-booked" style="background:#ecece8;color:#b5aca3;border-color:#ecece8;cursor:default;border-radius:6px;padding:10px 4px;font-size:12px;font-weight:600;text-align:center;">${s.start_time}</button>`).join('');
                document.getElementById('sesi-malam').innerHTML = [{id:7,start_time:'19:30',is_available:true},{id:8,start_time:'20:00',is_available:true},{id:9,start_time:'20:30',is_available:true},{id:10,start_time:'21:00',is_available:true}].map(slotHtml).join('');
            });
    }
    window.addEventListener('load', loadSchedules);
</script>
@endpush

@push('styles')
<style>
.time-grid { display:grid;grid-template-columns:repeat(auto-fill,minmax(84px,1fr));gap:8px; }
.time-slot { background:#fff;border:1px solid var(--line);border-radius:6px;padding:10px 4px;font-size:12px;font-weight:600;text-align:center;cursor:pointer;transition:.15s; }
.time-slot:hover { border-color:var(--red);color:var(--red); }
.time-slot.is-selected { background:var(--red);border-color:var(--red);color:#fff; }
.time-slot.is-booked { background:#ecece8;color:#b5aca3;border-color:#ecece8;cursor:default; }
</style>
@endpush
