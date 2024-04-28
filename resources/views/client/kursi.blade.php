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
    
    #submitBtn {
      margin-top: 20px;
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
              <div class="kursi" style="background: #858796" onclick="toggleSeat(this)">
                <div class="font-weight-bold text-white m-auto" style="font-size: 26px;">{{$transportasi->kode}}{{ $i }}</div>
              </div>
            </div>
          @endif
        @endfor
      </div>
      <button id="submitBtn" class="btn btn-primary">Submit</button>
    </div>
  </div>
@endsection
@section('script')
  <script>
    var selectedSeats = [];

    function toggleSeat(seat) {
      seat.classList.toggle('selected');
      var seatNumber = seat.querySelector('.font-weight-bold').textContent.trim();
      var index = selectedSeats.indexOf(seatNumber);
      if (index === -1) {
        selectedSeats.push(seatNumber);
      } else {
        selectedSeats.splice(index, 1);
      }
    }

    document.getElementById('submitBtn').addEventListener('click', function() {
      if (selectedSeats.length > 0) {
        var selectedSeatsJSON = JSON.stringify(selectedSeats);
        var data = @json($dataString);
        window.location.href = "{{ route('pesan', ['kursi' => 'placeholder', 'data' => 'placeholder']) }}"
          .replace('placeholder', selectedSeatsJSON)
          .replace('placeholder', data);
      } else {
        alert('Please select at least one seat.');
      }
    });
  </script>
@endsection
