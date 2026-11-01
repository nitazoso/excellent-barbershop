@extends('layouts.admin')

@section('title', 'Kelola Katalog Layanan — Excellent Atelier')

@push('styles')
<style>
    /* Styling khusus Kelola Katalog Layanan - Pixel-Perfect Figma match */
    .service-page-container {
        display: flex;
        flex-direction: column;
        gap: 16px;
    }

    /* Eyebrow & Sync Header */
    .service-eyebrow-row {
        display: flex;
        justify-content: space-between;
        align-items: center;
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
    }

    .vertical-bar-gold {
        width: 3.5px;
        height: 13px;
        background-color: #cca04b;
        border-radius: 1px;
        display: inline-block;
    }

    .sync-status-badge {
        font-size: 9px;
        font-weight: 800;
        color: #a67c37;
        background: #fef9ee;
        border: 1px solid #fdecd0;
        border-radius: 20px;
        padding: 3px 8px;
        letter-spacing: 0.06em;
        text-transform: uppercase;
        display: flex;
        align-items: center;
        gap: 5px;
    }

    .sync-status-badge .sync-dot {
        font-size: 10px;
        color: #a67c37;
    }

    .service-page-title {
        font-size: 20px;
        font-weight: 800;
        color: #0e1726;
        line-height: 1.2;
        margin-top: 4px;
        margin-bottom: 4px;
    }

    .service-page-desc {
        font-size: 11px;
        color: #64748b;
        line-height: 1.45;
    }

    /* Big Add Button */
    .btn-add-service-big {
        background: #0e1726;
        color: #ffffff;
        border: 1px solid #1e293b;
        border-radius: 8px;
        padding: 13px 18px;
        font-size: 11.5px;
        font-weight: 800;
        letter-spacing: 0.08em;
        text-transform: uppercase;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 8px;
        cursor: pointer;
        transition: all 0.2s;
        box-shadow: 0 4px 12px rgba(14, 23, 38, 0.15);
    }

    .btn-add-service-big:hover {
        background: #1a2a44;
        transform: translateY(-1px);
    }

    .btn-add-service-big .circle-plus {
        width: 17px;
        height: 17px;
        border: 1.5px solid #cca04b;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        color: #cca04b;
        font-size: 12px;
        font-weight: 900;
    }

    /* 2x2 Stats Grid */
    .service-stats-grid {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 10px;
    }

    .service-stat-card {
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

    .icon-scissors { background: #fef3c7; color: #b45309; }
    .icon-check { background: #e0f2fe; color: #0284c7; }
    .icon-eye-slash { background: #f1f5f9; color: #64748b; }
    .icon-clock { background: #ffedd5; color: #ea580c; }

    .service-stat-val {
        display: flex;
        align-items: baseline;
        gap: 4px;
        margin-top: 6px;
    }

    .stat-num-big {
        font-size: 18px;
        font-weight: 800;
        color: #0e1726;
        line-height: 1;
    }

    .stat-unit {
        font-size: 10.5px;
        color: #8c9aa8;
        font-weight: 600;
    }

    .stat-sub-detail {
        font-size: 9.5px;
        color: #94a3b8;
        font-weight: 600;
        margin-top: 2px;
    }

    /* Category Filter Tabs */
    .category-tabs-scroll {
        display: flex;
        align-items: center;
        gap: 8px;
        overflow-x: auto;
        padding-bottom: 2px;
    }

    .category-tabs-scroll::-webkit-scrollbar {
        display: none;
    }

    .cat-tab-btn {
        background: #ffffff;
        color: #64748b;
        border: 1px solid #e2e8f0;
        border-radius: 20px;
        padding: 6px 14px;
        font-size: 10.5px;
        font-weight: 700;
        cursor: pointer;
        white-space: nowrap;
        text-decoration: none;
        transition: all 0.2s;
    }

    .cat-tab-btn.active {
        background: #0e1726;
        color: #ffffff;
        border-color: #0e1726;
    }

    /* Search & Status Bar */
    .search-filter-row {
        display: flex;
        gap: 8px;
        align-items: center;
    }

    .service-search-box {
        position: relative;
        flex: 1;
    }

    .service-search-box svg {
        position: absolute;
        left: 12px;
        top: 50%;
        transform: translateY(-50%);
        color: #94a3b8;
    }

    .service-search-input {
        width: 100%;
        height: 38px;
        padding: 0 12px 0 34px;
        background: #ffffff;
        border: 1px solid #e2e8f0;
        border-radius: 8px;
        font-size: 11.5px;
        color: #0e1726;
        outline: none;
    }

    .service-search-input:focus {
        border-color: #cca04b;
    }

    .service-status-select {
        height: 38px;
        background: #ffffff;
        border: 1px solid #e2e8f0;
        border-radius: 8px;
        padding: 0 10px;
        font-size: 10.5px;
        font-weight: 700;
        color: #334155;
        outline: none;
        cursor: pointer;
    }

    /* Service Cards */
    .service-cards-stack {
        display: flex;
        flex-direction: column;
        gap: 12px;
    }

    .service-item-card {
        background: #ffffff;
        border: 1px solid #eef0f4;
        border-radius: 12px;
        padding: 16px;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.02);
        display: flex;
        flex-direction: column;
        gap: 10px;
        transition: transform 0.2s, box-shadow 0.2s;
        position: relative;
    }

    .service-item-card:hover {
        box-shadow: 0 4px 14px rgba(0, 0, 0, 0.05);
    }

    .service-item-card.inactive-service {
        background: #fafbfc;
        border-color: #e2e8f0;
    }

    .service-card-top {
        display: flex;
        justify-content: space-between;
        align-items: center;
    }

    .badge-cat-black {
        background: #0e1726;
        color: #ffffff;
        font-size: 8.5px;
        font-weight: 800;
        letter-spacing: 0.06em;
        text-transform: uppercase;
        border-radius: 4px;
        padding: 3px 8px;
    }

    .card-actions-top {
        display: flex;
        align-items: center;
        gap: 6px;
        position: relative;
    }

    .btn-icon-sq {
        width: 26px;
        height: 26px;
        background: #f1f5f9;
        border: none;
        border-radius: 4px;
        display: flex;
        align-items: center;
        justify-content: center;
        color: #64748b;
        cursor: pointer;
        transition: all 0.2s;
    }

    .btn-icon-sq:hover {
        background: #e2e8f0;
        color: #0e1726;
    }

    /* Special Badges */
    .badge-special-bestseller {
        display: inline-flex;
        align-items: center;
        gap: 4px;
        font-size: 8.5px;
        font-weight: 800;
        color: #854d0e;
        background: #cca04b;
        color: #0e1726;
        border-radius: 4px;
        padding: 2px 7px;
        align-self: flex-start;
    }

    .badge-special-bestseller-gold {
        display: inline-flex;
        align-items: center;
        gap: 4px;
        font-size: 8.5px;
        font-weight: 800;
        background: #cca04b;
        color: #0e1726;
        border-radius: 4px;
        padding: 2.5px 7px;
        align-self: flex-start;
    }

    .badge-special-restock {
        display: inline-flex;
        align-items: center;
        gap: 4px;
        font-size: 8.5px;
        font-weight: 800;
        color: #991b1b;
        background: #fef2f2;
        border: 1px solid #fecaca;
        border-radius: 4px;
        padding: 2.5px 7px;
        align-self: flex-start;
    }

    .service-title-text {
        font-size: 15.5px;
        font-weight: 800;
        color: #0e1726;
        line-height: 1.25;
    }

    .service-desc-text {
        font-size: 11px;
        color: #64748b;
        line-height: 1.45;
    }

    /* Meta Duration & Price Row */
    .service-meta-price-row {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding-top: 10px;
        border-top: 1px solid #f1f5f9;
    }

    .price-duration-left {
        display: flex;
        align-items: center;
        gap: 8px;
    }

    .duration-label {
        font-size: 11px;
        font-weight: 700;
        color: #64748b;
        display: flex;
        align-items: center;
        gap: 4px;
    }

    .dot-separator {
        color: #cbd5e1;
        font-size: 12px;
    }

    .price-bold {
        font-size: 15px;
        font-weight: 800;
        color: #0e1726;
    }

    .price-nett {
        font-size: 9.5px;
        color: #94a3b8;
        font-weight: 600;
    }

    .badge-service-status {
        font-size: 8.5px;
        font-weight: 800;
        letter-spacing: 0.05em;
        padding: 2px 7px;
        border-radius: 4px;
    }

    .badge-status-aktif {
        background: #fef9ee;
        color: #a67c37;
        border: 1px solid #fdecd0;
    }

    .badge-status-nonaktif {
        background: #f1f5f9;
        color: #64748b;
        border: 1px solid #e2e8f0;
    }

    /* Widget Status Row */
    .widget-status-row {
        background: #fafafa;
        border: 1px solid #f1f5f9;
        border-radius: 8px;
        padding: 9px 12px;
        display: flex;
        justify-content: space-between;
        align-items: center;
    }

    .widget-text-group {
        display: flex;
        flex-direction: column;
        gap: 1px;
    }

    .widget-label-tiny {
        font-size: 8.5px;
        font-weight: 800;
        letter-spacing: 0.08em;
        text-transform: uppercase;
        color: #8c9aa8;
    }

    .widget-desc-main {
        font-size: 11px;
        font-weight: 600;
        color: #0e1726;
    }

    /* Custom Switch Toggle matching Figma (Black track, gold thumb when ON) */
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
        background-color: #e2e8f0;
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
        background-color: #ffffff;
        border-radius: 50%;
        transition: 0.25s;
        box-shadow: 0 1px 3px rgba(0,0,0,0.15);
    }

    .switch-ui input:checked + .slider-ui {
        background-color: #0e1726;
    }

    .switch-ui input:checked + .slider-ui:before {
        transform: translateX(16px);
        background-color: #cca04b;
    }

    /* Dropdown Menu for 3 dots */
    .dropdown-menu-custom {
        display: none;
        position: absolute;
        right: 0;
        top: 32px;
        background: #ffffff;
        border: 1px solid #e2e8f0;
        border-radius: 8px;
        box-shadow: 0 10px 25px rgba(0, 0, 0, 0.12);
        z-index: 100;
        min-width: 140px;
        overflow: hidden;
    }

    .dropdown-menu-custom.active {
        display: block;
    }

    .dropdown-item-btn {
        display: block;
        width: 100%;
        text-align: left;
        padding: 9px 12px;
        font-size: 11px;
        color: #334155;
        background: none;
        border: none;
        cursor: pointer;
        font-weight: 600;
        transition: background 0.15s;
    }

    .dropdown-item-btn:hover {
        background: #f8fafc;
        color: #0e1726;
    }

    .dropdown-item-btn.btn-danger-item {
        color: #dc2626;
    }

    .dropdown-item-btn.btn-danger-item:hover {
        background: #fef2f2;
    }

    /* Modal Form Styles */
    .service-modal {
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

    .service-modal.active {
        display: flex;
    }

    .service-modal-card {
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

    .form-group-svc {
        display: flex;
        flex-direction: column;
        gap: 4px;
    }

    .form-group-svc label {
        font-size: 11px;
        font-weight: 700;
        color: #334155;
    }

    .form-input-svc {
        height: 38px;
        border: 1px solid #e2e8f0;
        border-radius: 8px;
        padding: 0 12px;
        font-size: 12px;
        color: #0e1726;
        outline: none;
    }

    .form-input-svc:focus {
        border-color: #cca04b;
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
<div class="service-page-container">
    {{-- Eyebrow & Sync Row --}}
    <div class="service-eyebrow-row">
        <div class="eyebrow-text">
            <span class="vertical-bar-gold"></span>
            <span>KATALOG MASTER</span>
        </div>
        <div class="sync-status-badge">
            <span class="sync-dot">•</span>
            <span>TERAKHIR SYNC {{ $stats['last_sync'] ?? '09:41' }}</span>
        </div>
    </div>

    {{-- Title & Desc --}}
    <div>
        <h1 class="service-page-title">Kelola Katalog Layanan</h1>
        <p class="service-page-desc">Atur daftar layanan grooming, durasi, harga resmi, dan status aktif/nonaktif.</p>
    </div>

    {{-- Big Add Button --}}
    <button type="button" class="btn-add-service-big" onclick="openAddServiceModal()">
        <span class="circle-plus">+</span>
        <span>TAMBAH LAYANAN BARU</span>
    </button>

    {{-- 2x2 Stats Grid --}}
    <div class="service-stats-grid">
        {{-- Card 1: Total Layanan --}}
        <div class="service-stat-card">
            <div class="stat-card-head">
                <span class="stat-card-label">TOTAL LAYANAN</span>
                <span class="stat-card-icon icon-scissors">✂️</span>
            </div>
            <div>
                <div class="service-stat-val">
                    <span class="stat-num-big">{{ $stats['total'] ?? 6 }}</span>
                    <span class="stat-unit">Layanan</span>
                </div>
                <div class="stat-sub-detail">{{ $stats['active_categories_count'] ?? 3 }} Kategori Aktif</div>
            </div>
        </div>

        {{-- Card 2: Layanan Aktif --}}
        <div class="service-stat-card">
            <div class="stat-card-head">
                <span class="stat-card-label">LAYANAN AKTIF</span>
                <span class="stat-card-icon icon-check">✓</span>
            </div>
            <div>
                <div class="service-stat-val">
                    <span class="stat-num-big">{{ $stats['active_count'] ?? 5 }}</span>
                    <span class="stat-unit">Layanan</span>
                </div>
                <div class="stat-sub-detail">Live di Reservasi</div>
            </div>
        </div>

        {{-- Card 3: Nonaktif --}}
        <div class="service-stat-card">
            <div class="stat-card-head">
                <span class="stat-card-label">NONAKTIF</span>
                <span class="stat-card-icon icon-eye-slash">👁️</span>
            </div>
            <div>
                <div class="service-stat-val">
                    <span class="stat-num-big">{{ $stats['inactive_count'] ?? 1 }}</span>
                    <span class="stat-unit">Layanan</span>
                </div>
                <div class="stat-sub-detail">Disembunyikan</div>
            </div>
        </div>

        {{-- Card 4: Rata-Rata Durasi --}}
        <div class="service-stat-card">
            <div class="stat-card-head">
                <span class="stat-card-label">RATA-RATA DURASI</span>
                <span class="stat-card-icon icon-clock">🕒</span>
            </div>
            <div>
                <div class="service-stat-val">
                    <span class="stat-num-big">{{ $stats['avg_duration'] ?? 45 }}</span>
                    <span class="stat-unit">Menit</span>
                </div>
                <div class="stat-sub-detail">Rentang {{ $stats['min_duration'] ?? 30 }}-{{ $stats['max_duration'] ?? 70 }} Mnt</div>
            </div>
        </div>
    </div>

    {{-- Category Filter Tabs --}}
    <div class="category-tabs-scroll">
        <button type="button" class="cat-tab-btn {{ empty($category) ? 'active' : '' }}" onclick="filterServiceByCategory('all', this)">
            SEMUA ({{ $categoryCounts['all'] ?? 6 }})
        </button>
        <button type="button" class="cat-tab-btn {{ ($category ?? '') === 'haircut_styling' ? 'active' : '' }}" onclick="filterServiceByCategory('haircut_styling', this)">
            HAIRCUT & STYLING ({{ $categoryCounts[\App\Models\Service::CAT_HAIRCUT] ?? 3 }})
        </button>
        <button type="button" class="cat-tab-btn {{ ($category ?? '') === 'beard_shaving' ? 'active' : '' }}" onclick="filterServiceByCategory('beard_shaving', this)">
            BEARD & SHAVING ({{ $categoryCounts[\App\Models\Service::CAT_BEARD] ?? 2 }})
        </button>
    </div>

    {{-- Search & Status Filter Row --}}
    <div class="search-filter-row">
        <div class="service-search-box">
            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <circle cx="11" cy="11" r="8"></circle>
                <line x1="21" y1="21" x2="16.65" y2="16.65"></line>
            </svg>
            <input type="text" id="serviceSearchInput" class="service-search-input" placeholder="Cari nama layanan..." value="{{ $search ?? '' }}">
        </div>
        <select id="serviceStatusFilter" class="service-status-select" onchange="filterServiceByStatus(this.value)">
            <option value="all" {{ empty($status) ? 'selected' : '' }}>SEMUA STATUS ▾</option>
            <option value="aktif" {{ ($status ?? '') === 'aktif' ? 'selected' : '' }}>AKTIF</option>
            <option value="nonaktif" {{ ($status ?? '') === 'nonaktif' ? 'selected' : '' }}>NONAKTIF</option>
        </select>
    </div>

    {{-- Service Cards Stack --}}
    <div class="service-cards-stack" id="serviceListContainer">
        @forelse($services as $s)
            @php
                $isAktif = $s->is_active;
                $hasWidget = $s->show_in_widget;
            @endphp
            <div class="service-item-card {{ !$isAktif ? 'inactive-service' : '' }}"
                 data-service-id="{{ $s->id }}"
                 data-category="{{ $s->category }}"
                 data-status="{{ $isAktif ? 'aktif' : 'nonaktif' }}"
                 data-name="{{ strtolower($s->name) }}"
                 data-desc="{{ strtolower($s->description ?? '') }}">
                
                {{-- Top Row: Category Badge & Actions --}}
                <div class="service-card-top">
                    <span class="badge-cat-black">{{ $s->category_label }}</span>
                    <div class="card-actions-top">
                        <button type="button" class="btn-icon-sq" title="Edit Layanan" onclick="openEditServiceModal({{ $s->id }})">
                            <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round">
                                <path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"></path>
                                <path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"></path>
                            </svg>
                        </button>
                        <button type="button" class="btn-icon-sq" title="Opsi" onclick="toggleCardDropdown({{ $s->id }})">
                            <svg width="13" height="13" viewBox="0 0 24 24" fill="currentColor">
                                <circle cx="12" cy="12" r="1.5"></circle>
                                <circle cx="12" cy="6" r="1.5"></circle>
                                <circle cx="12" cy="18" r="1.5"></circle>
                            </svg>
                        </button>

                        {{-- Dropdown for 3 dots --}}
                        <div id="dropdown-{{ $s->id }}" class="dropdown-menu-custom">
                            <button type="button" class="dropdown-item-btn" onclick="openEditServiceModal({{ $s->id }})">
                                ✏️ Edit Layanan
                            </button>
                            <button type="button" class="dropdown-item-btn" onclick="toggleServiceActiveState({{ $s->id }})">
                                {{ $isAktif ? '🚫 Nonaktifkan' : '✓ Aktifkan' }}
                            </button>
                            <form action="{{ route('admin.services.destroy', $s) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus layanan ini?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="dropdown-item-btn btn-danger-item">
                                    🗑️ Hapus Layanan
                                </button>
                            </form>
                        </div>
                    </div>
                </div>

                {{-- Special Badge (BEST SELLER or RESTOCK BAHAN) --}}
                @if($s->badge)
                    @if(str_contains(strtoupper($s->badge), 'BEST SELLER'))
                        <div class="badge-special-bestseller-gold">
                            <span>★</span>
                            <span>BEST SELLER</span>
                        </div>
                    @elseif(str_contains(strtoupper($s->badge), 'RESTOCK'))
                        <div class="badge-special-restock">
                            <svg width="11" height="11" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M10.29 3.86L1.82 18a2 2 0 0 0 1.71 3h16.94a2 2 0 0 0 1.71-3L13.71 3.86a2 2 0 0 0-3.42 0z"></path><line x1="12" y1="9" x2="12" y2="13"></line><line x1="12" y1="17" x2="12.01" y2="17"></line></svg>
                            <span>RESTOCK BAHAN</span>
                        </div>
                    @else
                        <div class="badge-special-bestseller-gold">
                            <span>★</span>
                            <span>{{ strtoupper($s->badge) }}</span>
                        </div>
                    @endif
                @endif

                {{-- Title & Description --}}
                <h3 class="service-title-text">{{ $s->name }}</h3>
                <p class="service-desc-text">{{ $s->description }}</p>

                {{-- Duration & Price Row --}}
                <div class="service-meta-price-row">
                    <div class="price-duration-left">
                        <div class="duration-label">
                            <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round">
                                <circle cx="12" cy="12" r="10"></circle>
                                <polyline points="12 6 12 12 16 14"></polyline>
                            </svg>
                            <span>{{ $s->duration_minutes }} MNT</span>
                        </div>
                        <span class="dot-separator">•</span>
                        <div class="price-bold">{{ $s->formatted_price }}</div>
                        <span class="price-nett">Nett</span>
                    </div>
                    <div>
                        @if($isAktif)
                            <span class="badge-service-status badge-status-aktif">AKTIF</span>
                        @else
                            <span class="badge-service-status badge-status-nonaktif">NONAKTIF</span>
                        @endif
                    </div>
                </div>

                {{-- Status Reservasi Widget Row --}}
                <div class="widget-status-row">
                    <div class="widget-text-group">
                        <span class="widget-label-tiny">STATUS RESERVASI</span>
                        <span class="widget-desc-main" id="widgetDesc-{{ $s->id }}">
                            {{ $hasWidget ? 'Live di Online Widget' : 'Disembunyikan dari Widget' }}
                        </span>
                    </div>
                    <label class="switch-ui">
                        <input type="checkbox"
                               id="toggleWidget-{{ $s->id }}"
                               {{ $hasWidget ? 'checked' : '' }}
                               onchange="toggleWidgetVisibility({{ $s->id }})">
                        <span class="slider-ui"></span>
                    </label>
                </div>

            </div>
        @empty
            <div style="text-align: center; padding: 40px 10px; color: #94a3b8; font-size: 13px;">
                Tidak ada data layanan yang sesuai filter.
            </div>
        @endforelse
    </div>
</div>

{{-- Modal Tambah Layanan Baru --}}
<div id="modalAddServiceNew" class="service-modal">
    <div class="service-modal-card">
        <div class="modal-head">
            <h3>+ Tambah Layanan Baru</h3>
            <button type="button" class="modal-close-btn" onclick="closeAddServiceModal()">&times;</button>
        </div>
        <form action="{{ route('admin.services.store') }}" method="POST">
            @csrf
            <div class="form-group-svc" style="margin-bottom: 10px;">
                <label>Nama Layanan *</label>
                <input type="text" name="name" class="form-input-svc" placeholder="Contoh: Signature Royal Grooming" required>
            </div>
            <div class="form-group-svc" style="margin-bottom: 10px;">
                <label>Kategori Layanan *</label>
                <select name="category" class="form-input-svc" required>
                    <option value="haircut_styling">HAIRCUT & STYLING</option>
                    <option value="beard_shaving">BEARD & SHAVING</option>
                    <option value="treatment_others">TREATMENT & DETOX</option>
                </select>
            </div>
            <div class="form-group-svc" style="margin-bottom: 10px;">
                <label>Badge Tambahan (Opsional)</label>
                <input type="text" name="badge" class="form-input-svc" placeholder="BEST SELLER / RESTOCK BAHAN">
            </div>
            <div class="form-group-svc" style="margin-bottom: 10px;">
                <label>Harga Resmi (Rp) *</label>
                <input type="number" name="price" class="form-input-svc" placeholder="120000" min="0" required>
            </div>
            <div class="form-group-svc" style="margin-bottom: 10px;">
                <label>Durasi (Menit) *</label>
                <input type="number" name="duration_minutes" class="form-input-svc" placeholder="60" min="5" required>
            </div>
            <div class="form-group-svc" style="margin-bottom: 14px;">
                <label>Deskripsi Layanan</label>
                <textarea name="description" class="form-input-svc" style="height: 60px; padding: 6px 12px;" placeholder="Penjelasan proses dan manfaat layanan..."></textarea>
            </div>
            <button type="submit" class="btn-add-service-big" style="width: 100%; height: 42px;">
                SIMPAN LAYANAN BARU
            </button>
        </form>
    </div>
</div>

{{-- Modal Edit Layanan --}}
<div id="modalEditService" class="service-modal">
    <div class="service-modal-card">
        <div class="modal-head">
            <h3>Edit Layanan Grooming</h3>
            <button type="button" class="modal-close-btn" onclick="closeEditServiceModal()">&times;</button>
        </div>
        <form id="formEditService" method="POST">
            @csrf
            @method('PUT')
            <input type="hidden" id="editServiceId" name="service_id">
            <div class="form-group-svc" style="margin-bottom: 10px;">
                <label>Nama Layanan *</label>
                <input type="text" id="editServiceName" name="name" class="form-input-svc" required>
            </div>
            <div class="form-group-svc" style="margin-bottom: 10px;">
                <label>Kategori Layanan *</label>
                <select id="editServiceCategory" name="category" class="form-input-svc" required>
                    <option value="haircut_styling">HAIRCUT & STYLING</option>
                    <option value="beard_shaving">BEARD & SHAVING</option>
                    <option value="treatment_others">TREATMENT & DETOX</option>
                </select>
            </div>
            <div class="form-group-svc" style="margin-bottom: 10px;">
                <label>Badge Tambahan</label>
                <input type="text" id="editServiceBadge" name="badge" class="form-input-svc">
            </div>
            <div class="form-group-svc" style="margin-bottom: 10px;">
                <label>Harga Resmi (Rp) *</label>
                <input type="number" id="editServicePrice" name="price" class="form-input-svc" min="0" required>
            </div>
            <div class="form-group-svc" style="margin-bottom: 10px;">
                <label>Durasi (Menit) *</label>
                <input type="number" id="editServiceDuration" name="duration_minutes" class="form-input-svc" min="5" required>
            </div>
            <div class="form-group-svc" style="margin-bottom: 14px;">
                <label>Deskripsi Layanan</label>
                <textarea id="editServiceDesc" name="description" class="form-input-svc" style="height: 60px; padding: 6px 12px;"></textarea>
            </div>
            <button type="submit" class="btn-add-service-big" style="width: 100%; height: 42px;">
                PERBARUI LAYANAN
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
    const searchInput = document.getElementById('serviceSearchInput');
    const serviceCards = document.querySelectorAll('.service-item-card');

    let currentCategoryFilter = '{{ $category ?? "all" }}';
    let currentStatusFilter = '{{ $status ?? "all" }}';

    function applyServiceFilters() {
        const query = searchInput.value.toLowerCase().trim();

        serviceCards.forEach(card => {
            const name = card.getAttribute('data-name') || '';
            const desc = card.getAttribute('data-desc') || '';
            const cat = card.getAttribute('data-category') || '';
            const stat = card.getAttribute('data-status') || '';

            const matchesQuery = !query || name.includes(query) || desc.includes(query);
            const matchesCat = (currentCategoryFilter === 'all') || (cat === currentCategoryFilter);
            const matchesStat = (currentStatusFilter === 'all') || (stat === currentStatusFilter);

            if (matchesQuery && matchesCat && matchesStat) {
                card.style.display = 'flex';
            } else {
                card.style.display = 'none';
            }
        });
    }

    if (searchInput) {
        searchInput.addEventListener('input', applyServiceFilters);
    }

    function filterServiceByCategory(cat, btnElement) {
        currentCategoryFilter = cat;

        document.querySelectorAll('.category-tabs-scroll .cat-tab-btn').forEach(btn => {
            btn.classList.remove('active');
        });
        btnElement.classList.add('active');

        applyServiceFilters();
    }

    function filterServiceByStatus(status) {
        currentStatusFilter = status;
        applyServiceFilters();
    }

    // Dropdown for 3 dots
    function toggleCardDropdown(id) {
        const dd = document.getElementById('dropdown-' + id);
        const isActive = dd.classList.contains('active');
        document.querySelectorAll('.dropdown-menu-custom').forEach(d => d.classList.remove('active'));
        if (!isActive) {
            dd.classList.add('active');
        }
    }

    document.addEventListener('click', function(e) {
        if (!e.target.closest('.card-actions-top')) {
            document.querySelectorAll('.dropdown-menu-custom').forEach(d => d.classList.remove('active'));
        }
    });

    // Modals
    function openAddServiceModal() {
        document.getElementById('modalAddServiceNew').classList.add('active');
    }

    function closeAddServiceModal() {
        document.getElementById('modalAddServiceNew').classList.remove('active');
    }

    const servicesData = @json($services->keyBy('id'));

    function openEditServiceModal(id) {
        const service = servicesData[id];
        if (!service) return;

        document.getElementById('editServiceId').value = service.id;
        document.getElementById('editServiceName').value = service.name || '';
        document.getElementById('editServiceCategory').value = service.category || 'haircut_styling';
        document.getElementById('editServiceBadge').value = service.badge || '';
        document.getElementById('editServicePrice').value = Math.round(service.price) || 0;
        document.getElementById('editServiceDuration').value = service.duration_minutes || 45;
        document.getElementById('editServiceDesc').value = service.description || '';

        document.getElementById('formEditService').action = '/admin/services/' + service.id;
        document.getElementById('modalEditService').classList.add('active');
    }

    function closeEditServiceModal() {
        document.getElementById('modalEditService').classList.remove('active');
    }

    // Toast
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

    // Toggle Widget Visibility
    async function toggleWidgetVisibility(id) {
        const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
        const descEl = document.getElementById('widgetDesc-' + id);

        try {
            const res = await fetch(`/admin/services/${id}/toggle-widget`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': csrfToken,
                    'Accept': 'application/json'
                }
            });
            const data = await res.json();
            if (data.success) {
                if (descEl) {
                    descEl.innerText = data.show_in_widget 
                        ? 'Live di Online Widget' 
                        : 'Disembunyikan dari Widget';
                }
                showToast(data.message || 'Status widget berhasil diperbarui');
            } else {
                showToast('Gagal mengubah status widget', true);
            }
        } catch (e) {
            showToast('Koneksi terputus', true);
        }
    }

    // Toggle Active State
    async function toggleServiceActiveState(id) {
        const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

        try {
            const res = await fetch(`/admin/services/${id}/toggle-active`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': csrfToken,
                    'Accept': 'application/json'
                }
            });
            const data = await res.json();
            if (data.success) {
                showToast(data.message || 'Status layanan diperbarui');
                setTimeout(() => window.location.reload(), 700);
            } else {
                showToast('Gagal mengubah status', true);
            }
        } catch (e) {
            showToast('Koneksi terputus', true);
        }
    }
</script>
@endpush
