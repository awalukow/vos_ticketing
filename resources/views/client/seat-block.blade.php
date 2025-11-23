@php
  $seatCode = $row . $num;
  $array = ['kursi' => $seatCode, 'rute' => $data['id'], 'waktu' => $data['waktu']];
  $cekData = json_encode($array);
@endphp

@if ($transportasi->kursi($cekData) != null)
  <div class="kursi bg-white mx-1" onclick="toggleSeat(this)">
    <div class="font-weight-bold text-primary m-auto">{{ $seatCode }}</div>
  </div>
@else
  <div class="kursi reserved mx-1">
    <div class="font-weight-bold text-white m-auto">{{ $seatCode }}</div>
  </div>
@endif
