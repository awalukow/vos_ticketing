@extends('layouts.app')
@section('title', 'Detail Ticket')
@if (Auth::user()->level == "Admin")
  @section('heading', 'Detail Pemesanan')
@endif
@section('styles')
@php
    use Carbon\Carbon;
@endphp
@php
    $currentDateTime = Carbon::now();
    if (env('APP_ENV') == 'production') {
        $targetDateTime = Carbon::create(2024, 7, 20, 8, 0, 0);
    } else {
        $targetDateTime = $currentDateTime;
    }
@endphp
  <style>
    /* Compact card styling */
    .ticket-card {
      border: none;
      border-top: 3px solid #b11e1f;
      border-radius: 8px;
      box-shadow: 0 4px 12px rgba(0,0,0,0.1);
      overflow: hidden;
      margin-bottom: 20px;
    }
    
    .card-header {
      background-color: #f8f9fa;
      padding: 0.75rem 1rem;
      border-bottom: 1px solid #e9ecef;
    }
    
    .card-body {
      padding: 1rem;
      color: #333;
      font-size: 0.9rem;
    }
    
    .card-title {
      color: #4e73df;
      text-decoration: none;
      font-size: 1.1rem;
      font-weight: 600;
      text-align: center;
      text-transform: uppercase;
      letter-spacing: 1px;
      display: flex;
      align-items: center;
      justify-content: center;
    }
    
    .title-icon {
      font-size: 1.25rem;
      margin-right: 8px;
      color: #4e73df;
    }
    
    /* Route styling */
    .route-info {
      font-size: 1.1rem;
      font-weight: 600;
      text-align: center;
      color: #2c3e50;
      margin: 1rem 0;
      padding: 0.75rem;
      background-color: #f8f9ff;
      border-radius: 6px;
      border: 1px solid #e7eaff;
    }
    
    .route-arrow {
      color: #858796;
      font-size: 1rem;
      margin: 0 6px;
    }
    
    /* Booking details */
    .booking-header {
      display: flex;
      justify-content: space-between;
      align-items: center;
      margin-bottom: 1rem;
      padding-bottom: 0.75rem;
      border-bottom: 1px solid #e9ecef;
    }
    
    .booking-code {
      font-family: 'Courier New', monospace;
      font-size: 1.25rem;
      font-weight: 700;
      color: #b11e1f;
      letter-spacing: 1px;
    }
    
    .qr-container {
      padding: 6px;
      background-color: white;
      border-radius: 6px;
      border: 1px solid #e9ecef;
    }
    
    /* Event details */
    .event-info {
      text-align: center;
      margin: 0rem 0;
      padding: 0.75rem;
      background: linear-gradient(135deg, #6a11cb 0%, #2575fc 100%);
      color: white;
      border-radius: 6px;
    }
    
    .event-date {
      font-size: 1.1rem;
      font-weight: 600;
      margin-bottom: 0.25rem;
    }
    
    .event-time {
      font-size: 0.95rem;
      opacity: 0.9;
    }
    
    /* Details table */
    .details-table {
      width: 100%;
      border-collapse: collapse;
      margin: 1rem 0;
    }
    
    .details-table tr {
      border-bottom: 1px solid #e9ecef;
    }
    
    .details-table tr:last-child {
      border-bottom: none;
    }
    
    .details-table td {
      padding: 8px 0;
    }
    
    .label {
      font-weight: 600;
      color: #5a5c69;
      width: 40%;
    }
    
    .value {
      text-align: right;
      font-weight: 500;
      color: #2c3e50;
    }
    
    /* Status badges */
    .status-badge {
      display: inline-block;
      padding: 4px 8px;
      border-radius: 16px;
      font-size: 0.75rem;
      font-weight: 600;
      text-transform: uppercase;
      letter-spacing: 0.5px;
    }
    
    .status-pending {
      background-color: #fff3cd;
      color: #856404;
      border: 1px solid #ffeaa7;
    }
    
    .status-success {
      background-color: #d4edda;
      color: #155724;
      border: 1px solid #a3cfbb;
    }
    
    .status-expired {
      background-color: #f8d7da;
      color: #721c24;
      border: 1px solid #f1aeb5;
    }
    
    .status-verified {
      background-color: #cce5ff;
      color: #004085;
      border: 1px solid #b8daff;
    }
    
    .status-church {
      background-color: #d1ecf1;
      color: #0c5460;
      border: 1px solid #bee5eb;
    }
    
    .status-physical {
      background-color: #d4edda;
      color: #155724;
      border: 1px solid #a3cfbb;
    }
    
    /* Buttons */
    .btn-custom {
      padding: 6px 12px;
      border-radius: 6px;
      font-size: 0.8rem;
      font-weight: 500;
      text-transform: uppercase;
      letter-spacing: 0.5px;
      transition: all 0.2s ease;
      border: none;
      cursor: pointer;
    }
    
    .btn-primary {
      background-color: #4e73df;
      color: white;
    }
    
    .btn-success {
      background-color: #1cc88a;
      color: white;
    }
    
    .btn-secondary {
      background-color: #858796;
      color: white;
    }
    
    .btn-danger {
      background-color: #e74a3b;
      color: white;
    }
    
    .btn-custom:hover {
      transform: translateY(-1px);
      box-shadow: 0 2px 8px rgba(0,0,0,0.15);
    }
    
    .btn-block {
      display: block;
      width: 100%;
      text-align: center;
    }
    
    /* Check-in form */
    .checkin-container {
      background-color: #f8f9ff;
      border-radius: 6px;
      padding: 1rem;
      border: 1px solid #e7eaff;
    }
    
    .input-group {
      display: flex;
      align-items: center;
      justify-content: center;
      margin-bottom: 0.75rem;
    }
    
    .btn-counter {
      width: 32px;
      height: 32px;
      border-radius: 6px;
      display: flex;
      align-items: center;
      justify-content: center;
      font-size: 0.9rem;
      border: none;
      padding: 0;
    }
    
    .btn-counter:hover {
      transform: scale(1.05);
    }
    
    #checkin-count {
      width: 45px;
      height: 32px;
      text-align: center;
      font-size: 0.95rem;
      font-weight: 600;
      border: 1px solid #d1d3e2;
      border-radius: 6px;
      margin: 0 6px;
    }
    
    /* Contact buttons */
    .contact-grid {
      display: grid;
      grid-template-columns: repeat(3, 1fr);
      gap: 6px;
    }
    
    .contact-btn {
      padding: 8px 6px;
      border-radius: 6px;
      display: flex;
      flex-direction: column;
      align-items: center;
      justify-content: center;
      text-align: center;
      font-weight: 500;
      font-size: 0.75rem;
      border: none;
    }
    
    .contact-icon {
      font-size: 1.1rem;
      margin-bottom: 3px;
    }
    
    /* Alert styling */
    .alert {
      padding: 0.75rem 1rem;
      border-radius: 6px;
      margin-bottom: 1rem;
      font-size: 0.85rem;
    }
    
    .alert-info {
      background-color: #d4edff;
      color: #004085;
      border: 1px solid #b8daff;
    }
    
    .alert-warning {
      background-color: #fff3cd;
      color: #856404;
      border: 1px solid #ffeaa7;
    }
    
    .alert-success {
      background-color: #d4edda;
      color: #155724;
      border: 1px solid #a3cfbb;
    }
    
    /* Responsive design */
    @media (max-width: 768px) {
      .ticket-card {
        margin-bottom: 15px;
        border-radius: 6px;
      }
      
      .card-body {
        padding: 0.75rem;
      }
      
      .card-title {
        font-size: 1rem;
      }
      
      .title-icon {
        font-size: 1.1rem;
      }
      
      .route-info {
        font-size: 1rem;
        margin: 0.75rem 0;
        padding: 0.5rem;
      }
      
      .booking-header {
        flex-direction: column;
        gap: 0.75rem;
      }
      
      .booking-code {
        font-size: 1.1rem;
      }
      
      .event-info {
        font-size: 0.85rem;
      }
      
      .event-date {
        font-size: 1rem;
      }
      
      .event-time {
        font-size: 0.9rem;
      }
      
      .details-table {
        font-size: 0.85rem;
      }
      
      .label {
        width: 50%;
      }
      
      .contact-grid {
        grid-template-columns: 1fr;
      }
      
      .btn-counter {
        width: 30px;
        height: 30px;
      }
      
      #checkin-count {
        width: 40px;
        height: 30px;
      }
    }
  </style>
