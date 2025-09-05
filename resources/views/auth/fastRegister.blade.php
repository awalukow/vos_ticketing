@extends('layouts.app')

@section('title', 'Daftar Akun')

@section('content')
<style>
  /* Gradient Background */
  .bg-gradient-primary {
    background: linear-gradient(135deg, #b71ea8ff 0%, #4d1d60ff 100%);
    min-height: 100vh;
    margin: 0;
    background-attachment: fixed;
  }

  /* Card Styling */
  .card-register {
    border-radius: 1.25rem;
    box-shadow: 0 10px 30px rgba(0, 0, 0, 0.15);
    overflow: hidden;
    max-width: 1000px;
    margin: 1.5rem auto;
    background-color: #fff;
    display: flex;
    flex-direction: row;
    flex-wrap: wrap;
  }

  /* Form Input Styling */
  .form-control-user {
    border-radius: 50px;
    padding: 0.75rem 1.25rem 0.75rem 2.75rem;
    font-size: 0.95rem;
    border: 1px solid #ddd;
    transition: all 0.3s;
  }

  .form-control-user:focus {
    border-color: #b71ea8;
    box-shadow: 0 0 0 0.2rem rgba(183, 30, 168, 0.25);
  }

  /* Input Icon */
  .input-icon {
    position: absolute;
    left: 18px;
    top: 50%;
    transform: translateY(-50%);
    color: #999;
    font-size: 16px;
    z-index: 2;
    width: 20px;
    text-align: center;
  }

  /* reCAPTCHA Centering */
  .g-recaptcha {
    display: flex;
    justify-content: center;
    margin: 15px 0;
  }

  /* Responsive Adjustments */
  @media (max-width: 991.98px) {
    .card-register {
      flex-direction: column;
      max-width: 95%;
      min-height: auto;
    }

    .col-lg-6 {
      width: 100%;
    }

    .col-lg-6 img {
      height: 180px !important;
      object-position: center;
    }

    .p-5 {
      padding: 1.5rem !important;
    }

    .btn-user {
      font-size: 1rem;
    }
  }

  /* Mobile Full Width */
  @media (max-width: 576px) {
    .container-fluid,
    .col-xl-10,
    .col-lg-12,
    .col-md-12 {
      padding-left: 1rem;
      padding-right: 1rem;
    }

    .form-control-user {
      padding-left: 2.25rem;
    }

    .input-icon {
      left: 15px;
    }
  }

  /* Modal Loading Overlay */
  .loading-overlay {
    display: none;
    align-items: center;
    justify-content: center;
    position: absolute;
    inset: 0;
    background-color: rgba(0, 0, 0, 0.7);
    color: white;
    z-index: 10;
    border-radius: 1.25rem;
  }

  .modal-footer {
    position: relative;
  }
</style>

<div class="container-fluid d-flex justify-content-center align-items-center min-vh-100">
  <div class="col-xl-10 col-lg-12 col-md-12">
    <div class="card card-register">
      
      <!-- Left Form Side -->
      <div class="col-lg-6 p-5 bg-white d-flex flex-column">
        <h2 class="h4 text-center text-gray-900 mb-4 fw-bold">Daftar Akun Baru</h2>
        <p class="text-muted text-center mb-4">Isi data di bawah untuk mendaftar</p>

        <form method="POST" action="{{ route('register') }}" id="registerForm">
          @csrf

          <!-- Name -->
          <div class="form-group mb-3 position-relative">
            <i class="fas fa-user input-icon"></i>
            <input type="text" name="name" class="form-control form-control-user @error('name') is-invalid @enderror"
                   placeholder="Nama Lengkap" value="{{ old('name') }}" required autofocus>
            @error('name')
              <div class="invalid-feedback d-block"><small>{{ $message }}</small></div>
            @enderror
          </div>

          <!-- Phone -->
          <div class="form-group mb-3 position-relative">
            <i class="fas fa-phone input-icon"></i>
            <input type="text" name="username" id="username"
                   class="form-control form-control-user @error('username') is-invalid @enderror"
                   placeholder="Nomor Telepon (0812...)" value="{{ old('username') }}" required>
            @error('username')
              <div class="invalid-feedback d-block"><small>{{ $message }}</small></div>
            @enderror
          </div>

          <!-- Email -->
          <div class="form-group mb-3 position-relative">
            <i class="fas fa-envelope input-icon"></i>
            <input type="email" name="email"
                   class="form-control form-control-user @error('email') is-invalid @enderror"
                   placeholder="Alamat Email" value="{{ old('email') }}" required>
            @error('email')
              <div class="invalid-feedback d-block"><small>{{ $message }}</small></div>
            @enderror
          </div>

          <!-- Password -->
          <div class="form-group mb-3 position-relative">
            <i class="fas fa-lock input-icon"></i>
            <input type="password" name="password"
                   class="form-control form-control-user @error('password') is-invalid @enderror"
                   placeholder="Password" autocomplete="new-password" required>
            @error('password')
              <div class="invalid-feedback d-block"><small>{{ $message }}</small></div>
            @enderror
          </div>

          <!-- Confirm Password -->
          <div class="form-group mb-3 position-relative">
            <i class="fas fa-lock input-icon"></i>
            <input type="password" name="password_confirmation"
                   class="form-control form-control-user @error('password_confirmation') is-invalid @enderror"
                   placeholder="Konfirmasi Password" autocomplete="new-password" required>
            @error('password_confirmation')
              <div class="invalid-feedback d-block"><small>{{ $message }}</small></div>
            @enderror
          </div>

          <!-- reCAPTCHA -->
          <div class="form-group mb-3">
            <div class="g-recaptcha" data-sitekey="{{ config('services.recaptcha.site_key') }}"></div>
            @error('g-recaptcha-response')
              <div class="text-danger text-center mt-2"><small>{{ $message }}</small></div>
            @enderror
          </div>

          <!-- Submit Button -->
          <div class="d-grid mb-3">
            <button type="button" class="btn btn-primary btn-user btn-block" id="submitButton">
              Lanjutkan Pendaftaran
            </button>
          </div>

          <!-- Login & WhatsApp -->
          <div class="text-center">
            <p class="mb-1">
              Sudah punya akun? <a href="{{ route('login') }}" class="text-primary fw-bold">Masuk di sini</a>
            </p>
            <p class="mb-0">
              <a href="https://wa.me/{{ $customerService->Value }}" target="_blank" class="text-decoration-none">
                <i class="fab fa-whatsapp text-success"></i>
                <small class="text-success fw-bold">Butuh bantuan? Chat WhatsApp</small>
              </a>
            </p>
          </div>
        </form>
      </div>

      <!-- Right Image Side -->
      <div class="col-lg-6 d-none d-lg-block p-0">
        <img src="{{ asset('img/img-signup.jpg') }}" alt="VOS" 
             class="img-fluid w-100 h-100" 
             style="object-fit: cover; height: 600px;">
      </div>
    </div>
  </div>
</div>

<!-- Error Modal -->
<div class="modal fade" id="errorModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title text-danger">Kesalahan</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body" id="errorModalBody"></div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
      </div>
    </div>
  </div>
</div>

<!-- Confirmation Modal -->
<div class="modal fade" id="confirmationModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Konfirmasi Pendaftaran</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <p>Apakah data berikut sudah benar?</p>
        <ul id="confirmationDetails" class="ps-3"></ul>
        <p class="mt-2"><strong>Password akan dikirim via email.</strong></p>
      </div>
      <div class="modal-footer position-relative">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Kembali</button>
        <button type="button" class="btn btn-primary" id="confirmButton">Daftar Sekarang</button>
        <div class="loading-overlay">
          <div class="spinner-border text-light" role="status"></div>
          <span class="ms-2">Mendaftar...</span>
        </div>
      </div>
    </div>
  </div>
</div>

@endsection

@section('script')
<script src="https://www.google.com/recaptcha/api.js" async defer></script>
<script>
  // Apply gradient background
  document.body.classList.add('bg-gradient-primary');

  // Show confirmation modal
  document.getElementById('submitButton').addEventListener('click', function () {
    const name = document.querySelector('input[name="name"]').value.trim();
    const username = document.querySelector('input[name="username"]').value.trim();
    const email = document.querySelector('input[name="email"]').value.trim();
    const recaptcha = grecaptcha.getResponse();

    // Validation
    const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
    const phoneRegex = /^0\d{9,}$/;

    if (!name) return showError('Nama wajib diisi.');
    if (!email || !emailRegex.test(email)) return showError('Email tidak valid.');
    if (!username || !phoneRegex.test(username)) return showError('Nomor telepon harus dimulai dengan 0.');
    if (!recaptcha) return showError('Silakan selesaikan reCAPTCHA.');

    // Format phone for display
    const formattedPhone = username.startsWith('0') ? '62' + username.slice(1) : username;

    // Populate confirmation
    document.getElementById('confirmationDetails').innerHTML = `
      <li><strong>Nama:</strong> ${name}</li>
      <li><strong>Telepon:</strong> ${formattedPhone}</li>
      <li><strong>Email:</strong> ${email}</li>
    `;

    // Show modal
    const modal = new bootstrap.Modal(document.getElementById('confirmationModal'));
    modal.show();
  });

  // Confirm and submit
  document.getElementById('confirmButton').addEventListener('click', function () {
    const usernameInput = document.querySelector('input[name="username"]');
    if (usernameInput.value.startsWith('0')) {
      usernameInput.value = '62' + usernameInput.value.slice(1);
    }

    const loading = document.querySelector('.loading-overlay');
    loading.style.display = 'flex';
    document.getElementById('confirmButton').disabled = true;

    document.getElementById('registerForm').submit();
  });

  function showError(message) {
    document.getElementById('errorModalBody').textContent = message;
    const modal = new bootstrap.Modal(document.getElementById('errorModal'));
    modal.show();
  }
</script>
@endsection