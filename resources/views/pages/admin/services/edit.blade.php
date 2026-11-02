@extends('layouts.admin')

@section('title', 'Edit Layanan — ' . $service->name)

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
            <a href="{{ route('admin.services.index') }}" style="font-size: 11px; font-weight: 700; color: #a67c37; text-decoration: none;">
                ← Kembali ke Katalog Layanan
            </a>
            <h1 style="font-size: 19px; font-weight: 800; color: #0e1726; margin-top: 4px;">Edit Layanan Grooming</h1>
        </div>
    </div>

    <div class="edit-page-card">
        <form action="{{ route('admin.services.update', $service) }}" method="POST">
            @csrf
            @method('PUT')

            <div class="form-group-item">
                <label>Nama Layanan *</label>
                <input type="text" name="name" class="form-control-styled" value="{{ old('name', $service->name) }}" required>
            </div>

            <div class="form-group-item">
                <label>Kategori Layanan *</label>
                <select name="category" class="form-control-styled" required>
                    <option value="haircut_styling" {{ $service->category === 'haircut_styling' ? 'selected' : '' }}>HAIRCUT & STYLING</option>
                    <option value="beard_shaving" {{ $service->category === 'beard_shaving' ? 'selected' : '' }}>BEARD & SHAVING</option>
                    <option value="treatment_others" {{ $service->category === 'treatment_others' ? 'selected' : '' }}>TREATMENT & DETOX</option>
                </select>
            </div>

            <div class="form-group-item">
                <label>Badge Tambahan (Opsional)</label>
                <input type="text" name="badge" class="form-control-styled" value="{{ old('badge', $service->badge) }}" placeholder="BEST SELLER / RESTOCK BAHAN">
            </div>

            <div class="form-group-item">
                <label>Harga Resmi (Rp) *</label>
                <input type="number" name="price" class="form-control-styled" value="{{ old('price', round($service->price)) }}" min="0" required>
            </div>

            <div class="form-group-item">
                <label>Durasi (Menit) *</label>
                <input type="number" name="duration_minutes" class="form-control-styled" value="{{ old('duration_minutes', $service->duration_minutes) }}" min="5" required>
            </div>

            <div class="form-group-item">
                <label>Deskripsi Layanan</label>
                <textarea name="description" class="form-control-styled" style="height: 70px; padding: 8px 12px;">{{ old('description', $service->description) }}</textarea>
            </div>

            <div style="display: flex; gap: 10px; margin-top: 18px;">
                <button type="submit" style="flex: 1; height: 42px; background: #0e1726; color: #ffffff; border: none; border-radius: 8px; font-weight: 700; font-size: 12px; cursor: pointer;">
                    Simpan Perubahan
                </button>
                <a href="{{ route('admin.services.index') }}" style="height: 42px; padding: 0 16px; background: #f1f5f9; color: #334155; border-radius: 8px; font-weight: 700; font-size: 12px; display: flex; align-items: center; justify-content: center; text-decoration: none;">
                    Batal
                </a>
            </div>
        </form>
    </div>
</div>
@endsection

