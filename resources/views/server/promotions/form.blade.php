<div class="row">
    <div class="col-md-6">
        <div class="form-group">
            <label for="code">Kode Promo *</label>
            <div class="input-group">
                <input type="text"
                    name="code"
                    id="code"
                    class="form-control"
                    value="{{ old('code') }}"
                    required
                    maxlength="8"
                    style="text-transform: uppercase;"
                    placeholder="Contoh: ABCD1234">
                <div class="input-group-append">
                    <button class="btn btn-outline-secondary" type="button" id="generatePromoCode">
                        Acak Kode
                    </button>
                </div>
            </div>
            <small class="text-muted">Huruf besar, tanpa spasi. Maksimal 8 karakter.</small>
        </div>
    </div>
    <div class="col-md-6">
        <div class="form-group">
            <label for="discount_type">Jenis Diskon *</label>
            <select name="discount_type" id="discount_type" class="form-control" required onchange="toggleDiscountValue()">
                <option value="">-- Pilih --</option>
                <option value="percent" {{ old('discount_type') == 'percent' ? 'selected' : '' }}>Persentase (%)</option>
                <option value="fixed" {{ old('discount_type') == 'fixed' ? 'selected' : '' }}>Nominal (Rp)</option>
                <option value="bogo" {{ old('discount_type') == 'bogo' ? 'selected' : '' }}>Beli Dapat Gratis (BOGO)</option>
                <option value="ticket_discount" {{ old('discount_type') == 'ticket_discount' ? 'selected' : '' }}> Diskon Tiket (Potong Harga per Tiket)</option>
            </select>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-md-6">
        <div class="form-group">
            <label for="discount_value">Nilai Diskon *</label>
            <input type="number" name="discount_value" id="discount_value" class="form-control" value="{{ old('discount_value') }}" required min="0" step="any">
        </div>
    </div>
    <div class="col-md-6">
        <div class="form-group">
            <label for="max_uses">Batas Penggunaan *</label>
            <input type="number" name="max_uses" id="max_uses" class="form-control" value="{{ old('max_uses', 1) }}" required min="1">
        </div>
    </div>
</div>

<div class="row">
    <div class="col-md-6">
        <div class="form-group">
            <label for="min_order">Minimal Jumlah Tiket (Opsional)</label>
            <input type="number"
                name="min_order"
                id="min_order"
                class="form-control"
                value="{{ old('min_order', 1) }}"
                min="1"
                placeholder="Contoh: 3">
            <small class="text-muted">Jika diisi, promo hanya berlaku jika jumlah tiket ≥ nilai ini.</small>
        </div>
        <div class="form-group">
            <label for="expires_at">Kadaluarsa (Opsional)</label>
            <input type="datetime-local" name="expires_at" id="expires_at" class="form-control" value="{{ old('expires_at') }}">
        </div>
    </div>
    <div class="col-md-6">
        <div class="form-group form-check mt-4">
            <input type="checkbox" class="form-check-input" id="is_active" name="is_active" {{ old('is_active') ? 'checked' : '' }}>
            <label class="form-check-label" for="is_active">Aktif</label>
        </div>
    </div>
</div>

<!-- BOGO Fields (Hidden by Default) -->
<div id="bogo-fields" class="row" style="display: none;">
    <div class="col-md-6">
        <div class="form-group">
            <label for="buy_quantity">Beli (Qty) *</label>
            <input type="number"
                   name="buy_quantity"
                   id="buy_quantity"
                   class="form-control"
                   value="{{ old('buy_quantity') }}"
                   min="1"
                   placeholder="Contoh: 2">
            <small class="text-muted">Jumlah tiket yang harus dibeli.</small>
        </div>
    </div>
    <div class="col-md-6">
        <div class="form-group">
            <label for="get_free">Dapatkan Gratis (Qty) *</label>
            <input type="number"
                   name="get_free"
                   id="get_free"
                   class="form-control"
                   value="{{ old('get_free') }}"
                   min="1"
                   placeholder="Contoh: 1">
            <small class="text-muted">Jumlah tiket gratis per set.</small>
        </div>
    </div>
