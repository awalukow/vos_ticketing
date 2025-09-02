@extends('layouts.app')
@section('title', 'Atur Ulang Kata Sandi')
@section('content')
<style>
    .bg-gradient-primary {
        background-color: #970b72ff;
        background-image: linear-gradient(135deg, #b71ea8ff 0%, #4d1d60ff 100%);
        min-height: 100vh;
    }
</style>

<div class="container d-flex justify-content-center align-items-center min-vh-100">
    <div class="col-xl-4 col-lg-5 col-md-7">
        <div class="card shadow-sm border-0">
            <div class="card-body p-5">
                <h2 class="h4 text-center text-gray-900 mb-4">Atur Ulang Kata Sandi</h2>

                <form method="POST" action="{{ route('password.update') }}">
                    @csrf

                    <!-- Token (hidden) -->
                    <input type="hidden" name="token" value="{{ $token }}">

                    <div class="form-group mb-3">
                        <input
                            type="email"
                            name="email"
                            class="form-control form-control-user @error('email') is-invalid @enderror"
                            placeholder="Email"
                            value="{{ $email ?? old('email') }}"
                            required
                        >
                        @error('email')
                            <div class="invalid-feedback d-block">
                                <small>{{ $message }}</small>
                            </div>
                        @enderror
                    </div>

                    <div class="form-group mb-3">
                        <input
                            type="password"
                            name="password"
                            class="form-control form-control-user @error('password') is-invalid @enderror"
                            placeholder="Kata Sandi Baru"
                            required
                        >
                        @error('password')
                            <div class="invalid-feedback d-block">
                                <small>{{ $message }}</small>
                            </div>
                        @enderror
                    </div>

                    <div class="form-group mb-3">
                        <input
                            type="password"
                            name="password_confirmation"
                            class="form-control form-control-user"
                            placeholder="Konfirmasi Kata Sandi"
                            required
                        >
                    </div>

                    <button type="submit" class="btn btn-primary btn-user btn-block">
                        Reset Kata Sandi
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