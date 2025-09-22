@extends('layouts.app')

@if (Auth::user()->level == "Admin")
  @section('title', 'Verifikasi Pembayaran')
  @section('heading', 'Verifikasi Pembayaran')
@elseif (Auth::user()->level == "Petugas")
  @section('title', 'Petugas')
@endif

@section('content')
<div class="container py-4">
  <div class="row justify-content-center">
    <div class="col-12 col-md-8 col-lg-6">
      <div class="card shadow-sm border-0 rounded-4">
        <div class="card-header bg-white py-3">
          <h5 class="mb-0 fw-bold text-primary">
            <i class="fas fa-search me-2"></i> Cari Pemesanan
          </h5>
        </div>
        <div class="card-body p-4">

          <!-- 🔍 Manual Search Form -->
          <form method="POST" action="{{ route('petugas.kode') }}" class="mb-4">
            @csrf
            <div class="input-group">
              <input
                type="text"
                class="form-control form-control-lg rounded-start-pill"
                id="kode"
                name="kode"
                placeholder="Masukkan Kode Pemesanan"
                required
                aria-label="Kode Pemesanan"
              />
              <button type="submit" class="btn btn-primary btn-lg rounded-end-pill px-4 fw-semibold">
                <i class="fas fa-search me-1"></i> Cari
              </button>
            </div>
          </form>

          <!-- 📷 QR Scanner Section -->
          <div class="border rounded-3 p-4 bg-light position-relative" id="qr-scanner-section">
            <h6 class="fw-bold mb-3 d-flex align-items-center gap-2">
              <i class="fas fa-qrcode text-success"></i> Pindai QR Code
            </h6>

            <button
              type="button"
              id="scan-qr-btn"
              class="btn btn-success btn-lg w-100 mb-3 py-3 fw-bold rounded-3"
              aria-label="Mulai pemindaian QR Code"
            >
              <i class="fas fa-camera me-2"></i> Mulai Pemindaian QR
            </button>

            <div id="scanner-status" class="text-center small text-muted fst-italic py-2">
              Tekan tombol di atas untuk memulai pemindaian.
            </div>

            <!-- Scanner Video Container (Hidden by default) -->
            <div id="scanner-container" class="text-center mt-4" style="display: none;">
              <div class="position-relative d-inline-block">
                <!-- 🎥 Live Camera Feed -->
                <video
                  id="qr-video"
                  width="100%"
                  height="auto"
                  class="border rounded bg-black w-100 h-auto max-w-100"
                  playsinline
                  muted
                ></video>
              </div>

              <p class="mt-3">
                <button id="close-scanner" class="btn btn-outline-secondary btn-sm px-4 rounded-pill">
                  <i class="fas fa-times me-1"></i> Batal
                </button>
              </p>
            </div>

          </div>

        </div>
      </div>
    </div>
  </div>
</div>

<!-- ✅ QR Code Library (jsQR) -->
<script src="https://cdn.jsdelivr.net/npm/jsqr@1.4.0/dist/jsQR.min.js"></script>

<!-- ✅ QR Scanner Script -->
<script>
document.addEventListener('DOMContentLoaded', function () {
  const scanButton       = document.getElementById('scan-qr-btn');
  const closeBtn         = document.getElementById('close-scanner');
  const video            = document.getElementById('qr-video');
  const scannerContainer = document.getElementById('scanner-container');
  const statusDiv        = document.getElementById('scanner-status');
  const kodeInput        = document.getElementById('kode');
  const cariButton       = document.querySelector('form button[type="submit"]');

  if (!scanButton) {
    statusDiv.innerHTML = "<span class='text-danger'>Error: Tombol scan tidak ditemukan.</span>";
    return;
  }

  let scanning = false;

  // Start scanning
  scanButton.addEventListener('click', async () => {
    statusDiv.innerHTML = "<span class='text-warning'>Meminta akses kamera...</span>";

    try {
      const stream = await navigator.mediaDevices.getUserMedia({
        video: { facingMode: "environment" }
      });

      video.srcObject = stream;

      video.onloadedmetadata = () => {
        video.play().catch(err => {
          console.error("❌ Gagal memainkan video:", err);
          statusDiv.innerHTML = "<span class='text-danger'>❌ Gagal memulai kamera. Coba refresh halaman.</span>";
        });
      };

      scannerContainer.style.display = 'block';
      scanning = true;
      statusDiv.innerHTML = "<span class='text-info'>Memindai... arahkan kamera ke QR Code</span>";

      const tick = () => {
        if (!scanning) return;

        if (video.readyState === video.HAVE_ENOUGH_DATA) {
          const canvas = document.createElement('canvas');
          canvas.width = video.videoWidth;
          canvas.height = video.videoHeight;
          const ctx = canvas.getContext('2d');
          ctx.drawImage(video, 0, 0, canvas.width, canvas.height);
          const imageData = ctx.getImageData(0, 0, canvas.width, canvas.height);

          if (imageData.data.length === 0) return;

          const code = jsQR(imageData.data, canvas.width, canvas.height, {
            inversionAttempts: "dontInvert",
          });

          if (code) {
            kodeInput.value = code.data.trim();

            // ✅ Vibrate on success
            if (navigator.vibrate) {
              navigator.vibrate([100, 50, 100]);
            }

            statusDiv.innerHTML = "<span class='text-success'>✅ QR Code terdeteksi! Mengirimkan...</span>";
            stopScanner();

            if (cariButton) {
              setTimeout(() => cariButton.click(), 300);
            } else {
              console.warn("⚠️ Tombol 'Cari' tidak ditemukan.");
            }
          }
        }

        requestAnimationFrame(tick);
      };

      requestAnimationFrame(tick);

    } catch (err) {
      console.error("🚨 Error akses kamera:", err);
      statusDiv.innerHTML = `
        <div class="text-danger small">
          ❌ Gagal mengakses kamera.<br>
          • Pastikan menggunakan HTTPS/localhost<br>
          • Izinkan akses kamera<br>
          • Periksa koneksi perangkat<br>
          <small>${err.name}: ${err.message}</small>
        </div>`;
      alert(`Gagal mengakses kamera: ${err.message}`);
    }
  });

  // Close scanner
  if (closeBtn) {
    closeBtn.addEventListener('click', () => {
      stopScanner();
      statusDiv.innerHTML = "<span class='text-muted'>Pemindaian dibatalkan.</span>";
    });
  }

  // Stop scanner function
  function stopScanner() {
    scanning = false;
    if (video.srcObject) {
      video.srcObject.getTracks().forEach(track => track.stop());
    }
    scannerContainer.style.display = 'none';
  }

  console.log("✅ QR Scanner siap digunakan.");
});
</script>
@endsection