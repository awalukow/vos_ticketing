@extends('layouts.app')
@section('title', 'Dashboard')
@section('heading', 'Dashboard')
@section('content')
  <h4>Ringkasan Penjualan</h4>
  <div class="row">
  <div class="col-xl-3 col-md-6 mb-4">
      <div class="card border-left-primary shadow h-100 py-2">
        <div class="card-body">
          <div class="row no-gutters align-items-center">
            <div class="col mr-2">
              <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">Total Kelas</div>
              <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $ruteCount }}</div>
            </div>
            <div class="col-auto">
              <i class="fas fa-route fa-2x text-gray-300"></i>
            </div>
          </div>
        </div>
      </div>
    </div>
    <div class="col-xl-3 col-md-6 mb-4">
      <div class="card border-left-success shadow h-100 py-2">
        <div class="card-body">
          <div class="row no-gutters align-items-center">
            <div class="col mr-2">
              <div class="text-xs font-weight-bold text-success text-uppercase mb-1">Pendapatan</div>
              <div class="h5 mb-0 font-weight-bold text-gray-800">Rp. {{ number_format($pendapatan, 0, ',', '.') }}</div>
            </div>
            <div class="col-auto">
              <i class="fas fa-dollar-sign fa-2x text-gray-300"></i>
            </div>
          </div>
        </div>
      </div>
    </div>
    
    <div class="col-xl-3 col-md-6 mb-4">
      <div class="card border-left-danger shadow h-100 py-2">
        <div class="card-body">
          <div class="row no-gutters align-items-center">
            <div class="col mr-2">
              <div class="text-xs font-weight-bold text-danger text-uppercase mb-1">Data User</div>
              <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $userCount }}</div>
            </div>
            <div class="col-auto">
              <i class="fas fa-users fa-2x text-gray-300"></i>
            </div>
          </div>
        </div>
      </div>
    </div>
    <div class="col-xl-3 col-md-6 mb-4">
      <div class="card border-left-dark shadow h-100 py-2">
        <div class="card-body">
          <div class="row no-gutters align-items-center">
            <div class="col mr-2">
              <div class="text-xs font-weight-bold text-danger text-uppercase mb-1">Pending Order</div>
              <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $pendingTicketCount }}</div>
            </div>
            <div class="col-auto">
              <i class="fa-regular fa-hourglass-half fa-2x text-gray-300"></i>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
  <h4>Detail Penjualan</h4>
  <div class="row">
    @foreach($rute_table as $rute)
      <div class="col-xl-3 col-md-6 mb-4">
        <div class="card border-left-primary shadow h-100 py-2">
          <div class="card-body">
            <div class="row no-gutters align-items-center">
              <div class="col mr-2">
                <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">{{$rute->transportasi->category->name}} | {{ $rute->tujuan }}</div>
                <div class="h5 mb-0 font-weight-bold text-gray-800">Lunas : {{ $rute->tickets_sold }} kursi</div>
                <div><small class="text-muted">Belum Lunas: {{ $rute->unpaid_seat }} kursi</small></div>
                <div><small class="text-muted">Sisa Kursi: {{ $rute->sisa_kursi }} kursi</small></div>
                <div><small class="text-muted">Nominal Terjual: Rp. {{ number_format($rute->nominal_terjual, 0, ',', '.') }}</small></div>
                <small class="text-muted">Sisa Alokasi Kursi Gereja: {{ $rute->unpaid_seat_church }} kursi</small>
              </div> 
            </div>
          </div>
        </div>
      </div>
    @endforeach
    <div class="col-xl-3 col-md-6 mb-4">
      <div class="card border-left-danger shadow h-100 py-2">
        <div class="card-body">
          <div class="row no-gutters align-items-center">
            <div class="col mr-2">
              <div class="text-xs font-weight-bold text-danger text-uppercase mb-1">Total Kursi Terjual</div>
              <div class="h5 mb-0 font-weight-bold text-gray-800">{{$paidTicketCount}} Kursi</div>
              <small class="text-muted">Total Nominal Terjual: Rp. {{ number_format($pendapatan, 0, ',', '.') }} </small>
            </div>
            <div class="col-auto">
              <i class="fas fa-dollar-sign fa-2x text-gray-300"></i>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>

  <h4>Penjualan Gereja</h4>
    <div class="row">
      @foreach($sortedChurches as $church)
        <div class="col-xl-3 col-md-6 mb-4">
          @if($church->isExpired != true)
            <div class="card border-left-danger shadow h-100 py-2">
          @else
            <div class="card border-left-primary shadow h-100 py-2">
          @endif
            <div class="card-body">
              <div class="row no-gutters align-items-center">
                <div class="col mr-2">
                  <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">{{ $church->name }}</div>
                  <div class="h5 mb-0 font-weight-bold text-gray-800">Lunas : {{ $church->sold_qty }} kursi</div>
                  <div><small class="text-muted">Nominal Terjual: Rp. {{ number_format($church->sold_nominal, 0, ',', '.') }}</small></div>
                  <div><small class="text-muted">Sisa Kursi: {{ $church->unsold_qty }} kursi</small></div>
                  @if($church->isExpired != true)
                      <div><small class="text-muted">Tanggal Expired: {{ $church->expiry_date }}</small></div>
                  @else
                      <div><small class="text-muted" style="color: red;">Tanggal Expired: {{ $church->expiry_date }}</small></div>
                  @endif
                </div> 
              </div>
            </div>
          </div>
        </div>
      @endforeach
    </div>

@endsection
