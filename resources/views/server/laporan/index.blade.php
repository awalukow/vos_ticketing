@extends('layouts.app')
@section('title', 'Transaksi')
@section('heading', 'Transaksi')
@section('styles')
  <link href="{{ asset('vendor/datatables/dataTables.bootstrap4.min.css') }}" rel="stylesheet"/>
  <style>
    thead > tr > th, tbody > tr > td {
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

    .loading-overlay {
      position: fixed;
      top: 0;
      left: 0;
      width: 100%;
      height: 100%;
      background: rgba(255, 255, 255, 0.7);
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
    <div class="card-header py-3">
      <!-- Button trigger modal -->
      <button
        type="button"
        class="btn btn-primary btn-sm btn-add"
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
              <td>Kode Pemesanan</td>
              <td>Kelas</td>
              <td>Nama Pemesan</td>
              @unless((request()->is('ticket-gereja') || request()->is('ticket-fisik')))
              <td>Kontak Pemesan</td>
              <td>Tanggal Pemesanan</td>
              @endunless
              @unless(request()->is('ticket-fisik'))
              <td>Tanggal Expired</td>
              @endunless
              @unless((request()->is('ticket-gereja') || request()->is('ticket-fisik')))
              <td>Verified By</td>
              @endunless
              <th>Action</th>
            </tr>
          </thead>
          <tbody>
            @foreach ($pemesanan as $data)
              <tr>
                <td>{{ $loop->iteration }}</td>
                <td>
                  <!--<h5 class="card-title">{!! DNS1D::getBarcodeHTML($data->kode, "C128", 2, 30) !!}</h5>-->
                  <!--<p class="card-text">
                    <small class="text-muted">
                      
                    </small>
                  </p>-->
                  {{ $data->kode }}
                </td>
                <td>
                  <h5 class="card-title">{{ $data->rute->tujuan }}</h5>
                  <p class="card-text">
                    <small class="text-muted">
                      <!--{{ $data->rute->start }} - {{ $data->rute->end }}-->
                      {{ $data->rute->transportasi->category->name }}
                    </small>
                  </p>
                </td>
                <td>
                  @if($data->referral && (request()->is('ticket-gereja') || request()->is('ticket-fisik')))
                    <h5 class="card-title">{{ $data->referral }}</h5>
                  @else
                    <h5 class="card-title">{{ $data->penumpang->name }}</h5>
                  @endif
                  <p class="card-text">
                    <small class="text-muted">
                      Jumlah Tiket : {{ $data->kursi }}
                    </small>
                  </p>
                </td>
                @unless((request()->is('ticket-gereja') || request()->is('ticket-fisik')))
                <td>
                  <h10 class="card-text">+{{ $data->penumpang->username }}</h5><br>
                  <h10  class="card-text"><small>{{ $data->penumpang->email }}</small></h5>
                </td>
                <td>
                  <h5 class="card-title">{{ date('d F Y', strtotime($data->created_at)) }}</h5>
                  <p class="card-text">
                    <small class="text-muted">
                      {{ date('H:i', strtotime($data->created_at) + 7*3600) }} WIB
                    </small>
                  </p>
                </td>
                @endunless
                @unless(request()->is('ticket-fisik'))
                <td>
                  <h5 class="card-title">{{ date('d F Y', strtotime($data->expired_date)) }}</h5>
                  <p class="card-text">
                    <small class="text-muted">
                      {{ date('H:i', strtotime($data->created_at) + 7*3600) }} WIB
                    </small>
                  </p>
                </td>
                @endunless
                @unless((request()->is('ticket-gereja') || request()->is('ticket-fisik')))
                <td>
                  <!--<h5 class="card-title">{!! DNS1D::getBarcodeHTML($data->kode, "C128", 2, 30) !!}</h5>-->
                  <!--<p class="card-text">
                    <small class="text-muted">
                      
                    </small>
                  </p>-->
                    {{ optional($data->petugas)->name ?? '-' }}
                    <p class="card-text">
                        <small class="text-muted">
                            @if($data->expired_date > now() || $data->status == "Sudah Bayar" || $data->status_pembayaran != null)
                                <a style="color: {{ $data->status_pembayaran == 'Menunggu Verifikasi' ? '#231d96' : ($data->status == 'Belum Bayar' ? 'red' : 'green') }};">
                                    Status: {{ $data->status_pembayaran == 'Menunggu Verifikasi' ? $data->status_pembayaran : $data->status }}
                                </a>
                            @elseif($data->expired_date < now() && ($data->status != "Sudah Bayar" || $data->status_pembayaran == null))
                                <a style="color: #290506;">
                                    Status: TICKET EXPIRED
                                </a>
                            @endif
                        </small>
                    </p>
                @endunless
                </td>
                <td>
                  <a
                    href="{{ route('transaksi.show', $data->kode) }}"
                    class="btn btn-info btn-circle"
                    ><i class="fas fa-search-plus"></i
                  ></a>
                  @unless((request()->is('ticket-gereja') || request()->is('ticket-fisik')))
                  <a
                    href="https://api.whatsapp.com/send?phone={{$data->penumpang->username}}"
                    class="btn btn-info btn-circle"
                    ><i class="fa-brands fa-whatsapp"></i
                  ></a>
                  @endunless
                  @if (Auth::user()->level == "SuperAdmin" && $data->expired_date != '1970-01-01 23:59:59' && $data->status != "Sudah Bayar")
                  <form action="{{ route('cancelOrder', $data->id) }}" method="POST" style="display: inline;">
                    @csrf
                    @method('POST')
                    <button type="submit" class="btn btn-danger btn-circle">
                      <i class="fas fa-times"></i>
                    </button>
                  </form>
                  @elseif(Auth::user()->level == "SuperAdmin")
                  <button type="submit" class="btn btn-secondary btn-circle" disabled>
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
      $('#dataTable').DataTable();

      // Attach the submit event to the specific cancel order forms
      $('form[action*="cancelOrder"]').on('submit', function(e) {
        $('.loading-overlay').show();
      });
    });
  </script>
@endsection
