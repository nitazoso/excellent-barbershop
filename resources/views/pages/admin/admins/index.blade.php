@extends('layouts.admin')

@section('title', 'Kelola Admin — Excellent Barbershop')

@push('styles')
<style>
    .admin-main {
        background: #f5f8fc;
        color: #17243a;
        font-family: Inter, Arial, sans-serif;
    }
    .admin-page-heading span,
    .admin-page-heading > a {
        color: #a87920;
    }
    .admin-page-heading h1 {
        color: #17243a;
        font-family: Inter, Arial, sans-serif;
        font-size: 20px;
        font-weight: 800;
        letter-spacing: -.03em;
    }
    .admin-page-heading p,
    .admin-panel p {
        color: #63748b !important;
    }
    .admin-panel {
        border-color: #e6ebf2;
    }
    .admin-panel h2,
    .admin-main strong {
        color: #17243a;
    }
    .admin-main input {
        border-color: #e6ebf2 !important;
        color: #17243a;
        font-family: Inter, Arial, sans-serif;
    }
    .admin-main button[type="submit"][style*="#a93822"] {
        background: #14233b !important;
    }
    .admin-main div[style*="#faf8f5"] {
        border-color: #e6ebf2 !important;
        background: #f5f8fc !important;
        color: #8292a8 !important;
    }
    .admin-main table {
        color: #17243a;
        font-family: Inter, Arial, sans-serif;
    }
    .admin-main thead tr[style] {
        border-color: #e6ebf2 !important;
        color: #8292a8 !important;
    }
    .admin-main tbody tr[style] {
        border-color: #e6ebf2 !important;
    }
    .admin-main [style*="#211f1c"],
    .admin-main [style*="#4a453f"] {
        color: #17243a !important;
    }
    .admin-main [style*="#6d655d"] {
        color: #63748b !important;
    }
    .admin-main [style*="#8d8379"] {
        color: #8292a8 !important;
    }
    .admin-main [style*="#e7e2da"],
    .admin-main [style*="#f0eae1"] {
        border-color: #e6ebf2 !important;
        background-color: #f0f2f5 !important;
    }
    .admin-main span[style*="#fbf3e4"] {
        border-color: #f0d391 !important;
        background: #fff0d7 !important;
        color: #a76517 !important;
    }
    .admin-main span[style*="#eef2f6"] {
        border-color: #d7e2f1 !important;
        background: #eaf2ff !important;
        color: #4678c8 !important;
    }
    .admin-main span[style*="#1e6b37"] {
        color: #258050 !important;
    }
    .admin-main span[style*="#28a745"] {
        background: #2e9c75 !important;
    }
    .admin-main span[style*="#a0978d"] {
        background: #9ba4b1 !important;
    }
    .admin-main button[style*="#e3b7af"] {
        border-color: #f2c5c3 !important;
        background: #fff0ef !important;
        color: #b84b43 !important;
    }
    .admin-main button[style*="#b2d9bb"] {
        border-color: #bde8cf !important;
        background: #e5f6ec !important;
        color: #258050 !important;
    }
    .admin-main div[role="alert"][style*="#a93822"] {
        border-color: #f2c5c3 !important;
        color: #b84b43 !important;
    }
    .admin-page-heading-actions {
        display: flex;
        align-items: center;
        gap: 10px;
    }
    .admin-add-admin-trigger,
    .admin-add-admin-submit {
        display: inline-flex;
        min-height: 38px;
        align-items: center;
        justify-content: center;
        gap: 7px;
        padding: 0 14px;
        border: 0;
        border-radius: 8px;
        background: #14233b;
        color: #fff;
        cursor: pointer;
        font: 700 12px Inter, Arial, sans-serif;
        text-decoration: none;
    }
    .admin-add-admin-trigger svg {
        width: 16px;
        height: 16px;
        fill: none;
        stroke: currentColor;
        stroke-linecap: round;
        stroke-width: 1.8;
    }
    .admin-add-admin-overlay {
        position: fixed;
        inset: 0;
        z-index: 100;
        display: none;
        align-items: center;
        justify-content: center;
        padding: 20px;
        background: #0b152499;
    }
    .admin-add-admin-overlay.is-open {
        display: flex;
    }
    .admin-add-admin-modal {
        width: min(100%, 520px);
        padding: 24px;
        border: 1px solid #e6ebf2;
        border-radius: 14px;
        background: #fff;
        box-shadow: 0 18px 50px #0b152933;
    }
    .admin-add-admin-modal header {
        display: flex;
        align-items: flex-start;
        justify-content: space-between;
        gap: 16px;
        margin-bottom: 18px;
    }
    .admin-add-admin-modal h2 {
        margin: 0 0 6px;
        color: #17243a;
        font-size: 18px;
    }
    .admin-add-admin-modal p {
        margin: 0;
        color: #63748b;
        font-size: 13px;
        line-height: 1.6;
    }
    .admin-add-admin-close {
        display: grid;
        width: 32px;
        height: 32px;
        flex: none;
        place-items: center;
        border: 1px solid #e6ebf2;
        border-radius: 8px;
        background: #f5f8fc;
        color: #63748b;
        cursor: pointer;
        font-size: 20px;
        line-height: 1;
    }
    .admin-add-admin-form {
        display: flex;
        align-items: center;
        gap: 10px;
    }
    .admin-add-admin-form input {
        width: 100%;
        height: 42px;
        padding: 0 12px;
        border: 1px solid #e6ebf2;
        border-radius: 8px;
        color: #17243a;
        font: 13px Inter, Arial, sans-serif;
    }
    .admin-add-admin-form input:focus {
        border-color: #c99b3f;
        outline: 3px solid #c99b3f26;
    }
    .admin-add-admin-modal [role="alert"] {
        margin-bottom: 14px;
        color: #b84b43;
        font-size: 12px;
    }
    @media (max-width: 520px) {
        .admin-page-heading-actions {
            align-items: flex-start;
            flex-direction: column;
        }
        .admin-add-admin-form {
            align-items: stretch;
            flex-direction: column;
        }
    }
