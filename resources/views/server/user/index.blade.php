@extends('layouts.app')
@section('title', 'User')
@section('heading', 'User')
@section('styles')
  <link href="{{ asset('vendor/datatables/dataTables.bootstrap4.min.css') }}" rel="stylesheet"/>
  <link href="{{ asset('vendor/select2/dist/css/select2.min.css') }}" rel="stylesheet"/>
  <style>
    thead > tr > th, tbody > tr > td{
      vertical-align: middle !important;
    }

    .card-title {
      float: left;
      font-size: 1.1rem;
      font-weight: 400;
      margin: 0;
    }

    .card-text {
      clear: both;
    }

    small {
      font-size: 80%;
      font-weight: 400;
    }

    .text-muted {
      color: #6c757d !important;
    }

    .select2-container .select2-selection--single {
      display: block;
      width: 100%;
      height: calc(1.5em + .75rem + 2px);
      padding: .375rem .75rem;
      font-size: 1rem;
      font-weight: 400;
      line-height: 2;
      color: #6e707e;
      background-color: #fff;
      background-clip: padding-box;
      border: 1px solid #d1d3e2;
      border-radius: .35rem;
      transition: border-color .15s ease-in-out,box-shadow .15s ease-in-out;
    }

    .select2-container--default .select2-selection--single .select2-selection__rendered {
      color: #6e707e;
      line-height: 28px;
    }

    .select2-container .select2-selection--single .select2-selection__rendered {
      display: block;
      padding-left: 0;
      padding-right: 0;
      overflow: hidden;
      text-overflow: ellipsis;
      white-space: nowrap;
      margin-top: -2px;
    }

    .select2-container--default .select2-selection--single .select2-selection__arrow {
      height: calc(1.5em + .75rem + 2px);
      position: absolute;
      top: 1px;
      right: 1px;
      width: 20px;
    }
  </style>
@endsection
@section('content')
  <div class="card shadow mb-4">
    <div class="card-header py-3">
      <!-- Button trigger modal -->
      <button
        type="button"
        class="btn btn-primary btn-sm"
        data-toggle="modal"
        data-target="#add-modal"
      >
        <i class="fas fa-plus"></i>
      </button>
    </div>
    <div class="card-body">
      <div class="table-responsive">
        <table
          class="table table-bordered table-striped table-hover"
          id="dataTable"
          width="100%"
          cellspacing="0"
        >
          <thead>
            <tr>
              <td>No</td>
              <td>Name</td>
              <td>Username/NoHP</td>
              <td>Email</td>
              <td>Level</td>
              <th>Action</th>
            </tr>
          </thead>
          <tbody>
            @foreach ($user as $data)
              <tr>
                <td>{{ $loop->iteration }}</td>
                <td>{{ $data->name }}</td>
                <td>{{ $data->username }}</td>
                <td>{{ $data->email }}</td>
                <td>{{ $data->level == 'Penumpang' ? 'Customer' : $data->level }}</td>
                <td>
                    <form action="{{ route('user.destroy', $data->id) }}" method="POST" style="display:inline-block;">
                        @csrf
                        @method('delete')
                        <button type="submit" class="btn btn-danger btn-sm btn-circle" onclick="return confirm('Yakin');">
                            <i class="fas fa-trash"></i>
                        </button>
                    </form>
                    <button type="button" class="btn btn-warning btn-sm btn-circle" data-toggle="modal" data-target="#change-password-modal-{{ $data->id }}">
                        <i class="fas fa-key"></i>
                    </button>
                </td>
              </tr>
              <!-- Change Password Modal -->
              <div class="modal fade" id="change-password-modal-{{ $data->id }}" tabindex="-1" role="dialog" aria-labelledby="changePasswordModalLabel" aria-hidden="true">
                <div class="modal-dialog" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="changePasswordModalLabel">Change Password for {{ $data->name }}</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <form action="{{ route('user.changePassword', $data->id) }}" method="POST">
                            @csrf
                            @method('patch')
                            <div class="modal-body">
                                <div class="form-group form-check">
                                    <input type="checkbox" class="form-check-input" id="defaultPasswordCheck-{{ $data->id }}" onchange="togglePasswordFields({{ $data->id }})">
                                    <label class="form-check-label" for="defaultPasswordCheck-{{ $data->id }}">Set Default Password</label>
                                </div>
                                <div class="form-group">
                                    <label for="new_password-{{ $data->id }}">New Password</label>
                                    <input type="password" class="form-control" id="new_password-{{ $data->id }}" name="new_password" placeholder="New Password" required>
                                </div>
                                <div class="form-group">
                                    <label for="new_password_confirmation-{{ $data->id }}">Confirm New Password</label>
                                    <input type="password" class="form-control" id="new_password_confirmation-{{ $data->id }}" name="new_password_confirmation" placeholder="Confirm New Password" required>
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                                <button type="submit" class="btn btn-primary">Change Password</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
            @endforeach
          </tbody>
        </table>
      </div>
    </div>
  </div>
  <!-- Add Modal -->
