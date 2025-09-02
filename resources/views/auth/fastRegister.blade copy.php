@extends('layouts.app')
@section('title', 'Daftar Akun')

@section('content')
<style>
  .bg-gradient-primary {
    background: linear-gradient(135deg, #b71ea8ff 0%, #4d1d60ff 100%);
    min-height: 100vh;
    margin: 0;
    background-attachment: fixed;
  }

  .card-register {
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
  }

  .input-icon {
    position: absolute;
    left: 15px;
    top: 50%;
    transform: translateY(-50%);
    color: #999;
    z-index: 2;
  }

  .position-relative i {
    width: 20px;
    text-align: center;
  }

  .password-strength {
    margin-top: 8px;
    font-size: 13px;
    display: none;
  }

  .password-strength.weak { color: #e74c3c; }
  .password-strength.medium { color: #f39c12; }
  .password-strength.strong { color: #27ae60; }

  .loading-overlay {
    display: none;
    align-items: center;
    justify-content: center;
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background: rgba(255, 255, 255, 0.8);
    z-index: 10;
    border-radius: 0.3rem;
  }
</style>

<div class="container d-flex justify-content-center align-items-center min-vh-100">
  <div class="col-xl-8 col-lg-10 col-md-12">
    <div class="card card-register">
      <div class="row g-0">
        
        <!-- Left Form Side -->
        <div class="col-lg-6">
          <div class="p-5">
            <h2 class="h4 text-center text-gray-900 mb-4 fw-bold">Buat Akun Baru</h2>
            <p class="text-muted text-center mb-4">Isi data di bawah untuk mendaftar</p>

            <form method="POST" action="{{ route('register') }}" id="registerForm">
              @csrf

              <!-- Name -->
              <div class="form-group mb-3 position-relative">
                <i class="fas fa-user input-icon"></i>
                <input
                  type="text"
                  name="name"
                  class="form-control form-control-user @error('name') is-invalid @enderror"
                  placeholder="Nama Lengkap"
                  value="{{ old('name') }}"
                  required
                  autofocus
                >
                @error('name')
                  <div class="invalid-feedback d-block">
                    <small>{{ $message }}</small>
                  </div>
                @enderror
              </div>

              <!-- Phone -->
              <div class="form-group mb-3 position-relative">
                <i class="fas fa-phone input-icon"></i>
                <input
                  type="text"
                  name="username"
                  id="username"
                  class="form-control form-control-user @error('username') is-invalid @enderror"
                  placeholder="Nomor Telepon (0812...)"
                  value="{{ old('username') }}"
                  required
                >
                @error('username')
                  <div class="invalid-feedback d-block">
                    <small>{{ $message }}</small>
                  </div>
                @enderror
              </div>

              <!-- Email -->
              <div class="form-group mb-3 position-relative">
                <i class="fas fa-envelope input-icon"></i>
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

              <!-- Password -->
              <div class="form-group mb-3 position-relative">
                <i class="fas fa-lock input-icon"></i>
                <input
                  type="password"
                  name="password"
                  id="password"
                  class="form-control form-control-user @error('password') is-invalid @enderror"
                  placeholder="Kata Sandi"
                  required
                >
                <div class="password-strength" id="passwordStrength"></div>
                @error('password')
                  <div class="invalid-feedback d-block">
                    <small>{{ $message }}</small>
                  </div>
                @enderror
              </div>

              <!-- Confirm Password -->
              <div class="form-group mb-3 position-relative">
                <i class="fas fa-lock input-icon"></i>
                <input
                  type="password"
                  name="password_confirmation"
                  id="password_confirmation"
                  class="form-control form-control-user"
                  placeholder="Ulangi Kata Sandi"
                  required
                >
              </div>

              <div class="d-grid mb-4">
                <button type="button" class="btn btn-primary btn-user btn-block" id="submitButton">
                  Lanjutkan Pendaftaran
                </button>
              </div>

              <div class="text-center">
                <a class="small link-primary" href="{{ route('login') }}">Sudah punya akun? Masuk di sini</a>
              </div>
            </form>
          </div>
        </div>

        <!-- Right Image Side -->
        <div class="col-lg-6 d-none d-lg-block bg-primary" style="background-color: #b71ea8;">
          <img src="{{ asset('img/interval.png') }}" alt="VOS" class="img-fluid h-100 object-fit-cover" style="object-fit: cover; border-top-right-radius: 1rem; border-bottom-right-radius: 1rem;">
        </div>
      </div>
    </div>
  </div>
</div>

<!-- Confirmation Modal -->
<div class="modal fade" id="confirmationModal" tabindex="-1" aria-labelledby="confirmationModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="confirmationModalLabel">Konfirmasi Pendaftaran</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <p>Apakah data berikut sudah benar?</p>
        <ul id="confirmationDetails">
          <!-- Dynamically inserted -->
        </ul>
        <p><strong>Password default:</strong> <code>password12345678</code></p>
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

<!-- Error Modal -->
<div class="modal fade" id="errorModal" tabindex="-1" aria-labelledby="errorModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title text-danger" id="errorModalLabel">Kesalahan</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body" id="errorModalBody"></div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
      </div>
    </div>
  </div>
</div>

@endsection

@section('script')
<script>
  // Apply gradient background
  document.body.classList.add('bg-gradient-primary');

  // Password strength indicator
  document.getElementById('password').addEventListener('input', function () {
    const password = this.value;
    const strengthBar = document.getElementById('passwordStrength');
    let strength = 0;

    if (password.length >= 8) strength++;
    if (/[A-Z]/.test(password)) strength++;
    if (/[0-9]/.test(password)) strength++;
    if (/[^A-Za-z0-9]/.test(password)) strength++;

    strengthBar.style.display = 'block';
    if (strength < 2) {
      strengthBar.className = 'password-strength weak';
      strengthBar.textContent = 'Kata sandi lemah';
    } else if (strength < 4) {
      strengthBar.className = 'password-strength medium';
      strengthBar.textContent = 'Kata sandi sedang';
    } else {
      strengthBar.className = 'password-strength strong';
      strengthBar.textContent = 'Kata sandi kuat';
    }
  });

  // Show confirmation modal
  document.getElementById('submitButton').addEventListener('click', function () {
    const name = document.querySelector('input[name="name"]').value.trim();
    const username = document.querySelector('input[name="username"]').value.trim();
    const email = document.querySelector('input[name="email"]').value.trim();
    const password = document.querySelector('input[name="password"]').value;

    // Validation
    const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
    const phoneRegex = /^0\d{9,}$/;

    if (!name) {
      showError('Nama wajib diisi.');
      return;
    }
    if (!email || !emailRegex.test(email)) {
      showError('Email tidak valid.');
      return;
    }
    if (!username || !phoneRegex.test(username)) {
      showError('Nomor telepon harus dimulai dengan 0 dan minimal 10 digit.');
      return;
    }
    if (password.length < 8) {
      showError('Kata sandi minimal 8 karakter.');
      return;
    }

    // Format phone number for display
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
    let phone = usernameInput.value;

    if (phone.startsWith('0')) {
      usernameInput.value = '62' + phone.slice(1);
    }

    // Show loading
    document.querySelector('.loading-overlay').style.display = 'flex';
    document.getElementById('confirmButton').disabled = true;

    // Submit form
    document.getElementById('registerForm').submit();
  });

  function showError(message) {
    document.getElementById('errorModalBody').textContent = message;
    const modal = new bootstrap.Modal(document.getElementById('errorModal'));
    modal.show();
  }
</script>
@endsection