</div>

<!-- Optional: Assign to Specific User -->
<div class="form-group">
    <label for="penumpang_id">Untuk Pengguna Tertentu (Opsional)</label>
    <select name="penumpang_id" id="penumpang_id" class="form-control select2" style="width: 100%;">
        <option value="">-- Semua Pengguna --</option>
        @foreach (\App\Models\User::where('level', 'Penumpang')->get() as $user)
            <option value="{{ $user->id }}" {{ old('penumpang_id') == $user->id ? 'selected' : '' }}>
                {{ $user->name }} ({{ $user->username }})
            </option>
        @endforeach
    </select>
    <small class="text-muted">Jika dipilih, hanya pengguna ini yang bisa pakai kode ini.</small>
</div>

<!-- Optional: Restrict to Route -->
<div class="form-group">
    <label for="rute_id">Berlaku untuk Kelas Tertentu (Opsional)</label>
    <select name="rute_id" id="rute_id" class="form-control select2" style="width: 100%;">
        <option value="">-- Semua Kelas --</option>
        @foreach (\App\Models\Rute::with('transportasi.category')->get() as $rute)
            <option value="{{ $rute->id }}" {{ old('rute_id') == $rute->id ? 'selected' : '' }}>
                {{ $rute->tujuan }} ({{ $rute->transportasi->category->name ?? '-' }})
            </option>
        @endforeach
    </select>
    <small class="text-muted">Jika dipilih, hanya berlaku untuk kelas ini.</small>
</div>

<script>
document.addEventListener('DOMContentLoaded', function () {
    const discountType = document.getElementById('discount_type');
    const discountValueGroup = document.getElementById('discount_value').closest('.form-group');
    const discountValueInput = document.getElementById('discount_value');
    const bogoFields = document.getElementById('bogo-fields');

    // --- Function to toggle visibility & values ---
    function toggleDiscountValue() {
        const type = discountType.value;

        if (type === 'percent') {
            discountValueGroup.style.display = 'block';
            bogoFields.style.display = 'none';
            discountValueInput.required = true;
            discountValueInput.step = '0.01';
            discountValueInput.placeholder = 'Contoh: 10.5';
        } 
        else if (type === 'fixed') {
            discountValueGroup.style.display = 'block';
            bogoFields.style.display = 'none';
            discountValueInput.required = true;
            discountValueInput.step = '1';
            discountValueInput.placeholder = 'Contoh: 25000';
        } 
        else if (type === 'bogo') {
            // Hide discount value field, default to 0
            discountValueGroup.style.display = 'none';
            discountValueInput.value = 0;
            discountValueInput.required = false;
            bogoFields.style.display = 'flex';
        } 
        else {
            // Default: show discount value
            discountValueGroup.style.display = 'block';
            bogoFields.style.display = 'none';
            discountValueInput.required = true;
        }
    }

    // --- Initialize state on load ---
    toggleDiscountValue();

    // --- Event listeners ---
    discountType.addEventListener('change', toggleDiscountValue);

    // --- Initialize Select2 ---
    $('#penumpang_id').select2({
        placeholder: '-- Semua Pengguna --',
        allowClear: true,
        width: '100%'
    });

    $('#rute_id').select2({
        placeholder: '-- Semua Kelas --',
        allowClear: true,
        width: '100%'
    });

    // --- Generate random 8-character alphanumeric code ---
    document.getElementById('generatePromoCode').addEventListener('click', function () {
        const chars = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789';
        let code = '';
        for (let i = 0; i < 8; i++) {
            code += chars.charAt(Math.floor(Math.random() * chars.length));
        }

        const input = document.getElementById('code');
        input.value = code;
        input.focus();

        // Brief highlight effect
        input.style.backgroundColor = '#fff3cd';
        setTimeout(() => {
            input.style.backgroundColor = '';
        }, 300);
    });
});
</script>