@endsection
@section('content')
  <div class="row justify-content-center">
    <div class="col-12">
      @if (Auth::user()->level != "Admin")
        <!--<a href="javascript:window.history.back();" class="btn btn-link mb-2" style="color: #4e73df; padding: 0; font-size: 0.9rem;">-->
        <a href="{{ url('/') }}" class="btn btn-link mb-2" style="color: #4e73df; padding: 0; font-size: 0.9rem;">
          <i class="fas fa-arrow-left mr-1"></i> Kembali
        </a>
      @endif
      
      <div class="card ticket-card shadow">
        <div class="card-header">
          <h5 class="card-title">
            <i class="fas fa-ticket-alt title-icon"></i>
            <span>Detail Ticket</span>
          </h5>
        </div>
        
        <div class="card-body">
          <div class="route-info">
            {{ $data->rute->transportasi->category->name }} 
            <i class="fas fa-long-arrow-alt-right route-arrow"></i> 
            {{ $data->rute->tujuan }}
          </div>
        </div>
        
        <div class="card-body">
          <div class="booking-header">
            <div>
              <div class="text-muted mb-1" style="font-size: 0.8rem;">Kode Booking</div>
              <div class="booking-code">{{ $data->kode }}</div>
            </div>
            <div class="qr-container">
              {!! DNS2D::getBarcodeHTML(redirect('/transaksi/'.$data->kode)->getTargetUrl(), "QRCODE", 4,4) !!}
            </div>
          </div>
        </div>
        
        <div class="card-body">
          <div class="event-info">
            <div class="event-date">Minggu, 09 November 2024</div>
            <div class="event-time">{{ date('H:i', strtotime($data->rute->jam)) }} WIB</div>
          </div>
        </div>
        
        <div class="card-body">
          <table class="details-table">
            <tr>
              <td class="label">Nama Kelas</td>
              <td class="value">{{ $data->rute->transportasi->name }} ({{ $data->rute->transportasi->kode }})</td>
            </tr>
            <tr>
              <td class="label">Nama Pemesan</td>
              <td class="value">{{ $data->penumpang->name }}</td>
            </tr>
            <tr>
              <td class="label">Nomor Kursi</td>
              <td class="value">{{ $data->kursi }}</td>
            </tr>
            <tr>
              <td class="label">Harga</td>
              <td class="value">Rp {{ number_format($data->total, 0, ',', '.') }}</td>
            </tr>
            
            <!-- Payment Status -->
            <tr>
              <td class="label">Status Pembayaran</td>
              <td class="value">
                @if (($data->expired_date >= now()) && $data->status_pembayaran == null || $data->status_pembayaran == "Sudah Verifikasi")
                  <span class="status-badge {{ $data->status == 'Belum Bayar' ? 'status-pending' : 'status-success' }}">
                    {{ $data->status }}
                  </span>
                @elseif (($data->expired_date >= now()) || $data->status_pembayaran == "Menunggu Verifikasi")
                  <span class="status-badge status-verified">{{ $data->status_pembayaran }}</span>
                @elseif ($data->isChurch == 1 && ($data->expired_date >= now() && $data->status_pembayaran != 'Sudah Bayar'))
                  <span class="status-badge status-church">TIKET GEREJA</span>
                @elseif ($data->isFisik == 1)
                  <span class="status-badge status-physical">TIKET FISIK</span>
                @else
                  <span class="status-badge status-expired">TIKET EXPIRED</span>
                @endif
              </td>
            </tr>
            
            <!-- Referral/Church/Physical info -->
            @if ($data->referral != null && $data->isChurch)
              <tr>
                <td class="label">Nama Gereja</td>
                <td class="value" style="text-transform: uppercase;"><strong>TIKET GEREJA</strong> | {{ $data->referral }}</td>
              </tr>
            @elseif ($data->referral != null && $data->isFisik)
              <tr>
                <td class="label">Nama Pembeli</td>
                <td class="value" style="text-transform: uppercase;"><strong>TIKET CETAK FISIK</strong> | {{ $data->referral }}</td>
              </tr>
            @elseif ($data->referral != null)
              <tr>
                <td class="label">Referral Singer</td>
                <td class="value" style="text-transform: uppercase;">{{ $data->referral }}</td>
              </tr>
            @endif
            
            <!-- Seat Check-in info -->
            @if ((Auth::user()->level == "Petugas" || Auth::user()->level == "SuperAdmin" || Auth::user()->level == "Admin") && $currentDateTime >= $targetDateTime)
              <tr>
                <td class="label"><strong>SEAT CHECK-IN</strong></td>
                <td class="value"><strong>{{ $data->seatCheckin }}/{{ $data->kursi }}</strong></td>
              </tr>
            @endif
          </table>
        </div>

        @if($data->status == "Sudah Bayar")
        <div class="card-body">
          <div class="event-info">
            <div class="event-date">Seat QR</div>
            <!-- Center the QR codes -->
            <div style="display: flex; justify-content: center; align-items: center; width: 100%; gap: 16px; flex-wrap: wrap;">
                @php
                  $seats = $data->kursi;
                  $seatArray = [];
                  
                  // Handle both string and array formats
                  if (is_string($seats) && substr($seats, 0, 1) === '[') {
                      $seatArray = json_decode($seats, true);
                      if (!is_array($seatArray)) {
                          $seatArray = [$seats];
                      }
                  } else {
                      $seatArray = [$seats];
                  }
                  
                  // Clean up seat names
                  $cleanedSeats = array_map(function($seat) {
                      return trim($seat, '[]"');
                  }, $seatArray);
                @endphp
                
                @foreach($cleanedSeats as $seat)
                  <div style="text-align: center; margin: 4px;">
                    <div style="background: white; padding: 4px; border-radius: 4px; display: inline-block;">
                      {!! DNS2D::getBarcodeHTML($data->kode . '_' . $seat, 'QRCODE', 6, 6) !!}
                    </div>
                    <div style="font-size: 0.7rem; margin-top: 2px;">{{ $seat }}</div>
                  </div>
                @endforeach
              </div>
          </div>
        </div>
        @endif
      
          @if (Auth::user()->level != "Penumpang")
            <div class="card-body">
              @if ($data->status_pembayaran != null)
                <a href="{{ asset('../storage/' . $data->bukti_pembayaran) }}" target="_blank" class="btn btn-success btn-custom btn-block">
                  <i class="fas fa-file-image mr-1"></i> Lihat Bukti
                </a>
              @elseif ((($data->expired_date >= now()) && $data->status_pembayaran == null) || ($data->status == "Belum Bayar" && $data->isChurch == 1))
                <button class="btn btn-secondary btn-custom btn-block" disabled>
                  <i class="fas fa-ban mr-1"></i> Lihat Bukti
                </button>
              @endif
            </div>
          @endif

        <!-- Check-in Form -->
        @if((Auth::user()->level == "Petugas" || Auth::user()->level == "SuperAdmin") && $data->seatCheckin < $data->kursi && $data->status == "Sudah Bayar" && $currentDateTime >= $targetDateTime)
          <div class="card-body">
            <div class="checkin-container">
              <form id="checkin-form" action="{{ route('laporan.updateCheckIn', $data->id) }}" method="POST">
                @csrf
                <div class="text-center mb-2">
                  <small class="text-muted">Seat Check-in</small>
                </div>
                <div class="input-group">
                  <button class="btn btn-danger btn-counter" type="button" id="minus-btn">
                    <i class="fas fa-minus"></i>
                  </button>
                  <input type="text" class="form-control text-center" id="checkin-count" name="seatNumber" value="1" readonly>
                  <button class="btn btn-success btn-counter" type="button" id="plus-btn">
                    <i class="fas fa-plus"></i>
                  </button>
                </div>
                <div id="loading" style="display: none; text-align: center; margin: 6px 0;">
                  <i class="fas fa-spinner fa-spin"></i>
                </div>
                <button type="submit" class="btn btn-primary btn-custom btn-block" id="submit-btn">
                  <i class="fas fa-check mr-1"></i> Submit
                </button>
              </form>
            </div>
          </div>
        @endif

        <!-- Verification Button -->
        @if (( $data->status == "Belum Bayar" && Auth::user()->level != "Penumpang" && $data->status_pembayaran == "Menunggu Verifikasi") || ($data->status_pembayaran == "Menunggu Verifikasi" && $data->isChurch == 1) || ($data->status_pembayaran == "Menunggu Verifikasi" && $data->isFisik == 1))
          <div class="card-body">
            <a href="{{ route('pembayaran', $data->id) }}" class="btn btn-primary btn-custom btn-block">
              <i class="fas fa-clipboard-check mr-1"></i> Verifikasi
            </a>
          </div>
        @endif

        <!-- Upload Bukti Pembayaran for Church -->
        @if (Auth::user()->level != "Penumpang" && $data->status == "Belum Bayar" && $data->status_pembayaran == null && $data->isChurch == 1 && $data->expired_date >= now())
          <div class="card-body">
            <form action="{{ route('upload.bukti.pembayaran', $data->id) }}" method="POST" enctype="multipart/form-data">
              @csrf
              <div class="form-group mb-2">
                <label for="bukti_pembayaran" class="form-label" style="font-size: 0.8rem;">Upload Bukti Pembayaran</label>
                <input type="file" class="form-control" name="bukti_pembayaran" required style="padding: 0.375rem 0.75rem; height: auto;">
              </div>
              <button type="submit" class="btn btn-primary btn-custom btn-block">
                <i class="fas fa-upload mr-1"></i> Upload
              </button>
            </form>
          </div>
        @endif

        <!-- Upload Bukti Pembayaran for Physical Ticket -->
        @if (Auth::user()->level != "Penumpang" && $data->status == "Belum Bayar" && $data->status_pembayaran == null && $data->isFisik == 1)
          <div class="card-body">
            <form action="{{ route('upload.bukti.pembayaran.fisik', $data->id) }}" method="POST" enctype="multipart/form-data">
              @csrf
              <div class="row g-2 mb-2">
                <div class="col-12">
                  <label for="bukti_pembayaran" class="form-label" style="font-size: 0.8rem;">Upload Bukti Pembayaran</label>
                  <input type="file" class="form-control" name="bukti_pembayaran" required style="padding: 0.375rem 0.75rem; height: auto;">
                </div>
                <div class="col-12">
                  <label for="referral" class="form-label" style="font-size: 0.8rem;">Identitas Pembeli</label>
                  <input type="text" class="form-control" name="referral" placeholder="Format: Nama-NomorHP" required style="padding: 0.375rem 0.75rem; height: auto;">
                </div>
              </div>
              <button type="submit" class="btn btn-primary btn-custom btn-block">
                <i class="fas fa-upload mr-1"></i> Upload
              </button>
            </form>
          </div>
        @endif
        
        <!-- Upload Bukti Pembayaran for Customer Online -->
        @if (
            ($data->expired_date >= now()) &&
            $data->status == "Belum Bayar" &&
            Auth::user()->level == "Penumpang" &&
            $data->status_pembayaran == null
        )

            @php
                // Use expired_date from the database for countdown and display
                $paymentExpiry = \Carbon\Carbon::parse($data->expired_date)->tz('Asia/Jakarta');
            @endphp

            <div class="card-body">

    <!-- Wrapper with flexbox -->
    <div class="d-flex flex-wrap">
        <!-- Left side: Payment instructions and countdown -->
        <div class="flex-grow-1" style="min-width: 0;">
            <!-- Payment Instructions -->
            <div class="alert alert-info mb-3 p-3">
                <strong>Instruksi Pembayaran:</strong><br>
                Bank Jago <strong>1058 7839 6486</strong> a.n Ratno Juniarto MS<br>
                Atau <strong>scan QR dibawah untuk pembayaran via Gopay/OVO/Dana/LinkAja/m-Banking</strong><br>
                Nominal: <strong>Rp {{ number_format($data->total, 0, ',', '.') }}</strong><br>
                Batas Waktu Pembayaran: <strong>{{ $paymentExpiry->locale('id')->isoFormat('LLLL') }}</strong>
            </div>
            <!-- Right side: QR Code -->
            <div class="ms-3 d-flex align-items-center" align="center">
                <img src="{{ asset('img/qris-vos-gopay.png') }}" alt="QR Code" style="max-width: 150px; height: auto;">
            </div>
            <!-- Countdown Timer -->
            <div class="alert alert-warning mb-3 p-3">
                <strong>Hitung Mundur Pembayaran:</strong><br>
                <span id="countdown" style="font-weight: bold; font-size: 1.2rem;"></span>
            </div>
        </div>
    </div>

    <!-- Upload Form -->
    <form action="{{ route('upload.bukti.pembayaran', $data->id) }}" method="POST" enctype="multipart/form-data">
        @csrf
        <div class="form-group mb-2">
            <label for="bukti_pembayaran" class="form-label" style="font-size: 0.8rem;">Upload Bukti Pembayaran</label>
            <input type="file" class="form-control" name="bukti_pembayaran" required style="padding: 0.375rem 0.75rem; height: auto;">
        </div>
        <button type="submit" class="btn btn-primary btn-custom btn-block">
            <i class="fas fa-upload mr-1"></i> Upload
        </button>
    </form>
