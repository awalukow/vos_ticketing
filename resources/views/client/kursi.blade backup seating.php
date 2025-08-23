@extends('layouts.app')
@section('title', 'Cari Kursi')
@section('styles')
  <style>
    a:hover {
      text-decoration: none;
    }
    
    .kursi {
      box-sizing: border-box;
      border: 2px solid #858796;
      width: 100%;
      height: 120px;
      display: flex;
      position: relative;
      cursor: pointer;
    }
    
    .kursi.selected {
      background-color: #007bff;
      border-color: #007bff;
      color: #fff;
    }
    
    .kursi.reserved {
      background-color: #ccc;
      border-color: #aaa;
      cursor: not-allowed;
    }
    
    #submitBtn {
      position: fixed;
      bottom: 0 ;
      left: 0;
      width: 100%;
      z-index: 999; /* Ensure it's on top of other content */
    }

    /* Loading animation */
    .loading-overlay {
      position: fixed;
      top: 0;
      left: 0;
      width: 100%;
      height: 100%;
      background-color: rgba(255, 255, 255, 0.7);
      z-index: 9999;
      display: none;
    }

    .loading-spinner {
      position: absolute;
      top: 50%;
      left: 50%;
      transform: translate(-50%, -50%);
      border: 4px solid #f3f3f3;
      border-top: 4px solid #3498db;
      border-radius: 50%;
      width: 50px;
      height: 50px;
      animation: spin 1s linear infinite;
    }

    @keyframes spin {
      0% { transform: rotate(0deg); }
      100% { transform: rotate(360deg); }
    }
  </style>
@endsection
@section('content')
  <div class="row justify-content-center">
    <div class="col-12" style="margin-top: -15px">
      <a href="javascript:window.history.back();" class="text-white btn"><i class="fas fa-arrow-left mr-2"></i> Kembali</a>
      <div class="row mt-2">
        @for ($i = 1; $i <= $transportasi->jumlah; $i++)
          @php
            $array = array('kursi' => $transportasi->kode . $i, 'rute' => $data['id'], 'waktu' => $data['waktu']);
            $cekData = json_encode($array);
          @endphp
          @if ($transportasi->kursi($cekData) != null)
            <div class="col-lg-2 col-md-3 col-sm-4 col-6 mb-4">
              <div class="kursi bg-white" onclick="toggleSeat(this)">
                <div class="font-weight-bold text-primary m-auto" style="font-size: 26px;">{{$transportasi->kode}}{{ $i }}</div>
              </div>
            </div>
          @else
            <div class="col-lg-2 col-md-3 col-sm-4 col-6 mb-4">
              <div class="kursi reserved" style="background: #858796">
                <div class="font-weight-bold text-white m-auto" style="font-size: 26px;">{{$transportasi->kode}}{{ $i }}</div>
              </div>
            </div>
          @endif
        @endfor
      </div>
    </div>
  </div>

  <!-- Submit button -->
  <button id="submitBtn" class="btn btn-primary">Submit</button>

  <!-- Modal -->
  <div class="modal fade" id="confirmationModal" tabindex="-1" aria-labelledby="confirmationModalLabel" aria-hidden="true">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="confirmationModalLabel">Konfirmasi Pembelian</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body" id="modalBodyContent">
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
          <button type="button" class="btn btn-primary" id="confirmPurchaseBtn">Confirm</button>
        </div>
      </div>
    </div>
  </div>

  <div class="loading-overlay">
    <div class="loading-spinner"></div>
  </div>
@endsection
@section('script')
  <script>
    var selectedSeats = [];

    function toggleSeat(seat) {
      if (!seat.classList.contains('reserved')) {
        seat.classList.toggle('selected');
        var seatNumber = seat.querySelector('.font-weight-bold').textContent.trim();
        var index = selectedSeats.indexOf(seatNumber);
        if (index === -1) {
          selectedSeats.push(seatNumber);
        } else {
          selectedSeats.splice(index, 1);
        }
      }
    }

    document.getElementById('submitBtn').addEventListener('click', function() {
      if (selectedSeats.length > 0) {
        var modalBody = document.getElementById('modalBodyContent');
        var seatList = selectedSeats.join(', '); // Join selected seats into a string separated by comma
        var content = "<p>Apakah anda yakin akan melanjutkan pembelian tiket dengan kursi: " + seatList + "?</p>";
        modalBody.innerHTML = content;
        $('#confirmationModal').modal('show');
      } else {
        alert('Silakan pilih minimal satu kursi.');
      }
    });

    document.getElementById('confirmPurchaseBtn').addEventListener('click', function() {
      // Show loading animation
      var loadingOverlay = document.querySelector('.loading-overlay');
      loadingOverlay.style.display = 'block';

      var selectedSeatsJSON = JSON.stringify(selectedSeats);
      var data = @json($dataString);
      window.location.href = "{{ route('pesan', ['kursi' => 'placeholder', 'data' => 'placeholder']) }}"
        .replace('placeholder', selectedSeatsJSON)
        .replace('placeholder', data);
    });

    // Close modal on Cancel button click
    document.querySelectorAll('[data-bs-dismiss="modal"]').forEach(function(button) {
      button.addEventListener('click', function() {
        $('#confirmationModal').modal('hide');
      });
    });

    // Function to make submit button follow the scroll
    function followScroll() {
      var submitBtn = document.getElementById('submitBtn');
      var rect = submitBtn.getBoundingClientRect();
      var offset = 20; // Adjust this value as needed

      if (rect.bottom + offset >= window. innerHeight) {
        submitBtn.style.bottom = (window.innerHeight - rect.bottom + offset) + 'px';
      } else {
        submitBtn.style.bottom = '0';
      }
    }

    // Call followScroll function on scroll event
    window.addEventListener('scroll', followScroll);
  </script>
@endsection