</style>
@endpush

@section('content')
    <div class="admin-page-heading">
        <div>
            <h1>Kelola Admin</h1>
            <p>Daftar pengguna dengan akses administrator studio dan manajemen hak akses.</p>
        </div>
        <div class="admin-page-heading-actions">
            @can('create', App\Models\User::class)
                <button class="admin-add-admin-trigger" type="button" id="openAddAdminModal" aria-haspopup="dialog" aria-controls="addAdminModal">
                    <svg viewBox="0 0 24 24" aria-hidden="true"><path d="M12 5v14m-7-7h14"/></svg>
                    TAMBAH ADMIN
                </button>
            @endcan
        </div>
    </div>

    @if (session('success'))
        <div role="alert" style="padding: 12px 16px; margin-bottom: 24px; background-color: #f1f8f3; border: 1px solid #c7e6cf; border-radius: 6px; color: #1e6b37; font-size: 13px; font-weight: 600;">
            {{ session('success') }}
        </div>
    @endif

    @if (session('error'))
        <div role="alert" style="padding: 12px 16px; margin-bottom: 24px; background-color: #fdf2f0; border: 1px solid #f2c7c0; border-radius: 6px; color: #a93822; font-size: 13px; font-weight: 600;">
            {{ session('error') }}
        </div>
    @endif

    @if ($errors->any())
        <div role="alert" style="padding: 12px 16px; margin-bottom: 24px; background-color: #fdf2f0; border: 1px solid #f2c7c0; border-radius: 6px; color: #a93822; font-size: 13px;">
            <ul style="margin: 0; padding-left: 20px;">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    @can('create', App\Models\User::class)
        <div class="admin-add-admin-overlay {{ $errors->any() ? 'is-open' : '' }}" id="addAdminModal" role="dialog" aria-modal="true" aria-labelledby="addAdminModalTitle" aria-hidden="{{ $errors->any() ? 'false' : 'true' }}">
            <section class="admin-add-admin-modal" role="document">
                <header>
                    <div>
                        <h2 id="addAdminModalTitle">Tambah Admin Baru</h2>
                        <p>Masukkan email Google calon admin. Akun akan aktif dan profilnya akan tersinkronisasi saat pertama kali login.</p>
                    </div>
                    <button class="admin-add-admin-close" type="button" aria-label="Tutup dialog">×</button>
                </header>
                @if ($errors->any())
                    <div role="alert">{{ $errors->first() }}</div>
                @endif
                <form class="admin-add-admin-form" action="{{ route('admin.admins.store') }}" method="POST">
                    @csrf
                    <input type="email" name="email" value="{{ old('email') }}" placeholder="nama@gmail.com" aria-label="Email Google admin" required>
                    <button class="admin-add-admin-submit" type="submit">TAMBAH ADMIN</button>
                </form>
            </section>
        </div>
    @else
        <div style="padding: 14px 18px; margin-bottom: 24px; background: #faf8f5; border: 1px solid #e7e2da; border-radius: 6px; font-size: 12px; color: #8d8379;">
            <em>Hanya akun dengan peran <strong>Owner</strong> yang memiliki hak untuk menambah atau menonaktifkan admin lain.</em>
        </div>
    @endcan

    <section class="admin-panel">
        <h2 style="font-size: 18px; margin-top: 0; margin-bottom: 18px;">Daftar Administrator</h2>

        <div style="overflow-x: auto;">
            <table style="width: 100%; border-collapse: collapse; font-size: 13px; text-align: left;">
                <thead>
                    <tr style="border-bottom: 2px solid #e7e2da; color: #8d8379; font-size: 11px; letter-spacing: 0.05em; text-transform: uppercase;">
                        <th style="padding: 10px 12px;">Admin</th>
                        <th style="padding: 10px 12px;">Email</th>
                        <th style="padding: 10px 12px;">Role</th>
                        <th style="padding: 10px 12px;">Status</th>
                        <th style="padding: 10px 12px;">Ditambahkan Oleh</th>
                        <th style="padding: 10px 12px; text-align: right;">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($admins as $adminItem)
                        <tr style="border-bottom: 1px solid #f0eae1;">
                            <td style="padding: 12px; display: flex; align-items: center; gap: 10px;">
                                @if ($adminItem->avatar)
                                    <img src="{{ $adminItem->avatar }}" alt="" style="width: 32px; height: 32px; border-radius: 50%; object-fit: cover;">
                                @else
                                    <div style="width: 32px; height: 32px; border-radius: 50%; background: #e7e2da; display: flex; align-items: center; justify-content: center; font-weight: 700; color: #6d655d; font-size: 12px;">
                                        {{ strtoupper(substr($adminItem->name ?: $adminItem->email, 0, 1)) }}
                                    </div>
                                @endif
                                <div>
                                    <strong style="color: #211f1c;">{{ $adminItem->name ?: 'Belum Login' }}</strong>
                                    @if ($adminItem->id === auth()->id())
                                        <span style="font-size: 10px; background: #f0eae1; padding: 2px 6px; border-radius: 3px; margin-left: 4px; color: #6d655d;">Anda</span>
                                    @endif
                                </div>
                            </td>
                            <td style="padding: 12px; color: #4a453f;">{{ $adminItem->email }}</td>
                            <td style="padding: 12px;">
                                @if ($adminItem->role === 'owner')
                                    <span style="display: inline-block; padding: 3px 8px; border-radius: 4px; font-size: 10px; font-weight: 800; letter-spacing: 0.05em; background: #fbf3e4; color: #9c650c; border: 1px solid #edd4a8;">OWNER</span>
                                @else
                                    <span style="display: inline-block; padding: 3px 8px; border-radius: 4px; font-size: 10px; font-weight: 700; letter-spacing: 0.05em; background: #eef2f6; color: #35537a; border: 1px solid #cad7e6;">ADMIN</span>
                                @endif
                            </td>
                            <td style="padding: 12px;">
                                @if ($adminItem->is_active)
                                    <span style="display: inline-flex; align-items: center; gap: 6px; color: #1e6b37; font-weight: 600; font-size: 12px;">
                                        <span style="width: 7px; height: 7px; border-radius: 50%; background: #28a745;"></span> Aktif
                                    </span>
                                @else
                                    <span style="display: inline-flex; align-items: center; gap: 6px; color: #8d8379; font-weight: 600; font-size: 12px;">
                                        <span style="width: 7px; height: 7px; border-radius: 50%; background: #a0978d;"></span> Nonaktif
                                    </span>
                                @endif
                            </td>
                            <td style="padding: 12px; color: #6d655d; font-size: 12px;">
                                {{ $adminItem->creator ? ($adminItem->creator->name ?: $adminItem->creator->email) : 'Sistem / Seeder' }}
                            </td>
                            <td style="padding: 12px; text-align: right;">
                                @if ($adminItem->id === auth()->id())
                                    <span style="font-size: 11px; color: #8d8379;">Akun Anda</span>
                                @elseif ($adminItem->role === 'owner')
                                    <span style="font-size: 11px; color: #8d8379;">Owner</span>
                                @elseif (auth()->user()->isOwner())
                                    <form action="{{ route('admin.admins.toggle-status', $adminItem) }}" method="POST" style="display: inline; margin: 0;">
                                        @csrf
                                        @method('PATCH')
                                        <button
                                            type="submit"
                                            style="border: 1px solid {{ $adminItem->is_active ? '#e3b7af' : '#b2d9bb' }}; background: {{ $adminItem->is_active ? '#fff5f3' : '#f4faf6' }}; color: {{ $adminItem->is_active ? '#a93822' : '#1e6b37' }}; border-radius: 4px; padding: 4px 10px; font-size: 11px; font-weight: 700; cursor: pointer;"
                                            onclick="return confirm('Apakah Anda yakin ingin {{ $adminItem->is_active ? 'menonaktifkan' : 'mengaktifkan kembali' }} akun {{ $adminItem->email }}?');"
                                        >
                                            {{ $adminItem->is_active ? 'Nonaktifkan' : 'Aktifkan' }}
                                        </button>
                                    </form>
                                @else
                                    <span style="font-size: 11px; color: #8d8379;">—</span>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" style="padding: 24px; text-align: center; color: #8d8379;">
                                Belum ada data administrator.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </section>
