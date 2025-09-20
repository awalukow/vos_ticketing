@extends('layouts.app')

@if (Auth::user()->level == "Admin")
  @section('title', 'Verifikasi Pembayaran')
  @section('heading', 'Verifikasi Pembayaran')
@elseif (Auth::user()->level == "Petugas")
  @section('title', 'Petugas')
@endif

@section('content')
  <div class="row justify-content-center">
    <div class="col-12">
      <div class="card shadow">
        <div class="card-body">
          <form method="POST" action="{{ route('petugas.kode') }}">
            @csrf
            <div class="row">
              <div class="col">
                <div class="form-group" style="margin-bottom: 0">
                  <input
                    type="text"
                    class="form-control"
                    id="kode"
                    name="kode"
                    placeholder="Kode Pemesanan"
                    required
                  />
                </div>
              </div>
              <div class="col-auto">
                <button type="submit" class="btn btn-primary px-4" style="font-size: 16px">
                  Cari
                </button>
              </div>
              <div class="col-auto">
                <button type="button" class="btn btn-success px-4" style="font-size: 16px" id="scan-qr-btn">
                  Scan QR
                </button>
              </div>
            </div>
          </form>

          <!-- Hidden Video Element for QR Scanner -->
          <div id="scanner-container" style="display: none; margin-top: 20px; text-align: center;">
            <video id="qr-video" width="300" height="225" style="border: 2px solid #000; background: #000;"></video>
            <p><button id="close-scanner" class="btn btn-secondary btn-sm mt-2">Close Scanner</button></p>
          </div>
        </div>
      </div>
    </div>
  </div>
@endsection

@push('scripts')
  <!-- Include jsQR library -->
  <script src="https://cdn.jsdelivr.net/npm/jsqr@1.4.0/dist/jsQR.min.js"></script>

  <script>
    document.addEventListener('DOMContentLoaded', function () {
      const scanButton = document.getElementById('scan-qr-btn');
      const closeScannerButton = document.getElementById('close-scanner');
      const video = document.getElementById('qr-video');
      const scannerContainer = document.getElementById('scanner-container');
      const kodeInput = document.getElementById('kode');
      const form = document.querySelector('form');

      let scanning = false;

      scanButton.addEventListener('click', async () => {
        try {
          // Request camera stream
          const stream = await navigator.mediaDevices.getUserMedia({
            video: { facingMode: "environment" }
          });

          video.srcObject = stream;
          scannerContainer.style.display = 'block';
          scanning = true;

          // Start scanning frames
          requestAnimationFrame(tick);

        } catch (err) {
          let message = 'Unable to access camera. Please ensure:\n' +
                        '• You are on HTTPS or localhost\n' +
                        '• A camera is connected and working\n' +
                        '• You allowed camera permissions in your browser\n\n' +
                        'Technical Details: ' + err.message;

          alert(message);
          console.error("Camera Error:", err);
        }
      });

      closeScannerButton.addEventListener('click', () => {
        stopScanner();
      });

      function stopScanner() {
        scanning = false;
        if (video.srcObject) {
          video.srcObject.getTracks().forEach(track => track.stop());
        }
        scannerContainer.style.display = 'none';
      }

      function tick() {
        if (!scanning) return;

        if (video.readyState === video.HAVE_ENOUGH_DATA) {
          const canvas = document.createElement('canvas');
          canvas.width = video.videoWidth;
          canvas.height = video.videoHeight;
          const ctx = canvas.getContext('2d');
          ctx.drawImage(video, 0, 0, canvas.width, canvas.height);
          const imageData = ctx.getImageData(0, 0, canvas.width, canvas.height);

          const code = jsQR(imageData.data, imageData.width, imageData.height, {
            inversionAttempts: "dontInvert",
          });

          if (code) {
            kodeInput.value = code.data;
            stopScanner();
            // Optional: Auto-submit form after scan
            // form.submit();
          }
        }

        requestAnimationFrame(tick);
      }
    });
  </script>
@endpush