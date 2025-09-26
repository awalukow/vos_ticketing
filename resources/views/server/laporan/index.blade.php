@extends('layouts.app')
@section('title', 'Transaksi')
@section('heading', 'Transaksi')
@section('styles')
  <link href="{{ asset('vendor/datatables/dataTables.bootstrap4.min.css') }}" rel="stylesheet"/>
  <style>
    /* === DESKTOP TABLE STYLING (ORIGINAL + IMPROVED) === */
    .table {
      border-collapse: separate;
      border-spacing: 0;
      width: 100%;
      margin: 0;
    }
    
    thead > tr > th {
      background-color: #f8f9fa;
      color: #495057;
      font-weight: 600;
      padding: 15px 12px;
      text-align: left;
      border-bottom: 2px solid #e9ecef;
      vertical-align: middle !important;
      white-space: nowrap;
    }
    
    tbody > tr {
      transition: all 0.2s ease-in-out;
    }
    
    tbody > tr:hover {
      background-color: #f8f9ff !important;
      transform: translateY(-1px);
      box-shadow: 0 4px 8px rgba(0,0,0,0.05);
    }
    
    tbody > tr > td {
      padding: 16px 12px;
      border-bottom: 1px solid #e9ecef;
      vertical-align: middle !important;
      word-wrap: break-word;
      white-space: normal;
    }

    /* === STATUS BADGES (DESKTOP) === */
    .status-badge {
      display: inline-block;
      padding: 4px 10px;
      border-radius: 20px;
      font-size: 0.8rem;
      font-weight: 500;
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

    /* === ACTION BUTTONS (DESKTOP) === */
    .btn-action {
      width: 36px;
      height: 36px;
      border-radius: 50%;
      display: flex;
      align-items: center;
      justify-content: center;
      margin: 0 2px;
      transition: all 0.2s ease;
      border: none;
      font-size: 0.9rem;
    }
    
    .btn-action:hover {
      transform: scale(1.1);
    }
    
    .btn-info {
      background-color: #4e73df;
      color: white;
    }
    
    .btn-danger {
      background-color: #e74a3b;
      color: white;
    }
    
    .btn-secondary {
      background-color: #858796;
      color: white;
    }

    /* === BOOKING CODE === */
    .booking-code {
      font-family: 'Courier New', monospace;
      font-weight: 700;
      letter-spacing: 1px;
      color: #4e73df;
    }

    /* === CONTACT INFO === */
    .contact-info {
      font-size: 0.95rem;
    }
    
    .contact-phone {
      font-weight: 600;
      color: #2c3e50;
    }
    
    .contact-email {
      color: #6c757d;
      font-style: italic;
    }

    /* === DATE/TIME === */
    .date-info {
      font-weight: 500;
      color: #2c3e50;
    }
    
    .time-info {
      color: #6c757d;
      font-size: 0.85rem;
    }

    /* === TICKET COUNT === */
    .ticket-count {
      font-weight: 500;
      color: #5a5c69;
    }

    /* === ROUTE/CATEGORY === */
    .route-info {
      font-weight: 500;
      color: #2c3e50;
    }
    
    .category-info {
      color: #6c757d;
      font-size: 0.85rem;
    }

    /* === MOBILE CARD LAYOUT === */
    @media (max-width: 768px) {
      .desktop-only {
        display: none !important;
      }

      .mobile-card-container {
        padding: 0;
      }

      .mobile-card {
        background: #fff;
        border: 1px solid #e9ecef;
        border-radius: 12px;
        padding: 16px;
        margin-bottom: 16px;
        box-shadow: 0 2px 6px rgba(0,0,0,0.03);
        transition: transform 0.2s ease;
      }

      .mobile-card:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(0,0,0,0.05);
      }

      .card-header-row {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 12px;
        padding-bottom: 8px;
        border-bottom: 1px solid #eee;
      }

      .card-title {
        font-size: 1rem;
        font-weight: 600;
        color: #2c3e50;
        margin: 0;
      }

      .card-subtitle {
        font-size: 0.85rem;
        color: #6c757d;
        margin: 4px 0 0;
      }

      .card-section {
        margin: 12px 0;
      }

      .card-label {
        font-weight: 600;
        color: #495057;
        font-size: 0.85rem;
        margin-bottom: 4px;
      }

      .card-value {
        font-size: 0.9rem;
        color: #2c3e50;
        word-wrap: break-word;
      }

      .booking-code {
        font-family: 'Courier New', monospace;
        font-weight: 700;
        letter-spacing: 1px;
        color: #4e73df;
        font-size: 1rem;
      }

      .contact-info {
        font-size: 0.85rem;
      }

      .contact-phone {
        font-weight: 600;
        color: #2c3e50;
      }

      .contact-email {
        color: #6c757d;
        font-style: italic;
      }

      .date-info {
        font-weight: 500;
        color: #2c3e50;
      }

      .time-info {
        color: #6c757d;
        font-size: 0.8rem;
      }

      .ticket-count {
        font-weight: 500;
        color: #5a5c69;
        font-size: 0.85rem;
      }

      .route-info {
        font-weight: 500;
        color: #2c3e50;
      }

      .category-info {
        color: #6c757d;
        font-size: 0.8rem;
      }

      .status-badge {
        display: inline-block;
        padding: 4px 10px;
        border-radius: 20px;
        font-size: 0.75rem;
        font-weight: 500;
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

      .btn-action {
        width: 32px;
        height: 32px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        margin: 0 4px;
        transition: all 0.2s ease;
        border: none;
        font-size: 0.8rem;
      }

      .btn-action:hover {
        transform: scale(1.1);
      }

      .btn-info {
        background-color: #4e73df;
        color: white;
      }

      .btn-danger {
        background-color: #e74a3b;
        color: white;
      }

      .btn-secondary {
        background-color: #858796;
        color: white;
      }

      .action-row {
        display: flex;
        justify-content: center;
        gap: 8px;
        margin-top: 12px;
        padding-top: 12px;
        border-top: 1px solid #eee;
      }

      .show-details-btn {
        background: #f8f9fa;
        border: 1px solid #dee2e6;
        border-radius: 6px;
        padding: 6px 12px;
        font-size: 0.85rem;
        margin-top: 8px;
        cursor: pointer;
        transition: background 0.2s;
        width: 100%;
        text-align: center;
      }

      .show-details-btn:hover {
        background: #e9ecef;
      }

      .mobile-details {
        display: none;
        margin-top: 12px;
        padding-top: 12px;
        border-top: 1px dashed #ddd;
      }
    }

    /* === LOADING OVERLAY === */
    .loading-overlay {
      position: fixed;
      top: 0;
      left: 0;
      width: 100%;
      height: 100%;
      background: rgba(255, 255, 255, 0.8);
      display: none;
      justify-content: center;
      align-items: center;
      z-index: 9999;
    }
    
    .spinner-border {
      width: 3rem;
      height: 3rem;
      border-width: .3em;
    }
  </style>
@endsection
@section('content')
  <div class="card shadow mb-4">
    <div class="card-header py-3 d-flex justify-content-between align-items-center">
      <h6 class="font-weight-bold text-primary mb-0">Daftar Transaksi</h6>
      <button onclick="window.location.href='{{ route('pemesanan.export') }}'" 
              type="button" 
              class="btn btn-success btn-sm">
          <i class="fas fa-file-excel"></i> Export Excel
      </button>
    </div>
    <div class="card-body">

      <!-- DESKTOP VIEW -->
      <div class="desktop-only">
        <div class="table-responsive">
          <table class="table table-hover" id="dataTable" width="100%" cellspacing="0">
            <thead>
              <tr>
                <th style="width: 5%;">No</th>
                <th style="width: 15%;">Kode Pemesanan</th>
                <th style="width: 20%;">Kelas & Rute</th>
                <th style="width: 18%;">Nama Pemesan</th>
                @unless((request()->is('ticket-gereja') || request()->is('ticket-fisik')))
                <th style="width: 15%;">Kontak Pemesan</th>
                <th style="width: 12%;">Tanggal Pemesanan</th>
                @endunless
                @unless(request()->is('ticket-fisik'))
                <th style="width: 12%;">Tanggal Expired</th>
                @endunless
                @unless((request()->is('ticket-gereja') || request()->is('ticket-fisik')))
                <th style="width: 18%;">Status & Verifikasi</th>
                @endunless
                <th style="width: 10%;">Action</th>
              </tr>
            </thead>
            <tbody>
              @foreach ($pemesanan as $data)
                <tr>
                  <td data-label="No">{{ $loop->iteration }}</td>
                  <td data-label="Kode Pemesanan">
                    <span class="booking-code">{{ $data->kode }}</span>
                  </td>
                  <td data-label="Kelas & Rute">
                    <h6 class="card-title route-info">{{ $data->rute->tujuan }}</h6>
                    <p class="card-text category-info">
                      {{ $data->rute->transportasi->category->name }}
                    </p>
                  </td>
                  <td data-label="Nama Pemesan">
                    @if($data->referral && (request()->is('ticket-gereja') || request()->is('ticket-fisik')))
                      <h6 class="card-title">{{ $data->referral }}</h6>
                    @else
                      <h6 class="card-title">{{ $data->penumpang->name }}</h6>
                    @endif
                    <p class="card-text ticket-count">
                      Jumlah Tiket : {{ $data->kursi }}
                    </p>
                  </td>
                  @unless((request()->is('ticket-gereja') || request()->is('ticket-fisik')))
                  <td data-label="Kontak Pemesan">
                    <div class="contact-info">
                      <div class="contact-phone">+{{ $data->penumpang->username }}</div>
                      <div class="contact-email">{{ $data->penumpang->email }}</div>
                    </div>
                  </td>
                  <td data-label="Tanggal Pemesanan">
                    <div class="date-info">{{ date('d F Y', strtotime($data->created_at)) }}</div>
                    <div class="time-info">{{ date('H:i', strtotime($data->created_at) + 7*3600) }} WIB</div>
                  </td>
                  @endunless
                  @unless(request()->is('ticket-fisik'))
                  <td data-label="Tanggal Expired">
                    <div class="date-info">{{ date('d F Y', strtotime($data->expired_date)) }}</div>
                    <div class="time-info">{{ date('H:i', strtotime($data->expired_date) + 7*3600) }} WIB</div>
                  </td>
                  @endunless
                  @unless((request()->is('ticket-gereja') || request()->is('ticket-fisik')))
                  <td data-label="Status & Verifikasi">
                    <div>
                      {{ optional($data->petugas)->name ?? '-' }}
                    </div>
                    <div class="mt-2">
                      @if($data->expired_date > now() || $data->status == "Sudah Bayar" || $data->status_pembayaran != null)
                          @if($data->status_pembayaran == 'Menunggu Verifikasi')
                              <span class="status-badge status-pending">Menunggu Verifikasi</span>
                          @elseif($data->status == 'Sudah Bayar')
                              <span class="status-badge status-success">Sudah Bayar</span>
                          @else
                              <span class="status-badge status-verified">{{ $data->status }}</span>
                          @endif
                      @elseif($data->expired_date < now() && ($data->status != "Sudah Bayar" || $data->status_pembayaran == null))
                          <span class="status-badge status-expired">TICKET EXPIRED</span>
                      @endif
                    </div>
                  </td>
                  @endunless
                  <td data-label="Action" class="text-center">
                    <a href="{{ route('transaksi.show', $data->kode) }}" class="btn-action btn-info" title="Detail">
                      <i class="fas fa-search-plus"></i>
                    </a>
                    @unless((request()->is('ticket-gereja') || request()->is('ticket-fisik')))
                    <a href="https://api.whatsapp.com/send?phone={{$data->penumpang->username}}" class="btn-action btn-info" title="WhatsApp">
                      <i class="fa-brands fa-whatsapp"></i>
                    </a>
                    @endunless
                    @if (Auth::user()->level == "SuperAdmin" && $data->expired_date != '1970-01-01 23:59:59' && $data->status != "Sudah Bayar")
                    <form action="{{ route('cancelOrder', $data->id) }}" method="POST" style="display: inline;">
                      @csrf
                      @method('POST')
                      <button type="submit" class="btn-action btn-danger" title="Cancel">
                        <i class="fas fa-times"></i>
                      </button>
                    </form>
                    @elseif(Auth::user()->level == "SuperAdmin")
                    <button type="submit" class="btn-action btn-secondary" disabled title="Cannot Cancel">
                      <i class="fas fa-times"></i>
                    </button>
                    @endif
                  </td>
                </tr>
              @endforeach
            </tbody>
          </table>
        </div>
      </div>

      <!-- MOBILE VIEW -->
      <div class="mobile-card-container d-block d-md-none">
        @foreach ($pemesanan as $data)
          <div class="mobile-card">
            <div class="card-header-row">
              <div>
                <span class="card-title">#{{ $loop->iteration }}</span><br>
                <span class="booking-code">{{ $data->kode }}</span>
              </div>
              <div>
                <span class="status-badge {{ 
                  $data->expired_date > now() || $data->status == 'Sudah Bayar' || $data->status_pembayaran != null
                    ? ($data->status_pembayaran == 'Menunggu Verifikasi' ? 'status-pending' : ($data->status == 'Sudah Bayar' ? 'status-success' : 'status-verified'))
                    : 'status-expired'
                }}">
                  {{
                    $data->expired_date > now() || $data->status == 'Sudah Bayar' || $data->status_pembayaran != null
                      ? ($data->status_pembayaran == 'Menunggu Verifikasi' ? 'Menunggu Verifikasi' : ($data->status == 'Sudah Bayar' ? 'Sudah Bayar' : $data->status))
                      : 'TICKET EXPIRED'
                  }}
                </span>
              </div>
            </div>

            <div class="card-section">
              <div class="card-label">Kelas & Rute</div>
              <div class="card-value route-info">{{ $data->rute->tujuan }}</div>
              <div class="card-value category-info">{{ $data->rute->transportasi->category->name }}</div>
            </div>

            <div class="card-section">
              <div class="card-label">Nama Pemesan</div>
              <div class="card-value">
                @if($data->referral && (request()->is('ticket-gereja') || request()->is('ticket-fisik')))
                  {{ $data->referral }}
                @else
                  {{ $data->penumpang->name }}
                @endif
              </div>
              <div class="card-value ticket-count">Jumlah Tiket: {{ $data->kursi }}</div>
            </div>

            @unless((request()->is('ticket-gereja') || request()->is('ticket-fisik')))
            <div class="card-section mobile-details">
              <div class="card-label">Kontak Pemesan</div>
              <div class="contact-info">
                <div class="contact-phone">+{{ $data->penumpang->username }}</div>
                <div class="contact-email">{{ $data->penumpang->email }}</div>
              </div>
            </div>
            <div class="card-section mobile-details">
              <div class="card-label">Tanggal Pemesanan</div>
              <div class="date-info">{{ date('d F Y', strtotime($data->created_at)) }}</div>
              <div class="time-info">{{ date('H:i', strtotime($data->created_at) + 7*3600) }} WIB</div>
            </div>
            @endunless

            @unless(request()->is('ticket-fisik'))
            <div class="card-section mobile-details">
              <div class="card-label">Tanggal Expired</div>
              <div class="date-info">{{ date('d F Y', strtotime($data->expired_date)) }}</div>
              <div class="time-info">{{ date('H:i', strtotime($data->expired_date) + 7*3600) }} WIB</div>
            </div>
            @endunless

            @unless((request()->is('ticket-gereja') || request()->is('ticket-fisik')))
            <div class="card-section mobile-details">
              <div class="card-label">Verifikasi Oleh</div>
              <div class="card-value">{{ optional($data->petugas)->name ?? '-' }}</div>
            </div>
            @endunless

            <div class="action-row">
              <a href="{{ route('transaksi.show', $data->kode) }}" class="btn-action btn-info" title="Detail">
                <i class="fas fa-search-plus"></i>
              </a>
              @unless((request()->is('ticket-gereja') || request()->is('ticket-fisik')))
              <a href="https://api.whatsapp.com/send?phone={{$data->penumpang->username}}" class="btn-action btn-info" title="WhatsApp">
                <i class="fa-brands fa-whatsapp"></i>
              </a>
              @endunless
              @if (Auth::user()->level == "SuperAdmin" && $data->expired_date != '1970-01-01 23:59:59' && $data->status != "Sudah Bayar")
              <form action="{{ route('cancelOrder', $data->id) }}" method="POST" style="display: inline;">
                @csrf
                @method('POST')
                <button type="submit" class="btn-action btn-danger" title="Cancel">
                  <i class="fas fa-times"></i>
                </button>
              </form>
              @elseif(Auth::user()->level == "SuperAdmin")
              <button type="submit" class="btn-action btn-secondary" disabled title="Cannot Cancel">
                <i class="fas fa-times"></i>
              </button>
              @endif
            </div>

            <!-- Show Details Toggle -->
            <button class="show-details-btn" onclick="toggleDetails(this)">
              Lihat Detail
            </button>
          </div>
        @endforeach
      </div>

    </div>
  </div>
  
  <div class="loading-overlay">
    <div class="spinner-border text-primary" role="status">
      <span class="sr-only">Loading...</span>
    </div>
  </div>