<div class="modal fade" id="add-modal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Tambah User</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form action="{{ route('user.store') }}" method="POST">
                @csrf
                <div class="modal-body">
                    <div class="form-group form-check">
                        <input type="checkbox" class="form-check-input" id="defaultPasswordCheck" onchange="toggleAddPasswordFields()">
                        <label class="form-check-label" for="defaultPasswordCheck">Set Default Password</label>
                    </div>
                    <div class="form-group">
                        <label for="name">Nama User</label>
                        <input type="text" class="form-control" id="name" name="name" placeholder="Nama User" required />
                    </div>
                    <div class="form-group">
                        <label for="username">Username</label>
                        <input type="text" class="form-control" id="username" name="username" placeholder="Username" required />
                    </div>
                    <div class="form-group">
                        <label for="email">Email</label>
                        <input type="email" class="form-control" id="email" name="email" placeholder="Email" required />
                    </div>
                    <div class="form-group">
                        <label for="level">Level User</label>
                        <select class="select2 form-control" id="level" name="level" required style="width: 100%; color: #6e707e;">
                            <option value="" disabled selected>-- Pilih Level User --</option>
                            <option value="SuperAdmin">System Admin (ADM1)</option>
                            <option value="Admin">Admin VOS (ADM2)</option>
                            <option value="Petugas">Petugas</option>
                            <option value="Penumpang">Customer</option>
                            <option value="AdminChurch">Admin Gereja (ADM3)</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="password">Password</label>
                        <input type="password" class="form-control" id="password" name="password" placeholder="Password" required />
                    </div>
                    <div class="form-group">
                        <label for="password_confirmation">Confirm Password</label>
                        <input type="password" class="form-control" id="password_confirmation" name="password_confirmation" placeholder="Confirm Password" required />
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Kembali</button>
                    <button type="submit" class="btn btn-primary">Tambah</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
@section('script')
<script src="{{ asset('vendor/datatables/jquery.dataTables.min.js') }}"></script>
<script src="{{ asset('vendor/datatables/dataTables.bootstrap4.min.js') }}"></script>
<script src="{{ asset('vendor/select2/dist/js/select2.full.min.js') }}"></script>
<script>
    $(document).ready(function() {
        $('#dataTable').DataTable();
    });

    if (jQuery().select2) {
        $(".select2").select2();
    }

    function togglePasswordFields(userId) {
        var checkBox = document.getElementById('defaultPasswordCheck-' + userId);
        var newPasswordField = document.getElementById('new_password-' + userId);
        var confirmPasswordField = document.getElementById('new_password_confirmation-' + userId);

        if (checkBox.checked) {
            newPasswordField.value = 'password12345678';
            confirmPasswordField.value = 'password12345678';
            newPasswordField.disabled = true;
            confirmPasswordField.disabled = true;
        } else {
            newPasswordField.value = '';
            confirmPasswordField.value = '';
            newPasswordField.disabled = false;
            confirmPasswordField.disabled = false;
        }
    }
    
    function toggleAddPasswordFields() {
        var checkBox = document.getElementById('defaultPasswordCheck');
        var passwordField = document.getElementById('password');
        var confirmPasswordField = document.getElementById('password_confirmation');

        if (checkBox.checked) {
            passwordField.value = 'password12345678';
            confirmPasswordField.value = 'password12345678';
            passwordField.disabled = true;
            confirmPasswordField.disabled = true;
        } else {
            passwordField.value = '';
            confirmPasswordField.value = '';
            passwordField.disabled = false;
            confirmPasswordField.disabled = false;
        }
    }
</script>
@endsection

