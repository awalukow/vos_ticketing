@extends('layouts.app')
@section('title', 'Login')
@section('content')
  <div class="col-xl-5 col-lg-6 col-md-9">
    <div class="card o-hidden border-0 shadow-lg my-5">
      <div class="card-body p-0">
        <!-- Nested Row within Card Body -->
        <div class="row">
          <div class="col-12">
            <div class="p-5">
              <div class="text-center">
                <h1 class="h4 text-gray-900 mb-4">VOS Online Ticketing V.0.10</h1>
              </div>
              <form method="POST" action="{{ route('register') }}" class="user" id="registerForm">
              @csrf
                <div class="form-group">
                  <input type="text" class="form-control form-control-user @error('name') is-invalid @enderror" name="name" required autocomplete="off" autofocus placeholder="Nama User">
                  @error('name')
                    <span class="invalid-feedback" role="alert">
                      <strong>{{ $message }}</strong>
                    </span>
                  @enderror
                </div>
                <div class="form-group">
                  <input type="text" class="form-control form-control-user @error('username') is-invalid @enderror" name="username" required autocomplete="off" placeholder="Nomor Telepon" id="username">
                  @error('username')
                    <span class="invalid-feedback" role="alert">
                      <strong>{{ $message }}</strong>
                    </span>
                  @enderror
                </div>
                <div class="form-group">
                  <input type="email" class="form-control form-control-user @error('email') is-invalid @enderror" name="email" required autocomplete="off" placeholder="Email">
                  @error('email')
                    <span class="invalid-feedback" role="alert">
                      <strong>{{ $message }}</strong>
                    </span>
                  @enderror
                </div>
                <div class="form-group" hidden>
                    <input type="password" class="form-control form-control-user" name="password" value="" placeholder="Password">
                </div>
                <div class="form-group" hidden>
                    <input type="password" class="form-control form-control-user" name="password_confirmation" value="" placeholder="Confirm Password">
                </div>

                <button type="button" class="btn btn-primary btn-user btn-block mt-4" id="submitButton">
                  {{ __('Continue') }}
                </button>
              </form>
              <hr>
              <div class="text-center">
                <a class="small" href="{{ route('login') }}">Login!</a>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>

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
          </ul>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-dismiss="modal">Kembali</button>
          <button type="button" class="btn btn-primary" id="confirmButton">Lanjutkan</button>
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
        var confirmationMessage = "<li><strong>Nama User:</strong> " + name + "</li>";
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

        // Submit the form
        document.getElementById('registerForm').submit();
    });
  </script>
@endsection
