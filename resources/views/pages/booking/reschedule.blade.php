@extends('layouts.app')

@section('title', 'Ganti Jadwal — Excellent Barbershop')

@section('content')
<section class="booking-page">
    <div style="max-width:640px;margin:0 auto;padding:30px 24px;background:#fff;border:1px solid var(--line);border-radius:16px;box-shadow:0 2px 14px rgba(0,0,0,.06);">
        <h2 style="font-family:var(--display);font-size:22px;font-weight:800;letter-spacing:-.02em;color:#3a3229;margin:0 0 14px;display:flex;align-items:center;justify-content:space-between;">
            <span style="display:flex;align-items:center;gap:10px;">
                <span style="background:#ecc94b;color:#1a1a1a;width:36px;height:36px;border-radius:50%;display:inline-flex;align-items:center;justify-content:center;font-size:16px;font-weight:700;">↔</span>
                Ganti Jadwal
            </span>
            <a href="{{ route('booking.result', $booking->booking_code) }}" style="font-size:11px;font-weight:700;color:#7b7168;text-decoration:none;">✕</a>
        </h2>

        <div style="display:flex;align-items:center;gap:12px;padding:14px;background:#faf9f6;border:1px solid var(--line);border-radius:10px;margin-bottom:18px;">
            <img src="{{ $booking->barber->photo ?? 'https://images.unsplash.com/photo-1599351431207-50cf638f8a7f?q=80&w=200&h=200&auto=format&fit=crop' }}" alt="{{ $booking->barber->name }}" style="width:48px;height:48px;border-radius:50%;object-fit:cover;border:2px solid var(--gold);">
            <div>
                <h3 style="font-family:var(--display);font-size:18px;font-weight:700;color:#3a3229;margin:0 0 2px;">{{ $booking->barber->name }}</h3>
                <span style="font-size:11px;color:#7b7168;">Online</span>
            </div>
        </div>

        <form method="POST" action="{{ route('booking.reschedule.update', $booking->booking_code) }}">
            @csrf
            @php $dateObj = \Carbon\Carbon::parse(request('date', $booking->booking_date->format('Y-m-d'))); @endphp
            <label for="date" style="font-size:11px;font-weight:700;letter-spacing:.04em;color:#3a3229;margin-bottom:6px;display:block;">{{ $dateObj->format('F Y') }}</label>
            <div class="calendar" style="background:#f5f4f8;border-radius:16px;padding:18px;margin-bottom:18px;">
                <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:14px;">
                    <a href="{{ route('booking.reschedule', ['booking_code' => $booking->booking_code, 'date' => $dateObj->copy()->subMonth()->format('Y-m-d')]) }}" style="width:36px;height:36px;border-radius:50%;border:1px solid #ddd;background:#fff;color:#3a3229;font-size:18px;cursor:pointer;text-decoration:none;display:inline-flex;align-items:center;justify-content:center;">&lt;</a>
                    <h3 style="margin:0;font-family:var(--display);font-size:18px;font-weight:700;letter-spacing:-.02em;color:#222;">{{ $dateObj->format('F Y') }}</h3>
                    <a href="{{ route('booking.reschedule', ['booking_code' => $booking->booking_code, 'date' => $dateObj->copy()->addMonth()->format('Y-m-d')]) }}" style="width:36px;height:36px;border-radius:50%;border:1px solid #ddd;background:#fff;color:#3a3229;font-size:18px;cursor:pointer;text-decoration:none;display:inline-flex;align-items:center;justify-content:center;">&gt;</a>
                </div>
                <div style="display:flex;gap:6px;overflow-x:auto;padding-bottom:8px;scroll-behavior:smooth;margin-bottom:12px;">
                @php
                    $daysInMonth = $dateObj->daysInMonth;
                    $selectedDay = (int) $dateObj->format('j');
                    $startD = max(1, $selectedDay - 3);
                    $endD = $startD + 6;
                @endphp
                @for ($d = $startD; $d <= $endD; $d++)
                    @php $dt = $dateObj->copy()->day($d); @endphp
                    <a href="{{ route('booking.reschedule', ['booking_code' => $booking->booking_code, 'date' => $dt->format('Y-m-d')]) }}" id="cal-day-{{ $d }}" style="min-width:56px;padding:10px 0;background:{{ $dt->format('Y-m-d') == $dateObj->format('Y-m-d') ? '#ecc94b' : '#fff' }};border:1px solid #ddd;border-radius:14px;font-family:var(--display);font-size:20px;font-weight:700;color:{{ $dt->format('Y-m-d') == $dateObj->format('Y-m-d') ? '#1a1a1a' : '#333' }};text-align:center;cursor:pointer;text-decoration:none;transition:.15s;display:inline-block;">{{ $d }}</a>
                @endfor
            </div>
            <input type="hidden" name="schedule_id" id="schedule_id" value="{{ $booking->schedule_id }}" required>
            <input type="hidden" name="date" id="date-field" value="{{ $dateObj->format('Y-m-d') }}">

            <div id="session-label" style="font-size:13px;font-weight:700;color:#3a3229;margin-bottom:6px;text-align:center;">Sesi: {{ $booking->start_time >= '15:00' && $booking->start_time < '19:30' ? 'Sesi Sore' : ($booking->start_time >= '19:30' ? 'Sesi Malam' : 'Jam Istirahat Barber') }}</div>
            <div style="background:#2a1c14;color:#fff;padding:10px 14px;border-radius:8px;margin-bottom:14px;font-size:13px;font-weight:700;">● Sesi Sore (15.00 – 18.00 WIB)</div>
            <div style="display:grid;grid-template-columns:repeat(3,1fr);gap:8px;margin-bottom:14px;" id="sesi-sore">
                @php
                    $currentId = $booking->schedule_id ?? 3;
                    $soreSlots = [['id'=>1,'time'=>'15.00'],['id'=>2,'time'=>'15.30'],['id'=>3,'time'=>'16.00'],['id'=>4,'time'=>'16.30'],['id'=>5,'time'=>'17.00'],['id'=>6,'time'=>'17.30']];
                @endphp
                @foreach ($soreSlots as $slot)
                    @php
                        $isBooked = ($slot['id'] == $currentId && $dateObj->format('Y-m-d') == $booking->booking_date->format('Y-m-d'));
                        $labelText = $isBooked ? 'Booking Anda' : 'Tersedia';
                        $bg = $isBooked ? '#ecc94b' : '#fff';
                        $color = $isBooked ? '#1a1a1a' : '#3a3229';
                        $border = $isBooked ? '#ecc94b' : 'var(--line)';
                    @endphp
                    <button type="button" onclick="selectSlot(this, {{ $slot['id'] }}, '{{ $slot['time'] }}', true)" id="slot-{{ $slot['id'] }}" style="padding:10px 4px;background:{{ $bg }};border:1px solid {{ $border }};border-radius:6px;font-size:12px;font-weight:600;text-align:center;cursor:pointer;color:{{ $color }};">{{ $slot['time'] }}<br><span style="font-size:9px;font-weight:500;color:#777;">{{ $labelText }}</span></button>
                @endforeach
            </div>

            <div style="background:#ecc94b;color:#1a1a1a;padding:10px 14px;border-radius:8px;margin-bottom:14px;font-size:13px;font-weight:700;">● Jam Istirahat Barber (18.00 – 19.30 WIB)</div>
            <div style="display:grid;grid-template-columns:repeat(3,1fr);gap:8px;margin-bottom:14px;">
                @foreach ([['id'=>7,'time'=>'18.00'],['id'=>8,'time'=>'18.30'],['id'=>9,'time'=>'19.00']] as $slot)
                    <button type="button" disabled style="padding:10px 4px;background:#ecece8;color:#b5aca3;border:1px solid #ecece8;border-radius:6px;font-size:12px;font-weight:600;text-align:center;cursor:default;">{{ $slot['time'] }}</button>
                @endforeach
            </div>

            <div style="background:#2a1c14;color:#fff;padding:10px 14px;border-radius:8px;margin-bottom:14px;font-size:13px;font-weight:700;">● Sesi Malam (19.30 – 21.00 WIB)</div>
            <div style="display:grid;grid-template-columns:repeat(4,1fr);gap:8px;margin-bottom:18px;" id="sesi-malam">
                @php
                    $currentId = $booking->schedule_id ?? 3;
                    $malamSlots = [['id'=>10,'time'=>'19.30'],['id'=>11,'time'=>'20.00'],['id'=>12,'time'=>'20.30'],['id'=>13,'time'=>'21.00']];
                @endphp
                @foreach ($malamSlots as $slot)
                    @php
                        $isBooked = ($slot['id'] == $currentId && $dateObj->format('Y-m-d') == $booking->booking_date->format('Y-m-d'));
                        $labelText = $isBooked ? 'Booking Anda' : 'Slot Ada';
                        $bg = $isBooked ? '#ecc94b' : '#fff';
                        $color = $isBooked ? '#1a1a1a' : '#3a3229';
                        $border = $isBooked ? '#ecc94b' : 'var(--line)';
                    @endphp
                    <button type="button" onclick="selectSlot(this, {{ $slot['id'] }}, '{{ $slot['time'] }}', true)" id="slot-{{ $slot['id'] }}" style="padding:10px 4px;background:{{ $bg }};border:1px solid {{ $border }};border-radius:6px;font-size:12px;font-weight:600;text-align:center;cursor:pointer;color:{{ $color }};">{{ $slot['time'] }}<br><span style="font-size:9px;font-weight:500;color:#777;">{{ $labelText }}</span></button>
                @endforeach
            </div>

            <button type="submit" style="width:100%;padding:14px;font-size:13px;font-weight:700;letter-spacing:.04em;border-radius:8px;border:none;background:#2a1c14;color:#fff;cursor:pointer;margin-bottom:8px;">▶ Simpan Perubahan</button>
            <a href="{{ route('booking.result', $booking->booking_code) }}" style="display:block;text-align:center;padding:10px;font-size:11px;font-weight:700;color:#3a3229;text-decoration:none;background:#f4f1ec;border-radius:8px;">Batal</a>
        </form>
    </div>
