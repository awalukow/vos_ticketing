<!DOCTYPE html>
<html>
<head>
    <title>E-Ticket VOS</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 20px;
        }
        .ticket {
            border: 2px solid #6a11cb;
            border-radius: 10px;
            padding: 20px;
            max-width: 600px;
            margin: 0 auto;
        }
        .header {
            text-align: center;
            border-bottom: 1px dashed #ccc;
            padding-bottom: 15px;
            margin-bottom: 20px;
        }
        .logo {
            font-size: 24px;
            font-weight: bold;
            color: #6a11cb;
        }
        .event {
            text-align: center;
            margin: 15px 0;
        }
        .event-name {
            font-size: 18px;
            font-weight: bold;
        }
        .event-date {
            font-size: 16px;
            margin: 5px 0;
        }
        .details {
            margin: 20px 0;
        }
        .detail-row {
            display: flex;
            margin-bottom: 10px;
        }
        .label {
            width: 120px;
            font-weight: bold;
        }
        .qr-code {
            text-align: center;
            margin: 20px 0;
        }
        .footer {
            text-align: center;
            font-size: 12px;
            color: #666;
            margin-top: 20px;
            border-top: 1px dashed #ccc;
            padding-top: 15px;
        }
    </style>
</head>
<body>
    <div class="ticket">
        <div class="header">
            <div class="logo">VOS</div>
            <div>E-TICKET</div>
        </div>
        
        <div class="event">
            <div class="event-name">{{ $eventName }}</div>
            <div class="event-date">{{ $eventDate }} | {{ $eventTime }} WIB</div>
        </div>
        
        <div class="details">
            <div class="detail-row">
                <div class="label">Booking Code:</div>
                <div class="value">{{ $pemesanan->kode }}</div>
            </div>
            <div class="detail-row">
                <div class="label">Seats:</div>
                <div class="value">{{ implode(', ', $seats) }}</div>
            </div>
            <div class="detail-row">
                <div class="label">Total:</div>
                <div class="value">Rp {{ number_format($pemesanan->total, 0, ',', '.') }}</div>
            </div>
        </div>
        
        <div class="qr-code">
            <!-- QR Code will be generated here with the booking code and seats -->
            Booking Code: {{ $pemesanan->kode }}<br>
            Seats: {{ implode(', ', $seats) }}
        </div>
        
        <div class="footer">
            Please present this ticket at the entrance.<br>
            © 2024 Voice of Soul Choir. All rights reserved.
        </div>
    </div>
</body>
</html>