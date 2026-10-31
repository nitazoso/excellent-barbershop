@extends('layouts.admin')

@section('title', 'Kelola Booking — Admin Excellent Barbershop')

@push('styles')
<style>
    .admin-alert {
        padding: 12px 16px;
        border-radius: 6px;
        margin-bottom: 20px;
        font-size: 13px;
        font-weight: 500;
        display: flex;
        justify-content: space-between;
        align-items: center;
    }
    .admin-alert-success {
        background-color: #ecfdf5;
        border: 1px solid #a7f3d0;
        color: #065f46;
    }
    .admin-alert-error {
        background-color: #fef2f2;
        border: 1px solid #fecaca;
        color: #991b1b;
    }

    .summary-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(180px, 1fr));
        gap: 14px;
        margin-bottom: 24px;
    }
    .summary-card {
        background: #fff;
        border: 1px solid #e7e2da;
        border-radius: 8px;
        padding: 16px;
    }
    .summary-card span {
        display: block;
        font-size: 11px;
        font-weight: 700;
        color: #8d8379;
        text-transform: uppercase;
        letter-spacing: .05em;
        margin-bottom: 6px;
    }
    .summary-card strong {
        font-size: 22px;
        color: #211f1c;
    }
    .summary-card.highlight strong {
        color: #a93822;
    }
    .summary-card.confirmed strong {
        color: #059669;
    }
    .summary-card.pending strong {
        color: #d97706;
    }
    .summary-card.rejected strong {
        color: #dc2626;
    }

    .filter-panel {
        background: #fff;
        border: 1px solid #e7e2da;
        border-radius: 8px;
        padding: 18px 20px;
        margin-bottom: 24px;
    }
    .filter-form {
        display: flex;
        flex-wrap: wrap;
        gap: 14px;
        align-items: flex-end;
    }
    .filter-group {
        display: flex;
        flex-direction: column;
        gap: 6px;
        min-width: 140px;
    }
    .filter-group label {
        font-size: 11px;
        font-weight: 700;
        color: #6d655d;
        text-transform: uppercase;
    }
    .filter-group input,
    .filter-group select {
        height: 38px;
        padding: 0 10px;
        border: 1px solid #ded8cf;
        border-radius: 5px;
        font: inherit;
        font-size: 13px;
        background: #fff;
    }
    .filter-actions {
        display: flex;
        gap: 8px;
        align-items: center;
    }
    .btn {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        height: 38px;
        padding: 0 16px;
        border-radius: 5px;
        font-size: 12px;
        font-weight: 700;
        letter-spacing: .04em;
        text-decoration: none;
        cursor: pointer;
        border: none;
        transition: opacity 0.2s;
    }
    .btn:hover {
        opacity: 0.88;
    }
    .btn-primary {
        background: #a93822;
        color: #fff;
    }
    .btn-secondary {
        background: #f0ebe1;
        color: #4a443d;
        border: 1px solid #ded8cf;
    }
    .btn-success {
        background: #059669;
        color: #fff;
    }
    .btn-danger {
        background: #dc2626;
        color: #fff;
    }
    .btn-sm {
        height: 30px;
        padding: 0 10px;
        font-size: 11px;
    }

    .booking-list {
        display: flex;
        flex-direction: column;
        gap: 16px;
    }
    .booking-card {
        background: #fff;
        border: 1px solid #e7e2da;
        border-radius: 8px;
        padding: 20px;
        transition: border-color 0.2s;
    }
    .booking-card:hover {
        border-color: #d4ccc0;
    }
    .booking-header {
        display: flex;
        justify-content: space-between;
        align-items: flex-start;
        margin-bottom: 12px;
        border-bottom: 1px solid #f0ebe1;
        padding-bottom: 12px;
    }
    .booking-code {
        font-size: 18px;
        font-weight: 800;
        color: #211f1c;
        margin: 0;
    }
    .booking-meta {
        font-size: 12px;
        color: #8d8379;
        margin-top: 2px;
    }
    .booking-details {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
        gap: 12px;
        font-size: 13px;
        margin-bottom: 14px;
    }
    .detail-item strong {
        display: block;
        font-size: 10px;
        color: #8d8379;
        text-transform: uppercase;
        margin-bottom: 2px;
    }
    .badge {
        display: inline-block;
        padding: 4px 10px;
        border-radius: 9999px;
        font-size: 11px;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: .05em;
    }
    .badge-pending {
        background: #fef3c7;
        color: #92400e;
        border: 1px solid #fde68a;
    }
    .badge-confirmed {
        background: #d1fae5;
        color: #065f46;
        border: 1px solid #a7f3d0;
    }
    .badge-rejected {
        background: #fee2e2;
        color: #991b1b;
        border: 1px solid #fecaca;
    }
    .rejection-box {
        margin-top: 10px;
        padding: 10px 14px;
        background: #fef2f2;
        border-left: 3px solid #dc2626;
        border-radius: 4px;
        font-size: 12px;
        color: #991b1b;
    }
    .booking-actions {
        display: flex;
        gap: 10px;
        justify-content: flex-end;
        align-items: center;
        border-top: 1px solid #f0ebe1;
        padding-top: 14px;
        margin-top: 10px;
    }

    /* Modal rejection */
    .modal-overlay {
        display: none;
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background: rgba(0, 0, 0, 0.45);
        z-index: 100;
        align-items: center;
        justify-content: center;
    }
    .modal-overlay.active {
        display: flex;
    }
    .modal-box {
        background: #fff;
        border-radius: 8px;
        padding: 24px;
        max-width: 480px;
        width: 90%;
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.15);
    }
    .modal-box h3 {
        margin-top: 0;
        margin-bottom: 8px;
        font-size: 18px;
    }
    .modal-box p {
        font-size: 13px;
        color: #6d655d;
        margin-bottom: 16px;
    }
    .modal-box textarea {
        width: 100%;
        height: 90px;
        padding: 10px;
        border: 1px solid #ded8cf;
        border-radius: 5px;
        font: inherit;
        font-size: 13px;
        resize: vertical;
        box-sizing: border-box;
    }

    .admin-page-heading span,
    .admin-page-heading>a {
        color: #a87920;
    }
    .admin-main {
        background: #f5f8fc;
        color: #17243a;
        font-family: Inter, Arial, sans-serif;
    }
    .admin-page-heading h1 {
        color: #17243a;
        font-family: Inter, Arial, sans-serif;
        font-size: 20px;
        font-weight: 800;
        letter-spacing: -.03em;
    }
    .admin-page-heading p,
    .booking-meta,
    .detail-item strong,
    .filter-group label,
    .modal-box p {
        color: #63748b;
    }
    .summary-card,
    .filter-panel,
    .booking-card,
    .modal-box {
        border-color: #e6ebf2;
    }
    .summary-card span {
        color: #63748b;
    }
    .summary-card strong {
        color: #17243a;
        font-family: Inter, Arial, sans-serif;
        font-size: 26px;
    }
    .summary-card.highlight strong {
        color: #c99b3f;
    }
    .summary-card.confirmed strong {
        color: #258050;
    }
    .summary-card.pending strong {
        color: #a76517;
    }
    .summary-card.rejected strong {
        color: #b84b43;
    }
    .filter-group input,
    .filter-group select,
    .modal-box textarea {
        border-color: #e6ebf2;
        color: #17243a;
        font-family: Inter, Arial, sans-serif;
    }
    .btn-primary,
    .btn-success {
        background: #14233b;
        color: #fff;
    }
    .btn-secondary {
        border-color: #e6ebf2;
        background: #fff;
        color: #46566d;
    }
    .btn-danger {
        background: #e34d5d;
        color: #fff;
    }
    .booking-card:hover {
        border-color: #c99b3f;
    }
    .booking-header,
    .booking-actions {
        border-color: #e6ebf2;
    }
    .booking-code {
        color: #17243a;
        font-family: Inter, Arial, sans-serif;
        font-size: 14px;
    }
    .detail-item strong {
        font-size: 10px;
    }
    .badge-pending {
        border-color: #f0d391;
        background: #fff0d7;
        color: #a76517;
    }
    .badge-confirmed {
        border-color: #bde8cf;
        background: #e5f6ec;
        color: #258050;
    }
    .badge-rejected {
        border-color: #f2c5c3;
        background: #fff0ef;
        color: #b84b43;
    }
    .rejection-box {
        border-left-color: #b84b43;
        background: #fff0ef;
        color: #b84b43;
    }
    .booking-card > div[style],
    .modal-box label {
        color: #63748b !important;
    }
    .booking-actions > span[style*="#059669"] {
        color: #258050 !important;
    }
    .booking-actions > span[style*="#dc2626"],
    .modal-box label span {
        color: #b84b43 !important;
    }
    .admin-panel {
        border-color: #e6ebf2;
        background: #fff;
        color: #17243a;
    }
