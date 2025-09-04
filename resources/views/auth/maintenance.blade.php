<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Page Under Maintenance</title>
    <style>
        body {
            font-family: 'Arial', sans-serif;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            margin: 0;
            padding: 0;
            min-height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
        }
        
        .maintenance-container {
            background: white;
            border-radius: 15px;
            box-shadow: 0 20px 40px rgba(0,0,0,0.1);
            padding: 40px;
            text-align: center;
            max-width: 500px;
            width: 90%;
        }
        
        .maintenance-icon {
            font-size: 80px;
            color: #6a11cb;
            margin-bottom: 20px;
        }
        
        h1 {
            color: #333;
            margin: 0 0 15px;
            font-size: 28px;
        }
        
        .maintenance-message {
            color: #666;
            line-height: 1.6;
            margin-bottom: 30px;
            font-size: 16px;
        }
        
        .maintenance-time {
            background: #f8f9ff;
            border: 1px solid #e7eaff;
            border-radius: 8px;
            padding: 15px;
            margin: 20px 0;
            color: #4e73df;
            font-weight: 600;
        }
        
        .contact-info {
            margin-top: 25px;
            padding-top: 20px;
            border-top: 1px solid #eee;
            color: #666;
        }
        
        .contact-link {
            color: #6a11cb;
            text-decoration: none;
            font-weight: 600;
        }
        
        .contact-link:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>
    <div class="maintenance-container">
        <div class="maintenance-icon">🔧</div>
        <h1>Website sedang dalam perbaikan</h1>
        <p class="maintenance-message">
            Kami sedang melakukan perbaikan terjadwal untuk meningkatkan layanan kami.
            Kami mohon maaf atas ketidaknyamanan ini dan menghargai kesabaran Anda.
        </p>
        
        <div class="maintenance-time">
            Perkiraan waktu pemulihan: {{ $maintenanceMode->ValueStart ?? '' }} - {{ $maintenanceMode->ValueEnd ?? '' }} <br>
            {{ $maintenanceMode->isRunning ? 'Sedang Berlangsung' : 'Finishing' }}
        </div>
        
        <p class="maintenance-message">
            Halaman akan kembali online segera. Terima kasih atas pengertian Anda.
        </p>
        
        <div class="contact-info">
            Butuh bantuan segera?<br>
            Hubungi kami di: <a href="https://wa.me/6285156651097" class="contact-link">Dukungan WhatsApp</a>
        </div>
    </div>
</body>
</html>