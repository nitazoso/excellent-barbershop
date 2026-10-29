@extends('layouts.admin')

@section('title', 'Kelola Booking — Admin Excellent Barbershop')

@push('styles')
<style>
    .admin-page-heading { display:flex; justify-content:space-between; align-items:flex-start; gap:12px; margin-bottom:18px; }
    .admin-page-heading > div:first-child > span { color:#a67c37; font-size:9px; font-weight:800; letter-spacing:.1em; }
    .admin-page-heading h1 { margin:4px 0; font-size:21px; line-height:1.2; color:#0e1726; }
    .admin-page-heading p { color:#64748b; font-size:10px; line-height:1.5; }
    .admin-page-heading > div:last-child { flex-direction:column; gap:6px !important; }
    .admin-page-heading .btn { height:30px; padding:0 9px; font-size:9px; white-space:nowrap; }
    .admin-title-row { display:flex; align-items:center; flex-wrap:wrap; gap:7px; }
    .waiting-count { display:inline-flex; padding:4px 8px; border-radius:999px; background:#ffe5a3; color:#8c5807; font-size:8px; font-weight:800; white-space:nowrap; }
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
        grid-template-columns: repeat(3, minmax(0, 1fr));
        gap: 8px;
        margin-bottom: 24px;
    }
    .summary-card:last-child { grid-column: auto; }
    .summary-card { position:relative; min-height:64px; }
    .summary-card .summary-icon { float:right; margin:0; font-size:13px; }
    .summary-card small { display:inline; margin-left:3px; color:#64748b; font-size:8px; }
    .summary-card {
        background: #fff;
        border: 1px solid #e7e2da;
        border-radius: 8px;
        padding: 12px;
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
        font-size: 18px;
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
    .status-pills { display:flex; gap:7px; overflow-x:auto; padding:1px 0 8px; margin:8px 0; scrollbar-width:none; }
    .status-pills::-webkit-scrollbar { display:none; }
    .status-pill { flex:0 0 auto; display:inline-flex; align-items:center; gap:5px; padding:7px 12px; border-radius:999px; background:#fff; border:1px solid #edf0f4; color:#3f4650; font-size:9px; font-weight:700; letter-spacing:.08em; text-decoration:none; white-space:nowrap; }
    .status-pill.active { background:#0e1726; color:#fff; }
    .status-pill.pending.active { background:#f2c94c; color:#392d05; }
    .status-pill span { display:inline-grid; min-width:16px; height:16px; place-items:center; padding:0 4px; border-radius:999px; background:#e5e7eb; color:#0e1726; font-size:8px; letter-spacing:0; }
    .status-pill.active span { background:#344155; color:white; }
    .status-pill.pending.active span { background:#f6d978; color:#5c4300; }
    .booking-search-form { margin-bottom:2px; }
    .booking-filter-form { display:grid; grid-template-columns:1fr 1fr; gap:7px; }
    .booking-search { min-width:0; position:relative; }
    .booking-search input { width:100%; height:36px; padding:0 36px 0 32px; border:1px solid #e2e6ed; border-radius:8px; background:#fff; font-size:10px; transition:box-shadow .2s,border-color .2s; }
    .booking-search input:focus { outline:none; border-color:#c69244; box-shadow:0 0 0 3px rgba(198,146,68,.16); }
    .booking-search::before { content:'⌕'; position:absolute; left:11px; top:7px; color:#64748b; font-size:16px; }
    .search-clear { position:absolute; top:50%; right:7px; display:none; width:26px; height:26px; transform:translateY(-50%); border:0; border-radius:50%; background:#f1f3f7; color:#475569; font-size:16px; cursor:pointer; }
    .booking-search.has-value .search-clear { display:grid; place-items:center; }
    .live-search-status { min-height:13px; margin:4px 0 0; color:#64748b; font-size:9px; }
    .live-search-empty { grid-column:1/-1; padding:18px 12px; border:1px dashed #cbd5e1; border-radius:10px; color:#64748b; background:#fff; text-align:center; font-size:11px; }
    .modal-open { overflow:hidden; }
    .reject-reason-meta { display:flex; justify-content:space-between; align-items:center; margin:-4px 0 8px; color:#8c9aa8; font-size:10px; }
    .reject-reason-meta output { font-variant-numeric:tabular-nums; }
    .booking-search-form:focus-within .booking-search::before { color:#a67c37; }
    @media (prefers-reduced-motion: reduce) {
        .booking-card,.summary-card,.status-pill,.booking-date-link,.call-button,.btn,.admin-alert-success { animation:none !important; transition:none !important; }
    }
    .booking-filter-select { min-width:0; height:36px; padding:0 9px; border:1px solid #e2e6ed; border-radius:8px; background:#fff; font-size:9px; }
    .booking-date-actions { display:contents; }
    .booking-date-link { display:inline-flex; min-width:0; min-height:36px; align-items:center; justify-content:space-between; padding:0 10px; border:1px solid #e2e6ed; border-radius:8px; background:#fff; color:#334155; font-size:9px; font-weight:700; text-decoration:none; }
    .all-dates-link { display:none; }
    .booking-date-details { grid-column:1/-1; font-size:9px; color:#64748b; }
    .booking-date-fields { display:flex; align-items:center; gap:6px; padding:8px 0; }
    .booking-date-fields input { min-width:0; width:50%; height:32px; padding:0 6px; border:1px solid #e2e6ed; border-radius:6px; font-size:9px; }
    .btn-filter-apply { min-height:30px; padding:0 10px; border:0; border-radius:6px; background:#a67c37; color:#fff; font-size:9px; font-weight:800; }
    .filter-form {
        display: grid;
        grid-template-columns: repeat(2, minmax(0, 1fr));
        gap: 10px;
    }
    .filter-group {
        display: flex;
        flex-direction: column;
        gap: 6px;
        min-width: 0;
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
    .filter-group input[type="search"] { width:100%; }
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
    .btn:active,.booking-date-link:active,.status-pill:active { transform:scale(.98); }
    .btn:disabled { opacity:.65; cursor:wait; }
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
        gap: 10px;
    }
    .booking-card {
        background: #fff;
        border: 1px solid #e7e2da;
        border-radius: 8px;
        padding: 14px;
        transition: border-color 0.2s;
    }
    .booking-card { animation:booking-card-enter .28s ease; transform:perspective(900px) rotateX(var(--booking-tilt-y, 0deg)) rotateY(var(--booking-tilt-x, 0deg)); transform-style:preserve-3d; transition:transform .22s ease,box-shadow .22s ease,border-color .22s ease; }
    .summary-card,.status-pill,.booking-date-link,.call-button,.btn { transition:transform .18s ease,box-shadow .18s ease,background-color .18s ease,filter .18s ease; }
    .booking-card:hover { border-color:#d4ccc0; }
    .summary-card:hover { transform:translateY(-3px) scale(1.015); box-shadow:0 7px 17px rgba(14,23,38,.1); }
    .status-pill:hover,.booking-date-link:hover { transform:translateY(-2px); box-shadow:0 4px 10px rgba(14,23,38,.09); }
    .call-button:hover { transform:translateY(-2px) rotate(-5deg) scale(1.06); box-shadow:0 5px 12px rgba(14,23,38,.12); }
    .btn:not(:disabled):hover { opacity:1; transform:translateY(-2px); box-shadow:0 5px 12px rgba(14,23,38,.16); filter:brightness(1.04); }
    .btn:not(:disabled):active,.booking-date-link:active,.status-pill:active { transform:translateY(0) scale(.97); box-shadow:none; }
    .booking-screen-frame .booking-card.is-pointer-hovered { transform:perspective(900px) translateY(-7px) scale(1.015) rotateX(var(--booking-tilt-y, 0deg)) rotateY(var(--booking-tilt-x, 0deg)); box-shadow:0 14px 28px rgba(14,23,38,.18); }
    .summary-card.is-pointer-hovered { transform:translateY(-3px) scale(1.015); box-shadow:0 7px 17px rgba(14,23,38,.1); }
    .status-pill.is-pointer-hovered,.booking-date-link.is-pointer-hovered { transform:translateY(-2px); box-shadow:0 4px 10px rgba(14,23,38,.09); }
    .call-button.is-pointer-hovered { transform:translateY(-2px) rotate(-5deg) scale(1.06); box-shadow:0 5px 12px rgba(14,23,38,.12); }
    .btn.is-pointer-hovered:not(:disabled) { opacity:1; transform:translateY(-2px); box-shadow:0 5px 12px rgba(14,23,38,.16); filter:brightness(1.04); }
    .booking-screen-frame :focus-visible { outline:3px solid rgba(198,146,68,.55); outline-offset:2px; }
    .admin-alert-success { transition:opacity .3s ease,transform .3s ease; }
    .admin-alert-success.is-dismissing { opacity:0; transform:translateY(-6px); }
    @keyframes booking-card-enter { from { opacity:0; transform:translateY(7px); } to { opacity:1; transform:translateY(0); } }
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
        grid-template-columns: repeat(2, minmax(0, 1fr));
        gap: 10px;
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
    .booking-actions form { flex: 1; }
    .booking-actions .btn { width: 100%; }
    .badge-in-progress { background: #dbeafe; color: #1d4ed8; }
    .badge-completed { background: #e5e7eb; color: #374151; }
    .badge-break { background: #fef3c7; color: #92400e; }
    .badge-vip { background:#111d33; color:#f5d478; }
    .badge-member { background:#edf1f6; color:#64748b; }
    .badge-chair { background:#dbeafe; color:#1d4ed8; }
    .booking-card-badges { display:flex; flex-wrap:wrap; justify-content:flex-end; gap:4px; }
    .customer-phone { color:#64748b; text-decoration:none; }
    .booking-running-time { margin:0 0 8px; color:#2563eb; font-size:9px; font-weight:700; }
    .booking-card-footer { display:flex; justify-content:space-between; align-items:center; gap:8px; margin-top:10px; padding-top:8px; border-top:1px solid #eef0f4; color:#8c9aa8; font-size:9px; }
    .booking-card-footer a { color:#a67c37; font-weight:800; text-decoration:none; }
    .btn-dark { background:#111d33; color:#fff; }
    .filter-group.search-group { grid-column: 1 / -1; }
    .filter-actions { grid-column: 1 / -1; }
    .customer-info { display:flex; align-items:center; gap:9px; margin-bottom:12px; min-width:0; }
    .customer-avatar { width:31px; height:31px; flex:0 0 31px; display:grid; place-items:center; border-radius:50%; background:#111d33; color:#fff; font-size:10px; font-weight:800; }
    .customer-name { display:block; color:#0e1726; font-size:12px; font-weight:800; }
    .customer-phone { display:block; margin-top:2px; color:#64748b; font-size:10px; }
    .detail-item.schedule-detail { padding:8px 10px; border-radius:7px; background:#f3f5f8; }
    .booking-pagination { display:flex; justify-content:space-between; align-items:center; flex-wrap:wrap; gap:12px; margin-top:20px; color:#64748b; font-size:12px; }
    .pagination-controls { display:flex; align-items:center; gap:5px; }
    .pagination-link, .pagination-current, .pagination-disabled { display:inline-flex; align-items:center; justify-content:center; min-width:34px; min-height:34px; padding:0 10px; border:1px solid #ded8cf; border-radius:5px; background:#fff; color:#4a443d; font-size:12px; text-decoration:none; }
    .pagination-current { border-color:#a93822; background:#a93822; color:#fff; font-weight:700; }
    .pagination-disabled { color:#a8a29e; background:#f5f3ef; }
    @media (max-width: 380px) {
        .booking-details { grid-template-columns: 1fr; }
        .booking-actions { flex-direction: column-reverse; }
        .filter-panel { padding: 12px; }
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
    @media (min-width: 768px) {
        .admin-page-heading { align-items:center; }
        .admin-page-heading > div:last-child { flex-direction:row; }
        .summary-grid { grid-template-columns:repeat(3, minmax(0, 1fr)); }
        .summary-card:last-child { grid-column:auto; }
        .filter-form { grid-template-columns:repeat(6, minmax(0, 1fr)); }
        .filter-group.search-group { grid-column:span 2; }
        .filter-actions { grid-column:span 2; }
        .booking-details { grid-template-columns:repeat(4, minmax(0, 1fr)); }
        .booking-actions form { flex:0 1 auto; }
        .booking-actions .btn { width:auto; }
    }
    @media (min-width: 1100px) {
        .booking-list { display:grid; grid-template-columns:repeat(2, minmax(0, 1fr)); align-items:start; }
        .booking-list > .booking-pagination { grid-column:1 / -1; }
        .booking-card { height:100%; }
        .booking-details { grid-template-columns:repeat(2, minmax(0, 1fr)); }
    }
    @media (min-width: 768px) and (max-width: 1099px) {
        .summary-grid { grid-template-columns:repeat(3, minmax(0, 1fr)); }
        .admin-page-heading { align-items:center; }
        .admin-page-heading > div:last-child { flex-direction:row; }
    }
    @media (max-width: 767px) {
        .booking-screen-frame .admin-page-heading h1 { font-size:19px; }
        .booking-screen-frame .booking-card { padding:12px; border-radius:11px; box-shadow:0 2px 8px rgba(14,23,38,.04); }
        .booking-screen-frame .booking-header { align-items:center; margin-bottom:10px; padding-bottom:9px; }
        .booking-screen-frame .booking-code { font-size:13px; }
        .booking-screen-frame .booking-meta { font-size:9px; }
        .booking-screen-frame .badge { padding:3px 7px; font-size:8px; }
        .booking-screen-frame .booking-details { grid-template-columns:repeat(2, minmax(0, 1fr)); gap:7px; margin-bottom:10px; font-size:11px; }
        .booking-screen-frame .detail-item strong { font-size:8px; }
        .booking-screen-frame .booking-actions { gap:7px; padding-top:10px; margin-top:8px; }
        .booking-screen-frame .booking-actions .btn { height:29px; font-size:9px; }
        .booking-screen-frame .summary-grid { grid-template-columns:repeat(2, minmax(0, 1fr)); }
        .booking-screen-frame .summary-card { padding:10px; border-radius:8px; }
        .booking-screen-frame .summary-card span { font-size:8px; }
        .booking-screen-frame .summary-card strong { font-size:16px; }
        .booking-screen-frame .filter-panel { padding:12px; }
        .booking-screen-frame .filter-form { grid-template-columns:repeat(2, minmax(0, 1fr)); }
        .booking-screen-frame .filter-group.search-group,
        .booking-screen-frame .filter-actions { grid-column:1 / -1; }
        .booking-screen-frame .filter-group label { font-size:8px; }
        .booking-screen-frame .filter-group input,
        .booking-screen-frame .filter-group select { height:34px; padding:0 8px; font-size:10px; }
        .booking-screen-frame .filter-actions .btn { height:32px; padding:0 12px; font-size:9px; }
        .booking-screen-frame .booking-pagination { justify-content:center; font-size:10px; }
        .booking-screen-frame .booking-pagination > span { width:100%; text-align:center; }
        .booking-screen-frame .pagination-link,
        .booking-screen-frame .pagination-current,
        .booking-screen-frame .pagination-disabled { min-width:30px; min-height:30px; font-size:10px; }
    }
    .booking-service-box { display:flex; align-items:center; gap:8px; min-width:0; margin:8px 0; padding:10px; border-radius:8px; background:#f5f6f8; color:#0e1726; }
    .booking-service-box strong { flex:1; min-width:0; font-size:11px; }
    .service-symbol { color:#a67c00; font-size:15px; }
    .service-duration { flex:0 0 auto; color:#3f4650; font-size:9px; letter-spacing:.06em; }
    .call-button { display:grid; width:36px; height:36px; flex:0 0 36px; place-items:center; margin-left:auto; border-radius:9px; background:#f1f3f7; color:#0e1726; font-size:15px; text-decoration:none; }
    .badge-chair { background:#dcecff; color:#2557a7; }
    .booking-screen-frame .booking-details { display:grid; grid-template-columns:minmax(0,1fr) auto; align-items:center; padding:8px 10px; border-radius:8px; background:#f7f8fa; }
    .booking-screen-frame .barber-detail,.booking-screen-frame .time-detail { display:flex; align-items:center; gap:6px; min-width:0; color:#475569; font-size:9px; }
    .booking-screen-frame .barber-detail strong,.booking-screen-frame .time-detail strong { display:inline; width:auto; flex:0 0 auto; margin:0; color:#475569; font-size:12px; }
    .booking-screen-frame .barber-detail span { min-width:0; overflow-wrap:anywhere; }
    .booking-screen-frame .price-detail { display:flex; justify-content:space-between; grid-column:1/-1; margin-top:5px; padding-top:5px; border-top:1px solid #e5e7eb; color:#64748b; font-size:9px; }
    .booking-screen-frame .price-detail strong { display:inline; margin:0; color:#64748b; font-size:9px; }
    .booking-screen-frame .price-detail span { color:#0e1726; font-weight:800; }
    .booking-screen-frame .summary-icon { float:right; margin:0; font-size:12px; }
    .booking-screen-frame .summary-card small { display:inline; margin-left:3px; color:#64748b; font-size:8px; }
    .booking-screen-frame .booking-actions > form,
    .booking-screen-frame .booking-actions > button { flex:1 1 0; min-width:0; }
    .booking-screen-frame .booking-actions > form .btn,
    .booking-screen-frame .booking-actions > button { width:100%; height:40px; min-height:40px; padding:0 6px; line-height:1.2; white-space:normal; }
    @media (max-width: 767px) {
        .booking-screen-frame .figma-topbar { justify-content:flex-start; gap:14px; }
        .booking-screen-frame .figma-brand-title { margin-left:0; font-size:12px; }
        .booking-screen-frame .figma-topbar-right { margin-left:auto; }
        .booking-screen-frame .admin-page-heading { margin-bottom:10px; }
        .booking-screen-frame .admin-page-heading > div:last-child { display:none; }
        .booking-screen-frame .admin-page-heading h1 { margin:0; font-size:16px; }
        .booking-screen-frame .admin-page-heading p { margin-top:6px; font-size:12px; line-height:1.65; }
        .booking-screen-frame .desktop-line-break { display:block; }
        .booking-screen-frame .waiting-count { padding:4px 8px; font-size:8px; }
        .booking-screen-frame .summary-grid { grid-template-columns:repeat(3,minmax(0,1fr)); gap:8px; margin-bottom:14px; }
        .booking-screen-frame .summary-card { min-height:65px; padding:10px; border-radius:9px; }
        .booking-screen-frame .summary-card span { margin-bottom:6px; font-size:7px; letter-spacing:.07em; }
        .booking-screen-frame .summary-card strong { font-size:16px; }
        .booking-screen-frame .summary-card small { font-size:7px; }
        .booking-screen-frame .filter-panel { display:flex; flex-direction:column; gap:2px; padding:0; margin-bottom:14px; border:0; background:transparent; }
        .booking-screen-frame .booking-search-form { width:100%; margin:0 0 1px; }
        .booking-screen-frame .booking-search input { height:42px; border:0; border-radius:10px; box-shadow:0 2px 8px rgba(14,23,38,.05); font-size:11px; }
        .booking-screen-frame .status-pills { gap:7px; margin:6px 0 8px; padding-bottom:0; }
        .booking-screen-frame .status-pill { padding:7px 12px; font-size:9px; }
        .booking-screen-frame .booking-filter-form { width:100%; grid-template-columns:repeat(2,minmax(0,1fr)); gap:7px; }
        .booking-screen-frame .booking-filter-select,.booking-screen-frame .booking-date-link { width:100%; min-width:0; height:36px; padding:0 9px; font-size:9px; }
        .booking-screen-frame .booking-date-details { grid-column:1/-1; }
        .booking-screen-frame .all-dates-link { display:none; }
        .booking-screen-frame .booking-list { gap:8px; }
        .booking-screen-frame .booking-card { padding:12px; border:1px solid #edf0f4; border-top:4px solid #a67c37; border-radius:12px; box-shadow:0 2px 8px rgba(14,23,38,.04); }
        .booking-screen-frame .booking-header { align-items:center; margin-bottom:10px; padding-bottom:8px; }
        .booking-screen-frame .booking-code { font-size:10px; }
        .booking-screen-frame .badge { padding:4px 7px; font-size:8px; letter-spacing:.08em; }
        .booking-screen-frame .customer-info { gap:8px; margin-bottom:9px; }
        .booking-screen-frame .customer-avatar { width:40px; height:40px; flex-basis:40px; font-size:14px; }
        .booking-screen-frame .customer-name { font-size:13px; }
        .booking-screen-frame .customer-phone { margin-top:3px; font-size:10px; }
        .booking-screen-frame .call-button { width:38px; height:38px; flex-basis:38px; }
        .booking-screen-frame .booking-service-box { min-height:42px; margin:8px 0 5px; padding:9px; }
        .booking-screen-frame .booking-service-box strong { font-size:11px; }
        .booking-screen-frame .booking-details { grid-template-columns:minmax(0,1fr) auto; gap:5px; margin-bottom:8px; padding:7px 9px; }
        .booking-screen-frame .barber-detail,.booking-screen-frame .time-detail { font-size:9px; }
        .booking-screen-frame .booking-actions { gap:7px; margin-top:7px; padding-top:0; border-top:0; }
        .booking-screen-frame .booking-actions form { flex:1; }
        .booking-screen-frame .booking-actions .btn { width:100%; min-height:40px; height:40px; border-radius:8px; font-size:9px; }
        .booking-screen-frame .btn-danger { background:#fee2e2; color:#b91c1c; }
        .booking-screen-frame .btn-success { background:#079669; color:#fff; }
        .booking-screen-frame .btn-dark { background:#0e1726; color:white; }
        .booking-screen-frame .booking-card-footer { margin-top:8px; padding-top:7px; font-size:8px; letter-spacing:.1em; }
        .booking-screen-frame .booking-pagination { justify-content:center; }
    }
    @media (min-width: 768px) {
        .booking-screen-frame .admin-page-heading { align-items:center; }
        .booking-screen-frame .admin-page-heading > div:last-child { display:flex; flex-direction:row; }
        .booking-screen-frame .summary-grid { grid-template-columns:repeat(3,minmax(0,1fr)); }
        .booking-screen-frame .filter-panel { display:grid; grid-template-columns:minmax(300px,1fr) auto; align-items:center; gap:12px; padding:14px; border:1px solid #e7e2da; border-radius:10px; background:#fff; }
        .booking-screen-frame .booking-search-form { grid-column:1; grid-row:1; min-width:280px; margin:0; }
        .booking-screen-frame .status-pills { grid-column:2; grid-row:1; margin:0; }
        .booking-screen-frame .booking-filter-form { grid-column:1/-1; grid-row:2; display:grid; grid-template-columns:minmax(180px,1fr) auto auto; align-items:center; gap:8px; }
        .booking-screen-frame .booking-date-actions { display:contents; }
        .booking-screen-frame .booking-date-link { min-height:36px; }
        .booking-screen-frame .all-dates-link { display:inline-flex; }
        .booking-screen-frame .booking-date-details { grid-column:1/-1; }
        .booking-screen-frame .booking-card { padding:16px; border-top:4px solid #a67c37; }
        .booking-screen-frame .booking-code { font-size:14px; }
        .booking-screen-frame .booking-card-footer { font-size:10px; }
    }
    @media (min-width: 1200px) {
        .booking-screen-frame .booking-list { grid-template-columns:repeat(3,minmax(0,1fr)); }
    }
    .booking-list > article.booking-card:hover,
    .booking-list > article.booking-card.is-pointer-hovered {
        position:relative;
        z-index:30;
        transform:translate3d(0,-12px,0) scale(1.025) !important;
        border-color:#c9aa72;
        box-shadow:0 20px 38px rgba(14,23,38,.26),0 4px 12px rgba(166,124,55,.18) !important;
    }
</style>
@endpush

@section('content')
    <div class="admin-page-heading">
        <div>
            <span>RESERVASI</span>
            <div class="admin-title-row">
                <h1>Kelola Booking</h1>
                <span class="waiting-count">● {{ $summary['pending'] }} Menunggu Konfirmasi</span>
            </div>
            <p>Kelola dan pantau semua jadwal reservasi pelanggan<br class="desktop-line-break"> Sempati Atelier.</p>
        </div>
        <div style="display: flex; gap: 10px;">
            <a href="{{ route('admin.bookings.export-pdf', request()->query()) }}" class="btn btn-secondary">
                📄 EKSPOR PDF
            </a>
            <a href="{{ route('admin.dashboard') }}" class="btn btn-secondary">
                ← DASHBOARD
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
        <div class="summary-card pending">
            <span>MENUNGGU <span class="summary-icon" aria-hidden="true">⌛</span></span>
            <strong>{{ $summary['pending'] }}</strong>
            <small>slot</small>
        </div>
        <div class="summary-card confirmed">
            <span>DIKONFIRMASI <span class="summary-icon" aria-hidden="true">☑</span></span>
            <strong>{{ $summary['confirmed'] }}</strong>
            <small>slot</small>
        </div>
        <div class="summary-card in-progress-summary">
            <span>DI KURSI <span class="summary-icon" aria-hidden="true">▣</span></span>
            <strong>{{ $summary['in_progress'] }}</strong>
            <small>kursi</small>
        </div>
    </div>

    <div class="filter-panel">
        @php
            $baseBookingFilters = request()->except(['page', 'status']);
            $todayBookingFilters = array_merge($baseBookingFilters, [
                'start_date' => today()->toDateString(),
                'end_date' => today()->toDateString(),
            ]);
            $allDatesBookingFilters = request()->except(['page', 'start_date', 'end_date']);
        @endphp
        <form method="GET" action="{{ route('admin.bookings') }}" class="booking-search-form">
            @if (request('status'))
                <input type="hidden" name="status" value="{{ request('status') }}">
            @endif
            @if (request('barber_id'))
                <input type="hidden" name="barber_id" value="{{ request('barber_id') }}">
            @endif
            @if (request('start_date'))
                <input type="hidden" name="start_date" value="{{ request('start_date') }}">
            @endif
            @if (request('end_date'))
                <input type="hidden" name="end_date" value="{{ request('end_date') }}">
            @endif
            <div class="booking-search">
                <input type="search" name="search" value="{{ request('search') }}" placeholder="Cari nama, no. HP, kode #EXC-..." aria-label="Cari booking">
                <button type="button" class="search-clear" aria-label="Hapus pencarian">×</button>
            </div>
        </form>
        <nav class="status-pills" aria-label="Filter status booking">
            <a class="status-pill {{ request('status') ? '' : 'active' }}" href="{{ route('admin.bookings', $baseBookingFilters) }}">Semua <span>{{ $summary['total'] }}</span></a>
            <a class="status-pill pending {{ request('status') === 'pending' ? 'active' : '' }}" href="{{ route('admin.bookings', array_merge($baseBookingFilters, ['status' => 'pending'])) }}">Menunggu <span>{{ $summary['pending'] }}</span></a>
            <a class="status-pill {{ request('status') === 'confirmed' ? 'active' : '' }}" href="{{ route('admin.bookings', array_merge($baseBookingFilters, ['status' => 'confirmed'])) }}">Dikonfirmasi <span>{{ $summary['confirmed'] }}</span></a>
        </nav>
        <form method="GET" action="{{ route('admin.bookings') }}" class="booking-filter-form">
            @if (request('status'))
                <input type="hidden" name="status" value="{{ request('status') }}">
            @endif
            @if (request('search'))
                <input type="hidden" name="search" value="{{ request('search') }}">
            @endif
            <select class="booking-filter-select" name="barber_id" aria-label="Filter barber" onchange="this.form.submit()">
                <option value="">Semua Barber ({{ $barbers->count() }})</option>
                @foreach ($barbers as $barber)
                    <option value="{{ $barber->id }}" {{ (string) request('barber_id') === (string) $barber->id ? 'selected' : '' }}>{{ $barber->name }} (Kursi {{ $barber->chair_code_label }})</option>
                @endforeach
            </select>
            <div class="booking-date-actions">
                <a class="booking-date-link" href="{{ route('admin.bookings', $todayBookingFilters) }}">▦ Hari ini ({{ today()->format('d M') }})</a>
                <a class="booking-date-link all-dates-link" href="{{ route('admin.bookings', $allDatesBookingFilters) }}">Semua Tanggal</a>
            </div>
            <details class="booking-date-details">
                <summary>Rentang tanggal khusus</summary>
                <div class="booking-date-fields">
                    <input type="date" name="start_date" value="{{ request('start_date') }}" aria-label="Dari tanggal">
                    <span>—</span>
                    <input type="date" name="end_date" value="{{ request('end_date') }}" aria-label="Sampai tanggal">
                    <button type="submit" class="btn-filter-apply">Terapkan</button>
                </div>
            </details>
        </form>
    </div>
    <p class="live-search-status" id="liveSearchStatus" aria-live="polite"></p>

    {{-- Daftar Booking --}}
    <section class="booking-list">
        @forelse ($bookings as $booking)
            <article class="booking-card">
                @php
                    $cardStatusLabel = match ($booking->status) {
                        'pending' => 'Menunggu',
                        'confirmed' => 'Dikonfirmasi',
                        'in_progress' => 'Sedang Datang di Kursi',
                        'completed' => 'Selesai',
                        'rejected' => 'Ditolak / Batal',
                        'break' => 'Jeda',
                        default => $booking->status_label,
                    };
                    $isVipMember = ($booking->customer->membership_type ?? 'regular') === 'vip';
                @endphp
                <div class="booking-header">
                    <div>
                        <h2 class="booking-code">#{{ $booking->booking_code }}</h2>
                    </div>
                    <div class="booking-card-badges">
                        <span class="badge {{ $isVipMember ? 'badge-vip' : 'badge-member' }}">{{ $isVipMember ? 'VIP Member' : 'Reguler' }}</span>
                        @if ($booking->status === 'in_progress')
                            <span class="badge badge-chair">Kursi {{ $booking->barber->chair_code_label ?? '01' }}</span>
                        @endif
                        <span class="badge {{ $booking->status_badge_class }}">{{ $cardStatusLabel }}</span>
                    </div>
                </div>

                <div class="customer-info">
                    <span class="customer-avatar">{{ mb_strtoupper(mb_substr($booking->customer->name ?? 'P', 0, 2)) }}</span>
                    <span>
                        <span class="customer-name">{{ $booking->customer->name ?? 'Pelanggan' }}</span>
                        <a class="customer-phone" href="tel:{{ $booking->customer->phone ?? '' }}">☎ {{ $booking->customer->phone ?? '-' }}</a>
                    </span>
                    <a class="call-button" href="tel:{{ $booking->customer->phone ?? '' }}" aria-label="Telepon pelanggan">☎</a>
                </div>

                <div class="booking-service-box">
                    <span class="service-symbol" aria-hidden="true">✂</span>
                    <strong>{{ $booking->service->name ?? '-' }}</strong>
                    <span class="service-duration">{{ $booking->service->duration_minutes ?? 0 }} Menit</span>
                </div>
                <div class="booking-details">
                    <div class="detail-item barber-detail">
                        <strong>▧</strong>
                        <span>Barber: {{ $booking->barber->name ?? '-' }} (Kursi {{ $booking->barber->chair_code_label ?? '01' }})</span>
                    </div>
                    <div class="detail-item time-detail">
                        <strong>◷</strong>
                        <span>{{ substr($booking->start_time, 0, 5) }} WIB</span>
                    </div>
                    <div class="detail-item price-detail">
                        <strong>Harga layanan</strong>
                        <span>Rp {{ number_format($booking->service->price ?? 0, 0, ',', '.') }}</span>
                    </div>
                </div>

                @if ($booking->notes)
                    <div style="font-size: 12px; color: #6d655d; margin-bottom: 8px;">
                        <strong>Catatan Pelanggan:</strong> {{ $booking->notes }}
                    </div>
                @endif

                @if ($booking->status === 'rejected' && $booking->rejection_reason)
                    <div class="rejection-box">
                        <strong>Alasan:</strong> {{ $booking->rejection_reason }}
                    </div>
                @endif

                @if ($booking->status === 'in_progress')
                    <div class="booking-running-time">Berjalan sekitar {{ max(1, (int) now()->diffInMinutes($booking->updated_at)) }} menit</div>
                @endif

                <div class="booking-actions">
                    @if ($booking->status === 'pending')
                        {{-- Form Konfirmasi --}}
                        <form method="POST" action="{{ route('admin.bookings.confirm', $booking) }}" onsubmit="return confirm('Apakah Anda yakin ingin mengonfirmasi booking ini?');">
                            @csrf
                            @method('PATCH')
                            <button type="submit" class="btn btn-success btn-sm">
                                ✓ TERIMA BOOKING
                            </button>
                        </form>

                        {{-- Tombol Tolak (Memicu Modal) --}}
                            <button type="button" class="btn btn-danger btn-sm" aria-haspopup="dialog" aria-controls="rejectModal" onclick='openRejectModal(@json($booking->id), @json($booking->booking_code))'>
                            ✕ TOLAK
                        </button>
                    @elseif ($booking->status === 'confirmed')
                        <form method="POST" action="{{ route('admin.bookings.status', $booking) }}">
                            @csrf @method('PATCH')
                            <input type="hidden" name="status" value="in_progress">
                            <button type="submit" class="btn btn-primary btn-sm">▶ TANDAI SEDANG DATANG</button>
                        </form>
                    @elseif ($booking->status === 'in_progress')
                        <form method="POST" action="{{ route('admin.bookings.status', $booking) }}">
                            @csrf @method('PATCH')
                            <input type="hidden" name="status" value="completed">
                            <button type="submit" class="btn btn-dark btn-sm">⟳ SELESAI SESI LAYANAN</button>
                        </form>
                    @elseif ($booking->status === 'rejected')
                        <span style="font-size: 11px; color: #dc2626; font-weight: 700;">BOOKING DITOLAK</span>
                    @else
                        <span style="font-size: 11px; color: #64748b; font-weight: 700;">{{ $booking->status_label }}</span>
                    @endif
                </div>
                <footer class="booking-card-footer">
                    <span>{{ $booking->booking_date->isToday() ? 'Hari Ini' : 'Jadwal' }} • {{ $booking->booking_date->format('d M Y') }}</span>
                    <a href="{{ route('booking.result', ['booking_code' => $booking->booking_code]) }}">Lihat Detail &gt;</a>
                </footer>
            </article>
        @empty
            <div class="admin-panel" style="text-align: center; padding: 48px 20px;">
                <h2>Tidak Ada Data Booking</h2>
                <p>Tidak ada reservasi yang ditemukan sesuai dengan kriteria filter yang dipilih.</p>
                <a href="{{ route('admin.bookings') }}" class="btn btn-secondary" style="margin-top: 12px;">Reset Filter</a>
            </div>
        @endforelse

        <div class="live-search-empty" id="liveSearchEmpty" hidden>Tidak ada booking pada halaman ini yang cocok dengan pencarian.</div>

        @if ($bookings->hasPages())
            <nav class="booking-pagination" aria-label="Navigasi halaman booking">
                <span>Menampilkan {{ $bookings->firstItem() }}–{{ $bookings->lastItem() }} dari {{ $bookings->total() }} booking</span>
                <div class="pagination-controls">
                    @if ($bookings->onFirstPage())
                        <span class="pagination-disabled" aria-disabled="true">← Sebelumnya</span>
                    @else
                        <a class="pagination-link" href="{{ $bookings->previousPageUrl() }}" rel="prev">← Sebelumnya</a>
                    @endif

                    @php($firstPage = max(1, $bookings->currentPage() - 1))
                    @php($lastPage = min($bookings->lastPage(), $bookings->currentPage() + 1))
                    @for ($page = $firstPage; $page <= $lastPage; $page++)
                        @if ($page === $bookings->currentPage())
                            <span class="pagination-current" aria-current="page">{{ $page }}</span>
                        @else
                            <a class="pagination-link" href="{{ $bookings->url($page) }}">{{ $page }}</a>
                        @endif
                    @endfor

                    @if ($bookings->hasMorePages())
                        <a class="pagination-link" href="{{ $bookings->nextPageUrl() }}" rel="next">Berikutnya →</a>
                    @else
                        <span class="pagination-disabled" aria-disabled="true">Berikutnya →</span>
                    @endif
                </div>
            </nav>
        @endif
    </section>

    {{-- Modal Tolak Booking --}}
    <div id="rejectModal" class="modal-overlay" role="dialog" aria-modal="true" aria-labelledby="rejectModalTitle" aria-describedby="rejectModalDescription">
        <div class="modal-box">
            <h3 id="rejectModalTitle">Tolak Booking</h3>
            <p id="rejectModalDescription">Masukkan alasan penolakan untuk booking <strong id="modalBookingCode"></strong>:</p>
            <form id="rejectForm" method="POST" action="">
                @csrf
                @method('PATCH')
                <div style="margin-bottom: 16px;">
                    <label for="rejection_reason" style="display: block; font-size: 11px; font-weight: 700; color: #6d655d; margin-bottom: 6px; text-transform: uppercase;">
                        Alasan Penolakan <span style="color: #dc2626;">*</span>
                    </label>
                    <textarea id="rejection_reason" name="rejection_reason" required maxlength="1000" placeholder="Contoh: Barber sedang berhalangan hadir / Jadwal telah terisi / Studio tutup..."></textarea>
                    <div class="reject-reason-meta"><span>Alasan penolakan wajib diisi</span><output id="rejectionReasonCount" aria-live="polite">0/1000</output></div>
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
    const bookingSearchForm = document.querySelector('.booking-search-form');
    const bookingSearchInput = bookingSearchForm?.querySelector('input[type="search"]');
    const bookingSearchContainer = bookingSearchForm?.querySelector('.booking-search');
    const bookingSearchClear = bookingSearchForm?.querySelector('.search-clear');
    const bookingSearchStatus = document.getElementById('liveSearchStatus');
    const bookingSearchEmpty = document.getElementById('liveSearchEmpty');
    const bookingCards = Array.from(document.querySelectorAll('.booking-card'));
    let lastRejectTrigger = null;

    let pointerCard = null;
    let pointerInteractive = null;

    function updatePointerMotion(event) {
        const card = event.target.closest?.('.booking-card');
        const interactive = event.target.closest?.('.summary-card, .status-pill, .booking-date-link, .call-button, .btn:not(:disabled)');

        if (pointerCard && pointerCard !== card) {
            pointerCard.classList.remove('is-pointer-hovered');
            pointerCard.style.removeProperty('--booking-tilt-x');
            pointerCard.style.removeProperty('--booking-tilt-y');
        }
        if (pointerInteractive && pointerInteractive !== interactive) {
            pointerInteractive.classList.remove('is-pointer-hovered');
        }

        pointerCard = card;
        pointerInteractive = interactive;

        if (card) {
            const bounds = card.getBoundingClientRect();
            const horizontal = (event.clientX - bounds.left) / bounds.width - 0.5;
            const vertical = (event.clientY - bounds.top) / bounds.height - 0.5;

            card.classList.add('is-pointer-hovered');
            card.style.setProperty('--booking-tilt-x', `${(horizontal * 9).toFixed(2)}deg`);
            card.style.setProperty('--booking-tilt-y', `${(vertical * -9).toFixed(2)}deg`);
        }

        interactive?.classList.add('is-pointer-hovered');
    }

    document.addEventListener('mousemove', updatePointerMotion, { passive: true });
    document.addEventListener('mouseover', updatePointerMotion, { passive: true });
    document.addEventListener('mouseleave', () => {
        pointerCard?.classList.remove('is-pointer-hovered');
        pointerCard?.style.removeProperty('--booking-tilt-x');
        pointerCard?.style.removeProperty('--booking-tilt-y');
        pointerInteractive?.classList.remove('is-pointer-hovered');
        pointerCard = null;
        pointerInteractive = null;
    });

    function filterVisibleBookings() {
        if (!bookingSearchInput || !bookingSearchContainer) {
            return;
        }

        const searchTerm = bookingSearchInput.value.trim().toLocaleLowerCase('id');
        let visibleCount = 0;

        bookingCards.forEach((card) => {
            const matches = card.textContent.toLocaleLowerCase('id').includes(searchTerm);
            card.hidden = !matches;
            if (matches) {
                visibleCount++;
            }
        });

        bookingSearchContainer.classList.toggle('has-value', searchTerm.length > 0);
        if (bookingSearchStatus) {
            bookingSearchStatus.textContent = searchTerm
                ? `Menampilkan ${visibleCount} dari ${bookingCards.length} booking di halaman ini.`
                : '';
        }
        if (bookingSearchEmpty) {
            bookingSearchEmpty.hidden = searchTerm.length === 0 || visibleCount > 0 || bookingCards.length === 0;
        }

        document.querySelector('.booking-pagination')?.toggleAttribute('hidden', searchTerm.length > 0);
    }

    function clearBookingSearch() {
        if (!bookingSearchInput) {
            return;
        }

        bookingSearchInput.value = '';
        if (new URLSearchParams(window.location.search).has('search')) {
            bookingSearchForm?.requestSubmit();
            return;
        }

        filterVisibleBookings();
        bookingSearchInput.focus();
    }

    function updateRejectionReasonCount() {
        const textarea = document.getElementById('rejection_reason');
        const count = document.getElementById('rejectionReasonCount');
        if (textarea && count) {
            count.value = `${textarea.value.length}/1000`;
        }
    }

    function openRejectModal(bookingId, bookingCode) {
        const modal = document.getElementById('rejectModal');
        const form = document.getElementById('rejectForm');
        const codeSpan = document.getElementById('modalBookingCode');
        const textarea = document.getElementById('rejection_reason');

        lastRejectTrigger = document.activeElement;
        form.action = `/admin/bookings/${bookingId}/reject`;
        codeSpan.innerText = '#' + bookingCode;
        textarea.value = '';
        modal.classList.add('active');
        document.body.classList.add('modal-open');
        updateRejectionReasonCount();
        textarea.focus();
    }

    function closeRejectModal() {
        const modal = document.getElementById('rejectModal');
        modal.classList.remove('active');
        document.body.classList.remove('modal-open');
        lastRejectTrigger?.focus();
    }

    // Tutup modal jika klik di luar area modal-box
    window.addEventListener('click', function(event) {
        const modal = document.getElementById('rejectModal');
        if (event.target === modal) {
            closeRejectModal();
        }
    });

    bookingSearchInput?.addEventListener('input', filterVisibleBookings);
    bookingSearchClear?.addEventListener('click', clearBookingSearch);
    document.getElementById('rejection_reason')?.addEventListener('input', updateRejectionReasonCount);
    filterVisibleBookings();

    document.querySelectorAll('.booking-actions form').forEach((form) => {
        form.addEventListener('submit', (event) => {
            if (event.defaultPrevented) {
                return;
            }

            const button = form.querySelector('button[type="submit"]');
            if (button) {
                button.disabled = true;
                button.textContent = 'MEMPROSES…';
            }
        });
    });

    document.getElementById('rejectForm')?.addEventListener('submit', () => {
        const submitButton = document.querySelector('#rejectForm button[type="submit"]');
        if (submitButton) {
            submitButton.disabled = true;
            submitButton.textContent = 'MEMPROSES…';
        }
    });

    document.addEventListener('keydown', (event) => {
        const rejectModal = document.getElementById('rejectModal');
        if (event.key === 'Escape' && rejectModal?.classList.contains('active')) {
            closeRejectModal();
            return;
        }

        if (event.key === '/' && !event.ctrlKey && !event.metaKey && !event.altKey
            && !event.target.matches('input, textarea, select, [contenteditable="true"]')) {
            event.preventDefault();
            bookingSearchInput?.focus();
        }

        if (event.key === 'Escape' && document.activeElement === bookingSearchInput && bookingSearchInput.value) {
            clearBookingSearch();
        }
    });

    const successAlert = document.querySelector('.admin-alert-success');
    if (successAlert) {
        window.setTimeout(() => {
            successAlert.classList.add('is-dismissing');
            window.setTimeout(() => successAlert.remove(), 320);
        }, 5000);
    }
</script>
@endpush
