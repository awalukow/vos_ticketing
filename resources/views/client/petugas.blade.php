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
            </div>
          </form>

          <!-- 🆕 QR Scanner Section -->
          <div id="qr-scanner-section" class="mt-4 p-3 border rounded" style="background: #f8f9fa;">
            <h5><i class="fas fa-qrcode"></i> QR Code Scanner</h5>
            <button type="button" class="btn btn-success" id="scan-qr-btn">
              <i class="fas fa-camera"></i> Scan QR Code
            </button>
            <div id="scanner-status" class="mt-2" style="min-height: 1.5em; font-weight: 500;">
              <span class="text-muted">Click “Scan QR Code” to start.</span>
            </div>

            <!-- Scanner Video (Hidden by default) -->
            <div id="scanner-container" style="display: none; margin-top: 20px; text-align: center;">
              <video id="qr-video" width="300" height="225" style="border: 2px solid #000; background: #000;"></video>
              <p class="mt-2">
                <button id="close-scanner" class="btn btn-secondary btn-sm">❌ Close</button>
              </p>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>

  <!-- ✅ QR Code Library -->
  <script src="https://cdn.jsdelivr.net/npm/jsqr@1.4.0/dist/jsQR.min.js"></script>

  <!-- ✅ Inline JavaScript — Guaranteed to Run -->
  <script>
    console.log("✅ QR Scanner script loaded.");

    document.addEventListener('DOMContentLoaded', function () {
      console.log("✅ DOM fully loaded.");

      const scanButton = document.getElementById('scan-qr-btn');
      const closeScannerButton = document.getElementById('close-scanner');
      const video = document.getElementById('qr-video');
      const scannerContainer = document.getElementById('scanner-container');
      const statusDiv = document.getElementById('scanner-status');
      const kodeInput = document.getElementById('kode');
      const cariButton = document.querySelector('form button[type="submit"]'); // 👈 Get "Cari" button

      if (!scanButton) {
        console.error("🚨 Scan button not found! Check HTML ID.");
        statusDiv.innerHTML = "<span class='text-danger'>Error: Scan button not found.</span>";
        return;
      }

      if (!cariButton) {
        console.warn("⚠️ 'Cari' button not found — auto-submit will not work.");
      }

      console.log("✅ Scan button found. Adding click listener...");

      let scanning = false;

      scanButton.addEventListener('click', async () => {
        console.log("🖱️ Scan button clicked.");
        statusDiv.innerHTML = "<span class='text-warning'>Requesting camera access...</span>";

        try {
          console.log("📹 Requesting camera stream...");
          const stream = await navigator.mediaDevices.getUserMedia({
            video: { facingMode: "environment" }
          });

          console.log("✅ Camera stream received:", stream);

          video.srcObject = stream;

          video.onloadedmetadata = () => {
            console.log("🎥 Video metadata loaded. Attempting to play...");
            video.play().catch(err => {
              console.error("❌ Failed to play video:", err);
              statusDiv.innerHTML = "<span class='text-danger'>❌ Camera feed failed to start. Try refreshing.</span>";
            });
          };

          scannerContainer.style.display = 'block';
          scanning = true;
          statusDiv.innerHTML = "<span class='text-info'>Scanning... point camera at QR code</span>";

          const tick = () => {
            if (!scanning) return;

            if (video.readyState === video.HAVE_ENOUGH_DATA) {
              const canvas = document.createElement('canvas');
              canvas.width = video.videoWidth;
              canvas.height = video.videoHeight;
              const ctx = canvas.getContext('2d');
              ctx.drawImage(video, 0, 0, canvas.width, canvas.height);
              const imageData = ctx.getImageData(0, 0, canvas.width, canvas.height);

              if (imageData.data.length === 0) {
                console.warn("⚠️ No pixel data — camera may be inactive or blocked.");
                return;
              }

              const code = jsQR(imageData.data, imageData.width, imageData.height, {
                inversionAttempts: "dontInvert",
              });

              if (code) {
                console.log("✅ QR Code detected:", code.data);
                kodeInput.value = code.data;
                statusDiv.innerHTML = "<span class='text-success'>✅ QR Code read! Submitting...</span>";
                stopScanner();

                // ✅ AUTO CLICK "CARI" BUTTON AFTER SCAN
                if (cariButton) {
                  console.log("🖱️ Auto-clicking 'Cari' button...");
                  cariButton.click();
                } else {
                  console.warn("⚠️ 'Cari' button not found — cannot auto-submit.");
                }
              }
            }

            requestAnimationFrame(tick);
          };

          requestAnimationFrame(tick);

        } catch (err) {
          console.error("🚨 Camera access error:", err);
          let message = `<span class='text-danger'>❌ Camera Error</span><br>
                        • Use HTTPS or localhost<br>
                        • Allow camera permission<br>
                        • Check camera is connected<br>
                        <small>Error: ${err.name || 'Unknown'}</small>`;
          statusDiv.innerHTML = message;
          alert(`Camera Access Failed:\n${err.message || 'Unknown error'}`);
        }
      });

      if (closeScannerButton) {
        closeScannerButton.addEventListener('click', () => {
          console.log("CloseOperation clicked.");
          stopScanner();
          statusDiv.innerHTML = "<span class='text-muted'>Scanner closed.</span>";
        });
      }

      function stopScanner() {
        scanning = false;
        if (video.srcObject) {
          video.srcObject.getTracks().forEach(track => {
            track.stop();
            console.log("⏹️ Stopped track:", track.label);
          });
        }
        scannerContainer.style.display = 'none';
      }

      console.log("✅ QR Scanner initialized and ready.");
    });
  </script>
@endsection