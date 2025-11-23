<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>VOS Ticket Order Confirmation</title>
    <style>
        /* Reset styles for better email client compatibility */
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body, html {
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Oxygen, Ubuntu, Cantarell, sans-serif;
            line-height: 1.6;
            color: #333;
            background-color: #f8f9fa;
            width: 100%;
            height: 100%;
            -webkit-text-size-adjust: none;
        }
        
        /* Wrapper for better mobile rendering */
        .email-wrapper {
            width: 100%;
            padding: 20px 10px;
            background-color: #f8f9fa;
        }
        
        .email-container {
            max-width: 650px;
            width: 100%;
            margin: 0 auto;
            background: white;
            border-radius: 12px;
            overflow: hidden;
            box-shadow: 0 8px 25px rgba(0,0,0,0.1);
            border: 1px solid #e9ecef;
            display: table;
            table-layout: fixed;
        }
        
        .header {
            background: linear-gradient(135deg, #6a11cb 0%, #2575fc 100%);
            color: white;
            padding: 25px 20px 20px;
            text-align: center;
        }
        
        .header h1 {
            margin: 0;
            font-size: 24px;
            font-weight: 700;
            letter-spacing: -0.5px;
        }
        
        .header p {
            margin: 8px 0 0;
            opacity: 0.9;
            font-size: 15px;
        }
        
        .content {
            padding: 25px 20px;
        }
        
        .section {
            margin-bottom: 20px;
            padding-bottom: 15px;
            border-bottom: 1px solid #eee;
        }
        
        .section:last-child {
            border-bottom: none;
            margin-bottom: 0;
            padding-bottom: 0;
        }
        
        .section-title {
            color: #2c3e50;
            font-size: 18px;
            font-weight: 600;
            margin: 0 0 12px 0;
            display: flex;
            align-items: center;
        }
        
        .order-details {
            background: #f8f9ff;
            border-radius: 8px;
            padding: 12px 15px;
            border: 1px solid #e7eaff;
            font-size: 14px;
        }
        
        .detail-row {
            display: flex;
            margin-bottom: 8px;
            width: 100%;
        }
        
        .detail-label {
            width: 130px;
            font-weight: 600;
            color: #444;
            flex-shrink: 0;
        }
        
        .detail-value {
            flex: 1;
            color: #2c3e50;
            word-wrap: break-word;
            overflow-wrap: break-word;
        }
        
        .amount {
            font-weight: 700;
            color: #6a11cb;
            font-size: 16px;
        }
        
        .payment-info {
            background: #fff8e6;
            border-radius: 8px;
            padding: 15px;
            border: 1px solid #ffe0a3;
            font-size: 14px;
        }
        
        .payment-method {
            font-weight: 600;
            color: #d97706;
            margin-bottom: 10px;
            display: flex;
            align-items: center;
        }
        
        .account-details {
            margin-top: 8px;
        }
        
        .action-buttons {
            text-align: center;
            margin: 25px 0 15px;
            padding: 0 10px;
        }
        
        .btn {
            display: inline-block;
            background: linear-gradient(135deg, #6a11cb 0%, #2575fc 100%);
            color: white;
            text-decoration: none;
            padding: 12px 20px;
            border-radius: 25px;
            font-weight: 600;
            font-size: 15px;
            transition: all 0.3s ease;
            box-shadow: 0 4px 15px rgba(106, 17, 203, 0.3);
            border: none;
            margin: 8px 5px;
            min-width: 180px;
        }
        
        .btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(106, 17, 203, 0.4);
        }
        
        .btn-outline {
            background: transparent;
            border: 2px solid #6a11cb;
            color: #6a11cb;
            box-shadow: none;
        }
        
        .btn-outline:hover {
            background: #6a11cb;
            color: white;
            transform: translateY(-2px);
        }
        
        .footer {
            background: #f8f9fa;
            padding: 20px;
            text-align: center;
            color: #6c757d;
            font-size: 13px;
            border-top: 1px solid #eee;
        }
        
        .footer-links {
            margin: 12px 0;
        }
        
        .footer-link {
            color: #6a11cb;
            text-decoration: none;
            margin: 0 8px;
            font-weight: 500;
            font-size: 13px;
        }
        
        .footer-link:hover {
            text-decoration: underline;
        }
        
        .social-icons {
            margin: 12px 0;
        }
        
        .social-icon {
            display: inline-block;
            width: 32px;
            height: 32px;
            background: #6a11cb;
            color: white;
            border-radius: 50%;
            line-height: 32px;
            text-align: center;
            margin: 0 5px;
            font-size: 14px;
            text-decoration: none;
        }
        
        .social-icon:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 10px rgba(106, 17, 203, 0.3);
        }
        
        .disclaimer {
            font-size: 11px;
            color: #999;
            margin-top: 15px;
            line-height: 1.5;
            text-align: center;
        }
        
        .logo {
            width: 50px;
            height: 50px;
            background: white;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 12px;
            box-shadow: 0 4px 15px rgba(0,0,0,0.1);
        }
        
        .logo-text {
            font-size: 20px;
            font-weight: 700;
            color: #6a11cb;
        }
        
        /* Mobile-specific styles */
        @media (max-width: 480px) {
            .email-container {
                margin: 0 10px;
                border-radius: 8px;
            }
            
            .header, .content, .footer {
                padding: 20px 15px;
            }
            
            .header h1 {
                font-size: 20px;
            }
            
            .header p {
                font-size: 14px;
            }
            
            .section-title {
                font-size: 16px;
            }
            
            .order-details, .payment-info {
                padding: 10px;
            }
            
            .detail-row {
                display: block;
                margin-bottom: 6px;
            }
            
            .detail-label {
                display: inline;
                width: auto;
                font-weight: 600;
            }
            
            .detail-value {
                display: inline;
                margin-left: 5px;
            }
            
            .action-buttons {
                margin: 20px 0;
            }
            
            .btn {
                display: block;
                margin: 10px auto;
                width: 100%;
                max-width: 250px;
                text-align: center;
            }
            
            .footer-link {
                display: block;
                margin: 5px 0;
            }
            
            .social-icons {
                margin: 10px 0;
            }
            
            .social-icon {
                margin: 0 3px;
            }
        }
    </style>
