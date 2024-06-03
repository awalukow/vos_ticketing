@extends('layouts.app')
@section('title', 'Detail Ticket')
@if (Auth::user()->level == "Admin")
  @section('heading', 'Detail Pemesanan')
@endif
@section('styles')
  <style>
    .card-body {
      padding: .5rem 1rem;
      color: #000;
      border-bottom: 1px solid #e3e6f0;
    }

    .title {
      color: #4e73df;
      text-decoration: none;
      font-size: 1.2rem;
      font-weight: 800;
      text-align: center;
      text-transform: uppercase;
      z-index: 1;
      align-items: center;
      justify-content: center;
      display: flex;
    }

    .title .title-text {
      display: inline;
    }

    .table {
      margin-bottom: 0;
      color: #000;
    }

    .table td {
      padding: 0;
      border-top: none;
    }
  </style>
@endsection
@section('content')
  <div class="row justify-content-center" style="margin-bottom: 35px;">
    @if (Auth::user()->level != "Admin")
    <div class="col-12" style="margin-top: -15px">
      <a href="javascript:window.history.back();" class="text-white btn"><i class="fas fa-arrow-left mr-2"></i> Kembali</a>
    @else
    <div class="col-12">
    @endif
      <div class="card shadow h-100" style="border-top: .25rem solid #b11e1f">
        <div class="card-body">
          <div class="row no-gutters align-items-center justify-content-center">
            <div class="col h5 font-weight-bold" style="margin-bottom: 0">Detail Pemesanan</div>
            <div class="col-auto">
              <span class="title">
                <div class="title-icon rotate-n-15">
                  <i class="fas fa-ticket-alt"></i>
                </div>
                <div class="title-text ml-1">Ticket</div>
              </span>
            </div>
          </div>
        </div>
        <div class="card-body">
          <div class="font-weight-bold h4 text-center" style="margin-bottom: 0">{{ $data->rute->transportasi->category->name }}<i class="fas fa-long-arrow-alt-right mx-2" style="color: #858796;"></i>{{$data->rute->tujuan}}</div>
        </div>
        <div class="card-body">
          <div class="row no-gutters align-items-center justify-content-center">
            <div class="col">
              <p style="margin-bottom: 0">Kode Booking</p>
              <h3 class="font-weight-bold">{{ $data->kode }}</h3>
            </div>
            <div class="col-auto">
              {!! DNS2D::getBarcodeHTML(redirect('/transaksi/'.$data->kode)->getTargetUrl(), "QRCODE", 5,5) !!}
            </div>
          </div>
          <p style="margin-bottom: 0; margin-top: 5px;">Jadwal Event</p>
          <h5 class="font-weight-bold text-center">
            <div>{{ $data->rute->transportasi->category->name }}</div>
            <div>
              <!--{{ date('l, d F Y', strtotime($data->event_date)) }}-->
              Sabtu, 20 Juli 2024
            </div>
            <div>
              {{ date('H:i', strtotime($data->rute->jam)) }} WIB
            </div>
          </h5>
        </div>
        <div class="card-body">
          <table class="table">
            <tr>
              <td>Nama Kelas</td>
              <td class="text-right">{{ $data->rute->transportasi->name }} ({{ $data->rute->transportasi->kode }})</td>
            </tr>
            <tr>
              <td>Nama Pemesan</td>
              <td class="text-right">{{ $data->penumpang->name }}</td>
            </tr>
            <tr>
              <td>Nomor Kursi</td>
              <td class="text-right">{{ $data->kursi }}</td>
            </tr>
            <tr>
              <td>Harga</td>
              <td class="text-right">Rp. {{ number_format($data->total, 0, ',', '.') }}</td>
            </tr>
            @if ($data->status_pembayaran == null)
            <tr>
              <td>Status Pembayaran</td>
              <td class="text-right">{{ $data->status }}</td>
            </tr>
            @elseif ($data->status_pembayaran != null)
            <tr>
              <td>Status Pembayaran</td>
              <td class="text-right">{{ $data->status_pembayaran }}</td>
            </tr>
            @endif
          </table>
        </div>

        <div class="card-body">
          @if (Auth::user()->level != "Penumpang" && $data->status_pembayaran != null)
            <a href="{{ asset('../storage/app/public/' . $data->bukti_pembayaran) }}" target="_blank" class="btn btn-success btn-block btn-sm text-white">Lihat Bukti Pembayaran</a>
          @elseif (Auth::user()->level != "Penumpang" && $data->status_pembayaran == null)
          <a class="btn btn-secondary btn-block btn-sm text-white" disabled>Lihat Bukti Pembayaran</a>
          @endif
          </div>

        @if ($data->status == "Belum Bayar" && Auth::user()->level != "Penumpang")
          <div class="card-body">
            <a href="{{ route('pembayaran', $data->id) }}" class="btn btn-primary btn-block btn-sm text-white">Verifikasi</a>
          </div>
        @endif
        @if ($data->status == "Belum Bayar" && Auth::user()->level == "Penumpang" &&  $data->status_pembayaran == null)
        <div>
            <h5 class="font-weight-bold text-center">
              <div><br>
                Silahkan lakukan pembayaran ke Rekening
              </div>
              <div>
                BCA 3420184785 a.n Ratno Juniarto MS <br> Dengan Nominal Rp. {{ number_format($data->total, 0, ',', '.') }}
              </div>
            </h5>
          </div>
          <div class="card-body">
            <form action="{{ route('upload.bukti.pembayaran', $data->id) }}" method="POST" enctype="multipart/form-data">
              @csrf
              <div class="form-group">
                <label for="bukti_pembayaran">Upload Bukti Pembayaran</label>
                <input type="file" class="form-control" name="bukti_pembayaran" required>
              </div>
              <button type="submit" class="btn btn-primary btn-block btn-sm text-white">Upload</button>
            </form>
          </div>
        @endif
        @if (($data->status == "Belum Bayar" && $data->status_pembayaran == "Menunggu Verifikasi") && Auth::user()->level == "Penumpang")
        <div>
            <h5 class="font-weight-bold text-center">
              <div><br>
                Berhasil mengirim bukti pembayaran. Mohon menunggu verifikasi pembayaran
              </div>
            </h5>
          </div>
          <div class="card-body">
              <a href="{{ asset('../storage/app/public/' . $data->bukti_pembayaran) }}" target="_blank" class="btn btn-success btn-block btn-sm text-white">Lihat Bukti Pembayaran</a>
              <a href="https://api.whatsapp.com/send?phone=6285156651097" target=_blank class="btn btn-success btn-block btn-sm text-white">Hubungi Admin</a>
          </div>
        @endif
        @if (($data->status == "Sudah Bayar") && Auth::user()->level == "Penumpang")
        <div>
            <h5 class="font-weight-bold text-center">
              <div><br>
                Pembayaran Berhasil. Mohon agar dapat menunjukkan halaman ini pada saat mendatangi venue konser.
              </div>
            </h5>
          </div>
          <div class="card-body">
              <a href="{{ asset('../storage/app/public/' . $data->bukti_pembayaran) }}" target="_blank" class="btn btn-success btn-block btn-sm text-white">Lihat Bukti Pembayaran</a>
              <a href="https://api.whatsapp.com/send?phone=6285156651097" target=_blank class="btn btn-success btn-block btn-sm text-white">Hubungi Admin</a>
          </div>
        @endif
      </div>
    </div>
  </div>
@endsection
