@extends('layouts.admin')

@section('title', 'Dashboard Admin — Excellent Barbershop')

@push('styles')
    <link rel="stylesheet" href="/css/admin-dashboard.css">
@endpush

@section('content')
    @if (session('success'))
        <div class="dashboard-flash dashboard-flash-success" role="status">{{ session('success') }}</div>
    @endif

    @if (session('error'))
        <div class="dashboard-flash dashboard-flash-error" role="alert">{{ session('error') }}</div>
    @endif

    <section class="dashboard-stats" aria-label="Ringkasan booking">
        <article class="dashboard-stat-card dashboard-stat-total">
            <div><span>Total booking</span><span class="dashboard-stat-icon"><svg viewBox="0 0 24 24" aria-hidden="true"><rect x="4" y="3" width="16" height="18" rx="2"/><path d="M8 7h8m-8 4h8m-8 4h5"/></svg></span></div>
            <strong>{{ $totalBookingsCount }}</strong>
            <small>Semua reservasi</small>
        </article>
        <article class="dashboard-stat-card dashboard-stat-pending">
            <div><span>Menunggu</span><span class="dashboard-stat-icon"><svg viewBox="0 0 24 24" aria-hidden="true"><circle cx="12" cy="12" r="9"/><path d="M12 7v5l3 2"/></svg></span></div>
            <strong>{{ $pendingBookingsCount }}</strong>
            <small>Perlu dikonfirmasi</small>
        </article>
        <article class="dashboard-stat-card dashboard-stat-today">
            <div><span>Hari ini</span><span class="dashboard-stat-icon"><svg viewBox="0 0 24 24" aria-hidden="true"><rect x="3" y="5" width="18" height="16" rx="2"/><path d="M16 3v4M8 3v4M3 10h18"/></svg></span></div>
            <strong>{{ $todayBookingsCount }}</strong>
            <small>Jadwal booking</small>
        </article>
        <article class="dashboard-stat-card dashboard-stat-confirmed">
            <div><span>Terkonfirmasi</span><span class="dashboard-stat-icon"><svg viewBox="0 0 24 24" aria-hidden="true"><circle cx="12" cy="12" r="9"/><path d="m8 12 2.5 2.5L16 9"/></svg></span></div>
            <strong>{{ $confirmedTodayBookingsCount }}</strong>
            <small>Booking hari ini</small>
        </article>
    </section>

    <nav class="dashboard-quick-links" aria-label="Akses cepat">
        <a href="{{ route('admin.bookings') }}"><svg viewBox="0 0 24 24" aria-hidden="true"><rect x="3" y="5" width="18" height="16" rx="2"/><path d="M16 3v4M8 3v4M3 10h18"/></svg> Booking</a>
        <a href="{{ route('admin.admins.index') }}"><svg viewBox="0 0 24 24" aria-hidden="true"><circle cx="9" cy="8" r="4"/><path d="M2 21v-2a7 7 0 0 1 14 0v2M18 8h5m-2.5-2.5v5"/></svg> Kelola Admin</a>
    </nav>

    <section class="dashboard-section">
        <div class="dashboard-section-heading">
            <div>
                <span class="dashboard-eyebrow">PERLU TINDAKAN</span>
                <h2>Antrean Perlu Ditinjau Segera</h2>
            </div>
            <span class="dashboard-pending-count">{{ $pendingBookingsCount }} Permintaan</span>
        </div>

        <div class="dashboard-booking-list">
            @forelse ($pendingBookings as $booking)
                <article class="dashboard-booking-card">
                    <div class="dashboard-booking-card-heading">
                        <div>
                            <span class="dashboard-booking-code">{{ $booking->booking_code }}</span>
                            <h3>{{ $booking->customer->name }}</h3>
                        </div>
                        <span class="dashboard-booking-age"><span aria-hidden="true"></span>{{ $booking->created_at->locale('id')->diffForHumans() }}</span>
                    </div>
                    <p class="dashboard-booking-meta">{{ $booking->customer->phone }} · {{ $booking->service->name }}</p>
                    <div class="dashboard-booking-schedule">
                        <span>Waktu &amp; Kursi</span>
                        <strong>{{ substr($booking->start_time, 0, 5) }} · {{ $booking->barber->name }}</strong>
                        <small>{{ $booking->booking_date->translatedFormat('d M Y') }}</small>
                    </div>
                    <div class="dashboard-booking-actions">
                        <form action="{{ route('admin.bookings.reject', $booking) }}" method="POST">
                            @csrf
                            @method('PATCH')
                            <input type="hidden" name="rejection_reason" value="">
                            <button class="dashboard-reject-button" type="button" onclick="rejectDashboardBooking(this)">TOLAK</button>
                        </form>
                        <form action="{{ route('admin.bookings.confirm', $booking) }}" method="POST">
                            @csrf
                            @method('PATCH')
                            <input type="hidden" name="status" value="confirmed">
                            <button class="dashboard-confirm-button" type="submit"><span aria-hidden="true">✓</span> KONFIRMASI</button>
                        </form>
                    </div>
                </article>
            @empty
                <div class="dashboard-empty-state">
                    <span class="dashboard-empty-icon" aria-hidden="true"><svg viewBox="0 0 24 24"><path d="m5 12 4 4L19 6"/></svg></span>
                    <strong>Semua booking sudah ditangani</strong>
                    <p>Booking baru yang menunggu konfirmasi akan muncul di sini.</p>
                </div>
            @endforelse
        </div>

        @if ($pendingBookingsCount > 0)
            <form class="dashboard-confirm-all-form" action="{{ route('admin.bookings.confirm-pending') }}" method="POST">
                @csrf
                <button class="dashboard-confirm-all-button" type="submit">
                    <span aria-hidden="true">✓</span> KONFIRMASI SEMUA ({{ $pendingBookingsCount }} BOOKING)
                </button>
            </form>
        @endif
    </section>

    <section class="dashboard-section dashboard-today-section">
        <div class="dashboard-section-heading">
            <div>
                <span class="dashboard-eyebrow">JADWAL OPERASIONAL</span>
                <h2>Jadwal Booking Hari Ini</h2>
            </div>
            <span class="dashboard-today-count">{{ $todayBookingsCount }} booking</span>
        </div>

        @php($todayBarbers = $todayBookings->pluck('barber')->filter()->unique('id')->values())
        <nav class="dashboard-today-filters" aria-label="Filter jadwal berdasarkan barber">
            <button class="is-active" type="button" data-barber-filter="all">Semua Kursi</button>
            @foreach ($todayBarbers as $barber)
                <button type="button" data-barber-filter="{{ $barber->id }}">
                    {{ $barber->name }} ({{ str_pad((string) $loop->iteration, 2, '0', STR_PAD_LEFT) }})
                </button>
            @endforeach
        </nav>

        <div class="dashboard-today-list">
            @forelse ($todayBookings as $booking)
                <article class="dashboard-today-item" data-barber-id="{{ $booking->barber_id }}">
                    <time>{{ substr($booking->start_time, 0, 5) }}</time>
                    <div class="dashboard-today-details">
                        <strong>{{ $booking->customer->name }}</strong>
                        <span>{{ $booking->service->name }} · {{ $booking->barber->name }}</span>
                    </div>
                    <span class="dashboard-status dashboard-status-{{ $booking->status }}">{{ ucfirst($booking->status) }}</span>
                </article>
            @empty
                <div class="dashboard-empty-state dashboard-empty-today">
                    <strong>Belum ada booking hari ini</strong>
                    <p>Jadwal reservasi hari ini akan tampil di sini.</p>
                </div>
            @endforelse
        </div>
    </section>
@endsection

@push('scripts')
    <script>
        document.querySelectorAll('.dashboard-today-filters [data-barber-filter]').forEach((filterButton) => {
            filterButton.addEventListener('click', () => {
                const selectedBarberId = filterButton.dataset.barberFilter;

                document.querySelectorAll('.dashboard-today-filters [data-barber-filter]').forEach((button) => {
                    button.classList.toggle('is-active', button === filterButton);
                });

                document.querySelectorAll('.dashboard-today-item[data-barber-id]').forEach((bookingItem) => {
                    bookingItem.hidden = selectedBarberId !== 'all'
                        && bookingItem.dataset.barberId !== selectedBarberId;
                });
            });
        });

        function rejectDashboardBooking(button) {
            const rejectionReason = window.prompt('Masukkan alasan penolakan booking:');

            if (!rejectionReason?.trim()) {
                return;
            }

            button.form.elements.rejection_reason.value = rejectionReason.trim();
            button.form.requestSubmit();
        }
    </script>
@endpush
