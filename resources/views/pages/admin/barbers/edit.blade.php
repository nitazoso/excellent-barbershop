@extends('layouts.admin')

@section('title', 'Edit Profil Barber — ' . $barber->name)

@push('styles')
<style>
    .edit-page-card {
        background: #ffffff;
        border: 1px solid #eef0f4;
        border-radius: 12px;
        padding: 20px;
        box-shadow: 0 2px 8px rgba(0,0,0,0.03);
    }
    .form-group-item {
        display: flex;
        flex-direction: column;
        gap: 5px;
        margin-bottom: 12px;
    }
    .form-group-item label {
        font-size: 11.5px;
        font-weight: 700;
        color: #334155;
    }
    .form-control-styled {
        height: 40px;
        border: 1px solid #e2e8f0;
        border-radius: 8px;
        padding: 0 12px;
        font-size: 12px;
        color: #0e1726;
        outline: none;
    }
    .form-control-styled:focus {
        border-color: #a67c37;
    }
</style>
@endpush

@section('content')
<div style="display: flex; flex-direction: column; gap: 16px;">
    <div style="display: flex; align-items: center; justify-content: space-between;">
        <div>
            <a href="{{ route('admin.barbers.index') }}" style="font-size: 11px; font-weight: 700; color: #a67c37; text-decoration: none;">
                ← Kembali ke Master Barber
            </a>
            <h1 style="font-size: 19px; font-weight: 800; color: #0e1726; margin-top: 4px;">Edit Profil Barber</h1>
        </div>
    </div>

    <div class="edit-page-card">
        <form action="{{ route('admin.barbers.update', $barber) }}" method="POST">
            @csrf
            @method('PUT')

            <div class="form-group-item">
                <label>Nama Lengkap Barber *</label>
                <input type="text" name="name" class="form-control-styled" value="{{ old('name', $barber->name) }}" required>
            </div>

            <div class="form-group-item">
                <label>Nomor Kursi</label>
                <input type="number" name="chair_number" class="form-control-styled" value="{{ old('chair_number', $barber->chair_number) }}" min="1">
            </div>

            <div class="form-group-item">
                <label>Jabatan / Tag Tim</label>
                <input type="text" name="role_title" class="form-control-styled" value="{{ old('role_title', $barber->role_title) }}" placeholder="Founder / Senior, Master Barber, dll">
            </div>

            <div class="form-group-item">
                <label>Spesialisasi</label>
                <input type="text" name="specialization" class="form-control-styled" value="{{ old('specialization', $barber->specialization) }}">
            </div>

            <div class="form-group-item">
                <label>Skill Tags (Pisahkan dengan koma)</label>
                <input type="text" name="skills_tags" class="form-control-styled" value="{{ old('skills_tags', $barber->skills_tags) }}">
            </div>

            <div class="form-group-item">
                <label>Status Ketersediaan</label>
                <select name="availability_status" class="form-control-styled">
                    <option value="online" {{ $barber->availability_status === 'online' ? 'selected' : '' }}>ONLINE</option>
                    <option value="cuti" {{ $barber->availability_status === 'cuti' ? 'selected' : '' }}>CUTI</option>
                    <option value="nonaktif" {{ $barber->availability_status === 'nonaktif' ? 'selected' : '' }}>NONAKTIF</option>
                </select>
            </div>

            <div class="form-group-item">
                <label>Rating & Ulasan</label>
                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 10px;">
                    <input type="number" step="0.01" name="rating" class="form-control-styled" value="{{ old('rating', $barber->rating) }}" placeholder="Rating (0-5)" min="0" max="5">
                    <input type="number" name="review_count" class="form-control-styled" value="{{ old('review_count', $barber->review_count) }}" placeholder="Jumlah ulasan" min="0">
                </div>
            </div>

            <div class="form-group-item">
                <label>Catatan Peringatan / Cuti</label>
                <textarea name="warning_note" class="form-control-styled" style="height: 60px; padding: 8px 12px;">{{ old('warning_note', $barber->warning_note) }}</textarea>
            </div>

            <div style="display: flex; gap: 10px; margin-top: 18px;">
                <button type="submit" style="flex: 1; height: 42px; background: #0e1726; color: #ffffff; border: none; border-radius: 8px; font-weight: 700; font-size: 12px; cursor: pointer;">
                    Simpan Perubahan
                </button>
                <a href="{{ route('admin.barbers.index') }}" style="height: 42px; padding: 0 16px; background: #f1f5f9; color: #334155; border-radius: 8px; font-weight: 700; font-size: 12px; display: flex; align-items: center; justify-content: center; text-decoration: none;">
                    Batal
                </a>
            </div>
        </form>
    </div>
</div>
@endsection