</section>

@push('scripts')
<script>
    let selectedSlot = null;

    function selectSlot(el, id, timeStr, updateLabels) {
        document.querySelectorAll('#sesi-sore button, #sesi-malam button').forEach(s => {
            if (!s.hasAttribute('disabled')) {
                const btnId = parseInt(s.getAttribute('id').replace('slot-', ''));
                const isSore = btnId >= 1 && btnId <= 6;
                const label = isSore ? 'Tersedia' : 'Slot Ada';
                s.style.background = '#fff';
                s.style.borderColor = 'var(--line)';
                s.style.color = '#3a3229';
                const sub = s.querySelector('span');
                if (sub) sub.innerText = label;
            }
        });
        el.style.background = '#ecc94b';
        el.style.borderColor = '#ecc94b';
        el.style.color = '#1a1a1a';
        const sub = el.querySelector('span');
        if (sub) sub.innerText = 'Booking Anda';
        selectedSlot = id;
        document.getElementById('schedule_id').value = id;
        const label = document.getElementById('session-label');
        if (label) {
            if (id >= 1 && id <= 6) label.innerText = 'Sesi Sore';
            else if (id >= 7 && id <= 9) label.innerText = 'Jam Istirahat Barber';
            else if (id >= 10 && id <= 13) label.innerText = 'Sesi Malam';
        }
    }

    function selectCalendarDay(el, d) {
        document.querySelectorAll('[id^="cal-day-"]').forEach(b => {
            b.style.background = '#fff';
            b.style.color = '#333';
            b.style.borderColor = '#ddd';
        });
        el.style.background = '#ecc94b';
        el.style.color = '#1a1a1a';
        el.style.borderColor = '#ecc94b';
        const year = parseInt(document.getElementById('date-field').value.split('-')[0]);
        const month = parseInt(document.getElementById('date-field').value.split('-')[1]);
        const padded = d < 10 ? '0'+d : d;
        const paddedMonth = month < 10 ? '0'+month : month;
        document.getElementById('date-field').value = year + '-' + paddedMonth + '-' + padded;
    }
</script>
@endpush
@endsection