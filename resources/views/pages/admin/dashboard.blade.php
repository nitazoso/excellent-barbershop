@extends('layouts.admin')

@section('title', 'Dashboard — Excellent Atelier')

@push('styles')
<style>
    /* ==========================================================================
       PIXEL-PERFECT FIGMA DASHBOARD STYLING
       ========================================================================== */

    /* 1. Date Header */
    .op-header-group {
        margin-bottom: 16px;
    }

    .op-kicker {
        font-size: 10px;
        font-weight: 700;
        color: #8c9aa8;
        letter-spacing: 0.08em;
        text-transform: uppercase;
        margin-bottom: 4px;
    }

    .op-title {
        font-size: 20px;
        font-weight: 800;
        color: #0e1726;
        letter-spacing: -0.02em;
        margin: 0;
    }

    /* 2. Top Summary Cards (2x2 Grid) */
    .figma-grid-2x2 {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 12px;
        margin-bottom: 16px;
    }

    .figma-card {
        background: #ffffff;
        border: 1px solid #eef0f4;
        border-radius: 14px;
        padding: 14px 14px 12px;
        display: flex;
        flex-direction: column;
        justify-content: space-between;
        min-height: 96px;
        box-shadow: 0 1px 3px rgba(0, 0, 0, 0.02);
    }

    .figma-card.cream-card {
        background: #fef9ee;
        border-color: #fdecd0;
    }

    .figma-card-top {
        display: flex;
        justify-content: space-between;
        align-items: center;
    }

    .card-kicker {
        font-size: 9.5px;
        font-weight: 700;
        color: #8c9aa8;
        letter-spacing: 0.04em;
        text-transform: uppercase;
    }

    .card-kicker.gold-kicker {
        color: #966d25;
    }

    .card-icon-box {
        width: 26px;
        height: 26px;
        border-radius: 6px;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .card-icon-box.slate-icon { background: #f1f4f9; color: #64748b; }
    .card-icon-box.gold-icon { background: #faedd0; color: #a06616; }
    .card-icon-box.green-icon { background: #e8f9f0; color: #059669; }
    .card-icon-box.blue-gray-icon { background: #f0f4f9; color: #8fa0b5; }

    .figma-card-bottom {
        display: flex;
        justify-content: space-between;
        align-items: flex-end;
        margin-top: 6px;
    }

    .card-big-num {
        font-size: 28px;
        font-weight: 800;
        color: #0e1726;
        line-height: 1;
        letter-spacing: -0.03em;
    }

    .card-big-num.gold-num { color: #a06616; }
    .card-big-num.muted-num { color: #8fa0b5; }

    .card-trend {
        font-size: 10px;
        font-weight: 700;
    }

    .card-trend.green-trend { color: #10b981; }
    .card-trend.gray-trend { color: #8c9aa8; font-weight: 600; }

    .card-badge-pill {
        font-size: 9px;
        font-weight: 800;
        padding: 3px 7px;
        border-radius: 4px;
        letter-spacing: 0.04em;
        text-transform: uppercase;
    }

    .card-badge-pill.gold-pill {
        background: #fdeac2;
        color: #8c5807;
    }

    /* 3. Quick Action Pill Buttons */
    .quick-pill-row {
        display: flex;
        gap: 8px;
        margin-bottom: 24px;
        overflow-x: auto;
        padding-bottom: 4px;
    }

    .quick-pill-btn {
        flex: 1;
        min-width: 95px;
        height: 36px;
        background: #ffffff;
        border: 1px solid #eef0f4;
        border-radius: 9999px;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 5px;
        font-size: 11px;
        font-weight: 700;
        color: #334155;
        cursor: pointer;
        text-decoration: none;
        white-space: nowrap;
        transition: all 0.15s ease;
        box-shadow: 0 1px 2px rgba(0,0,0,0.02);
    }

    .quick-pill-btn:hover {
        border-color: #cbd5e1;
        background: #f8fafc;
        color: #0e1726;
    }

    .quick-pill-btn svg {
        flex-shrink: 0;
    }

    /* 4. Section: Antrean Perlu Ditinjau Segera */
    .figma-section {
        margin-bottom: 26px;
    }

    .section-head-flex {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 12px;
    }

    .section-title-icon-group {
        display: flex;
        align-items: center;
        gap: 8px;
    }

    .bell-icon-square {
        width: 24px;
        height: 24px;
        border-radius: 6px;
        background: #fef4e6;
        color: #d97706;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .section-head-title {
        font-size: 13.5px;
        font-weight: 800;
        color: #0e1726;
        letter-spacing: -0.01em;
    }

    .permintaan-badge {
        background: #fef3d6;
        color: #966014;
        font-size: 10px;
        font-weight: 800;
        padding: 3px 8px;
        border-radius: 9999px;
    }

    /* Stacked Queue Cards */
    .queue-cards-list {
        display: flex;
        flex-direction: column;
        gap: 12px;
        margin-bottom: 12px;
    }

    .queue-card {
        background: #ffffff;
        border: 1px solid #f1f3f7;
        border-radius: 14px;
        padding: 14px 16px;
        box-shadow: 0 2px 6px rgba(0, 0, 0, 0.02);
    }

    .queue-card-top {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 6px;
    }

    .queue-resi {
        font-size: 11px;
        font-weight: 700;
        color: #7b8a9b;
        letter-spacing: 0.02em;
    }

    .queue-time-dot {
        font-size: 10px;
        font-weight: 600;
        color: #8c9aa8;
        display: flex;
        align-items: center;
        gap: 4px;
    }

    .queue-time-dot span.dot {
        color: #f59e0b;
        font-size: 8px;
    }

    .queue-customer-name {
        font-size: 15px;
        font-weight: 800;
        color: #0e1726;
        margin-bottom: 2px;
    }

    .queue-contact-line {
        font-size: 11px;
        color: #64748b;
        margin-bottom: 10px;
    }

    .queue-slot-graybox {
        background: #f4f6f9;
        border-radius: 8px;
        padding: 8px 12px;
        display: flex;
        justify-content: space-between;
        align-items: center;
        font-size: 11px;
        margin-bottom: 12px;
    }

    .slot-label {
        color: #64748b;
        font-weight: 500;
    }

    .slot-value {
        color: #0e1726;
        font-weight: 700;
    }

    .queue-actions-row {
        display: flex;
        gap: 10px;
    }

    .btn-tolak {
        flex: 1;
        height: 36px;
        background: #ffffff;
        border: 1px solid #fed7d7;
        color: #e53e3e;
        border-radius: 8px;
        font-size: 11px;
        font-weight: 800;
        letter-spacing: 0.05em;
        cursor: pointer;
        transition: all 0.15s;
    }

    .btn-tolak:hover {
        background: #fef2f2;
    }

    .btn-konfirmasi {
        flex: 1;
        height: 36px;
        background: #111d33;
        border: 1px solid #111d33;
        color: #ffffff;
        border-radius: 8px;
        font-size: 11px;
        font-weight: 800;
        letter-spacing: 0.05em;
        cursor: pointer;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 4px;
        transition: all 0.15s;
    }

    .btn-konfirmasi:hover {
        background: #091222;
    }

    /* Big Bulk Confirmation Button */
    .btn-bulk-confirm-all {
        width: 100%;
        height: 44px;
        background: #c69244;
        color: #ffffff;
        border: none;
        border-radius: 12px;
        font-size: 12px;
        font-weight: 800;
        letter-spacing: 0.06em;
        text-transform: uppercase;
        cursor: pointer;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 6px;
        box-shadow: 0 4px 12px rgba(198, 146, 68, 0.25);
        transition: background 0.15s ease;
    }

    .btn-bulk-confirm-all:hover {
        background: #b58237;
    }

    /* 5. Section: Jadwal Booking Hari Ini */
    .filter-slider-btn {
        background: none;
        border: none;
        color: #64748b;
        cursor: pointer;
        display: flex;
        align-items: center;
        padding: 4px;
    }

    .chair-pills-row {
        display: flex;
        gap: 6px;
        overflow-x: auto;
        padding-bottom: 8px;
        margin-bottom: 12px;
    }

    .chair-pill {
        padding: 6px 14px;
        border-radius: 8px;
        font-size: 11px;
        font-weight: 700;
        text-decoration: none;
        white-space: nowrap;
        background: #ffffff;
        border: 1px solid #eef0f4;
        color: #475569;
        transition: all 0.15s ease;
    }

    .chair-pill.active {
        background: #111d33;
        color: #ffffff;
        border-color: #111d33;
    }

    /* Timeline Box Container */
    .timeline-container-box {
        background: #f0f3f8;
        border-radius: 16px;
        padding: 10px;
        display: flex;
        flex-direction: column;
        gap: 8px;
    }

    /* Item Card Variants */
    .timeline-card-item {
        border-radius: 10px;
        padding: 10px 12px;
        display: flex;
        justify-content: space-between;
        align-items: center;
        gap: 8px;
    }

    /* Selesai / Standard Item */
    .timeline-card-item.item-white {
        background: #ffffff;
        border: 1px solid #f1f3f7;
    }

    /* Sedang Cukur Item (Navy Card) */
    .timeline-card-item.item-navy {
        background: #111d33;
        color: #ffffff;
    }

    /* Jeda Terjadwal Item (Cream Card) */
    .timeline-card-item.item-cream {
        background: #fffdf7;
        border: 1px solid #feebc8;
    }

    .t-left-time {
        width: 72px;
        flex-shrink: 0;
    }

    .t-time-text {
        font-size: 11.5px;
        font-weight: 800;
        color: #0e1726;
        line-height: 1.2;
    }

    .item-navy .t-time-text { color: #fbd38d; }
    .item-cream .t-time-text { color: #a06616; }

    .t-duration-sub {
        font-size: 9px;
        color: #8c9aa8;
        margin-top: 1px;
    }

    .item-navy .t-duration-sub { color: #94a3b8; }
    .item-cream .t-duration-sub { color: #b7791f; }

    .t-mid-info {
        flex: 1;
        min-width: 0;
    }

    .t-client-service {
        font-size: 11.5px;
        font-weight: 800;
        color: #0e1726;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
    }

    .item-navy .t-client-service { color: #ffffff; }
    .item-cream .t-client-service { color: #78350f; }

    .t-chair-barber {
        font-size: 9.5px;
        color: #64748b;
        margin-top: 1px;
    }

    .item-navy .t-chair-barber { color: #94a3b8; }
    .item-cream .t-chair-barber { color: #92400e; }

    .t-right-badge {
        flex-shrink: 0;
    }

    /* Badges */
    .pill-status-tag {
        font-size: 9.5px;
        font-weight: 800;
        padding: 4px 8px;
        border-radius: 6px;
        display: inline-flex;
        align-items: center;
        gap: 3px;
        white-space: nowrap;
    }

    .tag-selesai {
        background: #e6f9f0;
        color: #059669;
    }

    .tag-sedang-cukur {
        background: #c69244;
        color: #ffffff;
    }

    .tag-jeda {
        background: #ffffff;
        border: 1px solid #fbd38d;
        color: #c05621;
    }

    .tag-datang {
        background: #fef3d6;
        color: #b7791f;
    }

    /* Modal Form Overlay */
    .figma-modal-overlay {
        display: none;
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background: rgba(14, 23, 38, 0.6);
        backdrop-filter: blur(3px);
        z-index: 300;
        align-items: center;
        justify-content: center;
        padding: 16px;
    }

    .figma-modal-overlay.active {
        display: flex;
    }

    .figma-modal-card {
        background: #ffffff;
        border-radius: 16px;
        max-width: 420px;
        width: 100%;
        padding: 22px;
        box-shadow: 0 20px 40px rgba(0,0,0,0.2);
    }

    .modal-card-head {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 14px;
        padding-bottom: 10px;
        border-bottom: 1px solid #eef0f4;
    }

    .modal-card-head h3 {
        font-size: 15px;
        font-weight: 800;
        color: #0e1726;
        margin: 0;
    }

    .modal-x-btn {
        background: none;
        border: none;
        font-size: 20px;
        color: #94a3b8;
        cursor: pointer;
    }

    .modal-form-group {
        margin-bottom: 12px;
    }

    .modal-form-group label {
        display: block;
        font-size: 10.5px;
        font-weight: 700;
        color: #475569;
        text-transform: uppercase;
        margin-bottom: 5px;
    }

    .modal-input {
        width: 100%;
        height: 38px;
        padding: 0 10px;
        border: 1px solid #e2e8f0;
        border-radius: 8px;
        font-size: 12px;
        font-family: inherit;
        box-sizing: border-box;
    }

    textarea.modal-input {
        height: 70px;
        padding: 8px 10px;
        resize: vertical;
    }

    .modal-btns-row {
        display: flex;
        justify-content: flex-end;
        gap: 8px;
        margin-top: 16px;
    }

    .btn-modal-cancel {
        height: 34px;
        padding: 0 14px;
        border: 1px solid #e2e8f0;
        background: #ffffff;
        color: #475569;
        border-radius: 6px;
        font-size: 11px;
        font-weight: 700;
        cursor: pointer;
    }

    .btn-modal-save {
        height: 34px;
        padding: 0 16px;
        border: none;
        background: #c69244;
        color: #ffffff;
        border-radius: 6px;
        font-size: 11px;
        font-weight: 800;
        cursor: pointer;
    }

    /* Toast */
    .toast-box-notify {
        position: fixed;
        bottom: 20px;
        left: 50%;
        transform: translateX(-50%);
        background: #0e1726;
        color: #ffffff;
        padding: 10px 18px;
        border-radius: 8px;
        font-size: 12px;
        font-weight: 700;
        box-shadow: 0 10px 25px rgba(0,0,0,0.3);
        z-index: 400;
        display: none;
        align-items: center;
        gap: 6px;
        white-space: nowrap;
    }
</style>
@endpush

@section('content')
    <div id="toastAlert" class="toast-box-notify"></div>

    {{-- OPERASIONAL HARI INI HEADER --}}
    <div class="op-header-group">
        <div class="op-kicker">OPERASIONAL HARI INI</div>
        <h1 class="op-title">Kamis, 24 Okt 2024</h1>
    </div>

    {{-- 4 KOTAK STATISTIK (2x2 GRID) --}}
    <div class="figma-grid-2x2">
        {{-- Card 1: Total Booking --}}
        <div class="figma-card">
            <div class="figma-card-top">
                <span class="card-kicker">TOTAL BOOKING</span>
                <div class="card-icon-box slate-icon">
                    <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <rect x="3" y="4" width="18" height="18" rx="2" ry="2"></rect>
                        <line x1="16" y1="2" x2="16" y2="6"></line>
                        <line x1="8" y1="2" x2="8" y2="6"></line>
                        <line x1="3" y1="10" x2="21" y2="10"></line>
                    </svg>
                </div>
            </div>
            <div class="figma-card-bottom">
                <div class="card-big-num" id="statTotalToday">{{ sprintf('%02d', $stats['total_today'] ?? 24) }}</div>
                <div class="card-trend green-trend">+16% vol</div>
            </div>
        </div>

        {{-- Card 2: Menunggu (Cream Card) --}}
        <div class="figma-card cream-card">
            <div class="figma-card-top">
                <span class="card-kicker gold-kicker">MENUNGGU</span>
                <div class="card-icon-box gold-icon">
                    <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="M5 22h14"></path>
                        <path d="M5 2h14"></path>
                        <path d="M17 22v-4.172a2 2 0 0 0-.586-1.414L12 12l-4.414 4.414A2 2 0 0 0 7 17.828V22"></path>
                        <path d="M7 2v4.172a2 2 0 0 0 .586 1.414L12 12l4.414-4.414A2 2 0 0 0 17 6.172V2"></path>
                    </svg>
                </div>
            </div>
            <div class="figma-card-bottom">
                <div class="card-big-num gold-num" id="statPendingReview">{{ sprintf('%02d', $stats['pending_review'] ?? 3) }}</div>
                <div class="card-badge-pill gold-pill">PERLU AKSI</div>
            </div>
        </div>

        {{-- Card 3: Selesai --}}
        <div class="figma-card">
            <div class="figma-card-top">
                <span class="card-kicker">SELESAI</span>
                <div class="card-icon-box green-icon">
                    <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"></path>
                        <polyline points="22 4 12 14.01 9 11.01"></polyline>
                    </svg>
                </div>
            </div>
            <div class="figma-card-bottom">
                <div class="card-big-num" id="statCompletedToday">{{ sprintf('%02d', $stats['completed_today'] ?? 14) }}</div>
                <div class="card-trend gray-trend">58% target</div>
            </div>
        </div>

        {{-- Card 4: Dibatalkan --}}
        <div class="figma-card">
            <div class="figma-card-top">
                <span class="card-kicker">DIBATALKAN</span>
                <div class="card-icon-box blue-gray-icon">
                    <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <circle cx="12" cy="12" r="10"></circle>
                        <line x1="15" y1="9" x2="9" y2="15"></line>
                        <line x1="9" y1="9" x2="15" y2="15"></line>
                    </svg>
                </div>
            </div>
            <div class="figma-card-bottom">
                <div class="card-big-num muted-num" id="statRejectedToday">{{ sprintf('%02d', $stats['rejected_today'] ?? 1) }}</div>
                <div class="card-trend gray-trend">4.1% rate</div>
            </div>
        </div>
    </div>

    {{-- ROW OF 3 QUICK ACTION PILL BUTTONS --}}
    <div class="quick-pill-row">
        <button type="button" class="quick-pill-btn" onclick="openModal('modalAddBarber')">
            <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <path d="M16 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path>
                <circle cx="8.5" cy="7" r="4"></circle>
                <line x1="20" y1="8" x2="20" y2="14"></line>
                <line x1="23" y1="11" x2="17" y2="11"></line>
            </svg>
            <span>+ Barber</span>
        </button>
        <button type="button" class="quick-pill-btn" onclick="openModal('modalAddService')">
            <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="#e5a93c" stroke-width="2">
                <circle cx="12" cy="12" r="10"></circle>
                <line x1="12" y1="8" x2="12" y2="16"></line>
                <line x1="8" y1="12" x2="16" y2="12"></line>
            </svg>
            <span>+ Layanan</span>
        </button>
        <button type="button" class="quick-pill-btn" onclick="openModal('modalSettings')">
            <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <circle cx="12" cy="12" r="10"></circle>
                <polyline points="12 6 12 12 16 14"></polyline>
            </svg>
            <span>Jam Operasio...</span>
        </button>
    </div>

    {{-- SECTION: ANTREAN PERLU DITINJAU SEGERA --}}
    <section class="figma-section">
        <div class="section-head-flex">
            <div class="section-title-icon-group">
                <div class="bell-icon-square">
                    <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="M18 8A6 6 0 0 0 6 8c0 7-3 9-3 9h18s-3-2-3-9"></path>
                        <path d="M13.73 21a2 2 0 0 1-3.46 0"></path>
                    </svg>
                </div>
                <span class="section-head-title">Antrean Perlu Ditinjau Segera</span>
            </div>
            <span class="permintaan-badge" id="permintaanBadge">{{ $pendingBookings->count() }} Permintaan</span>
        </div>

        <div class="queue-cards-list" id="queueCardsList">
            @forelse ($pendingBookings as $booking)
                <div class="queue-card" id="bookingCard-{{ $booking->id }}">
                    <div class="queue-card-top">
                        <span class="queue-resi">#{{ $booking->booking_code }}</span>
                        <span class="queue-time-dot">
                            <span class="dot">●</span> {{ $booking->created_at->diffForHumans(null, true) }} lalu
                        </span>
                    </div>

                    <div class="queue-customer-name">{{ $booking->customer->name ?? 'Pelanggan' }}</div>
                    <div class="queue-contact-line">
                        {{ $booking->customer->phone ?? '-' }} • {{ $booking->service->name ?? '-' }}
                    </div>

                    <div class="queue-slot-graybox">
                        <span class="slot-label">Waktu & Kursi</span>
                        <span class="slot-value">{{ substr($booking->start_time, 0, 5) }} WIB • Kursi {{ sprintf('%02d', $booking->barber_id) }} ({{ $booking->barber->name ?? '-' }})</span>
                    </div>

                    <div class="queue-actions-row">
                        <button type="button" class="btn-tolak" onclick="openRejectModal('{{ $booking->id }}', '{{ $booking->booking_code }}')">
                            TOLAK
                        </button>
                        <button type="button" class="btn-konfirmasi" onclick="quickConfirm('{{ $booking->id }}', '{{ $booking->booking_code }}')">
                            ✓ KONFIRMASI
                        </button>
                    </div>
                </div>
            @empty
                <div style="background:#fff; border-radius:12px; padding:24px; text-align:center; color:#64748b; font-size:12px;">
                    ✨ Semua antrean sudah ditinjau.
                </div>
            @endforelse
        </div>

        @if ($pendingBookings->isNotEmpty())
            <form id="bulkConfirmForm" method="POST" action="{{ route('admin.bookings.bulk-confirm') }}" onsubmit="return handleBulkConfirm(event);">
                @csrf
                <button type="submit" class="btn-bulk-confirm-all" id="bulkConfirmBtn">
                    ✓✓ KONFIRMASI SEMUA ({{ $pendingBookings->count() }} BOOKING)
                </button>
            </form>
        @endif
    </section>

    {{-- SECTION: JADWAL BOOKING HARI INI --}}
    <section class="figma-section">
        <div class="section-head-flex">
            <span class="section-head-title">Jadwal Booking Hari Ini</span>
            <button type="button" class="filter-slider-btn" onclick="toggleSidebarDrawer()" title="Filter & Menu">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <line x1="4" y1="21" x2="4" y2="14"></line>
                    <line x1="4" y1="10" x2="4" y2="3"></line>
                    <line x1="12" y1="21" x2="12" y2="12"></line>
                    <line x1="12" y1="8" x2="12" y2="3"></line>
                    <line x1="20" y1="21" x2="20" y2="16"></line>
                    <line x1="20" y1="12" x2="20" y2="3"></line>
                    <line x1="1" y1="14" x2="7" y2="14"></line>
                    <line x1="9" y1="8" x2="15" y2="8"></line>
                    <line x1="17" y1="16" x2="23" y2="16"></line>
                </svg>
            </button>
        </div>

        {{-- Filter Pills --}}
        <div class="chair-pills-row">
            <a href="{{ route('admin.dashboard') }}" class="chair-pill {{ empty($selectedBarberId) ? 'active' : '' }}">
                Semua Kursi
            </a>
            @foreach ($barbers as $barber)
                <a href="{{ route('admin.dashboard', ['barber_id' => $barber->id]) }}" class="chair-pill {{ (string)$selectedBarberId === (string)$barber->id ? 'active' : '' }}">
                    {{ $barber->name }} ({{ sprintf('%02d', $barber->id) }})
                </a>
            @endforeach
        </div>

        {{-- Timeline Box Container --}}
        <div class="timeline-container-box">
            @forelse ($todaySchedules as $item)
                @php
                    $isNavy = ($item->status === 'in_progress');
                    $isCream = ($item->status === 'break');
                    $cardClass = $isNavy ? 'item-navy' : ($isCream ? 'item-cream' : 'item-white');
                @endphp

                <div class="timeline-card-item {{ $cardClass }}" id="schedItem-{{ $item->id }}">
                    <div class="t-left-time">
                        <div class="t-time-text">{{ substr($item->start_time, 0, 5) }} WIB</div>
                        <div class="t-duration-sub">
                            @if ($item->status === 'break')
                                45 Menit
                            @elseif ($item->status === 'confirmed')
                                Mendatang
                            @else
                                {{ $item->service->duration_minutes ?? 60 }} Menit
                            @endif
                        </div>
                    </div>

                    <div class="t-mid-info">
                        <div class="t-client-service">
                            @if ($item->status === 'break')
                                Istirahat Maghrib & Sanitasi
                            @else
                                {{ $item->customer->name ?? '-' }} • {{ $item->service->name ?? '-' }}
                            @endif
                        </div>
                        <div class="t-chair-barber">
                            @if ($item->status === 'break')
                                Semua Kursi
                            @else
                                Kursi {{ sprintf('%02d', $item->barber_id) }} ({{ $item->barber->name ?? '-' }})
                                <span style="display:none;">{{ $item->booking_code }}</span>
                            @endif
                        </div>
                    </div>

                    <div class="t-right-badge">
                        @if ($item->status === 'completed')
                            <span class="pill-status-tag tag-selesai">Selesai</span>
                        @elseif ($item->status === 'in_progress')
                            <span class="pill-status-tag tag-sedang-cukur">Sedang Cukur</span>
                        @elseif ($item->status === 'break')
                            <span class="pill-status-tag tag-jeda">Jeda Terjadwal</span>
                        @elseif ($item->status === 'confirmed')
                            <span class="pill-status-tag tag-datang">
                                <svg width="10" height="10" viewBox="0 0 24 24" fill="currentColor">
                                    <path d="M12 12c2.21 0 4-1.79 4-4s-1.79-4-4-4-4 1.79-4 4 1.79 4 4 4zm0 2c-2.67 0-8 1.34-8 4v2h16v-2c0-2.66-5.33-4-8-4z"/>
                                </svg>
                                Datang
                            </span>
                        @else
                            <span class="pill-status-tag tag-selesai">{{ ucfirst($item->status) }}</span>
                        @endif
                    </div>
                </div>
            @empty
                <div style="background:#fff; border-radius:10px; padding:20px; text-align:center; font-size:12px; color:#64748b;">
                    Tidak ada jadwal untuk filter ini.
                </div>
            @endforelse
        </div>
    </section>

    {{-- MODAL TAMBAH BARBER --}}
    <div id="modalAddBarber" class="figma-modal-overlay">
        <div class="figma-modal-card">
            <div class="modal-card-head">
                <h3>✂️ Tambah Barber Baru</h3>
                <button type="button" class="modal-x-btn" onclick="closeModal('modalAddBarber')">&times;</button>
            </div>
            <form method="POST" action="{{ route('admin.barbers.store') }}" onsubmit="return handleFormSubmitAjax(event, 'modalAddBarber', 'Barber baru berhasil ditambahkan!')">
                @csrf
                <div class="modal-form-group">
                    <label>Nama Barber <span style="color:red;">*</span></label>
                    <input type="text" name="name" class="modal-input" required placeholder="Contoh: Bramantyo">
                </div>
                <div class="modal-form-group">
                    <label>Keterangan / Spesialisasi</label>
                    <textarea name="description" class="modal-input" placeholder="Contoh: Master Barber"></textarea>
                </div>
                <div class="modal-btns-row">
                    <button type="button" class="btn-modal-cancel" onclick="closeModal('modalAddBarber')">Batal</button>
                    <button type="submit" class="btn-modal-save">Simpan Barber</button>
                </div>
            </form>
        </div>
    </div>

    {{-- MODAL TAMBAH LAYANAN --}}
    <div id="modalAddService" class="figma-modal-overlay">
        <div class="figma-modal-card">
            <div class="modal-card-head">
                <h3>💈 Tambah Layanan Baru</h3>
                <button type="button" class="modal-x-btn" onclick="closeModal('modalAddService')">&times;</button>
            </div>
            <form method="POST" action="{{ route('admin.services.store') }}" onsubmit="return handleFormSubmitAjax(event, 'modalAddService', 'Layanan baru berhasil ditambahkan!')">
                @csrf
                <div class="modal-form-group">
                    <label>Nama Layanan <span style="color:red;">*</span></label>
                    <input type="text" name="name" class="modal-input" required placeholder="Contoh: Classic Haircut & Wash">
                </div>
                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 10px;">
                    <div class="modal-form-group">
                        <label>Harga (Rp) <span style="color:red;">*</span></label>
                        <input type="number" name="price" class="modal-input" required min="0" placeholder="50000">
                    </div>
                    <div class="modal-form-group">
                        <label>Durasi (Mnt) <span style="color:red;">*</span></label>
                        <input type="number" name="duration_minutes" class="modal-input" required min="5" placeholder="45">
                    </div>
                </div>
                <div class="modal-btns-row">
                    <button type="button" class="btn-modal-cancel" onclick="closeModal('modalAddService')">Batal</button>
                    <button type="submit" class="btn-modal-save">Simpan Layanan</button>
                </div>
            </form>
        </div>
    </div>

    {{-- MODAL JAM OPERASIONAL --}}
    <div id="modalSettings" class="figma-modal-overlay">
        <div class="figma-modal-card">
            <div class="modal-card-head">
                <h3>⚙️ Jam Operasional Studio</h3>
                <button type="button" class="modal-x-btn" onclick="closeModal('modalSettings')">&times;</button>
            </div>
            <form method="POST" action="{{ route('admin.settings.update') }}" onsubmit="return handleFormSubmitAjax(event, 'modalSettings', 'Jam operasional berhasil disimpan!')">
                @csrf
                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 10px;">
                    <div class="modal-form-group">
                        <label>Jam Buka</label>
                        <input type="time" name="opening_time" class="modal-input" value="{{ substr($setting->opening_time ?? '10:00:00', 0, 5) }}" required>
                    </div>
                    <div class="modal-form-group">
                        <label>Jam Tutup</label>
                        <input type="time" name="closing_time" class="modal-input" value="{{ substr($setting->closing_time ?? '21:00:00', 0, 5) }}" required>
                    </div>
                </div>
                <div class="modal-form-group">
                    <label>Telepon Desk</label>
                    <input type="text" name="phone" class="modal-input" value="{{ $setting->phone ?? '+62 21 5550 1928' }}">
                </div>
                <div class="modal-btns-row">
                    <button type="button" class="btn-modal-cancel" onclick="closeModal('modalSettings')">Batal</button>
                    <button type="submit" class="btn-modal-save">Simpan Pengaturan</button>
                </div>
            </form>
        </div>
    </div>

    {{-- MODAL TOLAK BOOKING --}}
    <div id="rejectModal" class="figma-modal-overlay">
        <div class="figma-modal-card">
            <div class="modal-card-head">
                <h3 style="color: #e53e3e;">✕ Tolak Booking Reservasi</h3>
                <button type="button" class="modal-x-btn" onclick="closeModal('rejectModal')">&times;</button>
            </div>
            <form id="rejectForm" method="POST" action="" onsubmit="return handleRejectSubmit(event);">
                @csrf
                @method('PATCH')
                <p style="font-size: 12px; color: #64748b; margin-bottom: 10px;">
                    Alasan penolakan untuk <strong id="modalResi" style="color: #0e1726;"></strong> (wajib diisi):
                </p>
                <div class="modal-form-group">
                    <textarea id="rejection_reason" name="rejection_reason" class="modal-input" required placeholder="Contoh: Kursi sedang perawatan / Barber berhalangan hadir..."></textarea>
                </div>
                <div class="modal-btns-row">
                    <button type="button" class="btn-modal-cancel" onclick="closeModal('rejectModal')">Batal</button>
                    <button type="submit" class="btn-modal-save" style="background: #e53e3e;">Tolak Booking</button>
                </div>
            </form>
        </div>
    </div>
@endsection

@push('scripts')
<script>
    const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '';

    function showToast(msg) {
        const t = document.getElementById('toastAlert');
        t.innerText = msg;
        t.style.display = 'flex';
        setTimeout(() => { t.style.display = 'none'; }, 3500);
    }

    function openModal(id) {
        document.getElementById(id).classList.add('active');
    }

    function closeModal(id) {
        document.getElementById(id).classList.remove('active');
    }

    window.addEventListener('click', (e) => {
        if (e.target.classList.contains('figma-modal-overlay')) {
            e.target.classList.remove('active');
        }
    });

    // Quick Confirm Item via AJAX
    async function quickConfirm(bookingId, resi) {
        if (!confirm(`Konfirmasi booking #${resi}?`)) return;

        try {
            const res = await fetch(`/admin/bookings/${bookingId}/confirm`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': csrfToken,
                    'X-Requested-With': 'XMLHttpRequest'
                }
            });
            const data = await res.json();
            if (data.success) {
                showToast(`✓ Booking #${resi} berhasil dikonfirmasi!`);
                const card = document.getElementById(`bookingCard-${bookingId}`);
                if (card) {
                    card.style.opacity = '0';
                    setTimeout(() => {
                        card.remove();
                        updatePendingCount();
                    }, 200);
                }
            } else {
                alert(data.message || 'Gagal mengonfirmasi booking.');
            }
        } catch (err) {
            console.error(err);
            window.location.reload();
        }
    }

    // Modal Reject Form
    let currentRejectId = null;
    function openRejectModal(bookingId, resi) {
        currentRejectId = bookingId;
        const modal = document.getElementById('rejectModal');
        const resiEl = document.getElementById('modalResi');
        const reasonInput = document.getElementById('rejection_reason');
        const form = document.getElementById('rejectForm');

        resiEl.innerText = '#' + resi;
        form.action = `/admin/bookings/${bookingId}/reject`;
        reasonInput.value = '';
        modal.classList.add('active');
        reasonInput.focus();
    }

    async function handleRejectSubmit(e) {
        e.preventDefault();
        const form = e.target;
        const reason = document.getElementById('rejection_reason').value;

        try {
            const res = await fetch(form.action, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': csrfToken,
                    'X-Requested-With': 'XMLHttpRequest'
                },
                body: JSON.stringify({
                    rejection_reason: reason,
                    _method: 'PATCH'
                })
            });
            const data = await res.json();
            if (data.success) {
                showToast('✓ Booking berhasil ditolak.');
                closeModal('rejectModal');
                const card = document.getElementById(`bookingCard-${currentRejectId}`);
                if (card) {
                    card.style.opacity = '0';
                    setTimeout(() => {
                        card.remove();
                        updatePendingCount();
                    }, 200);
                }
            } else {
                alert(data.message || 'Gagal menolak booking.');
            }
        } catch (err) {
            console.error(err);
            form.submit();
        }
    }

    // Bulk Confirm All
    async function handleBulkConfirm(e) {
        e.preventDefault();
        if (!confirm('Setujui seluruh antrean pending sekaligus?')) return;

        const form = e.target;
        try {
            const res = await fetch(form.action, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': csrfToken,
                    'X-Requested-With': 'XMLHttpRequest'
                }
            });
            const data = await res.json();
            if (data.success) {
                showToast(data.message);
                const list = document.getElementById('queueCardsList');
                if (list) list.innerHTML = '<div style="background:#fff; border-radius:12px; padding:24px; text-align:center; color:#64748b; font-size:12px;">✨ Semua antrean sudah ditinjau.</div>';
                const btn = document.getElementById('bulkConfirmBtn');
                if (btn) btn.remove();
                const badge = document.getElementById('permintaanBadge');
                if (badge) badge.innerText = '0 Permintaan';
                const statPending = document.getElementById('statPendingReview');
                if (statPending) statPending.innerText = '00';
            } else {
                alert(data.message || 'Gagal konfirmasi massal.');
            }
        } catch (err) {
            console.error(err);
            form.submit();
        }
    }

    function updatePendingCount() {
        const list = document.getElementById('queueCardsList');
        const badge = document.getElementById('permintaanBadge');
        const bulkBtn = document.getElementById('bulkConfirmBtn');
        const remaining = list ? list.querySelectorAll('.queue-card').length : 0;

        if (badge) badge.innerText = `${remaining} Permintaan`;
        const statPending = document.getElementById('statPendingReview');
        if (statPending) statPending.innerText = remaining < 10 ? `0${remaining}` : remaining;

        if (remaining === 0) {
            if (bulkBtn) bulkBtn.remove();
            if (list) list.innerHTML = '<div style="background:#fff; border-radius:12px; padding:24px; text-align:center; color:#64748b; font-size:12px;">✨ Semua antrean sudah ditinjau.</div>';
        } else if (bulkBtn) {
            bulkBtn.innerText = `✓✓ KONFIRMASI SEMUA (${remaining} BOOKING)`;
        }
    }

    // Modal Quick Action Submission
    async function handleFormSubmitAjax(e, modalId, successMsg) {
        e.preventDefault();
        const form = e.target;
        const formData = new FormData(form);

        try {
            const res = await fetch(form.action, {
                method: 'POST',
                headers: {
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': csrfToken,
                    'X-Requested-With': 'XMLHttpRequest'
                },
                body: formData
            });
            const data = await res.json();
            if (data.success) {
                showToast(data.message || successMsg);
                closeModal(modalId);
                form.reset();
                setTimeout(() => window.location.reload(), 800);
            } else {
                alert(data.message || 'Terjadi kesalahan.');
            }
        } catch (err) {
            console.error(err);
            form.submit();
        }
    }
</script>
@endpush
