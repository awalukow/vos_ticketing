@extends('layouts.app')
@section('title', 'Sign up')

@section('content')
<style>
  .bg-gradient-primary {
            background-color: #970b0b;
            background-image: linear-gradient(180deg, #b71e1e 10%, #601d2f 100%);
            background-size: cover;
        }
</style>
<section class="vh-100" >
  <div class="container h-100">
    <div class="row d-flex justify-content-center align-items-center h-100">
      <div class="col-lg-12 col-xl-11">
        <div class="card text-black" style="border-radius: 25px;">
          <div class="card-body p-md-5">
            <div class="row justify-content-center">
              <div class="col-md-10 col-lg-6 col-xl-5 order-2 order-lg-1">
                <p class="text-center h1 fw-bold mb-5 mx-1 mx-md-4 mt-4">Signup by Admin</p>
                <form method="POST" action="{{ route('register') }}" class="mx-1 mx-md-4" id="registerForm">
                  @csrf
                  <div class="d-flex flex-row align-items-center mb-4">
                    <i class="fas fa-user fa-lg me-3 fa-fw"></i>
                    <div data-mdb-input-init class="form-outline flex-fill mb-0">
                      <input type="text" id="name" class="form-control form-control-user @error('name') is-invalid @enderror" name="name" required autocomplete="off" autofocus placeholder="Nama User">
                      @error('name')
                        <span class="invalid-feedback" role="alert">
                          <strong>{{ $message }}</strong>
                        </span>
                      @enderror
                    </div>
                  </div>

                  <div class="d-flex flex-row align-items-center mb-4">
                    <i class="fas fa-phone fa-lg me-3 fa-fw"></i>
                    <div data-mdb-input-init class="form-outline flex-fill mb-0">
                      <input type="text" id="username" class="form-control form-control-user @error('username') is-invalid @enderror" name="username" required autocomplete="off" placeholder="Nomor Telepon">
                      @error('username')
                        <span class="invalid-feedback" role="alert">
                          <strong>{{ $message }}</strong>
                        </span>
                      @enderror
                    </div>
                  </div>

                  <div class="d-flex flex-row align-items-center mb-4">
                    <i class="fas fa-envelope fa-lg me-3 fa-fw"></i>
                    <div data-mdb-input-init class="form-outline flex-fill mb-0">
                      <input type="email" id="email" class="form-control form-control-user @error('email') is-invalid @enderror" name="email" required autocomplete="off" placeholder="Email">
                      @error('email')
                        <span class="invalid-feedback" role="alert">
                          <strong>{{ $message }}</strong>
                        </span>
                      @enderror
                    </div>
                  </div>

                    <div data-mdb-input-init class="form-outline flex-fill mb-0" hidden>
                        <input type="password" class="form-control form-control-user " name="password" value="" placeholder="Password">

                    </div>
                <div data-mdb-input-init class="form-outline flex-fill mb-0" hidden>
                    <input type="password" class="form-control form-control-user " name="password_confirmation" value="" placeholder="Confirm Password">
                </div>

                  <div class="d-flex justify-content-center mx-4 mb-3 mb-lg-4">
                    <button type="button" class="btn btn-primary btn-lg" id="submitButton">Continue</button>
                  </div>
                  <div class="text-center">
                    <a class="medium" href="{{ route('login') }}">Login!</a>
                  </div>
                </form>
              </div>
              <div class="col-md-10 col-lg-6 col-xl-7 d-flex align-items-center order-1 order-lg-2">
                <img src="{{ asset('img/interval.png') }}"
                  class="img-fluid" alt="Sample image">
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</section>

<!-- Error Modal -->
<div class="modal fade" id="errorModal" tabindex="-1" role="dialog" aria-labelledby="errorModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="errorModalLabel">Error</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body" id="errorModalBody">
        <!-- Error messages will be inserted here -->
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
      </div>
    </div>
  </div>
</div>

<!-- Confirmation Modal -->
<div class="modal fade" id="confirmationModal" tabindex="-1" role="dialog" aria-labelledby="confirmationModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="confirmationModalLabel">Konfirmasi</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <p>Apakah data yang anda masukkan sudah benar?</p>
        <ul id="confirmationDetails">
          <!-- Confirmation details will be inserted here -->
          <li><strong>Password Default: password12345678</strong></li>
        </ul>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Kembali</button>
        <button type="button" class="btn btn-primary" id="confirmButton">Lanjutkan</button>

        <div class="loading-overlay" style="display: none;">
            <div class="spinner-border text-primary" role="status">
                <span class="sr-only">Loading...</span>
            </div>
            <span class="ml-2">Loading...</span>
        </div>
      </div>
    </div>
  </div>
</div>



@endsection

@section('script')
<script>
  $("body").addClass("bg-gradient-primary");

  // JavaScript to gather input values and display confirmation modal
  document.getElementById('submitButton').addEventListener('click', function(event) {
      event.preventDefault(); // Prevent default form submission

      // Gather input values
      var name = document.getElementsByName('name')[0].value;
      var username = document.getElementsByName('username')[0].value;
      var email = document.getElementsByName('email')[0].value;

      // Email validation regex
      var emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;

      // Phone number validation regex (starts with '0' and only contains numbers)
      var phoneRegex = /^0\d{9,}$/;

      // Validation flags
      var isValidEmail = emailRegex.test(email);
      var isValidPhone = phoneRegex.test(username);

      // If email or phone number is invalid, show error and return
      if (!isValidEmail) {
          $('#errorModalBody').html('<p>Email tidak valid!</p>');
          $('#errorModal').modal('show');
          return;
      }
      if (!isValidPhone) {
          $('#errorModalBody').html('<p>Nomor Telepon tidak valid!</p>');
          $('#errorModal').modal('show');
          return;
      }

      // If phone number starts with '0', replace '0' with '62'
      if (username.startsWith('0')) {
          username = '62' + username.substring(1);
      }

      // Build confirmation message
      var confirmationMessage = "<li><strong>Nama Lengkap:</strong> " + name + "</li>";
      confirmationMessage += "<li><strong>Nomor Telepon:</strong> " + username + "</li>";
      confirmationMessage += "<li><strong>Email:</strong> " + email + "</li>";

      // Set confirmation details in modal
      document.getElementById('confirmationDetails').innerHTML = confirmationMessage;

      // Show confirmation modal
      $('#confirmationModal').modal('show');
  });

  // Handle confirm button click event
  document.getElementById('confirmButton').addEventListener('click', function(event) {
      // Replace '0' with '62' in the phone number if it starts with '0'
      var username = document.getElementsByName('username')[0].value;
      if (username.startsWith('0')) {
          username = '62' + username.substring(1);
          document.getElementsByName('username')[0].value = username; // Update the input field value
      }

      // Disable the Confirm button
      $('#confirmButton, .btn-secondary').prop('disabled', true);
      // Show loading overlay
      document.querySelector('.loading-overlay').style.display = 'flex';

      // Attach an event listener to keep the loading animation displayed until page navigation begins
      window.addEventListener('beforeunload', function() {
          document.querySelector('.loading-overlay').style.display = 'block';
      });

      // Submit the form
      document.getElementById('registerForm').submit();
  });
</script>
@endsection
