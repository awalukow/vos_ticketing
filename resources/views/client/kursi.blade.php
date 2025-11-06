@extends('layouts.app')
@section('title', 'Cari Kursi')

@section('styles')
  <style>
    a:hover { text-decoration: none; }
    .showcase {
      background: rgba(255, 255, 255, 0.1);
      padding: 5px 10px;
      border-radius: 5px;
      color: #777;
      list-style-type: none;
      display: flex;
      justify-content: space-between;
    }

    .showcase li {
      display: flex;
      align-items: center;
      justify-content: center;
      margin: 0 10px;
    }
    .screen {
      background-color: #4b4a4aff;
      height: 30px;
      width: 50%;
      margin: 15px 0;
      box-shadow: 0 3px 10px rgba(255, 255, 255, 0.7);

      /* Center horizontally */
      position: relative;
      left: 50%;
      transform: translateX(-40%) rotateX(-45deg);
    }
    
    .kursi {
      box-sizing: border-box; 
      border: 2px solid #858796;
      width: 100%%;  /* Reduced to 50% */
      height: 40px; /* Reduced to 50% of 80px */
      display: flex; 
      position: relative; 
      cursor: pointer;
      justify-content: center;
      align-items: center;
      font-size: 12px; /* Reduced to 50% of 24px */
      font-weight: bold;
      color: #3498db; /* Default Blue */
      border-top-left-radius: 15px; /* Reduced radius by 50% */
      border-top-right-radius: 15px; /* Reduced radius by 50% */
    }


    /* Gold text */
    .kursi.gold {
      color: #004b9bff; /* Optional: adjust for contrast */
      background-color: #FFD700;
    }

    /* Google's Light Green 2 text */
    .kursi.platinum {
      color: #004b9bff;
      background-color: #A5D6A7; /* Material Design Light Green 200 */
    }

    /* vvip text */
    .kursi.vvip {
      color: #004b9bff;
      background-color: #8e7cc3; /* Material Design Light Green 200 */
    }

    /* Google's BLUE text */
    .kursi.undangan {
      color: #004b9bff;
      background-color: #00ffff; /* Material Design Light Green 200 */
    }

    /* Silver text */
    .kursi.silver {
      color: #004b9bff; /* Optional: adjust for contrast */
      background-color: #C0C0C0; /* Silver background */
    }
    .kursi.selected { 
      background-color: #007bff; 
      border-color: #007bff; 
      color: ; 
    }
    .kursi.reserved { 
      background-color: #ccc; 
      border-color: #aaa; 
      cursor: not-allowed; 
      color: #666; /* Optional: adjust for contrast */
    }

    .seatss.selected {
      background-color: #007bff;
    }

    .seatss.occupied {
      background-color: #858796;
    }
    #submitBtn { 
      position: fixed; 
      bottom: 0; 
      left: 0; 
      width: 100%; 
      z-index: 999; 
    }
    .seating-layout {
      overflow-x: auto;
      overflow-y: hidden;
      padding: 10px 0;
    }
    .seating-container {
      display: flex;
      gap: 10px;
      min-width: max-content;
    }
    .seat-item {
      width: 50px;
      /* REMOVE or reduce min-width */
      min-width: 0;
      flex-shrink: 0;
      margin-right: 0; /* ensure no margin */
      padding: 0;
    }

   
    /* Empty spacer for the gap between seats 9 and 10 */
    .seat-spacer {
      width: 290px; /* Width of 5 seats (R9-R13) */
      min-width: 290px;
      flex-shrink: 0;
    }

    /* Empty spacer for the gap between seats 9 and 10 */
    .middle-seat-spacer {
      width: 170px; /* Width of 5 seats (R9-R13) */
      min-width: 170px;
      flex-shrink: 0;
    }
    /* Empty spacer for the gap between seats 9 and 10 */
    .middle-seat-spacer-after {
      width: 110px; /* Width of 5 seats (R9-R13) */
      min-width: 110px;
      flex-shrink: 0;
    }
    .seatK-spacer {
      width: 50px; /* Width of 5 seats (R9-R13) */
      min-width: 50px;
      flex-shrink: 0;
    }
    .seatK-spacer-after {
      width: 50px; /* Width of 5 seats (R9-R13) */
      min-width: 50px;
      flex-shrink: 0;
    }
    .seatJ-spacer {
      width: 80px; /* Width of 5 seats (R9-R13) */
      min-width: 80px;
      flex-shrink: 0;
    }
    .seatJ-spacer-after {
      width: 80px; /* Width of 5 seats (R9-R13) */
      min-width: 80px;
      flex-shrink: 0;
    }
    /* Hidden seat */
    .hidden-seat {
      visibility: hidden;
      pointer-events: none;
    }
    /* Row spacing */
    .seat-row {
      margin-bottom: 10px;
    }
    .seatss {
      background-color: #444451;
      height: 12px;
      width: 15px;
      margin: 3px;
      border-top-left-radius: 6px;
      border-top-right-radius: 6px;
    }
  </style>
@endsection