</head>
<body>
    <div class="email-wrapper">
        <div class="email-container">
            <div class="header">
                <div class="logo">
                    <div class="logo-text">VOS</div>
                </div>
                <h1>Ticket Order Confirmed</h1>
                <p>Your booking has been successfully processed</p>
            </div>

            <div class="content">
                <div class="section">
                    <h2 class="section-title">
                        <span>✓</span> Booking Details
                    </h2>
                    <div class="order-details">
                        <div class="detail-row">
                            <div class="detail-label">Booking Code</div>
                            <div class="detail-value"><strong>{{ $bookingCode }}</strong></div>
                        </div>
                        <div class="detail-row">
                            <div class="detail-label">Event</div>
                            <div class="detail-value">VOS Interval | Pre Competition Concert</div>
                        </div>
                        <div class="detail-row">
                            <div class="detail-label">Date & Time</div>
                            <div class="detail-value">{{ $eventDate }}, {{ $eventTime }} WIB</div>
                        </div>
                        <div class="detail-row">
                            <div class="detail-label">Seat(s)</div>
                            <div class="detail-value">{{ $seats }}</div>
                        </div>
                        <div class="detail-row">
                            <div class="detail-label">Total Amount</div>
                            <div class="detail-value amount">{{ $totalAmount }}</div>
                        </div>
                    </div>
                </div>

                <div class="section">
                    <h2 class="section-title">
                        <span>💳</span> Payment Instructions
                    </h2>
                    <div class="payment-info">
                        <div class="payment-method">
                            💳 Bank Transfer - BCA
                        </div>
                        <div class="account-details">
                            <div class="detail-row">
                                <div class="detail-label">Account Number</div>
                                <div class="detail-value">3420184785</div>
                            </div>
                            <div class="detail-row">
                                <div class="detail-label">Account Name</div>
                                <div class="detail-value">Ratno Juniarto MS</div>
                            </div>
                            <div class="detail-row">
                                <div class="detail-label">Amount</div>
                                <div class="detail-value amount">{{ $totalAmount }}</div>
                            </div>
                        </div>
                    </div>
                    <p style="margin-top: 12px; font-size: 13px;">
                        Please complete your payment within 3 days to secure your booking. 
                        Upload your payment proof through your transaction page.
                    </p>
                </div>

                <div class="action-buttons">
                    <a href="{{ $paymentUrl }}" class="btn">Complete Payment Now</a>
                    <a href="{{ $transactionUrl }}" class="btn btn-outline">View Transaction</a>
                </div>

                <div class="section">
                    <h2 class="section-title">
                        <span>ℹ️</span> Next Steps
                    </h2>
                    <ol style="padding-left: 18px; font-size: 14px;">
                        <li style="margin-bottom: 6px;">Complete your payment using the bank details above</li>
                        <li style="margin-bottom: 6px;">Upload payment proof at your transaction page</li>
                        <li style="margin-bottom: 6px;">Receive e-ticket confirmation via email</li>
                        <li>Bring your e-ticket to the event venue</li>
                    </ol>
                </div>
            </div>

            <div class="footer">
                <div class="social-icons">
                    <a href="https://wa.me/6285823536364" class="social-icon">💬</a>
                    <a href="#" class="social-icon">📱</a>
                    <a href="#" class="social-icon">📷</a>
                </div>
                
                <div class="footer-links">
                    <a href="{{ $helpCenterUrl }}" class="footer-link">Help Center</a>
                    <a href="{{ $termsUrl }}" class="footer-link">Terms</a>
                    <a href="{{ $privacyUrl }}" class="footer-link">Privacy</a>
                </div>
                
                <p>
                    Need assistance? Contact our customer support:<br>
                    <a href="https://wa.me/6285823536364" style="color: #6a11cb; text-decoration: none;">WhatsApp Support</a> or 
                    <a href="mailto:cs@voiceofsoulchoir.id" style="color: #6a11cb; text-decoration: none;">cs@voiceofsoulchoir.id</a>
                </p>
                
                <div class="disclaimer">
                    This is an automated message. Please do not reply directly to this email.<br>
                    © 2024 Voice of Soul Choir. All rights reserved.
                </div>
            </div>
        </div>
    </div>
</body>
</html>