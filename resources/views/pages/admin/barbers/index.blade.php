@extends('layouts.admin')

@section('title', 'Kelola Master Barber — Excellent Atelier')

@push('styles')
<style>
    /* Styling khusus Kelola Master Barber - Pixel-Perfect Figma match */
    .barber-page-container {
        display: flex;
        flex-direction: column;
        gap: 16px;
    }

    /* Eyebrow & Header */
    .barber-header-row {
        display: flex;
        justify-content: space-between;
        align-items: flex-start;
        gap: 12px;
    }

    .eyebrow-text {
        font-size: 10px;
        font-weight: 800;
        letter-spacing: 0.1em;
        text-transform: uppercase;
        color: #a67c37;
        display: flex;
        align-items: center;
        gap: 6px;
        margin-bottom: 4px;
    }

    .eyebrow-text .bullet {
        width: 6px;
        height: 6px;
        border-radius: 50%;
        background-color: #a67c37;
        display: inline-block;
    }

    .barber-page-title {
        font-size: 20px;
        font-weight: 800;
        color: #0e1726;
        line-height: 1.2;
        margin-bottom: 4px;
    }

    .barber-page-desc {
        font-size: 11px;
        color: #64748b;
        line-height: 1.45;
        max-width: 280px;
    }

    .btn-add-barber {
        background: #0e1726;
        color: #ffffff;
        border: none;
        padding: 9px 14px;
        border-radius: 6px;
        font-size: 11.5px;
        font-weight: 700;
        display: inline-flex;
        align-items: center;
        gap: 6px;
        cursor: pointer;
        white-space: nowrap;
        transition: all 0.2s;
        box-shadow: 0 2px 6px rgba(14,23,38,0.15);
    }

    .btn-add-barber:hover {
        background: #1a2a44;
        transform: translateY(-1px);
    }

    /* Search Bar */
    .barber-search-box {
        position: relative;
        width: 100%;
    }

    .barber-search-box svg {
        position: absolute;
        left: 12px;
        top: 50%;
        transform: translateY(-50%);
        color: #94a3b8;
    }

    .barber-search-input {
        width: 100%;
        height: 38px;
        padding: 0 14px 0 36px;
        background: #ffffff;
        border: 1px solid #e2e8f0;
        border-radius: 8px;
        font-size: 12px;
        color: #0e1726;
        outline: none;
        transition: border-color 0.2s;
    }

    .barber-search-input:focus {
        border-color: #a67c37;
    }

    /* Filter Pills */
    .barber-filter-pills {
        display: flex;
        align-items: center;
        gap: 8px;
        overflow-x: auto;
        padding-bottom: 2px;
    }

    .barber-filter-pills::-webkit-scrollbar {
        display: none;
    }

    .pill-btn {
        background: #ffffff;
        color: #64748b;
        border: 1px solid #e2e8f0;
        border-radius: 20px;
        padding: 6px 14px;
        font-size: 11px;
        font-weight: 700;
        cursor: pointer;
        white-space: nowrap;
        text-decoration: none;
        transition: all 0.2s;
    }

    .pill-btn.active {
        background: #0e1726;
        color: #ffffff;
        border-color: #0e1726;
    }

    /* 2x2 Stats Grid */
    .stats-2x2-grid {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 10px;
    }

    .stat-card-box {
        background: #ffffff;
        border: 1px solid #f1f3f7;
        border-radius: 10px;
        padding: 12px;
        display: flex;
        flex-direction: column;
        justify-content: space-between;
        min-height: 78px;
    }

    .stat-card-head {
        display: flex;
        justify-content: space-between;
        align-items: center;
    }

    .stat-card-label {
        font-size: 8.5px;
        font-weight: 800;
        letter-spacing: 0.08em;
        text-transform: uppercase;
        color: #8c9aa8;
    }

    .stat-card-icon {
        width: 24px;
        height: 24px;
        border-radius: 6px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 12px;
    }

    .stat-icon-chair { background: #fef3c7; color: #b45309; }
    .stat-icon-calendar { background: #e0f2fe; color: #0284c7; }
    .stat-icon-star { background: #fef9c3; color: #ca8a04; }
    .stat-icon-shop { background: #f1f5f9; color: #475569; }

    .stat-card-val-row {
        display: flex;
        align-items: baseline;
        gap: 4px;
        margin-top: 6px;
    }

    .stat-card-num {
        font-size: 18px;
        font-weight: 800;
        color: #0e1726;
        line-height: 1;
    }

    .stat-card-sub {
        font-size: 10.5px;
        color: #8c9aa8;
        font-weight: 600;
    }

    .val-optimal {
        color: #059669;
        font-weight: 800;
    }

    /* Barber Cards */
    .barber-cards-stack {
        display: flex;
        flex-direction: column;
        gap: 14px;
    }

    .barber-card {
        background: #ffffff;
        border: 1px solid #eef0f4;
        border-radius: 12px;
        padding: 16px;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.02);
        display: flex;
        flex-direction: column;
        gap: 12px;
        transition: transform 0.2s, box-shadow 0.2s;
    }

    .barber-card:hover {
        box-shadow: 0 4px 14px rgba(0, 0, 0, 0.05);
    }

    .barber-card.inactive-card {
        background: #fafbfc;
        border-color: #e2e8f0;
    }

    .barber-card-top {
        display: flex;
        gap: 12px;
        align-items: flex-start;
    }

    .barber-avatar-wrap {
        width: 58px;
        height: 58px;
        border-radius: 8px;
        overflow: hidden;
        flex-shrink: 0;
        background: #1e293b;
        position: relative;
    }

    .barber-avatar-img {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }

    .barber-card.inactive-card .barber-avatar-img {
        filter: grayscale(100%);
        opacity: 0.65;
    }

    .barber-details-wrap {
        flex: 1;
        min-width: 0;
    }

    .barber-badges-row {
        display: flex;
        align-items: center;
        gap: 5px;
        flex-wrap: wrap;
    }

    .badge-kursi {
        font-size: 8.5px;
        font-weight: 800;
        color: #a67c37;
        background: #fef9ee;
        border: 1px solid #fdecd0;
        border-radius: 4px;
        padding: 1.5px 5px;
        letter-spacing: 0.05em;
    }

    .badge-online {
        font-size: 8.5px;
        font-weight: 800;
        color: #059669;
        background: #ecfdf5;
        border: 1px solid #a7f3d0;
        border-radius: 4px;
        padding: 1.5px 5px;
        letter-spacing: 0.05em;
    }

    .badge-cuti {
        font-size: 8.5px;
        font-weight: 800;
        color: #64748b;
        background: #f1f5f9;
        border: 1px solid #e2e8f0;
        border-radius: 4px;
        padding: 1.5px 5px;
        letter-spacing: 0.05em;
    }

    .badge-role-tag {
        margin-left: auto;
        font-size: 8.5px;
        font-weight: 700;
        color: #ffffff;
        background: #0e1726;
        border-radius: 4px;
        padding: 2px 7px;
        white-space: nowrap;
    }

    .badge-role-junior {
        margin-left: auto;
        font-size: 8.5px;
        font-weight: 700;
        color: #475569;
        background: #f1f5f9;
        border-radius: 4px;
        padding: 2px 7px;
        white-space: nowrap;
    }

    .barber-name-text {
        font-size: 15px;
        font-weight: 800;
        color: #0e1726;
        margin-top: 4px;
        line-height: 1.2;
    }

    .barber-spec-text {
        font-size: 11px;
        color: #64748b;
        margin-top: 2px;
        line-height: 1.35;
    }

    /* Skills Pill List */
    .skills-pill-list {
        display: flex;
        flex-wrap: wrap;
        gap: 5px;
    }

    .skill-tag-pill {
        font-size: 9.5px;
        color: #475569;
        background: #f8fafc;
        border: 1px solid #e2e8f0;
        border-radius: 4px;
        padding: 2px 7px;
    }

    /* Meta Row: Sesi & Rating */
    .barber-meta-row {
        display: flex;
        justify-content: space-between;
        align-items: center;
        font-size: 11px;
        color: #64748b;
        padding-top: 4px;
    }

    .meta-sesi {
        display: flex;
        align-items: center;
        gap: 5px;
        font-weight: 600;
    }

    .meta-rating {
        display: flex;
        align-items: center;
        gap: 4px;
    }

    .meta-rating .star-icon {
        color: #ca8a04;
        font-weight: 800;
    }

    .meta-rating .rating-bold {
        font-weight: 800;
        color: #0e1726;
    }

    /* Warning Note Box for Inactive */
    .barber-alert-box {
        background: #f8fafc;
        border: 1px solid #e2e8f0;
        border-radius: 8px;
        padding: 9px 12px;
        display: flex;
        flex-direction: column;
        gap: 3px;
    }

    .alert-box-title {
        display: flex;
        align-items: center;
        gap: 6px;
        font-size: 10.5px;
        font-weight: 700;
        color: #334155;
    }

    .alert-box-desc {
        font-size: 9.5px;
        color: #64748b;
        line-height: 1.4;
    }

    /* Toggle Switch Row */
    .booking-toggle-row {
        background: #fafafa;
        border: 1px solid #f1f5f9;
        border-radius: 8px;
        padding: 9px 12px;
        display: flex;
        justify-content: space-between;
        align-items: center;
    }

    .toggle-labels {
        display: flex;
        flex-direction: column;
    }

    .toggle-label-main {
        font-size: 11.5px;
        font-weight: 700;
        color: #0e1726;
    }

    .toggle-label-sub {
        font-size: 10px;
        color: #8c9aa8;
    }

    /* Custom Switch Toggle */
    .switch-ui {
        position: relative;
        display: inline-block;
        width: 38px;
        height: 22px;
        flex-shrink: 0;
    }

    .switch-ui input {
        opacity: 0;
        width: 0;
        height: 0;
    }

    .slider-ui {
        position: absolute;
        cursor: pointer;
        top: 0; left: 0; right: 0; bottom: 0;
        background-color: #cbd5e1;
        border-radius: 34px;
        transition: 0.25s;
    }

    .slider-ui:before {
        position: absolute;
        content: "";
        height: 16px;
        width: 16px;
        left: 3px;
        bottom: 3px;
        background-color: white;
        border-radius: 50%;
        transition: 0.25s;
        box-shadow: 0 1px 3px rgba(0,0,0,0.2);
    }

    .switch-ui input:checked + .slider-ui {
        background-color: #926c2d;
    }

    .switch-ui input:checked + .slider-ui:before {
        transform: translateX(16px);
    }

    /* Action Buttons Row */
    .barber-actions-row {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 8px;
    }

    .btn-card-secondary {
        background: #f1f5f9;
        color: #334155;
        border: none;
        border-radius: 6px;
        padding: 8px 10px;
        font-size: 11px;
        font-weight: 700;
        text-align: center;
        cursor: pointer;
        text-decoration: none;
        transition: all 0.2s;
    }

    .btn-card-secondary:hover {
        background: #e2e8f0;
    }

    .btn-card-primary {
        background: #0e1726;
        color: #ffffff;
        border: none;
        border-radius: 6px;
        padding: 8px 10px;
        font-size: 11px;
        font-weight: 700;
        text-align: center;
        cursor: pointer;
        text-decoration: none;
        transition: all 0.2s;
    }

    .btn-card-primary:hover {
        background: #1a2a44;
    }

    .btn-card-gold {
        background: #926c2d;
        color: #ffffff;
        border: none;
        border-radius: 6px;
        padding: 8px 10px;
        font-size: 11px;
        font-weight: 700;
        text-align: center;
        cursor: pointer;
        text-decoration: none;
        transition: all 0.2s;
    }

    .btn-card-gold:hover {
        background: #7d5c24;
    }

    /* Bottom Info Banner */
    .barber-sync-banner {
        background: #0e1726;
        border-radius: 10px;
        padding: 14px 16px;
        display: flex;
        align-items: flex-start;
        gap: 12px;
        margin-top: 8px;
    }

    .sync-icon-sq {
        width: 28px;
        height: 28px;
        border-radius: 6px;
        background: rgba(198, 146, 68, 0.2);
        color: #c69244;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 14px;
        flex-shrink: 0;
    }

    .sync-banner-content {
        display: flex;
        flex-direction: column;
        gap: 2px;
    }

    .sync-banner-title {
        font-size: 11px;
        font-weight: 800;
        letter-spacing: 0.06em;
        color: #ffffff;
    }

    .sync-banner-text {
        font-size: 10px;
        color: #94a3b8;
        line-height: 1.45;
    }

    /* Modal Form Styles */
    .barber-modal {
        display: none;
        position: fixed;
        top: 0; left: 0; width: 100%; height: 100%;
        background: rgba(0, 0, 0, 0.55);
        backdrop-filter: blur(3px);
        z-index: 300;
        justify-content: center;
        align-items: center;
        padding: 16px;
    }

    .barber-modal.active {
        display: flex;
    }

    .barber-modal-card {
        background: #ffffff;
        border-radius: 14px;
        width: 100%;
        max-width: 440px;
        max-height: 90vh;
        overflow-y: auto;
        padding: 22px;
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.25);
        display: flex;
        flex-direction: column;
        gap: 14px;
    }

    .modal-head {
        display: flex;
        justify-content: space-between;
        align-items: center;
        border-bottom: 1px solid #f1f3f7;
        padding-bottom: 12px;
    }

    .modal-head h3 {
        font-size: 16px;
        font-weight: 800;
        color: #0e1726;
    }

    .modal-close-btn {
        background: none;
        border: none;
        font-size: 20px;
        color: #94a3b8;
        cursor: pointer;
    }

    .form-group-barber {
        display: flex;
        flex-direction: column;
        gap: 4px;
    }

    .form-group-barber label {
        font-size: 11px;
        font-weight: 700;
        color: #334155;
    }

    .form-input-barber {
        height: 38px;
        border: 1px solid #e2e8f0;
        border-radius: 8px;
        padding: 0 12px;
        font-size: 12px;
        color: #0e1726;
        outline: none;
    }

    .form-input-barber:focus {
        border-color: #a67c37;
    }

    /* Toast Notification */
    .toast-box {
        position: fixed;
        bottom: 24px;
        left: 50%;
        transform: translateX(-50%) translateY(100px);
        background: #0e1726;
        color: #ffffff;
        padding: 10px 18px;
        border-radius: 30px;
        font-size: 12px;
        font-weight: 600;
        box-shadow: 0 6px 20px rgba(0, 0, 0, 0.2);
        z-index: 500;
        transition: transform 0.3s ease;
        display: flex;
        align-items: center;
        gap: 8px;
        pointer-events: none;
    }

    .toast-box.show {
        transform: translateX(-50%) translateY(0);
    }
</style>
@endpush

@section('content')
<div class="barber-page-container">
    {{-- Header Section --}}
    <div class="barber-header-row">
        <div>
            <div class="eyebrow-text">
                <span class="bullet"></span>
                <span>MANAJEMEN TIM</span>
            </div>
            <h1 class="barber-page-title">Kelola Master Barber</h1>
            <p class="barber-page-desc">Atur profil master barber, spesialisasi, status ketersediaan kursi, dan visibilitas booking.</p>
        </div>
        <button type="button" class="btn-add-barber" onclick="openAddBarberModal()">
            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                <line x1="12" y1="5" x2="12" y2="19"></line>
                <line x1="5" y1="12" x2="19" y2="12"></line>
            </svg>
            <span>Tambah Barber</span>
        </button>
    </div>

    {{-- Search Bar --}}
    <div class="barber-search-box">
        <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
            <circle cx="11" cy="11" r="8"></circle>
            <line x1="21" y1="21" x2="16.65" y2="16.65"></line>
        </svg>
        <input type="text" id="barberSearchInput" class="barber-search-input" placeholder="Cari nama barber atau spesialisasi..." value="{{ $search ?? '' }}">
    </div>

    {{-- Filter Pills --}}
    <div class="barber-filter-pills">
        <button type="button" class="pill-btn {{ empty($status) ? 'active' : '' }}" onclick="filterBarberByStatus('all', this)">
            Semua Barber ({{ $counts['all'] ?? 4 }})
        </button>
        <button type="button" class="pill-btn {{ ($status ?? '') === 'aktif' ? 'active' : '' }}" onclick="filterBarberByStatus('aktif', this)">
            Aktif ({{ $counts['aktif'] ?? 3 }})
        </button>
        <button type="button" class="pill-btn {{ ($status ?? '') === 'nonaktif' ? 'active' : '' }}" onclick="filterBarberByStatus('nonaktif', this)">
            Nonaktif ({{ $counts['nonaktif'] ?? 1 }})
        </button>
    </div>

    {{-- 2x2 Stats Grid --}}
    <div class="stats-2x2-grid">
        {{-- Card 1: Total Tim Kursi --}}
        <div class="stat-card-box">
            <div class="stat-card-head">
                <span class="stat-card-label">TOTAL TIM KURSI</span>
                <span class="stat-card-icon stat-icon-chair">💺</span>
            </div>
            <div class="stat-card-val-row">
                <span class="stat-card-num">{{ $stats['total_chairs'] ?? 4 }}</span>
                <span class="stat-card-sub">Profesional</span>
            </div>
        </div>

        {{-- Card 2: Kapasitas Reservasi --}}
        <div class="stat-card-box">
            <div class="stat-card-head">
                <span class="stat-card-label">KAPASITAS RESERVASI</span>
                <span class="stat-card-icon stat-icon-calendar">📅</span>
            </div>
            <div class="stat-card-val-row">
                <span class="stat-card-num">{{ $stats['capacity_per_day'] ?? 21 }}</span>
                <span class="stat-card-sub">Sesi/Hari</span>
            </div>
        </div>

        {{-- Card 3: Rata-Rata Rating --}}
        <div class="stat-card-box">
            <div class="stat-card-head">
                <span class="stat-card-label">RATA-RATA RATING</span>
                <span class="stat-card-icon stat-icon-star">★</span>
            </div>
            <div class="stat-card-val-row">
                <span class="stat-card-num" style="color: #0e1726;">
                    <span style="color: #ca8a04; font-size: 15px;">★</span> {{ $stats['avg_rating'] ?? '4.95' }}
                </span>
                <span class="stat-card-sub">/ 5.0</span>
            </div>
        </div>

        {{-- Card 4: Status Layanan --}}
        <div class="stat-card-box">
            <div class="stat-card-head">
                <span class="stat-card-label">STATUS LAYANAN</span>
                <span class="stat-card-icon stat-icon-shop">🏬</span>
            </div>
            <div class="stat-card-val-row">
                <span class="stat-card-num val-optimal">
                    {{ str_contains($stats['status_label'] ?? 'Optimal', 'Optimal') ? 'Optimal' : 'Terbatas' }}
                </span>
                <span class="stat-card-sub">({{ $stats['active_percentage'] ?? 75 }}%)</span>
            </div>
        </div>
    </div>

    {{-- Barber Cards Stack --}}
    <div class="barber-cards-stack" id="barberListContainer">
        @forelse($barbers as $b)
            @php
                $isCuti = ($b->availability_status === 'cuti' || !$b->is_active);
                $skills = $b->skills_array;
            @endphp
            <div class="barber-card {{ $isCuti ? 'inactive-card' : '' }}" 
                 data-barber-id="{{ $b->id }}"
                 data-status="{{ $isCuti ? 'nonaktif' : 'aktif' }}"
                 data-name="{{ strtolower($b->name) }}"
                 data-spec="{{ strtolower($b->specialization ?? '') }}">
                
                {{-- Top Row: Avatar & Profile Info --}}
                <div class="barber-card-top">
                    <div class="barber-avatar-wrap">
                        {{-- Stylish Photo Placeholder per Barber --}}
                        @if($b->id == 1)
                            <div style="width: 100%; height: 100%; background: linear-gradient(135deg, #1e293b, #0f172a); display: flex; align-items: center; justify-content: center; color: #f1c479; font-weight: 800; font-size: 20px;">
                                BR
                            </div>
                        @elseif($b->id == 2)
                            <div style="width: 100%; height: 100%; background: linear-gradient(135deg, #334155, #1e293b); display: flex; align-items: center; justify-content: center; color: #38bdf8; font-weight: 800; font-size: 20px;">
                                DW
                            </div>
                        @elseif($b->id == 3)
                            <div style="width: 100%; height: 100%; background: linear-gradient(135deg, #1e293b, #475569); display: flex; align-items: center; justify-content: center; color: #34d399; font-weight: 800; font-size: 20px;">
                                RS
                            </div>
                        @else
                            <div style="width: 100%; height: 100%; background: #64748b; display: flex; align-items: center; justify-content: center; color: #cbd5e1; font-weight: 800; font-size: 20px;">
                                FN
                            </div>
                        @endif
                    </div>

                    <div class="barber-details-wrap">
                        <div class="barber-badges-row">
                            <span class="badge-kursi">{{ strtoupper($b->chair_name) }}</span>
                            @if($isCuti)
                                <span class="badge-cuti">CUTI / NONAKTIF</span>
                                <span class="badge-role-junior">{{ $b->role_title ?? 'Junior Stylist' }}</span>
                            @else
                                <span class="badge-online">ONLINE</span>
                                <span class="badge-role-tag">{{ $b->role_title ?? 'Master Barber' }}</span>
                            @endif
                        </div>
                        <h2 class="barber-name-text">{{ $b->name }}</h2>
                        <div class="barber-spec-text">{{ $b->specialization ?? 'Barber Professional' }}</div>
                    </div>
                </div>

                {{-- Skills Pills (if not cuti or has skills) --}}
                @if(!$isCuti && count($skills) > 0)
                    <div class="skills-pill-list">
                        @foreach($skills as $skill)
                            <span class="skill-tag-pill">{{ $skill }}</span>
                        @endforeach
                    </div>
                @endif

                {{-- Alert Note for Cuti Barber --}}
                @if($isCuti)
                    <div class="barber-alert-box">
                        <div class="alert-box-title">
                            <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <path d="M17.94 17.94A10.07 10.07 0 0 1 12 20c-7 0-11-8-11-8a18.45 18.45 0 0 1 5.06-5.94M9.9 4.24A9.12 9.12 0 0 1 12 4c7 0 11 8 11 8a18.5 18.5 0 0 1-2.16 3.19m-6.72-1.07a3 3 0 1 1-4.24-4.24"></path>
                                <line x1="1" y1="1" x2="23" y2="23"></line>
                            </svg>
                            <span>Disembunyikan dari Form Reservasi</span>
                        </div>
                        <p class="alert-box-desc">
                            {{ $b->warning_note ?? 'Sedang masa pelatihan lanjutan atelier dan cuti operasional. Profil tidak muncul di web booking.' }}
                        </p>
                    </div>
                @else
                    {{-- Sesi Terjadwal & Rating Meta Row --}}
                    <div class="barber-meta-row">
                        <div class="meta-sesi">
                            <span>📅</span>
                            <span>{{ $b->effective_sessions_count }} Sesi Terjadwal</span>
                        </div>
                        <div class="meta-rating">
                            <span class="star-icon">★</span>
                            <span class="rating-bold">{{ number_format((float) ($b->rating ?: 5.0), 2) }}</span>
                            <span>({{ $b->review_count ?: 0 }} ulasan)</span>
                        </div>
                    </div>
                @endif

                {{-- Toggle Row: Terima Booking Publik --}}
                <div class="booking-toggle-row">
                    <div class="toggle-labels">
                        <span class="toggle-label-main">Terima Booking Publik</span>
                        <span class="toggle-label-sub" id="subLabel-{{ $b->id }}">
                            @if($b->accept_public_booking)
                                Tampil di portal reservasi pelanggan
                            @else
                                Saat ini nonaktif dan diarsipkan
                            @endif
                        </span>
                    </div>
                    <label class="switch-ui">
                        <input type="checkbox" 
                               id="toggleBooking-{{ $b->id }}"
                               {{ $b->accept_public_booking ? 'checked' : '' }}
                               onchange="toggleBarberBooking({{ $b->id }})">
                        <span class="slider-ui"></span>
                    </label>
                </div>

                {{-- Action Buttons --}}
                <div class="barber-actions-row">
                    @if($isCuti)
                        <button type="button" class="btn-card-gold" onclick="toggleBarberActive({{ $b->id }})">
                            Aktifkan Kembali
                        </button>
                        <button type="button" class="btn-card-secondary" onclick="openEditBarberModal({{ $b->id }})">
                            Detail Riwayat
                        </button>
                    @else
                        <button type="button" class="btn-card-secondary" onclick="openEditBarberModal({{ $b->id }})">
                            Edit Profil
                        </button>
                        <a href="{{ route('admin.dashboard') }}?barber={{ $b->id }}" class="btn-card-primary">
                            Kelola Jadwal
                        </a>
                    @endif
                </div>

            </div>
        @empty
            <div style="text-align: center; padding: 40px 10px; color: #94a3b8; font-size: 13px;">
                Tidak ada data barber yang sesuai filter.
            </div>
        @endforelse
    </div>

    {{-- Bottom Golden Sync Banner --}}
    <div class="barber-sync-banner">
        <div class="sync-icon-sq">
            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <rect x="2" y="3" width="20" height="14" rx="2" ry="2"></rect>
                <line x1="8" y1="21" x2="16" y2="21"></line>
                <line x1="12" y1="17" x2="12" y2="21"></line>
            </svg>
        </div>
        <div class="sync-banner-content">
            <h4 class="sync-banner-title">SINKRONISASI VISIBILITAS AKTIF</h4>
            <p class="sync-banner-text">
                Setiap perubahan status kursi atau pengaktifan barber langsung diperbarui dalam 1 detik ke sistem booking pelanggan.
            </p>
        </div>
    </div>
</div>

{{-- Modal Tambah Barber Baru --}}
<div id="modalAddBarberNew" class="barber-modal">
    <div class="barber-modal-card">
        <div class="modal-head">
            <h3>+ Tambah Barber Baru</h3>
            <button type="button" class="modal-close-btn" onclick="closeAddBarberModal()">&times;</button>
        </div>
        <form action="{{ route('admin.barbers.store') }}" method="POST">
            @csrf
            <div class="form-group-barber" style="margin-bottom: 10px;">
                <label>Nama Lengkap Barber *</label>
                <input type="text" name="name" class="form-input-barber" placeholder="Contoh: Bramantyo" required>
            </div>
            <div class="form-group-barber" style="margin-bottom: 10px;">
                <label>Nomor Kursi</label>
                <input type="number" name="chair_number" class="form-input-barber" placeholder="1, 2, 3, ..." min="1">
            </div>
            <div class="form-group-barber" style="margin-bottom: 10px;">
                <label>Jabatan / Tag Tim</label>
                <input type="text" name="role_title" class="form-input-barber" placeholder="Contoh: Founder / Senior, Master Barber">
            </div>
            <div class="form-group-barber" style="margin-bottom: 10px;">
                <label>Spesialisasi</label>
                <input type="text" name="specialization" class="form-input-barber" placeholder="Contoh: Atelier Specialist & Grooming Director">
            </div>
            <div class="form-group-barber" style="margin-bottom: 10px;">
                <label>Keahlian / Skill Tags (Pisahkan dengan koma)</label>
                <input type="text" name="skills_tags" class="form-input-barber" placeholder="Classic Scissor Cut, Gentlemen Taper Fade">
            </div>
            <div class="form-group-barber" style="margin-bottom: 14px;">
                <label>Status Ketersediaan</label>
                <select name="availability_status" class="form-input-barber">
                    <option value="online">ONLINE</option>
                    <option value="cuti">CUTI</option>
                    <option value="nonaktif">NONAKTIF</option>
                </select>
            </div>
            <button type="submit" class="btn-card-primary" style="width: 100%; height: 40px; font-size: 13px;">
                Simpan Data Barber
            </button>
        </form>
    </div>
</div>

{{-- Modal Edit Barber --}}
<div id="modalEditBarber" class="barber-modal">
    <div class="barber-modal-card">
        <div class="modal-head">
            <h3>Edit Profil Barber</h3>
            <button type="button" class="modal-close-btn" onclick="closeEditBarberModal()">&times;</button>
        </div>
        <form id="formEditBarber" method="POST">
            @csrf
            @method('PUT')
            <input type="hidden" id="editBarberId" name="barber_id">
            <div class="form-group-barber" style="margin-bottom: 10px;">
                <label>Nama Barber</label>
                <input type="text" id="editName" name="name" class="form-input-barber" required>
            </div>
            <div class="form-group-barber" style="margin-bottom: 10px;">
                <label>Nomor Kursi</label>
                <input type="number" id="editChairNumber" name="chair_number" class="form-input-barber" min="1">
            </div>
            <div class="form-group-barber" style="margin-bottom: 10px;">
                <label>Jabatan / Tag Tim</label>
                <input type="text" id="editRoleTitle" name="role_title" class="form-input-barber">
            </div>
            <div class="form-group-barber" style="margin-bottom: 10px;">
                <label>Spesialisasi</label>
                <input type="text" id="editSpecialization" name="specialization" class="form-input-barber">
            </div>
            <div class="form-group-barber" style="margin-bottom: 10px;">
                <label>Keahlian / Skill Tags (Pisahkan koma)</label>
                <input type="text" id="editSkillsTags" name="skills_tags" class="form-input-barber">
            </div>
            <div class="form-group-barber" style="margin-bottom: 10px;">
                <label>Status Ketersediaan</label>
                <select id="editAvailabilityStatus" name="availability_status" class="form-input-barber">
                    <option value="online">ONLINE</option>
                    <option value="cuti">CUTI</option>
                    <option value="nonaktif">NONAKTIF</option>
                </select>
            </div>
            <div class="form-group-barber" style="margin-bottom: 14px;">
                <label>Catatan Peringatan / Cuti (Bila Ada)</label>
                <textarea id="editWarningNote" name="warning_note" class="form-input-barber" style="height: 60px; padding: 6px 12px;"></textarea>
            </div>
            <button type="submit" class="btn-card-primary" style="width: 100%; height: 40px; font-size: 13px;">
                Perbarui Barber
            </button>
        </form>
    </div>
</div>

{{-- Toast Feedback Notification --}}
<div id="toastNotification" class="toast-box">
    <span id="toastIcon">✓</span>
    <span id="toastMsg">Status berhasil diperbarui</span>
</div>
@endsection

@push('scripts')
<script>
    // Live Client-side search and filtering
    const searchInput = document.getElementById('barberSearchInput');
    const cards = document.querySelectorAll('.barber-card');

    let currentStatusFilter = '{{ $status ?? "all" }}';

    function applyFilters() {
        const query = searchInput.value.toLowerCase().trim();

        cards.forEach(card => {
            const name = card.getAttribute('data-name') || '';
            const spec = card.getAttribute('data-spec') || '';
            const status = card.getAttribute('data-status') || '';

            const matchesQuery = !query || name.includes(query) || spec.includes(query);
            const matchesStatus = (currentStatusFilter === 'all') || (status === currentStatusFilter);

            if (matchesQuery && matchesStatus) {
                card.style.display = 'flex';
            } else {
                card.style.display = 'none';
            }
        });
    }

    if (searchInput) {
        searchInput.addEventListener('input', applyFilters);
    }

    function filterBarberByStatus(status, btnElement) {
        currentStatusFilter = status;

        document.querySelectorAll('.barber-filter-pills .pill-btn').forEach(btn => {
            btn.classList.remove('active');
        });
        btnElement.classList.add('active');

        applyFilters();
    }

    // Modal Handlers
    function openAddBarberModal() {
        document.getElementById('modalAddBarberNew').classList.add('active');
    }

    function closeAddBarberModal() {
        document.getElementById('modalAddBarberNew').classList.remove('active');
    }

    const barbersData = @json($barbers->keyBy('id'));

    function openEditBarberModal(id) {
        const barber = barbersData[id];
        if (!barber) return;

        document.getElementById('editBarberId').value = barber.id;
        document.getElementById('editName').value = barber.name || '';
        document.getElementById('editChairNumber').value = barber.chair_number || '';
        document.getElementById('editRoleTitle').value = barber.role_title || '';
        document.getElementById('editSpecialization').value = barber.specialization || '';
        document.getElementById('editSkillsTags').value = barber.skills_tags || '';
        document.getElementById('editAvailabilityStatus').value = barber.availability_status || 'online';
        document.getElementById('editWarningNote').value = barber.warning_note || '';

        document.getElementById('formEditBarber').action = '/admin/barbers/' + barber.id;
        document.getElementById('modalEditBarber').classList.add('active');
    }

    function closeEditBarberModal() {
        document.getElementById('modalEditBarber').classList.remove('active');
    }

    // Toast Notification helper
    function showToast(message, isError = false) {
        const toast = document.getElementById('toastNotification');
        const msgEl = document.getElementById('toastMsg');
        const iconEl = document.getElementById('toastIcon');

        msgEl.innerText = message;
        iconEl.innerText = isError ? '✕' : '✓';
        toast.style.background = isError ? '#991b1b' : '#0e1726';

        toast.classList.add('show');
        setTimeout(() => {
            toast.classList.remove('show');
        }, 2600);
    }

    // Toggle Public Booking
    async function toggleBarberBooking(id) {
        const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
        const subLabel = document.getElementById('subLabel-' + id);

        try {
            const res = await fetch(`/admin/barbers/${id}/toggle-booking`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': csrfToken,
                    'Accept': 'application/json'
                }
            });
            const data = await res.json();
            if (data.success) {
                if (subLabel) {
                    subLabel.innerText = data.accept_public_booking 
                        ? 'Tampil di portal reservasi pelanggan' 
                        : 'Saat ini nonaktif dan diarsipkan';
                }
                showToast(data.message || 'Status booking publik diperbarui');
            } else {
                showToast('Gagal mengubah status', true);
            }
        } catch (e) {
            showToast('Koneksi terputus', true);
        }
    }

    // Toggle Active / Cuti
    async function toggleBarberActive(id) {
        const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

        try {
            const res = await fetch(`/admin/barbers/${id}/toggle-active`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': csrfToken,
                    'Accept': 'application/json'
                }
            });
            const data = await res.json();
            if (data.success) {
                showToast(data.message || 'Barber berhasil diaktifkan kembali');
                setTimeout(() => window.location.reload(), 800);
            }
        } catch (e) {
            showToast('Gagal memproses aksi', true);
        }
    }
</script>
@endpush