@section('content')
  <div class="row justify-content-center">
    <div class="col-12" style="margin-top: -15px">
      <a href="javascript:window.history.back();" class="text-white btn"><i class="fas fa-arrow-left mr-2"></i> Kembali</a>

      <!-- Combined seating layout with multiple rows in the same scrollable area -->
      <br><br><br>

        <ul class="showcase">
          <li>
            <div class="seatss selected"></div>
            <small>Selected</small>
          </li>
          <li>
            <div class="seatss occupied"></div>
            <small>Occupied</small>
          </li>
        </ul>
      <div class="seating-layout">
        <!-- R Row (bottom row) -->
        <div class="seating-container seat-row">
          <!-- Hidden R0 seat for alignment -->
          <div class="seat-item hidden-seat">
            <div class="kursi bg-white">
              <div>R0</div>
            </div>
          </div>
          
          @for ($i = 1; $i <= 20; $i++)
          @php
            $array = array('kursi' => 'R' . $i, 'rute' => $data['id'], 'waktu' => $data['waktu']);
            $cekData = json_encode($array);
          @endphp
          @if ($transportasi->kursi($cekData) != null && $transportasi->name == 'SILVER')
            <div class="seat-item">
              <div class="kursi silver" onclick="toggleSeat(this)">
                <div>R{{ $i }}</div>
              </div>
            </div>
          @else
            <div class="seat-item">
              <div class="kursi reserved" style="background: #858796">
                <div>R{{ $i }}</div>
              </div>
            </div>
          @endif
          @endfor
        </div>

         <!-- Q Row -->
        <div class="seating-container seat-row">
          @for ($i = 1; $i <= 9; $i++)
          @php
            $array = array('kursi' => 'Q' . $i, 'rute' => $data['id'], 'waktu' => $data['waktu']);
            $cekData = json_encode($array);
          @endphp
          @if ($transportasi->kursi($cekData) != null && $transportasi->name == 'SILVER')
            <div class="seat-item">
              <div class="kursi silver" onclick="toggleSeat(this)">
                <div>Q{{ $i }}</div>
              </div>
            </div>
          @else
            <div class="seat-item">
              <div class="kursi reserved" style="background: #858796">
                <div>Q{{ $i }}</div>
              </div>
            </div>
          @endif
          @endfor
          
          <!-- Spacer for the gap between Q9 and Q10 -->
          <div class="seat-spacer"></div>
          
          @for ($i = 10; $i <= 17; $i++)
          @php
            $array = array('kursi' => 'Q' . $i, 'rute' => $data['id'], 'waktu' => $data['waktu']);
            $cekData = json_encode($array);
          @endphp
          @if ($transportasi->kursi($cekData) != null && $transportasi->name == 'SILVER')
            <div class="seat-item">
              <div class="kursi silver" onclick="toggleSeat(this)">
                <div>Q{{ $i }}</div>
              </div>
            </div>
          @else
            <div class="seat-item">
              <div class="kursi reserved" style="background: #858796">
                <div>Q{{ $i }}</div>
              </div>
            </div>
          @endif
          @endfor
        </div>
        
        <!-- P Row -->
        <div class="seating-container seat-row">
          @for ($i = 1; $i <= 9; $i++)
          @php
            $array = array('kursi' => 'P' . $i, 'rute' => $data['id'], 'waktu' => $data['waktu']);
            $cekData = json_encode($array);
          @endphp
          @if ($transportasi->kursi($cekData) != null && $transportasi->name == 'SILVER')
            <div class="seat-item">
              <div class="kursi silver" onclick="toggleSeat(this)">
                <div>P{{ $i }}</div>
              </div>
            </div>
          @else
            <div class="seat-item">
              <div class="kursi reserved" style="background: #858796">
                <div>P{{ $i }}</div>
              </div>
            </div>
          @endif
          @endfor
          
          <!-- Spacer for the gap between P9 and P10 -->
          <div class="seat-spacer"></div>
          
          @for ($i = 10; $i <= 17; $i++)
          @php
            $array = array('kursi' => 'P' . $i, 'rute' => $data['id'], 'waktu' => $data['waktu']);
            $cekData = json_encode($array);
          @endphp
          @if ($transportasi->kursi($cekData) != null && $transportasi->name == 'SILVER')
            <div class="seat-item">
              <div class="kursi silver" onclick="toggleSeat(this)">
                <div>P{{ $i }}</div>
              </div>
            </div>
          @else
            <div class="seat-item">
              <div class="kursi reserved" style="background: #858796">
                <div>P{{ $i }}</div>
              </div>
            </div>
          @endif
          @endfor
        </div>

        <!-- O Row -->
        <div class="seating-container seat-row">
          @for ($i = 1; $i <= 2; $i++)
          @php
            $array = array('kursi' => 'O' . $i, 'rute' => $data['id'], 'waktu' => $data['waktu']);
            $cekData = json_encode($array);
          @endphp
          @if ($transportasi->kursi($cekData) != null && $transportasi->name == 'PLATINUM')
            <div class="seat-item">
              <div class="kursi platinum" onclick="toggleSeat(this)">
                <div>O{{ $i }}</div>
              </div>
            </div>
          @else
            <div class="seat-item">
              <div class="kursi reserved" style="background: #858796">
                <div>O{{ $i }}</div>
              </div>
            </div>
            @endif
          @endfor

          @for ($i = 3; $i <= 4; $i++)
          @php
            $array = array('kursi' => 'O' . $i, 'rute' => $data['id'], 'waktu' => $data['waktu']);
            $cekData = json_encode($array);
          @endphp
          @if ($transportasi->kursi($cekData) != null && $transportasi->name == 'UNDANGAN')
            <div class="seat-item">
              <div class="kursi undangan" onclick="toggleSeat(this)">
                <div>O{{ $i }}</div>
              </div>
            </div>
          @else
            <div class="seat-item">
              <div class="kursi reserved" style="background: #858796">
                <div>O{{ $i }}</div>
              </div>
            </div>
            @endif
          @endfor

          @for ($i = 5; $i <= 6; $i++)
          @php
            $array = array('kursi' => 'O' . $i, 'rute' => $data['id'], 'waktu' => $data['waktu']);
            $cekData = json_encode($array);
          @endphp
          @if ($transportasi->kursi($cekData) != null && $transportasi->name == 'PLATINUM')
            <div class="seat-item">
              <div class="kursi platinum" onclick="toggleSeat(this)">
                <div>O{{ $i }}</div>
              </div>
            </div>
          @else
            <div class="seat-item">
              <div class="kursi reserved" style="background: #858796">
                <div>O{{ $i }}</div>
              </div>
            </div>
            @endif
          @endfor

          @for ($i = 7; $i <= 7; $i++)
          @php
            $array = array('kursi' => 'O' . $i, 'rute' => $data['id'], 'waktu' => $data['waktu']);
            $cekData = json_encode($array);
          @endphp
          @if ($transportasi->kursi($cekData) != null && $transportasi->name == 'UNDANGAN')
            <div class="seat-item">
              <div class="kursi undangan" onclick="toggleSeat(this)">
                <div>O{{ $i }}</div>
              </div>
            </div>
          @else
            <div class="seat-item">
              <div class="kursi reserved" style="background: #858796">
                <div>O{{ $i }}</div>
              </div>
            </div>
            @endif
          @endfor

          @for ($i = 8; $i <= 9; $i++)
          @php
            $array = array('kursi' => 'O' . $i, 'rute' => $data['id'], 'waktu' => $data['waktu']);
            $cekData = json_encode($array);
          @endphp
          @if ($transportasi->kursi($cekData) != null && $transportasi->name == 'VVIP')
            <div class="seat-item">
              <div class="kursi vvip" onclick="toggleSeat(this)">
                <div>O{{ $i }}</div>
              </div>
            </div>
          @else
            <div class="seat-item">
              <div class="kursi reserved" style="background: #858796">
                <div>O{{ $i }}</div>
              </div>
            </div>
            @endif
          @endfor
          
          <!-- Spacer for the gap between O9 and O10 -->
          <div class="seat-spacer"></div>
          
          @for ($i = 10; $i <= 12; $i++)
          @php
            $array = array('kursi' => 'O' . $i, 'rute' => $data['id'], 'waktu' => $data['waktu']);
            $cekData = json_encode($array);
          @endphp
          @if ($transportasi->kursi($cekData) != null && $transportasi->name == 'VVIP')
            <div class="seat-item">
              <div class="kursi vvip" onclick="toggleSeat(this)">
                <div>O{{ $i }}</div>
              </div>
            </div>
            @else
            <div class="seat-item">
              <div class="kursi reserved" style="background: #858796">
                <div>O{{ $i }}</div>
              </div>
            </div>
            @endif
          @endfor

          @for ($i = 13; $i <= 17; $i++)
          @php
            $array = array('kursi' => 'O' . $i, 'rute' => $data['id'], 'waktu' => $data['waktu']);
            $cekData = json_encode($array);
          @endphp
          @if ($transportasi->kursi($cekData) != null && $transportasi->name == 'PLATINUM')
            <div class="seat-item">
              <div class="kursi platinum" onclick="toggleSeat(this)">
                <div>O{{ $i }}</div>
              </div>
            </div>
            @else
            <div class="seat-item">
              <div class="kursi reserved" style="background: #858796">
                <div>O{{ $i }}</div>
              </div>
            </div>
            @endif
          @endfor
        </div>

        <!-- N Row -->
        <div class="seating-container seat-row">
          @for ($i = 1; $i <= 9; $i++)
          @php
            $array = array('kursi' => 'N' . $i, 'rute' => $data['id'], 'waktu' => $data['waktu']);
            $cekData = json_encode($array);
          @endphp
          @if ($transportasi->kursi($cekData) != null && $transportasi->name == 'GOLD')
            <div class="seat-item">
              <div class="kursi gold" onclick="toggleSeat(this)">
                <div>N{{ $i }}</div>
              </div>
            </div>
          @else
            <div class="seat-item">
              <div class="kursi reserved" style="background: #858796">
                <div>N{{ $i }}</div>
              </div>
            </div>
          @endif
          @endfor
          
          <!-- Spacer for the gap between N9 and N10 -->
          <div class="seat-spacer"></div>
          
          @for ($i = 10; $i <= 17; $i++)
          @php
            $array = array('kursi' => 'N' . $i, 'rute' => $data['id'], 'waktu' => $data['waktu']);
            $cekData = json_encode($array);
          @endphp
          @if ($transportasi->kursi($cekData) != null && $transportasi->name == 'GOLD')
            <div class="seat-item">
              <div class="kursi gold" onclick="toggleSeat(this)">
                <div>N{{ $i }}</div>
              </div>
            </div>
          @else
            <div class="seat-item">
              <div class="kursi reserved" style="background: #858796">
                <div>N{{ $i }}</div>
              </div>
            </div>
          @endif
          @endfor
        </div>

        <!-- M Row -->
        <div class="seating-container seat-row">
          @for ($i = 1; $i <= 3; $i++)
          @php
            $array = array('kursi' => 'M' . $i, 'rute' => $data['id'], 'waktu' => $data['waktu']);
            $cekData = json_encode($array);
          @endphp
          @if ($transportasi->kursi($cekData) != null && $transportasi->name == 'PLATINUM')
            <div class="seat-item">
              <div class="kursi platinum" onclick="toggleSeat(this)">
                <div>M{{ $i }}</div>
              </div>
            </div>
          @else
            <div class="seat-item">
              <div class="kursi reserved" style="background: #858796">
                <div>M{{ $i }}</div>
              </div>
            </div>
          @endif
          @endfor

          @for ($i = 4; $i <= 5; $i++)
          @php
            $array = array('kursi' => 'M' . $i, 'rute' => $data['id'], 'waktu' => $data['waktu']);
            $cekData = json_encode($array);
          @endphp
          @if ($transportasi->kursi($cekData) != null && $transportasi->name == 'VVIP')
            <div class="seat-item">
              <div class="kursi vvip" onclick="toggleSeat(this)">
                <div>M{{ $i }}</div>
              </div>
            </div>
          @else
            <div class="seat-item">
              <div class="kursi reserved" style="background: #858796">
                <div>M{{ $i }}</div>
              </div>
            </div>
          @endif
          @endfor

          @for ($i = 6; $i <= 7; $i++)
          @php
            $array = array('kursi' => 'M' . $i, 'rute' => $data['id'], 'waktu' => $data['waktu']);
            $cekData = json_encode($array);
          @endphp
          @if ($transportasi->kursi($cekData) != null && $transportasi->name == 'PLATINUM')
            <div class="seat-item">
              <div class="kursi platinum" onclick="toggleSeat(this)">
                <div>M{{ $i }}</div>
              </div>
            </div>
          @else
            <div class="seat-item">
              <div class="kursi reserved" style="background: #858796">
                <div>M{{ $i }}</div>
              </div>
            </div>
          @endif
          @endfor

          @for ($i = 8; $i <= 9; $i++)
          @php
            $array = array('kursi' => 'M' . $i, 'rute' => $data['id'], 'waktu' => $data['waktu']);
            $cekData = json_encode($array);
          @endphp
          @if ($transportasi->kursi($cekData) != null && $transportasi->name == 'VVIP')
            <div class="seat-item">
              <div class="kursi vvip" onclick="toggleSeat(this)">
                <div>M{{ $i }}</div>
              </div>
            </div>
          @else
            <div class="seat-item">
              <div class="kursi reserved" style="background: #858796">
                <div>M{{ $i }}</div>
              </div>
            </div>
          @endif
          @endfor
          
          <!-- Spacer for the gap between M9 and M10 -->
          <div class="seat-spacer"></div>
          
          @for ($i = 10; $i <= 10; $i++)
          @php
            $array = array('kursi' => 'M' . $i, 'rute' => $data['id'], 'waktu' => $data['waktu']);
            $cekData = json_encode($array);
          @endphp
          @if ($transportasi->kursi($cekData) != null && $transportasi->name == 'VVIP')
            <div class="seat-item">
              <div class="kursi vvip" onclick="toggleSeat(this)">
                <div>M{{ $i }}</div>
              </div>
            </div>
          @else
            <div class="seat-item">
              <div class="kursi reserved" style="background: #858796">
                <div>M{{ $i }}</div>
              </div>
            </div>
          @endif
          @endfor

          @for ($i = 11; $i <= 14; $i++)
          @php
            $array = array('kursi' => 'M' . $i, 'rute' => $data['id'], 'waktu' => $data['waktu']);
            $cekData = json_encode($array);
          @endphp
          @if ($transportasi->kursi($cekData) != null && $transportasi->name == 'PLATINUM')
            <div class="seat-item">
              <div class="kursi platinum" onclick="toggleSeat(this)">
                <div>M{{ $i }}</div>
              </div>
            </div>
          @else
            <div class="seat-item">
              <div class="kursi reserved" style="background: #858796">
                <div>M{{ $i }}</div>
              </div>
            </div>
          @endif
          @endfor

          @for ($i = 15; $i <= 17; $i++)
          @php
            $array = array('kursi' => 'M' . $i, 'rute' => $data['id'], 'waktu' => $data['waktu']);
            $cekData = json_encode($array);
          @endphp
          @if ($transportasi->kursi($cekData) != null && $transportasi->name == 'PLATINUM')
            <div class="seat-item">
              <div class="kursi platinum" onclick="toggleSeat(this)">
                <div>M{{ $i }}</div>
              </div>
            </div>
          @else
            <div class="seat-item">
              <div class="kursi reserved" style="background: #858796">
                <div>M{{ $i }}</div>
              </div>
            </div>
          @endif
          @endfor
        </div>

         <!-- L Row -->
        <div class="seating-container seat-row">
          @for ($i = 1; $i <= 7; $i++)
          @php
            $array = array('kursi' => 'L' . $i, 'rute' => $data['id'], 'waktu' => $data['waktu']);
            $cekData = json_encode($array);
          @endphp
          @if ($transportasi->kursi($cekData) != null && $transportasi->name == 'SILVER')
            <div class="seat-item">
              <div class="kursi silver" onclick="toggleSeat(this)">
                <div>L{{ $i }}</div>
              </div>
            </div>
          @else
            <div class="seat-item">
              <div class="kursi reserved" style="background: #858796">
                <div>L{{ $i }}</div>
              </div>
            </div>
          @endif
          @endfor
          
          <!-- Spacer for the gap between L7 and L8 -->
          <div class="middle-seat-spacer"></div>

          @for ($i = 8; $i <= 10; $i++)
          @php
            $array = array('kursi' => 'L' . $i, 'rute' => $data['id'], 'waktu' => $data['waktu']);
            $cekData = json_encode($array);
          @endphp
          @if ($transportasi->kursi($cekData) != null && $transportasi->name == 'SILVER')
            <div class="seat-item">
              <div class="kursi silver" onclick="toggleSeat(this)">
                <div>L{{ $i }}</div>
              </div>
            </div>
          @else
            <div class="seat-item">
              <div class="kursi reserved" style="background: #858796">
                <div>L{{ $i }}</div>
              </div>
            </div>
          @endif
          @endfor

          <!-- Spacer for the gap between L7 and L8 -->
          <div class="middle-seat-spacer-after"></div>

          @for ($i = 11; $i <= 17; $i++)
          @php
            $array = array('kursi' => 'L' . $i, 'rute' => $data['id'], 'waktu' => $data['waktu']);
            $cekData = json_encode($array);
          @endphp
          @if ($transportasi->kursi($cekData) != null && $transportasi->name == 'SILVER')
            <div class="seat-item">
              <div class="kursi silver" onclick="toggleSeat(this)">
                <div>L{{ $i }}</div>
              </div>
            </div>
          @else
            <div class="seat-item">
              <div class="kursi reserved" style="background: #858796">
                <div>L{{ $i }}</div>
              </div>
            </div>
          @endif
          @endfor
        </div>
        <br>

         <!-- K Row -->
        <div class="seating-container seat-row">
          @for ($i = 1; $i <= 6; $i++)
          @php
            $array = array('kursi' => 'K' . $i, 'rute' => $data['id'], 'waktu' => $data['waktu']);
            $cekData = json_encode($array);
          @endphp
          @if ($transportasi->kursi($cekData) != null && $transportasi->name == 'UNDANGAN')
            <div class="seat-item">
              <div class="kursi undangan" onclick="toggleSeat(this)">
                <div>K{{ $i }}</div>
              </div>
            </div>
          @else
            <div class="seat-item">
              <div class="kursi reserved" style="background: #858796">
                <div>K{{ $i }}</div>
              </div>
            </div>
          @endif
          @endfor

          <!-- Spacer for the gap between K6 and K7 -->
          <div class="seatK-spacer"></div>

          @for ($i = 7; $i <= 14; $i++)
          @php
            $array = array('kursi' => 'K' . $i, 'rute' => $data['id'], 'waktu' => $data['waktu']);
            $cekData = json_encode($array);
          @endphp
          @if ($transportasi->kursi($cekData) != null && $transportasi->name == 'UNDANGAN')
            <div class="seat-item">
              <div class="kursi undangan" onclick="toggleSeat(this)">
                <div>K{{ $i }}</div>
              </div>
            </div>
          @else
            <div class="seat-item">
              <div class="kursi reserved" style="background: #858796">
                <div>K{{ $i }}</div>
              </div>
            </div>
          @endif
          @endfor

          <!-- Spacer for the gap between K7 and K8 -->
          <div class="seatK-spacer-after"></div>

          @for ($i = 15; $i <= 20; $i++)
          @php
            $array = array('kursi' => 'K' . $i, 'rute' => $data['id'], 'waktu' => $data['waktu']);
            $cekData = json_encode($array);
          @endphp
          @if ($transportasi->kursi($cekData) != null && $transportasi->name == 'GOLD')
            <div class="seat-item">
              <div class="kursi gold" onclick="toggleSeat(this)">
                <div>K{{ $i }}</div>
              </div>
            </div>
          @else
            <div class="seat-item">
              <div class="kursi reserved" style="background: #858796">
                <div>K{{ $i }}</div>
              </div>
            </div>
          @endif
          @endfor
        </div>

        <!-- J Row -->
        <div class="seating-container seat-row">
          @for ($i = 1; $i <= 6; $i++)
          @php
            $array = array('kursi' => 'J' . $i, 'rute' => $data['id'], 'waktu' => $data['waktu']);
            $cekData = json_encode($array);
          @endphp
          @if ($transportasi->kursi($cekData) != null && $transportasi->name == 'PLATINUM')
            <div class="seat-item">
              <div class="kursi platinum" onclick="toggleSeat(this)">
                <div>J{{ $i }}</div>
              </div>
            </div>
          @else
            <div class="seat-item">
              <div class="kursi reserved" style="background: #858796">
                <div>J{{ $i }}</div>
              </div>
            </div>
          @endif
          @endfor

          <!-- Spacer for the gap between J6 and J7 -->
          <div class="seatJ-spacer"></div>

          @for ($i = 7; $i <= 10; $i++)
          @php
            $array = array('kursi' => 'J' . $i, 'rute' => $data['id'], 'waktu' => $data['waktu']);
            $cekData = json_encode($array);
          @endphp
          @if ($transportasi->kursi($cekData) != null && $transportasi->name == 'UNDANGAN')
            <div class="seat-item">
              <div class="kursi undangan" onclick="toggleSeat(this)">
                <div>J{{ $i }}</div>
              </div>
            </div>
          @else
            <div class="seat-item">
              <div class="kursi reserved" style="background: #858796">
                <div>J{{ $i }}</div>
              </div>
            </div>
          @endif
          @endfor

          @for ($i = 11; $i <= 13; $i++)
          @php
            $array = array('kursi' => 'J' . $i, 'rute' => $data['id'], 'waktu' => $data['waktu']);
            $cekData = json_encode($array);
          @endphp
          @if ($transportasi->kursi($cekData) != null && $transportasi->name == 'GOLD')
            <div class="seat-item">
              <div class="kursi gold" onclick="toggleSeat(this)">
                <div>J{{ $i }}</div>
              </div>
            </div>
          @else
            <div class="seat-item">
              <div class="kursi reserved" style="background: #858796">
                <div>J{{ $i }}</div>
              </div>
            </div>
          @endif
          @endfor

          <!-- Spacer for the gap between J7 and J8 -->
          <div class="seatJ-spacer-after"></div>

          @for ($i = 14; $i <= 19; $i++)
          @php
            $array = array('kursi' => 'J' . $i, 'rute' => $data['id'], 'waktu' => $data['waktu']);
            $cekData = json_encode($array);
          @endphp
          @if ($transportasi->kursi($cekData) != null && $transportasi->name == 'GOLD')
            <div class="seat-item">
              <div class="kursi gold" onclick="toggleSeat(this)">
                <div>J{{ $i }}</div>
              </div>
            </div>
          @else
            <div class="seat-item">
              <div class="kursi reserved" style="background: #858796">
                <div>J{{ $i }}</div>
              </div>
            </div>
          @endif
          @endfor
        </div>

         <!-- I Row -->
        <div class="seating-container seat-row">
          @for ($i = 1; $i <= 6; $i++)
          @php
            $array = array('kursi' => 'I' . $i, 'rute' => $data['id'], 'waktu' => $data['waktu']);
            $cekData = json_encode($array);
          @endphp
          @if ($transportasi->kursi($cekData) != null && $transportasi->name == 'GOLD')
            <div class="seat-item">
              <div class="kursi gold" onclick="toggleSeat(this)">
                <div>I{{ $i }}</div>
              </div>
            </div>
          @else
            <div class="seat-item">
              <div class="kursi reserved" style="background: #858796">
                <div>I{{ $i }}</div>
              </div>
            </div>
          @endif
          @endfor

          <!-- Spacer for the gap between I6 and I7 -->
          <div class="seatK-spacer"></div>

          @for ($i = 7; $i <= 14; $i++)
          @php
            $array = array('kursi' => 'I' . $i, 'rute' => $data['id'], 'waktu' => $data['waktu']);
            $cekData = json_encode($array);
          @endphp
          @if ($transportasi->kursi($cekData) != null && $transportasi->name == 'GOLD')
            <div class="seat-item">
              <div class="kursi gold" onclick="toggleSeat(this)">
                <div>I{{ $i }}</div>
              </div>
            </div>
          @else
            <div class="seat-item">
              <div class="kursi reserved" style="background: #858796">
                <div>I{{ $i }}</div>
              </div>
            </div>
          @endif
          @endfor

          <!-- Spacer for the gap between I7 and I8 -->
          <div class="seatK-spacer-after"></div>

          @for ($i = 15; $i <= 20; $i++)
          @php
            $array = array('kursi' => 'I' . $i, 'rute' => $data['id'], 'waktu' => $data['waktu']);
            $cekData = json_encode($array);
          @endphp
          @if ($transportasi->kursi($cekData) != null && $transportasi->name == 'GOLD')
            <div class="seat-item">
              <div class="kursi gold" onclick="toggleSeat(this)">
                <div>I{{ $i }}</div>
              </div>
            </div>
          @else
            <div class="seat-item">
              <div class="kursi reserved" style="background: #858796">
                <div>I{{ $i }}</div>
              </div>
            </div>
          @endif
          @endfor
        </div>

        <!-- H Row -->
        <div class="seating-container seat-row">
          @for ($i = 1; $i <= 6; $i++)
          @php
            $array = array('kursi' => 'H' . $i, 'rute' => $data['id'], 'waktu' => $data['waktu']);
            $cekData = json_encode($array);
          @endphp
          @if ($transportasi->kursi($cekData) != null && $transportasi->name == 'GOLD')
            <div class="seat-item">
              <div class="kursi gold" onclick="toggleSeat(this)">
                <div>H{{ $i }}</div>
              </div>
            </div>
          @else
            <div class="seat-item">
              <div class="kursi reserved" style="background: #858796">
                <div>H{{ $i }}</div>
              </div>
            </div>
          @endif
          @endfor

          <!-- Spacer for the gap between H6 and H7 -->
          <div class="seatJ-spacer"></div>

          @for ($i = 7; $i <= 13; $i++)
          @php
            $array = array('kursi' => 'H' . $i, 'rute' => $data['id'], 'waktu' => $data['waktu']);
            $cekData = json_encode($array);
          @endphp
          @if ($transportasi->kursi($cekData) != null && $transportasi->name == 'GOLD')
            <div class="seat-item">
              <div class="kursi gold" onclick="toggleSeat(this)">
                <div>H{{ $i }}</div>
              </div>
            </div>
          @else
            <div class="seat-item">
              <div class="kursi reserved" style="background: #858796">
                <div>H{{ $i }}</div>
              </div>
            </div>
          @endif
          @endfor

          <!-- Spacer for the gap between H7 and H8 -->
          <div class="seatJ-spacer-after"></div>

          @for ($i = 14; $i <= 19; $i++)
          @php
            $array = array('kursi' => 'H' . $i, 'rute' => $data['id'], 'waktu' => $data['waktu']);
            $cekData = json_encode($array);
          @endphp
          @if ($transportasi->kursi($cekData) != null && $transportasi->name == 'GOLD')
            <div class="seat-item">
              <div class="kursi gold" onclick="toggleSeat(this)">
                <div>H{{ $i }}</div>
              </div>
            </div>
          @else
            <div class="seat-item">
              <div class="kursi reserved" style="background: #858796">
                <div>H{{ $i }}</div>
              </div>
            </div>
          @endif
          @endfor
        </div>

         <!-- G Row -->
        <div class="seating-container seat-row">
          @for ($i = 1; $i <= 6; $i++)
          @php
            $array = array('kursi' => 'G' . $i, 'rute' => $data['id'], 'waktu' => $data['waktu']);
            $cekData = json_encode($array);
          @endphp
          @if ($transportasi->kursi($cekData) != null && $transportasi->name == 'GOLD')
            <div class="seat-item">
              <div class="kursi gold" onclick="toggleSeat(this)">
                <div>G{{ $i }}</div>
              </div>
            </div>
          @else
            <div class="seat-item">
              <div class="kursi reserved" style="background: #858796">
                <div>G{{ $i }}</div>
              </div>
            </div>
          @endif
          @endfor

          <!-- Spacer for the gap between G6 and G7 -->
          <div class="seatK-spacer"></div>

          @for ($i = 7; $i <= 14; $i++)
          @php
            $array = array('kursi' => 'G' . $i, 'rute' => $data['id'], 'waktu' => $data['waktu']);
            $cekData = json_encode($array);
          @endphp
          @if ($transportasi->kursi($cekData) != null && $transportasi->name == 'GOLD')
            <div class="seat-item">
              <div class="kursi gold" onclick="toggleSeat(this)">
                <div>G{{ $i }}</div>
              </div>
            </div>
          @else
            <div class="seat-item">
              <div class="kursi reserved" style="background: #858796">
                <div>G{{ $i }}</div>
              </div>
            </div>
          @endif
          @endfor

          <!-- Spacer for the gap between G7 and G8 -->
          <div class="seatK-spacer-after"></div>

          @for ($i = 15; $i <= 20; $i++)
          @php
            $array = array('kursi' => 'G' . $i, 'rute' => $data['id'], 'waktu' => $data['waktu']);
            $cekData = json_encode($array);
          @endphp
          @if ($transportasi->kursi($cekData) != null && $transportasi->name == 'GOLD')
            <div class="seat-item">
              <div class="kursi gold" onclick="toggleSeat(this)">
                <div>G{{ $i }}</div>
              </div>
            </div>
          @else
            <div class="seat-item">
              <div class="kursi reserved" style="background: #858796">
                <div>G{{ $i }}</div>
              </div>
            </div>
          @endif
          @endfor
        </div>

        <!-- F Row -->
        <div class="seating-container seat-row">
          @for ($i = 1; $i <= 6; $i++)
          @php
            $array = array('kursi' => 'F' . $i, 'rute' => $data['id'], 'waktu' => $data['waktu']);
            $cekData = json_encode($array);
          @endphp
          @if ($transportasi->kursi($cekData) != null && $transportasi->name == 'GOLD')
            <div class="seat-item">
              <div class="kursi gold" onclick="toggleSeat(this)">
                <div>F{{ $i }}</div>
              </div>
            </div>
          @else
            <div class="seat-item">
              <div class="kursi reserved" style="background: #858796">
                <div>F{{ $i }}</div>
              </div>
            </div>
          @endif
          @endfor

          <!-- Spacer for the gap between F6 and F7 -->
          <div class="seatJ-spacer"></div>

          @for ($i = 7; $i <= 13; $i++)
          @php
            $array = array('kursi' => 'F' . $i, 'rute' => $data['id'], 'waktu' => $data['waktu']);
            $cekData = json_encode($array);
          @endphp
          @if ($transportasi->kursi($cekData) != null && $transportasi->name == 'GOLD')
            <div class="seat-item">
              <div class="kursi gold" onclick="toggleSeat(this)">
                <div>F{{ $i }}</div>
              </div>
            </div>
          @else
            <div class="seat-item">
              <div class="kursi reserved" style="background: #858796">
                <div>F{{ $i }}</div>
              </div>
            </div>
          @endif
          @endfor

          <!-- Spacer for the gap between F7 and F8 -->
          <div class="seatJ-spacer-after"></div>

          @for ($i = 14; $i <= 19; $i++)
          @php
            $array = array('kursi' => 'F' . $i, 'rute' => $data['id'], 'waktu' => $data['waktu']);
            $cekData = json_encode($array);
          @endphp
          @if ($transportasi->kursi($cekData) != null && $transportasi->name == 'GOLD')
            <div class="seat-item">
              <div class="kursi gold" onclick="toggleSeat(this)">
                <div>F{{ $i }}</div>
              </div>
            </div>
          @else
            <div class="seat-item">
              <div class="kursi reserved" style="background: #858796">
                <div>F{{ $i }}</div>
              </div>
            </div>
          @endif
          @endfor
        </div>

         <!-- E Row -->
        <div class="seating-container seat-row">
          @for ($i = 1; $i <= 6; $i++)
          @php
            $array = array('kursi' => 'E' . $i, 'rute' => $data['id'], 'waktu' => $data['waktu']);
            $cekData = json_encode($array);
          @endphp
          @if ($transportasi->kursi($cekData) != null && $transportasi->name == 'GOLD')
            <div class="seat-item">
              <div class="kursi gold" onclick="toggleSeat(this)">
                <div>E{{ $i }}</div>
              </div>
            </div>
          @else
            <div class="seat-item">
              <div class="kursi reserved" style="background: #858796">
                <div>E{{ $i }}</div>
              </div>
            </div>
          @endif
          @endfor

          <!-- Spacer for the gap between E6 and E7 -->
          <div class="seatK-spacer"></div>

          @for ($i = 7; $i <= 14; $i++)
          @php
            $array = array('kursi' => 'E' . $i, 'rute' => $data['id'], 'waktu' => $data['waktu']);
            $cekData = json_encode($array);
          @endphp
          @if ($transportasi->kursi($cekData) != null && $transportasi->name == 'GOLD')
            <div class="seat-item">
              <div class="kursi gold" onclick="toggleSeat(this)">
                <div>E{{ $i }}</div>
              </div>
            </div>
          @else
            <div class="seat-item">
              <div class="kursi reserved" style="background: #858796">
                <div>E{{ $i }}</div>
              </div>
            </div>
          @endif
          @endfor

          <!-- Spacer for the gap between E7 and E8 -->
          <div class="seatK-spacer-after"></div>

          @for ($i = 15; $i <= 20; $i++)
          @php
            $array = array('kursi' => 'E' . $i, 'rute' => $data['id'], 'waktu' => $data['waktu']);
            $cekData = json_encode($array);
          @endphp
          @if ($transportasi->kursi($cekData) != null && $transportasi->name == 'GOLD')
            <div class="seat-item">
              <div class="kursi gold" onclick="toggleSeat(this)">
                <div>E{{ $i }}</div>
              </div>
            </div>
          @else
            <div class="seat-item">
              <div class="kursi reserved" style="background: #858796">
                <div>E{{ $i }}</div>
              </div>
            </div>
          @endif
          @endfor
        </div>

        <!-- D Row -->
        <div class="seating-container seat-row">
          @for ($i = 1; $i <= 6; $i++)
          @php
            $array = array('kursi' => 'D' . $i, 'rute' => $data['id'], 'waktu' => $data['waktu']);
            $cekData = json_encode($array);
          @endphp
          @if ($transportasi->kursi($cekData) != null && $transportasi->name == 'PLATINUM')
            <div class="seat-item">
              <div class="kursi platinum" onclick="toggleSeat(this)">
                <div>D{{ $i }}</div>
              </div>
            </div>
          @else
            <div class="seat-item">
              <div class="kursi reserved" style="background: #858796">
                <div>D{{ $i }}</div>
              </div>
            </div>
          @endif
          @endfor

          <!-- Spacer for the gap between D6 and D7 -->
          <div class="seatJ-spacer"></div>

          @for ($i = 7; $i <= 13; $i++)
          @php
            $array = array('kursi' => 'D' . $i, 'rute' => $data['id'], 'waktu' => $data['waktu']);
            $cekData = json_encode($array);
          @endphp
          @if ($transportasi->kursi($cekData) != null && $transportasi->name == 'PLATINUM')
            <div class="seat-item">
              <div class="kursi platinum" onclick="toggleSeat(this)">
                <div>D{{ $i }}</div>
              </div>
            </div>
          @else
            <div class="seat-item">
              <div class="kursi reserved" style="background: #858796">
                <div>D{{ $i }}</div>
              </div>
            </div>
          @endif
          @endfor

          <!-- Spacer for the gap between D7 and D8 -->
          <div class="seatJ-spacer-after"></div>

          @for ($i = 14; $i <= 17; $i++)
          @php
            $array = array('kursi' => 'D' . $i, 'rute' => $data['id'], 'waktu' => $data['waktu']);
            $cekData = json_encode($array);
          @endphp
          @if ($transportasi->kursi($cekData) != null && $transportasi->name == 'PLATINUM')
            <div class="seat-item">
              <div class="kursi platinum" onclick="toggleSeat(this)">
                <div>D{{ $i }}</div>
              </div>
            </div>
          @else
            <div class="seat-item">
              <div class="kursi reserved" style="background: #858796">
                <div>D{{ $i }}</div>
              </div>
            </div>
          @endif
          @endfor

          @for ($i = 18; $i <= 19; $i++)
          @php
            $array = array('kursi' => 'D' . $i, 'rute' => $data['id'], 'waktu' => $data['waktu']);
            $cekData = json_encode($array);
          @endphp
          @if ($transportasi->kursi($cekData) != null && $transportasi->name == 'UNDANGAN')
            <div class="seat-item">
              <div class="kursi undangan" onclick="toggleSeat(this)">
                <div>D{{ $i }}</div>
              </div>
            </div>
          @else
            <div class="seat-item">
              <div class="kursi reserved" style="background: #858796">
                <div>D{{ $i }}</div>
              </div>
            </div>
          @endif
          @endfor
        </div>

         <!-- C Row -->
        <div class="seating-container seat-row"> 
          @for ($i = 1; $i <= 6; $i++)
          @php
            $array = array('kursi' => 'C' . $i, 'rute' => $data['id'], 'waktu' => $data['waktu']);
            $cekData = json_encode($array);
          @endphp
          @if ($transportasi->kursi($cekData) != null && $transportasi->name == 'PLATINUM')
            <div class="seat-item">
              <div class="kursi platinum" onclick="toggleSeat(this)">
                <div>C{{ $i }}</div>
              </div>
            </div>
          @else
            <div class="seat-item">
              <div class="kursi reserved" style="background: #858796">
                <div>C{{ $i }}</div>
              </div>
            </div>
          @endif
          @endfor

          <!-- Spacer for the gap between C6 and C7 -->
          <div class="seatK-spacer"></div>

          @for ($i = 7; $i <= 14; $i++)
          @php
            $array = array('kursi' => 'C' . $i, 'rute' => $data['id'], 'waktu' => $data['waktu']);
            $cekData = json_encode($array);
          @endphp
          @if ($transportasi->kursi($cekData) != null && $transportasi->name == 'PLATINUM')
            <div class="seat-item">
              <div class="kursi platinum" onclick="toggleSeat(this)">
                <div>C{{ $i }}</div>
              </div>
            </div>
          @else
            <div class="seat-item">
              <div class="kursi reserved" style="background: #858796">
                <div>C{{ $i }}</div>
              </div>
            </div>
          @endif
          @endfor

          <!-- Spacer for the gap between C7 and C8 -->
          <div class="seatK-spacer-after"></div>

          @for ($i = 15; $i <= 20; $i++)
          @php
            $array = array('kursi' => 'C' . $i, 'rute' => $data['id'], 'waktu' => $data['waktu']);
            $cekData = json_encode($array);
          @endphp
          @if ($transportasi->kursi($cekData) != null && $transportasi->name == 'PLATINUM')
            <div class="seat-item">
              <div class="kursi platinum" onclick="toggleSeat(this)">
                <div>C{{ $i }}</div>
              </div>
            </div>
          @else
            <div class="seat-item">
              <div class="kursi reserved" style="background: #858796">
                <div>C{{ $i }}</div>
              </div>
            </div>
          @endif
          @endfor
        </div>
        
        <!-- B Row -->
        <div class="seating-container seat-row">
            @for ($i = 1; $i <= 6; $i++)
            @php
                $array = array('kursi' => 'B' . $i, 'rute' => $data['id'], 'waktu' => $data['waktu']);
                $cekData = json_encode($array);
            @endphp
            @if ($transportasi->kursi($cekData) != null && $transportasi->name == 'UNDANGAN')
                <div class="seat-item">
                    <div class="kursi undangan" onclick="toggleSeat(this)">
                        <div>B{{ $i }}</div>
                    </div>
                </div>
            @else
                <div class="seat-item">
                    <div class="kursi reserved" style="background: #858796">
                        <div>B{{ $i }}</div>
                    </div>
                </div>
            @endif
            @endfor

            <!-- Spacer for the gap between B6 and B7 -->
            <div class="seatJ-spacer"></div>

            @for ($i = 7; $i <= 13; $i++)
            @php
                $array = array('kursi' => 'B' . $i, 'rute' => $data['id'], 'waktu' => $data['waktu']);
                $cekData = json_encode($array);
            @endphp
            @if ($transportasi->kursi($cekData) != null && $transportasi->name == 'UNDANGAN')
                <div class="seat-item">
                    <div class="kursi undangan" onclick="toggleSeat(this)">
                        <div>B{{ $i }}</div>
                    </div>
                </div>
            @else
                <div class="seat-item">
                    <div class="kursi reserved" style="background: #858796">
                        <div>B{{ $i }}</div>
                    </div>
                </div>
            @endif
            @endfor

            <!-- Spacer for the gap between B7 and B8 -->
            <div class="seatJ-spacer-after"></div>

            @for ($i = 14; $i <= 19; $i++)
            @php
                $array = array('kursi' => 'B' . $i, 'rute' => $data['id'], 'waktu' => $data['waktu']);
                $cekData = json_encode($array);
            @endphp
            @if ($transportasi->kursi($cekData) != null && $transportasi->name == 'UNDANGAN')
                <div class="seat-item">
                    <div class="kursi undangan" onclick="toggleSeat(this)">
                        <div>B{{ $i }}</div>
                    </div>
                </div>
            @else
                <div class="seat-item">
                    <div class="kursi reserved" style="background: #858796">
                        <div>B{{ $i }}</div>
                    </div>
                </div>
            @endif
            @endfor
        </div>


         <!-- A Row -->
        <div class="seating-container seat-row">
          @for ($i = 1; $i <= 6; $i++)
          @php
            $array = array('kursi' => 'A' . $i, 'rute' => $data['id'], 'waktu' => $data['waktu']);
            $cekData = json_encode($array);
          @endphp
          @if ($transportasi->kursi($cekData) != null && $transportasi->name == 'PLATINUM')
            <div class="seat-item">
              <div class="kursi platinum" onclick="toggleSeat(this)">
                <div>A{{ $i }}</div>
              </div>
            </div>
          @else
            <div class="seat-item">
              <div class="kursi reserved" style="background: #858796">
                <div>A{{ $i }}</div>
              </div>
            </div>
          @endif
          @endfor

          <!-- Spacer for the gap between A6 and A7 -->
          <div class="seatK-spacer"></div>

          @for ($i = 7; $i <= 14; $i++)
          @php
            $array = array('kursi' => 'A' . $i, 'rute' => $data['id'], 'waktu' => $data['waktu']);
            $cekData = json_encode($array);
          @endphp
          @if ($transportasi->kursi($cekData) != null && $transportasi->name == 'PLATINUM')
            <div class="seat-item">
              <div class="kursi platinum" onclick="toggleSeat(this)">
                <div>A{{ $i }}</div>
              </div>
            </div>
          @else
            <div class="seat-item">
              <div class="kursi reserved" style="background: #858796">
                <div>A{{ $i }}</div>
              </div>
            </div>
          @endif
          @endfor

          <!-- Spacer for the gap between A7 and A8 -->
          <div class="seatK-spacer-after"></div>

          @for ($i = 15; $i <= 20; $i++)
          @php
            $array = array('kursi' => 'A' . $i, 'rute' => $data['id'], 'waktu' => $data['waktu']);
            $cekData = json_encode($array);
          @endphp
          @if ($transportasi->kursi($cekData) != null && $transportasi->name == 'PLATINUM')
            <div class="seat-item">
              <div class="kursi platinum" onclick="toggleSeat(this)">
                <div>A{{ $i }}</div>
              </div>
            </div>
          @else
            <div class="seat-item">
              <div class="kursi reserved" style="background: #858796">
                <div>A{{ $i }}</div>
              </div>
            </div>
          @endif
          @endfor
        </div>
        <!-- Stage Row -->
        <div class="screen" style="display: flex; justify-content: center; align-items: center; height: 100px; background-color: #333;">
          <div style="color: white; font-size: 3em;">STAGE</div>
        </div>
      </div>
    </div>
  </div>

  <!-- Submit button -->
  <button id="submitBtn" class="btn btn-primary">Submit</button>

  <!-- Confirmation Modal -->
  <div class="modal fade" id="confirmationModal" tabindex="-1" role="dialog" aria-labelledby="confirmationModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="confirmationModalLabel">Konfirmasi Pembelian</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close" id="closeConfirmation">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body" id="modalBodyContent"></div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-dismiss="modal" id="cancelConfirmation">Batal</button>
          <button type="button" class="btn btn-primary" id="confirmPurchaseBtn">Lanjut</button>
        </div>
      </div>
    </div>
  </div>

