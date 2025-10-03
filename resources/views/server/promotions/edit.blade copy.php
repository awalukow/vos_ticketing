@extends('layouts.app')
@section('title', 'Edit Promotion')
@section('heading', 'Edit Promotion')

@section('content')
<div class="card shadow mb-4">
    <div class="card-header py-3">
        <h6 class="m-0 font-weight-bold text-primary">Edit Kode Promo</h6>
    </div>
    <div class="card-body">
        <form action="{{ route('promotions.update', $promotion) }}" method="POST">
            @csrf
            @method('PUT')
            @include('server.promotions.form')
            <div class="d-flex justify-content-end">
                <a href="{{ route('promotions.index') }}" class="btn btn-secondary mr-2">Batal</a>
                <button type="submit" class="btn btn-primary">Perbarui</button>
            </div>
        </form>
    </div>
</div>
@endsection

@section('script')
<script src="{{ asset('vendor/select2/dist/js/select2.full.min.js') }}"></script>
<script>
    $(document).ready(function () {
        $('.select2').select2({
            placeholder: '-- Pilih --',
            allowClear: true,
            width: '100%'
        });
    });
</script>
@endsection