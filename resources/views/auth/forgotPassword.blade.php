@extends('layouts.app')
@section('title', 'Lupa Kata Sandi')
@section('content')
<style>
    .bg-gradient-primary {
        background-color: #970b72ff;
        background-image: linear-gradient(135deg, #b71ea8ff 0%, #4d1d60ff 100%);
        min-height: 100vh;
        margin: 0;
        background-size: cover;
        background-position: center;
    }
</style>

<div class="container d-flex justify-content-center align-items-center min-vh-100">
    <div class="col-xl-4 col-lg-5 col-md-7">
        <div class="card shadow-sm border-0">
            <div class="card-body p-5">
                <h2 class="h4 text-center text-gray-900 mb-4">Lupa Kata Sandi?</h2>
                <p class="text-muted text-center mb-4">
                    Masukkan email Anda, kami akan kirimkan tautan untuk mengatur ulang kata sandi.
                </p>

                @if (session('status'))
                    <div class="alert alert-success">
                        {{ session('status') }}
                    </div>
                @endif

                <form method="POST" action="{{ route('password.email') }}">
                    @csrf

                    <div class="form-group mb-3">
                        <input
                            type="email"
                            name="email"
                            class="form-control form-control-user @error('email') is-invalid @enderror"
                            placeholder="Alamat Email"
                            value="{{ old('email') }}"
                            required
                        >
                        @error('email')
                            <div class="invalid-feedback d-block">
                                <small>{{ $message }}</small>
                            </div>
                        @enderror
                    </div>

                    <button type="submit" class="btn btn-primary btn-user btn-block">
                        Kirim Tautan Reset
                    </button>
                </form>

                <hr class="my-4">
                <div class="text-center">
                    <a class="small link-primary" href="{{ route('login') }}">Kembali ke Login</a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('script')
<script>
    // Apply gradient background to body
    document.body.classList.add('bg-gradient-primary');
</script>
@endsection