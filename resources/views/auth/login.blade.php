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
                <h1 class="h4 text-gray-900 mb-4">Selamat Datang!</h1>
              </div>
              <form id="loginForm" method="POST" action="{{ route('login') }}" class="user">
              @csrf
                <div class="form-group">
                  <input type="text" class="form-control form-control-user @error('username') is-invalid @enderror" name="username" required autocomplete="off" placeholder="Username" id="username">
                  @error('username')
                    <span class="invalid-feedback" role="alert">
                      <strong>{{ $message }}</strong>
                    </span>
                  @enderror
                </div>
                <div class="form-group">
                  <input type="password" class="form-control form-control-user @error('password') is-invalid @enderror" name="password" required placeholder="Password">
                  @error('password')
                    <span class="invalid-feedback" role="alert">
                      <strong>{{ $message }}</strong>
                    </span>
                  @enderror
                </div>
                <div class="form-group">
                  <div class="custom-control custom-checkbox small">
                    <input type="checkbox" class="custom-control-input" name="remember" id="remember" {{ old('remember') ? 'checked' : '' }}>
                    <label class="custom-control-label" for="remember">{{ __('Remember Me') }}</label>
                  </div>
                </div>
                <button type="button" id="loginButton" class="btn btn-primary btn-user btn-block">
                  {{ __('Login') }}
                </button>
              </form>
              <hr>
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

    // JavaScript function to adjust input value
    function adjustUsername() {
      var usernameInput = document.getElementById("username");
      var inputValue = usernameInput.value;
      if (inputValue.startsWith("0")) {
        usernameInput.value = "62" + inputValue.slice(1);
      }
    }

    // Add event listener for login button click
    document.getElementById("loginButton").addEventListener("click", function() {
      adjustUsername();
      // Submit the form after modification
      document.getElementById("loginForm").submit();
    });

    // Add event listener for Enter key press on username input field
    document.getElementById("username").addEventListener("keypress", function(event) {
      if (event.key === "Enter") {
        adjustUsername();
        // Submit the form after modification
        document.getElementById("loginForm").submit();
      }
    });
  </script>
@endsection
