@extends('layouts.app')

@section('title', 'Dashboard')
@section('heading', 'Dashboard')

@section('content')

<div class="container-fluid">

  <!-- === SALES SUMMARY SECTION === -->
  <div class="d-flex justify-content-between align-items-center mb-4">
    <h4 class="text-primary fw-bold">📊 Ringkasan Penjualan</h4>
    <small class="text-muted">Data terbaru per {{ now()->format('d M Y H:i') }}</small>
  </div>

  <div class="row g-4 mb-5">
    <!-- Total Rute -->
    <div class="col-xl-3 col-md-6">
      <div class="card border-start border-4 border-primary shadow-sm h-100">
        <div class="card-body d-flex align-items-center">
          <div class="flex-grow-1">
            <div class="text-uppercase text-primary fw-bold small">Total Kelas</div>
            <div class="display-6 fw-bold text-gray-800">{{ $ruteCount }}</div>
          </div>
          <div class="text-primary ms-3">
            <i class="fas fa-route fa-2x opacity-75"></i>
          </div>
        </div>
      </div>
    </div>

    <!-- Pendapatan -->
    <div class="col-xl-3 col-md-6">
      <div class="card border-start border-4 border-success shadow-sm h-100">
        <div class="card-body d-flex align-items-center">
          <div class="flex-grow-1">
            <div class="text-uppercase text-success fw-bold small">Pendapatan</div>
            <div class="display-6 fw-bold text-gray-800">Rp {{ number_format($pendapatan, 0, ',', '.') }}</div>
          </div>
          <div class="text-success ms-3">
            <i class="fas fa-wallet fa-2x opacity-75"></i>
          </div>
        </div>
      </div>
    </div>

    <!-- Data User -->
    <div class="col-xl-3 col-md-6">
      <div class="card border-start border-4 border-info shadow-sm h-100">
        <div class="card-body d-flex align-items-center">
          <div class="flex-grow-1">
            <div class="text-uppercase text-info fw-bold small">Data User</div>
            <div class="display-6 fw-bold text-gray-800">{{ $userCount }}</div>
          </div>
          <div class="text-info ms-3">
            <i class="fas fa-users fa-2x opacity-75"></i>
          </div>
        </div>
      </div>
    </div>

    <!-- Pending Order -->
    <div class="col-xl-3 col-md-6">
      <div class="card border-start border-4 border-warning shadow-sm h-100">
        <div class="card-body d-flex align-items-center">
          <div class="flex-grow-1">
            <div class="text-uppercase text-warning fw-bold small">Pending Order</div>
            <div class="display-6 fw-bold text-gray-800">{{ $pendingTicketCount }}</div>
          </div>
          <div class="text-warning ms-3">
            <i class="fa-regular fa-hourglass-half fa-2x opacity-75"></i>
          </div>
        </div>
      </div>
    </div>
  </div>

  <!-- === SALES DETAIL SECTION === -->
  <h4 class="text-primary fw-bold mb-4">🧾 Detail Penjualan per Rute</h4>
  <div class="row g-4 mb-5">
    @foreach($rute_table as $rute)
      <div class="col-xl-3 col-md-6">
        <div class="card border-start border-4 border-primary shadow-sm h-100">
          <div class="card-body">
            <h6 class="fw-bold text-primary mb-3">
              {{ $rute->transportasi->category->name }} → {{ $rute->tujuan }}
            </h6>
            <ul class="list-unstyled small">
              <li class="mb-1"><strong>Lunas:</strong> {{ $rute->tickets_sold }} kursi</li>
              <li class="mb-1"><strong>Belum Lunas:</strong> {{ $rute->unpaid_seat }} kursi</li>
              <li class="mb-1"><strong>Sisa Kursi:</strong> {{ $rute->sisa_kursi }} kursi</li>
              <li class="mb-1"><strong>Nominal Terjual:</strong> Rp {{ number_format($rute->nominal_terjual, 0, ',', '.') }}</li>
              <!--<li class="mb-1"><strong>Sisa Alokasi Gereja:</strong> {{ $rute->unpaid_seat_church }} kursi</li>
              <li class="mb-1"><strong>Sisa Tiket Fisik:</strong> {{ $rute->unpaid_seat_fisik }} kursi</li>-->
            </ul>
          </div>
        </div>
      </div>
    @endforeach

    <!-- TOTAL KESULURUHAN CARD - Only if 4 or fewer rutes -->
    @if(count($rute_table) < 4)
      <div class="col-xl-3 col-md-6">
        <div class="card border-start border-4 border-danger shadow-sm h-100">
          <div class="card-body d-flex flex-column justify-content-center">
            <div class="text-uppercase text-danger fw-bold small">Total Keseluruhan</div>
            <div class="display-6 fw-bold text-gray-800 mt-2">{{ $paidTicketCount }} Kursi</div>
            <div class="small text-muted mt-2">
              <strong>Total Pendapatan:</strong> Rp {{ number_format($pendapatan, 0, ',', '.') }}
            </div>
            <div class="mt-auto text-end">
              <i class="fas fa-chart-line fa-2x text-danger opacity-50"></i>
            </div>
          </div>
        </div>
      </div>
    @endif
  </div>

  <!-- If more than 4 rutes, show total in a new row -->
  @if(count($rute_table) >= 4)
    <div class="row g-4 mb-5">
      <div class="col-12">
        <div class="card border-start border-4 border-danger shadow-sm h-100">
          <div class="card-body d-flex align-items-center justify-content-between p-4">
            <div>
              <div class="text-uppercase text-danger fw-bold small">Total Keseluruhan</div>
              <div class="display-6 fw-bold text-gray-800 mt-2">{{ $paidTicketCount }} Kursi</div>
              <div class="small text-muted mt-2">
                <strong>Total Pendapatan:</strong> Rp {{ number_format($pendapatan, 0, ',', '.') }}
              </div>
            </div>
            <div class="text-danger ms-3">
              <i class="fas fa-chart-line fa-3x opacity-75"></i>
            </div>
          </div>
        </div>
      </div>
    </div>
  @endif

  <!-- === CHURCH SALES SECTION === -->
  <h4 class="text-primary fw-bold mb-4">⛪ Penjualan Gereja</h4>
  <div class="row g-4">
    @forelse($sortedChurches as $church)
      <div class="col-xl-3 col-md-6">
        @php
          $cardClass = $church->isExpired ? 'border-primary' : 'border-danger';
          $textClass = $church->isExpired ? 'text-primary' : 'text-danger';
          $expiryClass = $church->isExpired ? 'text-muted' : 'text-danger fw-bold';
        @endphp

        <div class="card border-start border-4 {{ $cardClass }} shadow-sm h-100">
          <div class="card-body">
            <h6 class="fw-bold {{ $textClass }} mb-3">{{ $church->name }}</h6>
            <ul class="list-unstyled small">
              <li class="mb-1"><strong>Lunas:</strong> {{ $church->sold_qty }} kursi</li>
              <li class="mb-1"><strong>Nominal:</strong> Rp {{ number_format($church->sold_nominal, 0, ',', '.') }}</li>
              <li class="mb-1"><strong>Sisa Kursi:</strong> {{ $church->unsold_qty }} kursi</li>
              <li class="mb-1">
                <strong>Tanggal Expired:</strong>
                <span class="{{ $expiryClass }}">{{ $church->expiry_date }}</span>
              </li>
            </ul>
          </div>
        </div>
      </div>
    @empty
      <div class="col-12">
        <div class="alert alert-info text-center">Belum ada data penjualan gereja.</div>
      </div>
    @endforelse
  </div>

  <!-- === REFERRAL RANKING SECTION === -->
  @if($rankedReferrals->isNotEmpty())
    <h4 class="text-primary fw-bold mb-4 mt-5">🏆 Top Referral Rankings</h4>
    <div class="row g-4">
      @foreach($rankedReferrals as $referral)
        <div class="col-xl-3 col-md-6">
          <div class="card border-start border-4 border-purple shadow-sm h-100">
            <div class="card-body d-flex flex-column">
              <div class="d-flex align-items-center mb-3">
                <div class="badge bg-purple text-white rounded-circle d-flex align-items-center justify-content-center me-3" style="width: 40px; height: 40px; font-weight: bold; font-size: 1.1rem;">
                  @if($referral->rank == 1) 🥇
                  @elseif($referral->rank == 2) 🥈
                  @elseif($referral->rank == 3) 🥉
                  @else {{ $referral->rank }}
                  @endif
                </div>
                <h6 class="fw-bold text-purple mb-0 text-uppercase">{{ Str::limit($referral->referral, 25) }}</h6>
              </div>
              <ul class="list-unstyled small">
                <li class="mb-1"><strong>📦 Total Pesanan:</strong> {{ $referral->total_orders }}</li>
                <li class="mb-1"><strong>💰 Total Pendapatan:</strong> Rp {{ number_format($referral->total_revenue, 0, ',', '.') }}</li>
              </ul>
            </div>
          </div>
        </div>
      @endforeach
    </div>
  @endif

</div>

@endsection

@push('css')
<style>
  .border-purple { border-color: #8b5cf6 !important; }
  .text-purple { color: #8b5cf6 !important; }
  .bg-purple { background-color: #8b5cf6 !important; }

  .card:hover {
    transform: translateY(-2px);
    box-shadow: 0 0.5rem 1rem rgba(0,0,0,0.1) !important;
    transition: all 0.2s ease-in-out;
  }
</style>
@endpush