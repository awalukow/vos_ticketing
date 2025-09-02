<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Atur Ulang Kata Sandi - VOS</title>
</head>
<body style="margin:0; padding:0; background-color:#f9f4fb; font-family:Arial, sans-serif;">

  <table width="100%" cellpadding="0" cellspacing="0" border="0" style="background-color:#f9f4fb; padding:20px 0;">
    <tr>
      <td align="center">
        <table width="650" cellpadding="0" cellspacing="0" border="0" style="background-color:#ffffff; border:1px solid #e9ecef; border-radius:8px; overflow:hidden;">

          <!-- Header -->
          <tr>
            <td align="center" style="background: #b71ea8; color: #ffffff; padding: 30px 20px;">
              <div style="margin-bottom: 15px;">
                <div style="width:50px; height:50px; background:#ffffff; border-radius:50%; display:inline-block; line-height:50px; font-weight:bold; font-size:20px; color:#b71ea8;">
                  VOS
                </div>
              </div>
              <h1 style="margin:0; font-size:22px; font-weight:700; text-align:center;">Atur Ulang Kata Sandi</h1>
              <p style="margin:8px 0 0; font-size:14px; text-align:center;">Permintaan untuk mengatur ulang kata sandi Anda</p>
              <div style="font-size:30px; margin-top:10px;">🔐</div>
            </td>
          </tr>

          <!-- Content -->
          <tr>
            <td style="padding: 25px 20px;">

              <!-- Intro -->
              <p style="font-size:15px; color:#2c3e50; line-height:1.6;">
                Halo,
              </p>
              <p style="font-size:15px; color:#2c3e50; line-height:1.6;">
                Anda menerima email ini karena kami menerima permintaan untuk mengatur ulang kata sandi akun Anda. Klik tombol di bawah untuk melanjutkan.
              </p>

              <!-- Reset Button -->
              <div style="text-align:center; margin: 30px 0;">
                <a href="{{ $resetUrl }}"
                   style="display:inline-block; background-color:#b71ea8; color:#ffffff; text-decoration:none; padding:14px 30px; border-radius:28px; font-weight:700; font-size:16px; box-shadow:0 4px 10px rgba(183, 30, 168, 0.3);">
                   Reset Kata Sandi
                </a>
              </div>

              <!-- Token Info -->
              <div style="background:#f0e6f5; border:1px solid #d8c0e8; border-radius:6px; padding:12px; color:#4d1d60; font-size:14px; margin:20px 0;">
                <strong>Perhatian:</strong> Tautan ini hanya berlaku selama <strong>60 menit</strong>. Jika Anda tidak meminta reset, abaikan email ini.
              </div>

              <!-- Alternative Access -->
              <p style="font-size:14px; color:#666; margin:15px 0;">
                Jika tombol di atas tidak bekerja, salin dan tempel tautan berikut di browser Anda:
              </p>
              <div style="background:#f5f5f5; border:1px solid #ddd; border-radius:6px; padding:10px; font-size:13px; word-break:break-all; color:#555; margin:10px 0;">
                <a href="{{ $resetUrl }}" style="color:#b71ea8; text-decoration:underline;">{{ $resetUrl }}</a>
              </div>

              <!-- Next Steps -->
              <h2 style="font-size:18px; color:#4d1d60; font-weight:600; margin:25px 0 12px 0;">Langkah-Langkah</h2>
              <ol style="padding-left:18px; font-size:14px; color:#555; margin:0;">
                <li style="margin-bottom:6px;">Klik tombol <strong>Reset Kata Sandi</strong></li>
                <li style="margin-bottom:6px;">Buat kata sandi baru yang kuat</li>
                <li style="margin-bottom:6px;">Gunakan akun Anda seperti biasa setelah reset</li>
              </ol>

            </td>
          </tr>

          <!-- Footer -->
          <tr>
            <td style="padding:20px; background-color:#f9f4fb; text-align:center; font-size:13px; color:#777;">
              <div style="margin:10px 0;">
                <a href="https://wa.me/6285823536364" style="display:inline-block; background:#b71ea8; color:white; text-decoration:none; border-radius:50%; width:32px; height:32px; line-height:32px; text-align:center; font-size:14px; margin:0 5px;">💬</a>
                <a href="#" style="display:inline-block; background:#b71ea8; color:white; text-decoration:none; border-radius:50%; width:32px; height:32px; line-height:32px; text-align:center; font-size:14px; margin:0 5px;">📱</a>
                <a href="#" style="display:inline-block; background:#b71ea8; color:white; text-decoration:none; border-radius:50%; width:32px; height:32px; line-height:32px; text-align:center; font-size:14px; margin:0 5px;">📷</a>
              </div>

              <div style="margin:12px 0;">
                <a href="https://wa.me/6285823536364" style="color:#b71ea8; text-decoration:none; margin:0 8px;">WhatsApp Support</a> |
                <a href="mailto:cs@voiceofsoulchoir.id" style="color:#b71ea8; text-decoration:none; margin:0 8px;">cs@voiceofsoulchoir.id</a>
              </div>

              <p style="font-size:11px; color:#999; margin:0; line-height:1.5;">
                Ini adalah pesan otomatis. Harap jangan membalas langsung ke email ini.<br>
                © 2025 Voice of Soul Choir. Hak Cipta Dilindungi.
              </p>
            </td>
          </tr>

        </table>
      </td>
    </tr>
  </table>

</body>
</html>