<!-- Referral & Promo Code Modal -->
<div class="modal fade" id="referralModal" tabindex="-1" role="dialog" aria-labelledby="referralModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="referralModalLabel">Referral & Kode Promo</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <!-- Referral Input -->
        <div class="form-group">
          <label for="referralInput">Nama Referral (Opsional)</label>
          <input type="text" id="referralInput" class="form-control" placeholder="Contoh: Stevie G">
          <small class="text-muted d-block mt-1">Isi jika direkomendasikan oleh penyanyi VOS.</small>
        </div>

        <!-- Promo Code Input with Check Button -->
        <div class="form-group mt-3">
          <label for="promoCodeInput">Kode Promo (Opsional)</label>
          <div class="input-group">
            <input type="text" id="promoCodeInput" class="form-control" placeholder="Masukkan kode promo">
            <div class="input-group-append">
              <button class="btn btn-outline-secondary" type="button" id="checkPromoBtn">Cek</button>
            </div>
          </div>
          <small id="promoFeedback" class="form-text"></small>
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
        <button type="button" class="btn btn-primary" id="confirmReferralBtn" disabled>Lanjutkan</button>
      </div>
    </div>
  </div>
</div>
  <!-- Email Modal -->
  <div class="modal fade" id="emailModal" tabindex="-1" role="dialog" aria-labelledby="emailModalLabel" aria-hidden="true">
      <div class="modal-dialog" role="document">
          <div class="modal-content">
              <div class="modal-header">
                  <h5 class="modal-title" id="emailModalLabel">Masukkan Email Pemesan</h5>
                  <button type="button" class="close" data-dismiss="modal" aria-label="Close" id="closeEmail">
                      <span aria-hidden="true">&times;</span>
                  </button>
              </div>
              <div class="modal-body">
                  <input type="email" id="emailInput" class="form-control" placeholder="Email Pemesan">
                  <small class="text-muted d-block mt-2">QR Code akan dikirimkan melalui email</small>
              </div>
              <div class="modal-footer">
                  <button type="button" class="btn btn-secondary" data-dismiss="modal" id="cancelEmail">Batal</button>
                  <button type="button" class="btn btn-primary" id="confirmEmailBtn">Lanjutkan</button>
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
    var seatPrice = {{ (int) $data['harga'] }};
    var dataString = @json($dataString);
    var selectedSeats = [];
    var userRole = "{{ Auth::user()->level }}";
    var data = @json($data); // For rute_id in /check-promo
    var ruteId = data.id;

    function toggleSeat(seat) {
        if (!seat.classList.contains('reserved')) {
            seat.classList.toggle('selected');
            var seatNumber = seat.querySelector('div').textContent.trim();
            var idx = selectedSeats.indexOf(seatNumber);
            if (idx === -1) {
                selectedSeats.push(seatNumber);
            } else {
                selectedSeats.splice(idx, 1);
            }
        }
    }

    document.getElementById('submitBtn').addEventListener('click', function () {
        if (selectedSeats.length === 0) {
            alert('Silakan pilih minimal satu kursi.');
            return;
        }
        if (selectedSeats.length > 5 && userRole === "Penumpang") {
            alert('Maksimal 5 kursi per transaksi.');
            return;
        }

        var seatList = selectedSeats.join(', ');
        var totalHarga = selectedSeats.length * seatPrice;

        document.getElementById('modalBodyContent').innerHTML =
            "<p>Apakah Anda yakin akan melanjutkan pembelian tiket dengan kursi: <strong>" + seatList + "</strong>?</p>" +
            "<p>Total: <strong>Rp " + formatRupiah(totalHarga) + "</strong></p>";

        $('#confirmationModal').modal('show');
    });

    document.getElementById('confirmPurchaseBtn').addEventListener('click', function () {
        $('#confirmationModal').modal('hide');
        $('#referralModal').modal('show');
        resetPromoState();
    });

    function resetPromoState() {
        const promoInput = document.getElementById('promoCodeInput');
        const promoFeedback = document.getElementById('promoFeedback');
        const confirmBtn = document.getElementById('confirmReferralBtn');

        promoInput.value = '';
        promoFeedback.textContent = '';
        promoFeedback.className = 'form-text';
        promoValidated = false;
        confirmBtn.disabled = false;
    }

    let promoValidated = false;

    document.getElementById('checkPromoBtn').addEventListener('click', function () {
        const promoInput = document.getElementById('promoCodeInput');
        const promoFeedback = document.getElementById('promoFeedback');
        const confirmBtn = document.getElementById('confirmReferralBtn');
        const checkBtn = document.getElementById('checkPromoBtn');
        const promoCode = (promoInput.value || '').trim();
        const seatCount = selectedSeats.length;

        if (!promoCode) {
            promoFeedback.textContent = '';
            promoFeedback.className = 'form-text';
            promoValidated = true;
            confirmBtn.disabled = false;
            return;
        }

        if (!/^[A-Z0-9]{4,12}$/i.test(promoCode)) {
            promoFeedback.textContent = 'Format kode tidak valid.';
            promoFeedback.className = 'form-text text-danger';
            promoValidated = false;
            confirmBtn.disabled = true;
            return;
        }

        promoInput.disabled = true;
        checkBtn.disabled = true;
        promoFeedback.textContent = 'Memeriksa...';
        promoFeedback.className = 'form-text text-muted';

        fetch(`{{ url('/check-promo') }}?code=${encodeURIComponent(promoCode)}&rute_id=${ruteId}&seat_count=${seatCount}`)
          .then(response => response.json())
          .then(data => {
              if (data.valid) {
                  const seatCount = selectedSeats.length;
                  const totalOriginal = seatCount * seatPrice;
                  let totalDiscount = 0;
                  let discountDisplayText = '';
                  if (data.discount_type === 'percent') {
                      totalDiscount = totalOriginal * (data.discount_value / 100);
                      discountDisplayText = `${data.discount_value}%`;
                  } else if (data.discount_type === 'fixed') {
                      totalDiscount = data.discount_value * seatCount;
                      discountDisplayText = `Rp ${formatRupiah(data.discount_value)} per tiket`;
                  } else if (data.discount_type === 'bogo') {
                      const buy = data.buy_quantity;
                      const free = data.get_free;
                      const perSet = buy + free;
                      const fullSets = Math.floor(seatCount / perSet);
                      const remainder = seatCount % perSet;
                      const payable = fullSets * buy + Math.min(remainder, buy);
                      const freeTickets = seatCount - payable;
                      if (freeTickets <= 0) {
                          promoFeedback.innerHTML = `✗ Minimal beli <strong>${buy}</strong> tiket untuk mendapatkan gratis.`;
                          promoFeedback.className = 'form-text text-danger';
                          promoValidated = false;
                          confirmBtn.disabled = true;
                          return;
                      }
                      totalDiscount = freeTickets * seatPrice;
                      discountDisplayText = `Beli ${buy} Gratis ${free}`;
                  } else if (data.discount_type === 'ticket_discount') {
                      const perTicketDiscount = Math.min(data.discount_value, seatPrice);
                      totalDiscount = perTicketDiscount * seatCount;
                      discountDisplayText = `Diskon Tiket: Rp ${formatRupiah(perTicketDiscount)} per tiket`;
                  }
                  const finalTotal = totalOriginal - totalDiscount;
                  promoFeedback.innerHTML = `
                      ✓ <strong>Kode valid!</strong><br>
                      Diskon: ${discountDisplayText}<br>
                      Total diskon: Rp ${formatRupiah(totalDiscount)}<br>
                      Total akhir: <strong>Rp ${formatRupiah(finalTotal)}</strong>
                  `;
                  promoFeedback.className = 'form-text text-success';
                  promoValidated = true;
                  confirmBtn.disabled = false;
              } else {
                  promoFeedback.textContent = `✗ ${data.message}`;
                  promoFeedback.className = 'form-text text-danger';
                  promoValidated = false;
                  confirmBtn.disabled = true;
              }
          })
          .catch(() => {
              promoFeedback.textContent = 'Gagal memeriksa kode. Coba lagi.';
              promoFeedback.className = 'form-text text-danger';
              promoValidated = false;
              confirmBtn.disabled = true;
          })
          .finally(() => {
              promoInput.disabled = false;
              checkBtn.disabled = false;
          });
    });

    document.getElementById('confirmReferralBtn').addEventListener('click', function () {
        const promoCode = (document.getElementById('promoCodeInput').value || '').trim();

        if (promoCode && !promoValidated) {
            alert('Silakan klik "Cek" untuk memvalidasi kode promo terlebih dahulu.');
            return;
        }

        const referral = (document.getElementById('referralInput').value || '').trim();
        $('#referralModal').modal('hide');

        if (userRole !== "Penumpang") {
            $('#emailModal').modal('show');
            document.getElementById('confirmEmailBtn').setAttribute('data-referral', referral);
            document.getElementById('confirmEmailBtn').setAttribute('data-promo', promoCode);
        } else {
            proceedToBooking(referral, '', promoCode);
        }
    });

    document.getElementById('confirmEmailBtn').addEventListener('click', function () {
        const referral = this.getAttribute('data-referral') || '';
        const promoCode = this.getAttribute('data-promo') || '';
        const email = (document.getElementById('emailInput').value || '').trim();

        if (!email) {
            alert('Silakan masukkan email pemesan');
            return;
        }

        $('#emailModal').modal('hide');
        proceedToBooking(referral, email, promoCode);
    });

    function proceedToBooking(referral, email, promoCode = '') {
        document.querySelector('.loading-overlay').style.display = 'block';

        const seatsParam = encodeURIComponent(JSON.stringify(selectedSeats));
        const dataParam = encodeURIComponent(dataString);

        let url;

        if (referral) {
            url = "{{ route('pesan', ['kursi' => 'K_PLACE', 'data' => 'D_PLACE', 'referral' => 'R_PLACE']) }}"
                .replace('K_PLACE', seatsParam)
                .replace('D_PLACE', dataParam)
                .replace('R_PLACE', encodeURIComponent(referral));
        } else {
            url = "{{ route('pesan', ['kursi' => 'K_PLACE', 'data' => 'D_PLACE']) }}"
                .replace('K_PLACE', seatsParam)
                .replace('D_PLACE', dataParam);
        }

        // ✅ Now safely append query params
        const queryParams = [];
        if (promoCode) queryParams.push(`promo=${encodeURIComponent(promoCode).toUpperCase()}`);
        if (email) queryParams.push(`email=${encodeURIComponent(email)}`);

        if (queryParams.length > 0) {
            url += '?' + queryParams.join('&');
        }

        window.location.href = url;
    }

    function formatRupiah(angka) {
        return (angka || 0).toString().replace(/\B(?=(\d{3})+(?!\d))/g, '.');
    }
</script>
@endsection