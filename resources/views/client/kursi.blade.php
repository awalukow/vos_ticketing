<!-- kursi.blade.php -->

@extends('layouts.app')
@section('title', 'Pilih Jumlah Tiket')
@section('content')
  <div class="row justify-content-center">
    <div class="col-12">
      <a href="{{ route('show', ['id' => $id, 'data' => $data]) }}" class="text-white btn"><i class="fas fa-arrow-left mr-2"></i> Kembali</a>
      <div class="row mt-2">
        <div class="col-lg-6 col-md-8 col-sm-10 col-12 mb-4">
          <label for="ticketCount">Pilih Jumlah Tiket:</label>
          <input type="number" id="ticketCount" class="form-control" min="1" value="1">
        </div>
      </div>
    </div>
  </div>
@endsection

@section('script')
  <script>
    $(document).ready(function() {
      // Handle submit button click
      $('#submitBtn').click(function() {
        var selectedCount = $('#ticketCount').val();
        
        // Redirect to booking page with selected ticket count
        window.location.href = "{{ route('pesan', ['data' => 'placeholder']) }}".replace('placeholder', selectedCount);
      });
    });
  </script>
@endsection
