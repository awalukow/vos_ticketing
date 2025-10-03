@extends('layouts.app')
@section('title', 'Edit Promotion')
@section('heading', 'Edit Kode Promo')

@section('styles')
<link href="{{ asset('vendor/select2/dist/css/select2.min.css') }}" rel="stylesheet"/>
<style>
    .form-group {
        margin-bottom: 1.2rem;
    }
    .form-check-label {
        cursor: pointer;
    }
    .select2-container {
        width: 100% !important;
    }
    .card-title {
        float: left;
        font-size: 1.1rem;
        font-weight: 400;
        margin: 0;
    }
    .card-text {
        clear: both;
    }
    small.text-muted {
        font-size: 80%;
        color: #6c757d;
    }
</style>
@endsection

@section('content')
<div class="card shadow mb-4">
    <div class="card-header py-3">
        <h6 class="m-0 font-weight-bold text-primary">Edit Kode Promo</h6>
    </div>

    <div class="card-body">
        <form action="{{ route('promotions.update', $promotion) }}" method="POST">
            @csrf
            @method('PUT')

            <!-- Kode Promo -->
            <div class="form-group">
                <label for="code">Kode Promo *</label>
                <input type="text"
                       name="code"
                       id="code"
                       class="form-control @error('code') is-invalid @enderror"
                       value="{{ old('code', $promotion->code) }}"
                       required
                       maxlength="12"
                       style="text-transform: uppercase;">
                @error('code')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
                <small class="text-muted">Gunakan huruf besar tanpa spasi.</small>
            </div>

            <!-- Jenis Diskon -->
            <div class="form-group">
                <label for="discount_type">Jenis Diskon *</label>
                <select name="discount_type"
                        id="discount_type"
                        class="form-control @error('discount_type') is-invalid @enderror"
                        required
                        onchange="toggleDiscountValue()">
                    <option value="">-- Pilih Jenis --</option>
                    <option value="percent" {{ old('discount_type', $promotion->discount_type) == 'percent' ? 'selected' : '' }}>
                        Persentase (%)
                    </option>
                    <option value="fixed" {{ old('discount_type', $promotion->discount_type) == 'fixed' ? 'selected' : '' }}>
                        Nominal (Rp)
                    </option>
                </select>
                @error('discount_type')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <!-- Nilai Diskon -->
            <div class="form-group">
                <label for="discount_value">Nilai Diskon *</label>
                <input type="number"
                       step="any"
                       min="0"
                       name="discount_value"
                       id="discount_value"
                       class="form-control @error('discount_value') is-invalid @enderror"
                       value="{{ old('discount_value', $promotion->discount_value) }}"
                       required>
                @error('discount_value')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <!-- Batas Penggunaan -->
            <div class="form-group">
                <label for="max_uses">Batas Penggunaan *</label>
                <input type="number"
                       min="1"
                       name="max_uses"
                       id="max_uses"
                       class="form-control @error('max_uses') is-invalid @enderror"
                       value="{{ old('max_uses', $promotion->max_uses) }}"
                       required>
                @error('max_uses')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
                <small class="text-muted">Jumlah maksimal penggunaan promo ini.</small>
            </div>

            <!-- Kadaluarsa -->
            <div class="form-group">
                <label for="expires_at">Kadaluarsa (Opsional)</label>
                <input type="datetime-local"
                       name="expires_at"
                       id="expires_at"
                       class="form-control @error('expires_at') is-invalid @enderror"
                       value="{{ old('expires_at', $promotion->expires_at?->format('Y-m-d\TH:i')) }}">
                @error('expires_at')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <!-- Status Aktif -->
            <div class="form-group form-check">
                <input type="checkbox"
                       name="is_active"
                       id="is_active"
                       class="form-check-input"
                       {{ old('is_active', $promotion->is_active) ? 'checked' : '' }}>
                <label class="form-check-label" for="is_active">Aktif</label>
            </div>

            <!-- Untuk Pengguna Tertentu -->
            <div class="form-group">
                <label for="penumpang_id">Untuk Pengguna Tertentu (Opsional)</label>
                <select name="penumpang_id"
                        id="penumpang_id"
                        class="form-control select2 @error('penumpang_id') is-invalid @enderror"
                        style="width: 100%;">
                    <option value="">-- Semua Pengguna --</option>
                    @foreach (\App\Models\User::where('level', 'Penumpang')->get() as $user)
                        <option value="{{ $user->id }}"
                            {{ old('penumpang_id', $promotion->penumpang_id) == $user->id ? 'selected' : '' }}>
                            {{ $user->name }} ({{ $user->username }})
                        </option>
                    @endforeach
                </select>
                @error('penumpang_id')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
                <small class="text-muted">Jika dipilih, hanya pengguna ini yang bisa gunakan kode ini.</small>
            </div>

            <!-- Berlaku untuk Rute Tertentu -->
            <div class="form-group">
                <label for="rute_id">Berlaku untuk Rute Tertentu (Opsional)</label>
                <select name="rute_id"
                        id="rute_id"
                        class="form-control select2 @error('rute_id') is-invalid @enderror"
                        style="width: 100%;">
                    <option value="">-- Semua Rute --</option>
                    @foreach (\App\Models\Rute::with('transportasi.category')->orderBy('tujuan')->get() as $rute)
                        <option value="{{ $rute->id }}"
                            {{ old('rute_id', $promotion->rute_id) == $rute->id ? 'selected' : '' }}>
                            {{ $rute->tujuan }} ({{ $rute->transportasi->category->name ?? 'Unknown' }})
                        </option>
                    @endforeach
                </select>
                @error('rute_id')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
                <small class="text-muted">Jika dipilih, hanya berlaku saat booking rute ini.</small>
            </div>

            <!-- Buttons -->
            <div class="d-flex justify-content-end mt-4">
                <a href="{{ route('promotions.index') }}" class="btn btn-secondary mr-2">Batal</a>
                <button type="submit" class="btn btn-primary">Perbarui Promo</button>
            </div>
        </form>
    </div>
</div>
@endsection

@section('script')
<script src="{{ asset('vendor/select2/dist/js/select2.full.min.js') }}"></script>
<script>
    // Toggle input step based on discount type
    function toggleDiscountValue() {
        const type = document.getElementById('discount_type').value;
        const input = document.getElementById('discount_value');
        if (type === 'percent') {
            input.step = '0.01';
            input.placeholder = 'Contoh: 10.5';
        } else {
            input.step = '1';
            input.placeholder = 'Contoh: 25000';
        }
    }

    // Initialize on load
    document.addEventListener('DOMContentLoaded', function () {
        toggleDiscountValue();

        // Initialize Select2
        $('.select2').select2({
            placeholder: '-- Pilih --',
            allowClear: true,
            width: '100%'
        });

        // Force selected values in Select2 (critical for Edit)
        const penumpangId = "{{ old('penumpang_id', $promotion->penumpang_id) }}";
        const ruteId = "{{ old('rute_id', $promotion->rute_id) }}";

        if (penumpangId) {
            $('#penumpang_id').val(penumpangId).trigger('change');
        }
        if (ruteId) {
            $('#rute_id').val(ruteId).trigger('change');
        }
    });
</script>
@endsection