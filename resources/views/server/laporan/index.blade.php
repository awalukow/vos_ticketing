@extends('layouts.app')
@section('title', 'Transaksi')
@section('heading', 'Transaksi')
@section('styles')
  <link href="{{ asset('vendor/datatables/dataTables.bootstrap4.min.css') }}" rel="stylesheet"/>
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
              @unless(request()->is('ticket-gereja'))
              <td>Kontak Pemesan</td>
              <td>Tanggal Pemesanan</td>
              @endunless
              <td>Tanggal Expired</td>
              @unless(request()->is('ticket-gereja'))
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
                  @if(!$data->referral){
                    <h5 class="card-title">{{ $data->penumpang->name }}</h5>
                  }
                  @else{
                    <h5 class="card-title">{{ $data->referral }}</h5>
                  }
                  @endif
                  <p class="card-text">
                    <small class="text-muted">
                      Jumlah Tiket : {{ $data->kursi }}
                    </small>
                  </p>
                </td>
                @unless(request()->is('ticket-gereja'))
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
                <td>
                  <h5 class="card-title">{{ date('d F Y', strtotime($data->expired_date)) }}</h5>
                  <p class="card-text">
                    <small class="text-muted">
                      {{ date('H:i', strtotime($data->created_at) + 7*3600) }} WIB
                    </small>
                  </p>
                </td>
                @unless(request()->is('ticket-gereja'))
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
                  <a
                    href="https://api.whatsapp.com/send?phone={{$data->penumpang->username}}"
                    class="btn btn-info btn-circle"
                    ><i class="fa-brands fa-whatsapp"></i
                  ></a>
                </td>
              </tr>
            @endforeach
          </tbody>
        </table>
      </div>
    </div>
  </div>
@endsection
@section('script')
  <script src="{{ asset('vendor/datatables/jquery.dataTables.min.js') }}"></script>
  <script src="{{ asset('vendor/datatables/dataTables.bootstrap4.min.js') }}"></script>
  <script>
    $(document).ready(function() {
      $('#dataTable').DataTable();
    });
  </script>
@endsection
