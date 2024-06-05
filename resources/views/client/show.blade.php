@extends('layouts.app')

@section('title', $id ?? '')

@section('styles')
  <style>
    a:hover {
      text-decoration: none;
    }
  </style>
@endsection

@section('content')
  <div class="row justify-content-center">
    <div class="col-12" style="margin-top: -15px">
      <a href="{{ url('/') }}" class="text-white btn"><i class="fas fa-arrow-left mr-2"></i> Kembali</a>
      <div class="row mt-2">
        @if (count($dataRute) > 0)
          @foreach ($dataRute as $data)
            <div class="col-lg-6 mb-4">
              @if ($data['kursi'] == 0)
                <div class="card o-hidden border-0 shadow h-100 py-2">
                  <div class="card-body">
                    <div class="row no-gutters align-items-center">
                      <div class="col mr-2">
                        <div class="font-weight-bold text-muted text-uppercase mb-1">{{ $data['tujuan'] }}</div>
                        <div class="h5 mb-0 font-weight-bold text-muted mb-1">{{ $data['start'] }} - {{ $data['end'] }}</div>
                        <small class="text-muted">{{ $data['transportasi'] }} ({{ $data['kode'] }})</small>
                      </div>
                      <div class="col-auto text-right">
                        <div class="h5 mb-0 font-weight-bold text-muted">Rp. {{ number_format($data['harga'], 0, ',', '.') }}</div>
                        <small class="text-muted">/Orang</small>
                        <p class="text-muted font-weight-bold">Habis</p>
                      </div>
                    </div>
                  </div>
                </div>
              @else
              <a href="#" class="card-link ticketSelection" data-kursi="{{ $data['kursi'] }}" data-rute-id="{{ $data['id'] }}" data-waktu="{{ $data['waktu'] }}" data-harga="{{ $data['harga'] }}" data-rute="{{ json_encode($data) }}">
                <div class="card o-hidden border-0 shadow h-100 py-2">
                  <div class="card-body">
                    <div class="row no-gutters align-items-center">
                      <div class="col mr-2">
                        <div class="font-weight-bold text-gray-800 text-uppercase mb-1">{{ $data['tujuan'] }}</div>
                        <div class="h5 mb-0 font-weight-bold text-primary mb-1">{{ $data['kategori'] }} - {{ $data['waktu'] }}</div>
                        <small class="text-muted">{{ $data['transportasi'] }} ({{ $data['kode'] }})</small>
                      </div>
                      <div class="col-auto text-right">
                        <div class="h5 mb-0 font-weight-bold text-primary">Rp. {{ number_format($data['harga'], 0, ',', '.') }}</div>
                        <small class="text-muted">/Orang</small>
                        @if ($data['kursi'] < 50)
                          <p class="text-primary" style="margin: 0;"><small>{{ $data['kursi'] }} Kursi Tersedia</small></p>
                        @else
                          <p class="text-primary" style="margin: 0;"><small>> 50 Kursi Tersedia</small></p>
                        @endif
                      </div>
                    </div>
                  </div>
                </div>
              </a>
              @endif
            </div>
          @endforeach
        @else
          <div class="col-12 mb-4">
              <div class="card o-hidden border-0 shadow h-100 py-2">
                <div class="card-body text-center">
                  <h3 class="text-gray-900 font-weight-bold">Ticket tidak tersedia</h3>
                  <p class="text-muted">Ubah pencarian dengan data yang berbeda.</p>
                  <a href="{{ url('/') }}" class="btn btn-primary" style="font-size: 16px; border-radius: 10rem;">
                    Ubah Pencarian
                  </a>
                </div>
              </div>
            </a>
          </div>
        @endif
      </div>
    </div>
  </div>

  <!-- Ticket Selection Modal -->
  <div class="modal fade" id="ticketModal" tabindex="-1" role="dialog" aria-labelledby="ticketModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="ticketModalLabel">Masukkan jumlah tiket</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
          <div class="form-group">
            <label for="ticketCount">Tempat Duduk Tersedia:</label>
            <span id="maxSeats"></span>
            <div><label style="color: red;">Maksimal 5 per User</label></div>
          </div>
          <div class="form-group-ticket">
            <label for="ticketCount">Jumlah Tiket:</label>
            <div class="input-group">
              <div class="input-group-prepend">
                <button id="decrementBtn" class="btn btn-secondary" type="button">-</button>
              </div>
              <input type="text" id="ticketCount" class="form-control" value="1" readonly>
              <div class="input-group-append">
                <button id="incrementBtn" class="btn btn-secondary" type="button">+</button>
              </div>
            </div>
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
          <button id="confirmBtn" type="button" class="btn btn-primary">Confirm</button>
          <!-- Loading animation -->
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
    var selectedCount = 1; // Store selected count globally
    var selectedRuteId; // To store the selected route ID
    var selectedWaktu; // To store the selected time
    var selectedData; // To store the selected data

    // Show modal when ticket selection is clicked
    $(document).on('click', '.ticketSelection', function() {
        var maxSeats = $(this).data('kursi');
        selectedRuteId = $(this).data('rute-id'); // Set the selected route ID
        selectedWaktu = $(this).data('waktu'); // Set the selected time
        selectedData = $(this).data('rute'); // Set the selected data
        var harga = $(this).data('harga'); // Get the ticket price

        $('#maxSeats').text(maxSeats); // Display the actual available seats
        $('#ticketCount').val(selectedCount); // Set the current count

        // Remove existing referral textbox if it exists
        $('#referralGroup').remove();

        // Add the referral textbox if the price is greater than 150,000
        if (harga >= 150000) {
            var referralHtml = `
                <div class="form-group mb-3" id="referralGroup">
                    <label for="referral">Referral:</label>
                    <input type="text" id="referral" name="referral" class="form-control" placeholder="Masukkan Nama Referral">
                </div>
            `;
            $(referralHtml).insertAfter('.form-group-ticket');
        }

        $('#ticketModal').modal('show');
    });

    // Increment ticket count
    $(document).on('click', '#incrementBtn', function() {
        var currentCount = parseInt($('#ticketCount').val());
        var maxSeats = parseInt($('#maxSeats').text());
        if (currentCount < Math.min(maxSeats, 5)) { // Ensure the count does not exceed 5
            $('#ticketCount').val(currentCount + 1);
            selectedCount = currentCount + 1; // Update the selected count
        }
    });

    // Decrement ticket count
    $(document).on('click', '#decrementBtn', function() {
        var currentCount = parseInt($('#ticketCount').val());
        if (currentCount > 1) {
            $('#ticketCount').val(currentCount - 1);
            selectedCount = currentCount - 1; // Update the selected count
        }
    });

    // Confirm ticket selection
    $(document).on('click', '#confirmBtn', function() {
        // Disable the Confirm button
        $('#confirmBtn, .btn-secondary').prop('disabled', true);

        // Show loading animation
        $('.loading-overlay').css('display', 'flex');

        var referral = $('#referral').val(); // Get the referral value
        var url = "{{ route('pesan', ['kursi' => ':kursi', 'data' => ':data', 'referral' => ':referral']) }}";
        url = url.replace(':kursi', selectedCount);

        // Encrypt data using a route
        $.get("{{ route('encryptData') }}", { data: selectedData }, function(encryptedData) {
            url = url.replace(':data', encryptedData);
            url = url.replace(':referral', referral ? referral : ''); // Add referral to the URL
            // Attach an event listener to keep the loading animation displayed until page navigation begins
            $(window).on('beforeunload', function() {
                $('.loading-overlay').css('display', 'block');
            });
            window.location.href = url;
        });
    });
</script>

@endsection
