<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Payment Confirmation - VOS</title>
</head>
<body style="margin:0; padding:0; background-color:#f8f9fa; font-family:Arial, sans-serif;">

  <table width="100%" cellpadding="0" cellspacing="0" border="0" style="background-color:#f8f9fa; padding:20px 0;">
    <tr>
      <td align="center">
        <table width="650" cellpadding="0" cellspacing="0" border="0" style="background-color:#ffffff; border:1px solid #e9ecef; border-radius:8px; overflow:hidden;">

          <!-- Header -->
          <tr>
            <td align="center" style="background: #27ae60; color: #ffffff; padding: 30px 20px;">
              <div style="margin-bottom: 15px;">
                <div style="width:50px; height:50px; background:#ffffff; border-radius:50%; display:inline-block; line-height:50px; font-weight:bold; font-size:20px; color:#27ae60;">
                  VOS
                </div>
              </div>
              <h1 style="margin:0; font-size:22px; font-weight:700; text-align:center;">Payment Confirmed</h1>
              <p style="margin:8px 0 0; font-size:14px; text-align:center;">Your payment has been successfully verified</p>
              <div style="font-size:30px; margin-top:10px;">✓</div>
            </td>
          </tr>

          <!-- Content -->
          <tr>
            <td style="padding: 25px 20px;">

              <!-- Booking Details -->
              <h2 style="font-size:18px; color:#2c3e50; font-weight:600; margin:0 0 12px 0;">Booking Details</h2>
              <table cellpadding="0" cellspacing="0" border="0" width="100%" style="background:#f8fff9; border:1px solid #e7ffe9; border-radius:8px; padding:12px; font-size:14px;">
                <tr>
                  <td style="padding:8px 0; width:130px; font-weight:bold; color:#444;">Booking Code</td>
                  <td style="padding:8px 0;"><strong>{{ $bookingCode }}</strong></td>
                </tr>
                <tr>
                  <td style="padding:8px 0; font-weight:bold; color:#444;">Event</td>
                  <td style="padding:8px 0;">{{ $eventName }}</td>
                </tr>
                <tr>
                  <td style="padding:8px 0; font-weight:bold; color:#444;">Date & Time</td>
                  <td style="padding:8px 0;">{{ $eventDate }}, {{ $eventTime }} WIB</td>
                </tr>
                <tr>
                  <td style="padding:8px 0; font-weight:bold; color:#444;">Seat(s)</td>
                  <td style="padding:8px 0;">{{ $seats }}</td>
                </tr>
                <tr>
                  <td style="padding:8px 0; font-weight:bold; color:#444;">Total Amount</td>
                  <td style="padding:8px 0; color:#27ae60; font-weight:700;">{{ $totalAmount }}</td>
                </tr>
              </table>

              <!-- Payment Message -->
              <div style="background:#d4edda; border:1px solid #c3e6cb; border-radius:6px; padding:12px; color:#155724; font-weight:bold; text-align:center; margin:20px 0;">
                PAYMENT SUCCESSFULLY VERIFIED
              </div>

<!-- QR Codes for Seats -->
<h2 style="font-size:18px; color:#2c3e50; font-weight:600; margin:20px 0 12px 0;">Seat QR Codes</h2>
<p style="font-size:14px; margin:0 0 15px 0; color:#666;">Scan each QR code at the entrance for seat verification:</p>

<div style="display: flex; flex-wrap: wrap; justify-content: center; gap: 15px; margin: 15px 0;">
  <!-- Generate QR codes for each seat -->
  @foreach(explode(', ', $seats) as $index => $seat)
    <div style="text-align: center; margin: 10px;">
      <div style="background: white; padding: 8px; border-radius: 8px; display: inline-block;">
        <!-- Embed the QR code using CID -->
        <img src="cid:qr_{{ $index }}@{{ config('app.url') }}" alt="QR Code" width="150" height="150" />
      </div>
      <div style="margin-top: 8px; font-size: 14px; font-weight: 600;">
        {{ $seat }}
      </div>
      <div style="font-size: 12px; color: #666;">
        {{ $bookingCode }}_{{ $seat }}
      </div>
    </div>
  @endforeach
</div>


              <!-- Next Steps -->
              <h2 style="font-size:18px; color:#2c3e50; font-weight:600; margin:20px 0 12px 0;">Next Steps</h2>
              <ol style="padding-left:18px; font-size:14px; margin:0;">
                <li style="margin-bottom:6px;">Your e-ticket is now confirmed and ready to use</li>
                <li style="margin-bottom:6px;">Keep this email as your proof of payment</li>
                <li style="margin-bottom:6px;">Bring your e-ticket to the event venue</li>
                <li>Present your booking code at the entrance</li>
                <li>Use the QR codes above for seat verification</li>
              </ol>

              <!-- Action Buttons -->
              <div style="text-align:center; margin:30px 0;">
                <a href="{{ $transactionUrl }}" style="display:inline-block; background-color:#27ae60; color:#ffffff; text-decoration:none; padding:12px 20px; border-radius:25px; font-weight:600; font-size:15px; margin:5px;">View Your Ticket</a>
                <a href="{{ $helpCenterUrl }}" style="display:inline-block; border:2px solid #27ae60; color:#27ae60; text-decoration:none; padding:12px 20px; border-radius:25px; font-weight:600; font-size:15px; margin:5px;">Need Help?</a>
              </div>
            </td>
          </tr>

          <!-- Footer -->
          <tr>
            <td style="padding:20px; background-color:#f8f9fa; text-align:center; font-size:13px; color:#6c757d;">
              <div style="margin:10px 0;">
                <a href="https://wa.me/6285823536364" style="display:inline-block; background:#27ae60; color:white; text-decoration:none; border-radius:50%; width:32px; height:32px; line-height:32px; text-align:center; font-size:14px; margin:0 5px;">💬</a>
                <a href="#" style="display:inline-block; background:#27ae60; color:white; text-decoration:none; border-radius:50%; width:32px; height:32px; line-height:32px; text-align:center; font-size:14px; margin:0 5px;">📱</a>
                <a href="#" style="display:inline-block; background:#27ae60; color:white; text-decoration:none; border-radius:50%; width:32px; height:32px; line-height:32px; text-align:center; font-size:14px; margin:0 5px;">📷</a>
              </div>

              <div style="margin:12px 0;">
                <a href="{{ $helpCenterUrl }}" style="color:#27ae60; text-decoration:none; margin:0 8px;">Help Center</a>
                <a href="{{ $termsUrl }}" style="color:#27ae60; text-decoration:none; margin:0 8px;">Terms</a>
                <a href="{{ $privacyUrl }}" style="color:#27ae60; text-decoration:none; margin:0 8px;">Privacy</a>
              </div>

              <p style="margin:0 0 10px;">
                Need assistance? Contact our customer support:<br>
                <a href="https://wa.me/6285823536364" style="color:#27ae60; text-decoration:none;">WhatsApp Support</a> or 
                <a href="mailto:cs@voiceofsoulchoir.id" style="color:#27ae60; text-decoration:none;">cs@voiceofsoulchoir.id</a>
              </p>

              <p style="font-size:11px; color:#999; margin:0; line-height:1.5;">
                This is an automated message. Please do not reply directly to this email.<br>
                © 2024 Voice of Soul Choir. All rights reserved.
              </p>
            </td>
          </tr>

        </table>
      </td>
    </tr>
  </table>

</body>
</html>