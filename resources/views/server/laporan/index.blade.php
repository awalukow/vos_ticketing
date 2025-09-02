@extends('layouts.app')
@section('title', 'Transaksi')
@section('heading', 'Transaksi')
@section('styles')
  <link href="{{ asset('vendor/datatables/dataTables.bootstrap4.min.css') }}" rel="stylesheet"/>
  <style>
    /* Modern table styling */
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
    }
    
    /* Card styling for mobile */
    .card-title {
      font-size: 1.1rem;
      font-weight: 600;
      margin: 0;
      color: #2c3e50;
    }
    
    .card-subtitle {
      font-size: 0.95rem;
      color: #6c757d;
      margin: 4px 0 0;
    }
    
    .card-text {
      margin: 0;
    }
    
    /* Status badges */
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
    
    /* Button styling */
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
    
    /* Responsive design */
    @media (max-width: 768px) {
      .table-responsive {
        border-radius: 8px;
        overflow: hidden;
      }
      
      .table thead {
        display: none;
      }
      
      .table tbody tr {
        display: block;
        margin-bottom: 15px;
        border: 1px solid #e9ecef;
        border-radius: 8px;
        padding: 10px;
      }
      
      .table tbody td {
        display: flex;
        padding: 8px 0;
        border: none;
      }
      
      .table tbody td:before {
        content: attr(data-label);
        width: 140px;
        font-weight: 600;
        color: #5a5c69;
        padding-right: 10px;
      }
      
      .btn-action {
        width: 32px;
        height: 32px;
      }
    }
    
    /* Loading overlay */
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
    
    /* Additional styling */
    .booking-code {
      font-family: 'Courier New', monospace;
      font-weight: 700;
      letter-spacing: 1px;
      color: #4e73df;
    }
    
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
    
    .date-info {
      font-weight: 500;
      color: #2c3e50;
    }
    
    .time-info {
      color: #6c757d;
      font-size: 0.85rem;
    }
    
    .ticket-count {
      font-weight: 500;
      color: #5a5c69;
    }
    
    .route-info {
      font-weight: 500;
      color: #2c3e50;
    }
    
    .category-info {
      color: #6c757d;
      font-size: 0.85rem;
    }
  </style>
@endsection
@section('content')
  <div class="card shadow mb-4">
    <div class="card-header py-3 d-flex justify-content-between align-items-center">
      <h6 class="font-weight-bold text-primary mb-0">Daftar Transaksi</h6>
      <button type="button" class="btn btn-primary btn-sm btn-add">
        <i class="fas fa-plus"></i> Tambah
      </button>
    </div>
    <div class="card-body">
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
                @endunless
                </td>
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
        }
      });

      // Add hover effect for better UX
      $('#dataTable tbody').on('mouseenter', 'tr', function() {
        $(this).css('cursor', 'pointer');
      });

      // Loading overlay for cancel actions
      $('form[action*="cancelOrder"]').on('submit', function(e) {
        $('.loading-overlay').show();
      });
    });
  </script>
@endsection