</div>


            <!-- Countdown Timer Script -->
            <script>
                document.addEventListener("DOMContentLoaded", function () {
                    const expiryTime = new Date("{{ $paymentExpiry->toIso8601String() }}").getTime();
                    const countdownElement = document.getElementById("countdown");

                    const interval = setInterval(function () {
                        const now = new Date().getTime();
                        const distance = expiryTime - now;

                        if (distance <= 0) {
                            clearInterval(interval);
                            countdownElement.innerHTML = "Waktu pembayaran telah habis.";
                            return;
                        }

                        const days = Math.floor(distance / (1000 * 60 * 60 * 24));
                        const hours = Math.floor((distance % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
                        const minutes = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));
                        const seconds = Math.floor((distance % (1000 * 60)) / 1000);

                        let display = "";
                        if (days > 0) display += days + " hari ";
                        display += hours + " jam " + minutes + " menit " + seconds + " detik";

                        countdownElement.innerHTML = display;
                    }, 1000);
                });
            </script>

        @endif


        <!-- Customer Payment Verification Status -->
        @if (($data->status == "Belum Bayar" && $data->status_pembayaran == "Menunggu Verifikasi") && Auth::user()->level == "Penumpang")
          <div class="card-body">
            <div class="alert alert-warning mb-3 p-2">
              <small>
                <strong>Menunggu Verifikasi</strong><br>
                Bukti pembayaran telah dikirim. Mohon menunggu verifikasi dari admin.
              </small>
            </div>
            
            <a href="{{ asset('../storage/' . $data->bukti_pembayaran) }}" target="_blank" class="btn btn-success btn-custom btn-block mb-1">
              <i class="fas fa-file-image mr-1"></i> Lihat Bukti
            </a>
            <a href="https://api.whatsapp.com/send?phone=6285823536364" target="_blank" class="btn btn-success btn-custom btn-block">
              <i class="fab fa-whatsapp mr-1"></i> Hubungi Admin
            </a>
          </div>
        @endif

        <!-- Successful Payment for Customer -->
        @if (($data->status == "Sudah Bayar") && Auth::user()->level == "Penumpang")
          <div class="card-body">
            <div class="alert alert-success mb-3 p-2">
              <small>
                <strong>Pembayaran Berhasil!</strong><br>
                Tunjukkan halaman ini saat mendatangi venue konser.
              </small>
            </div>
            
            <a href="{{ asset('../storage/' . $data->bukti_pembayaran) }}" target="_blank" class="btn btn-success btn-custom btn-block mb-1">
              <i class="fas fa-file-image mr-1"></i> Lihat Bukti
            </a>
            <a href="https://api.whatsapp.com/send?phone=6285823536364" target="_blank" class="btn btn-success btn-custom btn-block">
              <i class="fab fa-whatsapp mr-1"></i> Hubungi Admin
            </a>
          </div>
        @endif

        <!-- Expired Ticket Actions -->
        @if($data->expired_date < now() && Auth::user()->level == "Penumpang")
          <div class="card-body text-center">
            <a href="https://api.whatsapp.com/send?phone=6285823536364" target="_blank" class="btn btn-success btn-custom btn-block">
              <i class="fab fa-whatsapp mr-1"></i> Hubungi Admin
            </a>
          </div>
        @elseif(Auth::user()->level != "Penumpang" && Auth::user()->level != "Petugas" && $data->isChurch == 0 && $data->isFisik == 0)
          <div class="card-body">
            <div class="contact-grid">
              <a href="https://api.whatsapp.com/send?phone={{$data->penumpang->username}}" target="_blank" class="contact-btn btn-success">
                <i class="fab fa-whatsapp contact-icon"></i>
                WA
              </a>
              <a href="mailto:{{$data->penumpang->email}}" target="_blank" class="contact-btn btn-success">
                <i class="far fa-envelope contact-icon"></i>
                Email
              </a>
              <a href="tel:+{{$data->penumpang->username}}" class="contact-btn btn-success">
                <i class="fas fa-phone contact-icon"></i>
                Telp
              </a>
            </div>
          </div>
        @endif
      </div>
    </div>
  </div>

  <script>
  document.addEventListener('DOMContentLoaded', function () {
    const minusBtn = document.getElementById('minus-btn');
    const plusBtn = document.getElementById('plus-btn');
    const checkinCount = document.getElementById('checkin-count');
    const submitBtn = document.getElementById('submit-btn');
    const loadingDiv = document.getElementById('loading');
    const checkinForm = document.getElementById('checkin-form');

    minusBtn.addEventListener('click', function () {
      let currentValue = parseInt(checkinCount.value);
      if (currentValue > 0) {
        checkinCount.value = currentValue - 1;
      }
    });

    plusBtn.addEventListener('click', function () {
      let currentValue = parseInt(checkinCount.value);
      if (currentValue < {{ $data->kursi }} - {{$data->seatCheckin}}) {
        checkinCount.value = currentValue + 1;
      }
    });

    checkinForm.addEventListener('submit', function () {
      submitBtn.disabled = true;
      loadingDiv.style.display = 'block';
    });
  });
  </script>
@endsection