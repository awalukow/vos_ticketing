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
                <h1 class="h4 text-gray-900 mb-4">VOS Online Ticketing V.0.1</h1>
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

                <button type="submit" class="btn btn-primary btn-user btn-block mt-4">
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
@endsection
@section('script')
  <script>
    $("body").addClass("bg-gradient-primary");

    // JavaScript to modify input value
    document.getElementById('registerForm').addEventListener('submit', function(event) {
        var usernameInput = document.getElementById('username');
        var usernameValue = usernameInput.value;

        // Check if the first digit is not 62 and starts with 0
        if (usernameValue.startsWith('0') && !usernameValue.startsWith('62')) {
            // Replace 0 with 62
            usernameInput.value = '62' + usernameValue.substring(1);
        }
    });
  </script>
@endsection