</style>
@endpush

@section('content')
    <div class="admin-page-heading">
        <div>
            <span>RESERVASI</span>
            <h1>Kelola Booking</h1>
            <p>Pantau jadwal, konfirmasi atau tolak pesanan, dan ekspor laporan reservasi pelanggan.</p>
        </div>
        <div style="display: flex; gap: 10px;">
            <a href="{{ route('admin.bookings.export-pdf', request()->query()) }}" class="btn btn-secondary">
                📄 EKSPOR PDF
            </a>
        </div>
    </div>

    @if (session('success'))
        <div class="admin-alert admin-alert-success">
            <span>✓ {{ session('success') }}</span>
        </div>
    @endif

    @if (session('error'))
        <div class="admin-alert admin-alert-error">
            <span>✕ {{ session('error') }}</span>
        </div>
    @endif

    @if ($errors->any())
        <div class="admin-alert admin-alert-error">
            <ul style="margin: 0; padding-left: 20px;">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    {{-- Ringkasan Statistik Rekap --}}
    <div class="summary-grid">
        <div class="summary-card">
            <span>Total Booking</span>
            <strong>{{ $summary['total'] }}</strong>
        </div>
        <div class="summary-card confirmed">
            <span>Dikonfirmasi</span>
            <strong>{{ $summary['confirmed'] }}</strong>
        </div>
        <div class="summary-card pending">
            <span>Menunggu Konfirmasi</span>
            <strong>{{ $summary['pending'] }}</strong>
        </div>
        <div class="summary-card rejected">
            <span>Ditolak</span>
            <strong>{{ $summary['rejected'] }}</strong>
        </div>
        <div class="summary-card highlight">
            <span>Total Estimasi Omset</span>
            <strong>Rp {{ number_format($summary['revenue'], 0, ',', '.') }}</strong>
        </div>
    </div>

    {{-- Filter Panel --}}
    <div class="filter-panel">
        <form method="GET" action="{{ route('admin.bookings') }}" class="filter-form">
            <div class="filter-group">
                <label for="start_date">Dari Tanggal</label>
                <input type="date" id="start_date" name="start_date" value="{{ request('start_date') }}">
            </div>
            <div class="filter-group">
                <label for="end_date">Sampai Tanggal</label>
                <input type="date" id="end_date" name="end_date" value="{{ request('end_date') }}">
            </div>
            <div class="filter-group">
                <label for="status">Status</label>
                <select id="status" name="status">
                    <option value="">Semua Status</option>
                    <option value="pending" {{ request('status') === 'pending' ? 'selected' : '' }}>Pending</option>
                    <option value="confirmed" {{ request('status') === 'confirmed' ? 'selected' : '' }}>Confirmed</option>
                    <option value="rejected" {{ request('status') === 'rejected' ? 'selected' : '' }}>Rejected</option>
                </select>
            </div>
            <div class="filter-group">
                <label for="barber_id">Barber</label>
                <select id="barber_id" name="barber_id">
                    <option value="">Semua Barber</option>
                    @foreach ($barbers as $barber)
                        <option value="{{ $barber->id }}" {{ (string) request('barber_id') === (string) $barber->id ? 'selected' : '' }}>
                            {{ $barber->name }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="filter-actions">
                <button type="submit" class="btn btn-primary">FILTER</button>
                <a href="{{ route('admin.bookings') }}" class="btn btn-secondary">RESET</a>
            </div>
        </form>
    </div>

    {{-- Daftar Booking --}}
    <section class="booking-list">
        @forelse ($bookings as $booking)
            <article class="booking-card">
                <div class="booking-header">
                    <div>
                        <h2 class="booking-code">{{ $booking->booking_code }}</h2>
                        <div class="booking-meta">
                            Dibuat pada {{ $booking->created_at->format('d M Y, H:i') }} WIB
                        </div>
                    </div>
                    <div>
                        @if($booking->status === 'confirmed')
                            <span class="badge badge-confirmed">Confirmed</span>
                        @elseif($booking->status === 'rejected')
                            <span class="badge badge-rejected">Rejected</span>
                        @else
                            <span class="badge badge-pending">Pending</span>
                        @endif
                    </div>
                </div>

                <div class="booking-details">
                    <div class="detail-item">
                        <strong>Pelanggan</strong>
                        {{ $booking->customer->name ?? '-' }} ({{ $booking->customer->phone ?? '-' }})
                    </div>
                    <div class="detail-item">
                        <strong>Jadwal Booking</strong>
                        {{ $booking->booking_date->format('d M Y') }} · {{ substr($booking->start_time, 0, 5) }} - {{ substr($booking->end_time, 0, 5) }}
                    </div>
                    <div class="detail-item">
                        <strong>Barber & Layanan</strong>
                        {{ $booking->barber->name ?? '-' }} — {{ $booking->service->name ?? '-' }}
                    </div>
                    <div class="detail-item">
                        <strong>Biaya Layanan</strong>
                        Rp {{ number_format($booking->service->price ?? 0, 0, ',', '.') }}
                    </div>
                </div>

                @if ($booking->notes)
                    <div style="font-size: 12px; color: #6d655d; margin-bottom: 8px;">
                        <strong>Catatan Pelanggan:</strong> {{ $booking->notes }}
                    </div>
                @endif

                @if ($booking->status === 'rejected' && $booking->rejection_reason)
                    <div class="rejection-box">
                        <strong>Alasan Penolakan:</strong> {{ $booking->rejection_reason }}
                    </div>
                @endif

                <div class="booking-actions">
                    @if ($booking->status === 'pending')
                        {{-- Form Konfirmasi --}}
                        <form method="POST" action="{{ route('admin.bookings.confirm', $booking) }}" onsubmit="return confirm('Apakah Anda yakin ingin mengonfirmasi booking ini?');">
                            @csrf
                            @method('PATCH')
                            <button type="submit" class="btn btn-success btn-sm">
                                ✓ KONFIRMASI
                            </button>
                        </form>

                        {{-- Tombol Tolak (Memicu Modal) --}}
                        <button type="button" class="btn btn-danger btn-sm" onclick="openRejectModal('{{ $booking->id }}', '{{ $booking->booking_code }}')">
                            ✕ TOLAK
                        </button>
                    @elseif ($booking->status === 'confirmed')
                        <span style="font-size: 11px; color: #059669; font-weight: 700;">SUDAH DIKONFIRMASI</span>
                        {{-- Opsi tolak jika diperlukan --}}
                        <button type="button" class="btn btn-secondary btn-sm" onclick="openRejectModal('{{ $booking->id }}', '{{ $booking->booking_code }}')">
                            Ubah ke Tolak
                        </button>
                    @else
                        <span style="font-size: 11px; color: #dc2626; font-weight: 700;">BOOKING DITOLAK</span>
                    @endif
                </div>
            </article>
        @empty
            <div class="admin-panel" style="text-align: center; padding: 48px 20px;">
                <h2>Tidak Ada Data Booking</h2>
                <p>Tidak ada reservasi yang ditemukan sesuai dengan kriteria filter yang dipilih.</p>
                <a href="{{ route('admin.bookings') }}" class="btn btn-secondary" style="margin-top: 12px;">Reset Filter</a>
            </div>
        @endforelse

        <div style="margin-top: 20px;">
            {{ $bookings->links() }}
        </div>
    </section>

    {{-- Modal Tolak Booking --}}
    <div id="rejectModal" class="modal-overlay">
        <div class="modal-box">
            <h3>Tolak Booking</h3>
            <p>Masukkan alasan penolakan untuk booking <strong id="modalBookingCode"></strong>:</p>
            <form id="rejectForm" method="POST" action="" data-reject-url-template="{{ route('admin.bookings.reject', ['booking' => '__BOOKING__']) }}">
                @csrf
                @method('PATCH')
                <div style="margin-bottom: 16px;">
                    <label for="rejection_reason" style="display: block; font-size: 11px; font-weight: 700; color: #6d655d; margin-bottom: 6px; text-transform: uppercase;">
                        Alasan Penolakan <span style="color: #dc2626;">*</span>
                    </label>
                    <textarea id="rejection_reason" name="rejection_reason" required placeholder="Contoh: Barber sedang berhalangan hadir / Jadwal telah terisi / Studio tutup..."></textarea>
                </div>
                <div style="display: flex; justify-content: flex-end; gap: 8px;">
                    <button type="button" class="btn btn-secondary" onclick="closeRejectModal()">Batal</button>
                    <button type="submit" class="btn btn-danger">Tolak Booking</button>
                </div>
            </form>
        </div>
    </div>
@endsection

@push('scripts')
<script>
    function openRejectModal(bookingId, bookingCode) {
        const modal = document.getElementById('rejectModal');
        const form = document.getElementById('rejectForm');
        const codeSpan = document.getElementById('modalBookingCode');
        const textarea = document.getElementById('rejection_reason');

        form.action = form.dataset.rejectUrlTemplate.replace('__BOOKING__', bookingId);
        codeSpan.innerText = '#' + bookingCode;
        textarea.value = '';
        modal.classList.add('active');
        textarea.focus();
    }

    function closeRejectModal() {
        const modal = document.getElementById('rejectModal');
        modal.classList.remove('active');
    }

    // Tutup modal jika klik di luar area modal-box
    window.addEventListener('click', function(event) {
        const modal = document.getElementById('rejectModal');
        if (event.target === modal) {
            closeRejectModal();
        }
    });
</script>
@endpush
