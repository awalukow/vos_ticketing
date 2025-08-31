<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>VOS Ticket Order Confirmation</title>
    <style>
        /* This style will be stripped by Gmail, but kept for clients that support it */
        @media (max-width: 600px) {
            .container { width: 100% !important; margin: 0 !important; }
            .content { padding: 15px !important; }
            .button-container { display: block !important; }
            .button { display: block !important; width: 100% !important; margin-bottom: 10px !important; }
        }
    </style>
</head>
<body style="margin: 0; padding: 0; background-color: #f8f9fa; font-family: Arial, sans-serif;">

    <!-- Main wrapper for email clients -->
    <table width="100%" cellpadding="0" cellspacing="0" border="0">
        <tr>
            <td align="center" style="padding: 20px 0;">
                
                <!-- Main container -->
                <table class="container" width="650" cellpadding="0" cellspacing="0" border="0" style="max-width: 650px; background-color: #ffffff; border: 1px solid #e9ecef; border-radius: 8px; overflow: hidden; box-shadow: 0 4px 12px rgba(0,0,0,0.1);">
                    
                    <!-- Header -->
                    <tr>
                        <td style="background-color: #6a11cb; padding: 25px 20px; text-align: center; border-bottom: 3px solid #2575fc;">
                            <table width="100%" cellpadding="0" cellspacing="0" border="0">
                                <tr>
                                    <td align="center" style="padding-bottom: 15px;">
                                        <table width="50" height="50" cellpadding="0" cellspacing="0" border="0" style="background-color: #ffffff; border-radius: 50%; display: inline-block; box-shadow: 0 4px 8px rgba(0,0,0,0.1);">
                                            <tr>
                                                <!--<td align="center" style="font-size: 20px; font-weight: bold; color: #6a11cb;">VOS</td>-->
                                                <img src="{{ asset('img/favicon.png') }}" alt="Logo" class="custom-logo">
                                            </tr>
                                        </table>
                                    </td>
                                </tr>
                                <tr>
                                    <td align="center" style="color: white; font-size: 22px; font-weight: bold; padding: 5px 0;">
                                        Ticket Order Confirmed
                                    </td>
                                </tr>
                                <tr>
                                    <td align="center" style="color: rgba(255,255,255,0.9); font-size: 15px;">
                                        Your booking has been successfully processed
                                    </td>
                                </tr>
                            </table>
                        </td>
                    </tr>
                    
                    <!-- Content -->
                    <tr>
                        <td class="content" style="padding: 25px 20px;">
                            
                            <!-- Booking Details -->
                            <table width="100%" cellpadding="0" cellspacing="0" border="0" style="margin-bottom: 20px;">
                                <tr>
                                    <td style="color: #2c3e50; font-size: 18px; font-weight: bold; padding-bottom: 12px; border-bottom: 1px solid #eee;">
                                        ✓ Booking Details
                                    </td>
                                </tr>
                            </table>
                            
                            <table width="100%" cellpadding="0" cellspacing="0" border="0" style="background-color: #f8f9ff; border: 1px solid #e7eaff; border-radius: 6px; padding: 12px 15px; margin-bottom: 20px;">
                                <tr>
                                    <td width="130" style="font-weight: bold; color: #444; padding: 8px 0; font-size: 14px;">Booking Code</td>
                                    <td style="color: #2c3e50; padding: 8px 0; font-size: 14px;"><strong>{{ $bookingCode }}</strong></td>
                                </tr>
                                <tr>
                                    <td style="font-weight: bold; color: #444; padding: 8px 0; font-size: 14px;">Event</td>
                                    <td style="color: #2c3e50; padding: 8px 0; font-size: 14px;">{{ $eventName }}</td>
                                </tr>
                                <tr>
                                    <td style="font-weight: bold; color: #444; padding: 8px 0; font-size: 14px;">Date & Time</td>
                                    <td style="color: #2c3e50; padding: 8px 0; font-size: 14px;">{{ $eventDate }}, {{ $eventTime }} WIB</td>
                                </tr>
                                <tr>
                                    <td style="font-weight: bold; color: #444; padding: 8px 0; font-size: 14px;">Seat(s)</td>
                                    <td style="color: #2c3e50; padding: 8px 0; font-size: 14px;">{{ $seats }}</td>
                                </tr>
                                <tr>
                                    <td style="font-weight: bold; color: #444; padding: 8px 0; font-size: 14px;">Total Amount</td>
                                    <td style="color: #6a11cb; font-weight: bold; font-size: 16px;">Rp {{ number_format($totalAmount, 0, ',', '.') }}</td>
                                </tr>
                            </table>
                            
                            <!-- Payment Instructions -->
                            <table width="100%" cellpadding="0" cellspacing="0" border="0" style="margin-bottom: 20px;">
                                <tr>
                                    <td style="color: #2c3e50; font-size: 18px; font-weight: bold; padding-bottom: 12px; border-bottom: 1px solid #eee;">
                                        💳 Payment Instructions
                                    </td>
                                </tr>
                            </table>
                            
                            <table width="100%" cellpadding="0" cellspacing="0" border="0" style="background-color: #fff8e6; border: 1px solid #ffe0a3; border-radius: 6px; padding: 15px; margin-bottom: 20px;">
                                <tr>
                                    <td style="font-weight: bold; color: #d97706; padding-bottom: 10px; font-size: 14px;">
                                        Bank Transfer - Bank JAGO
                                    </td>
                                </tr>
                                <tr>
                                    <td style="padding-top: 8px; font-size: 14px;">
                                        <table width="100%" cellpadding="0" cellspacing="0" border="0">
                                            <tr>
                                                <td width="130" style="font-weight: bold; color: #444; padding: 6px 0;">Account Number</td>
                                                <td style="color: #2c3e50; padding: 6px 0;">1058 7839 6486</td>
                                            </tr>
                                            <tr>
                                                <td style="font-weight: bold; color: #444; padding: 6px 0;">Account Name</td>
                                                <td style="color: #2c3e50; padding: 6px 0;">Ratno Juniarto MS</td>
                                            </tr>
                                            <tr>
                                                <td style="font-weight: bold; color: #444; padding: 6px 0;">Amount</td>
                                                <td style="color: #6a11cb; font-weight: bold; font-size: 16px;">Rp {{ number_format($totalAmount, 0, ',', '.') }}</td>
                                            </tr>
                                        </table>
                                    </td>
                                </tr>
                                <br>
                                <tr>
                                    <td style="font-weight: bold; color: #d97706; padding-bottom: 10px; font-size: 14px;">
                                        QRIS
                                    </td>
                                </tr>
                                <tr>
                                    <td style="padding-top: 8px; font-size: 14px;">
                                        <table width="100%" cellpadding="0" cellspacing="0" border="0">
                                            <tr>
                                                <td align="center">
                                                    <img src="{{ asset('img/qris-vos-gopay.png') }}" alt="QR Code" style="max-width: 150px; height: auto;">
                                                </td>
                                            </tr>
                                            <tr>
                                                <td style="color: #2c3e50; padding: 6px 0;" align="center">
                                                    Apabila QRIS tidak muncul, silahkan klik tombol "View Transaction"
                                                </td>
                                            </tr>
                                        </table>
                                    </td>
                                </tr>
                            </table>

                            <div class="ms-3 d-flex align-items-center">
                            </div>
                            <!-- Action Buttons -->
                            <table class="button-container" width="100%" cellpadding="0" cellspacing="0" border="0" style="text-align: center; margin: 20px 0;">
                                <tr>
                                    <td>
                                        <table cellpadding="0" cellspacing="0" border="0" style="display: inline-block; margin: 0 5px;">
                                            <tr>
                                                <td align="center" bgcolor="#6a11cb" style="border-radius: 25px; padding: 12px 25px;">
                                                    <a href="{{ $paymentUrl }}" target="_blank" style="font-size: 15px; font-weight: bold; color: #ffffff; text-decoration: none; display: inline-block;">
                                                        Complete Payment Now
                                                    </a>
                                                </td>
                                            </tr>
                                        </table>
                                        <table cellpadding="0" cellspacing="0" border="0" style="display: inline-block; margin: 0 5px;">
                                            <tr>
                                                <td align="center" style="border-radius: 25px; padding: 10px 23px; border: 2px solid #6a11cb;">
                                                    <a href="{{ $transactionUrl }}" target="_blank" style="font-size: 15px; font-weight: bold; color: #6a11cb; text-decoration: none; display: inline-block;">
                                                        View Transaction
                                                    </a>
                                                </td>
                                            </tr>
                                        </table>
                                    </td>
                                </tr>
                            </table>
                            
                            <!-- Next Steps -->
                            <table width="100%" cellpadding="0" cellspacing="0" border="0" style="margin-bottom: 15px;">
                                <tr>
                                    <td style="color: #2c3e50; font-size: 18px; font-weight: bold; padding-bottom: 12px; border-bottom: 1px solid #eee;">
                                        ℹ️ Next Steps
                                    </td>
                                </tr>
                            </table>
                            
                            <div style="text-align: center;">
                                <ol style="color: #333; font-size: 14px; margin: 15px 0 0 20px; padding: 0; display: inline-block; text-align: left;">
                                    <li style="margin-bottom: 8px;">Complete your payment using the bank details above</li>
                                    <li style="margin-bottom: 8px;">Upload payment proof at your transaction page</li>
                                    <li style="margin-bottom: 8px;">Receive e-ticket confirmation via email</li>
                                    <li>Bring your e-ticket to the event venue</li>
                                </ol>
                            </div>
                        </td>
                    </tr>
                    
                    <!-- Footer -->
                    <tr>
                        <td style="background-color: #f8f9fa; padding: 20px; text-align: center; border-top: 1px solid #eee; color: #6c757d; font-size: 13px;">
                            
                            <table width="100%" cellpadding="0" cellspacing="0" border="0" style="margin-bottom: 12px;">
                                <tr>
                                    <td align="center">
                                        <a href="https://wa.me/62818290913" target="_blank" style="display: inline-block; width: 32px; height: 32px; background-color: #6a11cb; border-radius: 50%; color: #ffffff; text-decoration: none; margin: 0 5px; line-height: 32px;">
                                            💬
                                        </a>
                                        <a href="#" target="_blank" style="display: inline-block; width: 32px; height: 32px; background-color: #6a11cb; border-radius: 50%; color: #ffffff; text-decoration: none; margin: 0 5px; line-height: 32px;">
                                            📱
                                        </a>
                                        <a href="#" target="_blank" style="display: inline-block; width: 32px; height: 32px; background-color: #6a11cb; border-radius: 50%; color: #ffffff; text-decoration: none; margin: 0 5px; line-height: 32px;">
                                            📷
                                        </a>
                                    </td>
                                </tr>
                            </table>
                            
                            <table width="100%" cellpadding="0" cellspacing="0" border="0" style="margin-bottom: 12px;">
                                <tr>
                                    <td align="center">
                                        <a href="{{ $helpCenterUrl }}" target="_blank" style="color: #6a11cb; text-decoration: none; margin: 0 8px; font-size: 13px;">Help Center</a>
                                        <a href="{{ $termsUrl }}" target="_blank" style="color: #6a11cb; text-decoration: none; margin: 0 8px; font-size: 13px;">Terms</a>
                                        <a href="{{ $privacyUrl }}" target="_blank" style="color: #6a11cb; text-decoration: none; margin: 0 8px; font-size: 13px;">Privacy</a>
                                    </td>
                                </tr>
                            </table>
                            
                            <p style="margin: 8px 0; color: #6c757d; font-size: 13px;">
                                Need assistance? Contact our customer support:<br>
                                <a href="https://wa.me/62818290913" target="_blank" style="color: #6a11cb; text-decoration: none;">WhatsApp Support</a> or 
                                <a href="mailto:cs@voiceofsoulchoir.id" style="color: #6a11cb; text-decoration: none;">cs@voiceofsoulchoir.id</a>
                            </p>
                            
                            <p style="margin: 15px 0 0; color: #999; font-size: 11px; line-height: 1.5;">
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