@endsection

@push('scripts')
<script>
    const addAdminModal = document.getElementById('addAdminModal');
    const openAddAdminModalButton = document.getElementById('openAddAdminModal');
    const closeAddAdminModalButton = addAdminModal?.querySelector('.admin-add-admin-close');
    const addAdminEmailInput = addAdminModal?.querySelector('input[name="email"]');

    function closeAddAdminModal() {
        if (!addAdminModal) {
            return;
        }

        addAdminModal.classList.remove('is-open');
        addAdminModal.setAttribute('aria-hidden', 'true');
        openAddAdminModalButton?.focus();
    }

    openAddAdminModalButton?.addEventListener('click', () => {
        addAdminModal?.classList.add('is-open');
        addAdminModal?.setAttribute('aria-hidden', 'false');
        addAdminEmailInput?.focus();
    });

    closeAddAdminModalButton?.addEventListener('click', closeAddAdminModal);

    addAdminModal?.addEventListener('click', (event) => {
        if (event.target === addAdminModal) {
            closeAddAdminModal();
        }
    });

    window.addEventListener('keydown', (event) => {
        if (event.key === 'Escape' && addAdminModal?.classList.contains('is-open')) {
            closeAddAdminModal();
        }
    });

    if (addAdminModal?.classList.contains('is-open')) {
        addAdminEmailInput?.focus();
    }
</script>
@endpush
