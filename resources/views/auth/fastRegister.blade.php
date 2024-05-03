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
              <form method="POST" action="{{ route('register') }}" class="user">
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
                  <input type="text" class="form-control form-control-user @error('username') is-invalid @enderror" name="username" required autocomplete="off" placeholder="Nomor Telepon">
                  @error('username')
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

    // JavaScript to transform phone number as per the condition
    $('input[name="username"]').on('input', function() {
      var phoneNumber = $(this).val();
      if (phoneNumber.charAt(0) === '0' && phoneNumber.charAt(1) !== '0') {
        phoneNumber = '62' + phoneNumber.substr(1);
        $(this).val(phoneNumber);
      }
    });
  </script>
@endsection