@endsection
@section('script')
  <script src="{{ asset('vendor/datatables/jquery.dataTables.min.js') }}"></script>
  <script src="{{ asset('vendor/datatables/dataTables.bootstrap4.min.js') }}"></script>
  <script>
    $(document).ready(function() {
      // Initialize DataTable only on desktop
      if ($(window).width() > 768) {
        $('#dataTable').DataTable({
          "pageLength": 25,
          "lengthMenu": [[10, 25, 50, -1], [10, 25, 50, "All"]],
          "order": [[0, "asc"]],
          "language": {
            "lengthMenu": "Tampilkan _MENU_ data per halaman",
            "zeroRecords": "Data tidak ditemukan",
            "info": "Halaman _PAGE_ dari _PAGES_",
            "infoEmpty": "Data tidak tersedia",
            "infoFiltered": "(filtered from _MAX_ total records)",
            "search": "Cari:",
            "paginate": {
              "first": "Pertama",
              "last": "Terakhir",
              "next": "Selanjutnya",
              "previous": "Sebelumnya"
            }
          },
          "columnDefs": [
            { "targets": "_all", "className": "dt-left" }
          ]
        });
      }

      // Add hover effect for better UX
      $('#dataTable tbody').on('mouseenter', 'tr', function() {
        $(this).css('cursor', 'pointer');
      });

      // Loading overlay for cancel actions
      $('form[action*="cancelOrder"]').on('submit', function(e) {
        $('.loading-overlay').show();
      });
    });

    // Toggle extra details on mobile
    function toggleDetails(btn) {
      const card = btn.closest('.mobile-card');
      const details = card.querySelectorAll('.mobile-details');
      const isVisible = details[0].style.display === 'block';

      details.forEach(el => {
        el.style.display = isVisible ? 'none' : 'block';
      });

      btn.textContent = isVisible ? 'Lihat Detail' : 'Sembunyikan';
    }
  </script>
@endsection