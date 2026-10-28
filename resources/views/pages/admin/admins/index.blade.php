@extends('layouts.admin')

@section('title', 'Kelola Admin — Excellent Barbershop')

@section('content')
    <div class="admin-page-heading">
        <div>
            <span>PANEL PENGELOLA</span>
            <h1>Kelola Admin</h1>
            <p>Daftar pengguna dengan akses administrator studio dan manajemen hak akses.</p>
        </div>
        <a href="{{ route('admin.dashboard') }}">← KEMBALI KE DASHBOARD</a>
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
        <section class="admin-panel" style="margin-bottom: 28px;">
            <h2 style="font-size: 18px; margin-top: 0; margin-bottom: 8px;">Tambah Admin Baru</h2>
            <p style="margin-top: 0; margin-bottom: 16px; font-size: 13px; color: #6d655d;">
                Masukkan email Google calon admin. Akun akan dibuat dengan status aktif, dan profil (nama serta foto) akan tersinkronisasi otomatis saat pertama kali login.
            </p>

            <form action="{{ route('admin.admins.store') }}" method="POST" style="display: flex; gap: 12px; align-items: flex-start; max-width: 600px;">
                @csrf
                <div style="flex: 1;">
                    <input
                        type="email"
                        name="email"
                        value="{{ old('email') }}"
                        placeholder="nama@gmail.com"
                        required
                        style="width: 100%; height: 42px; padding: 0 12px; border: 1px solid #ded8cf; border-radius: 5px; font: inherit;"
                    >
                </div>
                <button
                    type="submit"
                    style="height: 42px; padding: 0 20px; border: 0; border-radius: 5px; background: #a93822; color: #fff; font-weight: 700; font-size: 12px; letter-spacing: 0.05em; cursor: pointer;"
                >
                    TAMBAH ADMIN
                </button>
            </form>
        </section>
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
