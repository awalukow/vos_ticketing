@extends('layouts.app')
@section('title', 'Login')
@section('content')
  <style>
    .bg-gradient-primary {
      background-color: #970b72ff;
      background-image: linear-gradient(135deg, #b71ea8ff 0%, #4d1d60ff 100%);
      min-height: 100vh;
    }

    .card-login {
      border: none;
      border-radius: 1rem;
      box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
      overflow: hidden;
    }

    .form-control-user {
      border-radius: 1.5rem;
      padding: 0.75rem 1.25rem;
      font-size: 0.95rem;
    }

    .btn-user {
      border-radius: 1.5rem;
      padding: 0.75rem;
      font-weight: 600;
      transition: all 0.3s ease;
    }

    .btn-user:hover {
      transform: translateY(-2px);
    }

    .text-primary {
      color: #b71ea8ff !important;
    }

    .link-primary {
      color: #b71ea8ff;
      transition: color 0.2s;
    }

    .link-primary:hover {
      color: #8a1078;
      text-decoration: underline;
    }
  </style>

  <div class="container d-flex justify-content-center align-items-center min-vh-100">
    <div class="col-xl-4 col-lg-5 col-md-7">
      <div class="card card-login">
        <!-- Header with Logo -->
        <div class="text-center py-4 bg-white border-bottom">
          <img src="{{ asset('img/vos-logo.png') }}" alt="Logo" style="max-height: 60px;">
          <small class="text-muted d-block mt-2">Masuk ke akun Anda</small>
        </div>
        
        <!-- Body -->
        <div class="card-body p-5">
          <form id="loginForm" method="POST" action="{{ route('login') }}" class="user">
            @csrf

            <!-- Username -->
            <div class="form-group mb-3">
              <input
                type="text"
                class="form-control form-control-user @error('login') is-invalid @enderror"
                name="login"
                id="login"
                value="{{ old('login') }}"
                required
                autocomplete="off"
                placeholder="Nomor HP / Email / Username"
                autofocus
              >
              @error('login')
                <div class="invalid-feedback d-block">
                  <small>{{ $message }}</small>
                </div>
              @enderror
            </div>

            <!-- Password -->
            <div class="form-group mb-3">
              <input
                type="password"
                class="form-control form-control-user @error('password') is-invalid @enderror"
                name="password"
                required
                placeholder="Kata Sandi"
              >
              @error('password')
                <div class="invalid-feedback d-block">
                  <small>{{ $message }}</small>
                </div>
              @enderror
            </div>

            <!-- Remember Me & Forgot Password -->
            <div class="d-flex justify-content-between mb-4">
              <div class="custom-control custom-checkbox small">
                <input
                  type="checkbox"
                  class="custom-control-input"
                  name="remember"
                  id="remember"
                  {{ old('remember') ? 'checked' : '' }}
                >
                <label class="custom-control-label" for="remember">{{ __('Ingat Saya') }}</label>
              </div>
              @if (Route::has('password.request'))
                  <a class="link-primary small" href="{{ route('password.request') }}">Lupa kata sandi?</a>
              @endif
            </div>

            <!-- Login Button -->
            <button
              type="button"
              id="loginButton"
              class="btn btn-primary btn-user btn-block btn-lg"
            >
              <span id="buttonText">{{ __('Masuk') }}</span>
              <span id="loadingSpinner" class="spinner-border spinner-border-sm d-none" role="status"></span>
            </button>
          </form>

          <!-- Divider -->
          <hr class="my-4">
          <div class="text-center">
            <small class="text-muted">Belum punya akun? 
              <a href="{{ route('fast-register') }}" class="link-primary">Daftar di sini</a>
            </small>
          </div>
        </div>
      </div>
    </div>
  </div>
@endsection

@section('script')
  <script>
    // Add gradient background
    document.body.classList.add("bg-gradient-primary");

    // Handle login button click
    document.getElementById("loginButton").addEventListener("click", function () {
      const button = document.getElementById("loginButton");
      const text = document.getElementById("buttonText");
      const spinner = document.getElementById("loadingSpinner");

      // Prevent multiple submissions
      if (button.disabled) return;

      // Get username input
      const usernameInput = document.getElementById("login");
      let inputValue = usernameInput.value.trim();

      // Auto-format: replace leading '0' with '62'
      if (inputValue && inputValue.startsWith("0")) {
        usernameInput.value = "62" + inputValue.slice(1);
      }

      // Show loading state
      button.disabled = true;
      text.textContent = "Memuat...";
      spinner.classList.remove("d-none");

      // Submit form
      document.getElementById("loginForm").submit();
    });
  </script>
@endsection