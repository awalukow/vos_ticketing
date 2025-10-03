@extends('layouts.app')
@section('title', 'Promotion')
@section('heading', 'Promotion')
@section('styles')
<link href="{{ asset('vendor/datatables/dataTables.bootstrap4.min.css') }}" rel="stylesheet"/>
<style>
    .badge-status {
        font-size: 0.8em;
        padding: 0.5em 0.7em;
    }
    .badge-success { background-color: #28a745; color: white; }
    .badge-secondary { background-color: #6c757d; color: white; }
    .badge-danger { background-color: #dc3545; color: white; }
    .table td, .table th { vertical-align: middle !important; }
</style>
@endsection
@section('content')
<div class="card shadow mb-4">
    <div class="card-header py-3 d-flex justify-content-between align-items-center">
        <h6 class="m-0 font-weight-bold text-primary">Daftar Promo</h6>
        <button type="button" class="btn btn-primary btn-sm" data-toggle="modal" data-target="#addModal">
            <i class="fas fa-plus"></i> Tambah
        </button>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-bordered table-striped" id="dataTable" width="100%" cellspacing="0">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Kode</th>
                        <th>Jenis Diskon</th>
                        <th>Nilai</th>
                        <th>Batas Penggunaan</th>
                        <th>Telah Digunakan</th>
                        <th>Expired At</th>
                        <th>Status</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($promotions as $promo)
                    <tr>
                        <td>{{ $loop->iteration }}</td>
                        <td><strong>{{ $promo->code }}</strong></td>
                        <td>{{ ucfirst($promo->discount_type) }}</td>
                        <td>
                            @if($promo->discount_type === 'percent')
                                {{ $promo->discount_value }}%
                            @else
                                Rp {{ number_format($promo->discount_value, 0, ',', '.') }}
                            @endif
                        </td>
                        <td>{{ $promo->max_uses }}</td>
                        <td>{{ $promo->used_count }}</td>
                        <td>{{ $promo->expires_at ? $promo->expires_at->format('d M Y H:i') : 'Tidak ada' }}</td>
                        <td>
                           @if($promo->is_active && (!$promo->expires_at || $promo->expires_at >= now()))
                                <span class="badge badge-success badge-status">Aktif</span>
                            @else
                                <span class="badge badge-danger badge-status">Tidak Aktif</span>
                            @endif
                        </td>
                        <td>
                            <div class="btn-group" role="group">
                                <a href="{{ route('promotions.edit', $promo) }}" class="btn btn-warning btn-sm"><i class="fas fa-edit"></i></a>
                                <form action="{{ route('promotions.destroy', $promo) }}" method="POST" style="display:inline;" onsubmit="return confirm('Hapus promo ini?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-danger btn-sm"><i class="fas fa-trash"></i></button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Add Modal -->
<div class="modal fade" id="addModal" tabindex="-1" role="dialog" aria-labelledby="addModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="addModalLabel">Tambah Kode Promo</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form action="{{ route('promotions.store') }}" method="POST">
                @csrf
                <div class="modal-body">
                    @include('server.promotions.form')
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary">Simpan</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@section('script')
<script src="{{ asset('vendor/datatables/jquery.dataTables.min.js') }}"></script>
<script src="{{ asset('vendor/datatables/dataTables.bootstrap4.min.js') }}"></script>
<script>
    $(document).ready(function () {
        $('#dataTable').DataTable();
    });
</script>
@endsection