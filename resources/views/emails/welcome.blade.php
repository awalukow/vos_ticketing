<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Welcome to E-Ticketing VOS - Account Created</title>
</head>
<body style="margin: 0; padding: 0; background-color: #f8f9fa; font-family: Arial, sans-serif;">

    <!-- Main wrapper for email clients -->
    <table width="100%" cellpadding="0" cellspacing="0" border="0" style="background-color: #f8f9fa; padding: 20px 0;">
        <tr>
            <td align="center">
                
                <!-- Main container -->
                <table width="650" cellpadding="0" cellspacing="0" border="0" style="background-color: #ffffff; border: 1px solid #e9ecef; border-radius: 8px; overflow: hidden; box-shadow: 0 4px 12px rgba(0,0,0,0.1);">
                    
                    <!-- Header -->
                    <tr>
                        <td style="background: linear-gradient(135deg, #6a11cb 0%, #2575fc 100%); padding: 30px 20px; text-align: center; border-bottom: 3px solid #2575fc;">
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
                                        Welcome to VOS e-Ticket!
                                    </td>
                                </tr>
                                <tr>
                                    <td align="center" style="color: rgba(255,255,255,0.9); font-size: 15px;">
                                        Your account has been successfully created
                                    </td>
                                </tr>
                                <tr>
                                    <td align="center" style="padding-top: 10px;">
                                        <span style="font-size: 36px; color: white; font-weight: bold;">✓</span>
                                    </td>
                                </tr>
                            </table>
                        </td>
                    </tr>
                    
                    <!-- Content -->
                    <tr>
                        <td style="padding: 25px 20px;">
                            
                            <!-- Account Information -->
                            <h2 style="color: #2c3e50; font-size: 18px; font-weight: 600; margin: 0 0 12px 0;">Your Account Information</h2>
                            
                            <table width="100%" cellpadding="0" cellspacing="0" border="0" style="background-color: #f8f9ff; border: 1px solid #e7eaff; border-radius: 6px; padding: 12px 15px; margin-bottom: 20px;">
                                <tr>
                                    <td width="130" style="font-weight: bold; color: #444; padding: 8px 0; font-size: 14px;">Name</td>
                                    <td style="color: #2c3e50; padding: 8px 0; font-size: 14px;">{{ $name }}</td>
                                </tr>
                                <tr>
                                    <td style="font-weight: bold; color: #444; padding: 8px 0; font-size: 14px;">Username</td>
                                    <td style="color: #2c3e50; padding: 8px 0; font-size: 14px;">{{ $username }}</td>
                                </tr>
                                <tr>
                                    <td style="font-weight: bold; color: #444; padding: 8px 0; font-size: 14px;">Password</td>
                                    <td style="color: #2c3e50; padding: 8px 0; font-size: 14px;">{{ $password }}</td>
                                </tr>
                            </table>
                            
                            <!-- Security Notice -->
                            <div style="background-color: #fff8e6; border: 1px solid #ffe0a3; border-radius: 6px; padding: 12px; margin: 15px 0;">
                                <strong style="color: #d97706;">Security Notice:</strong> 
                                <p style="margin: 8px 0 0; font-size: 14px; color: #666;">
                                    For your account security, we recommend changing your password immediately after logging in.
                                </p>
                            </div>
                            
                            <!-- Action Button -->
                            <table width="100%" cellpadding="0" cellspacing="0" border="0" style="text-align: center; margin: 25px 0;">
                                <tr>
                                    <td>
                                        <table cellpadding="0" cellspacing="0" border="0" style="display: inline-block;">
                                            <tr>
                                                <td align="center" bgcolor="#6a11cb" style="border-radius: 25px; padding: 12px 25px;">
                                                    <a href="{{ $settingsUrl }}" target="_blank" style="font-size: 15px; font-weight: bold; color: #ffffff; text-decoration: none; display: inline-block;">
                                                        Change Password
                                                    </a>
                                                </td>
                                            </tr>
                                        </table>
                                    </td>
                                </tr>
                            </table>
                            
                            <!-- Next Steps -->
                            <h2 style="color: #2c3e50; font-size: 18px; font-weight: 600; margin: 0 0 12px 0;">Next Steps</h2>
                            <ol style="color: #333; font-size: 14px; margin: 15px 0 0 20px; padding: 0;">
                                <li style="margin-bottom: 8px;">Use your username and password to log in to your account</li>
                                <li style="margin-bottom: 8px;">Change your password for better security</li>
                                <li style="margin-bottom: 8px;">Browse available events and book your tickets</li>
                                <li>Enjoy the concert!</li>
                            </ol>
                            
                        </td>
                    </tr>
                    
                    <!-- Footer -->
                    <tr>
                        <td style="background-color: #f8f9fa; padding: 20px; text-align: center; border-top: 1px solid #eee; color: #6c757d; font-size: 13px;">
                            
                            <table width="100%" cellpadding="0" cellspacing="0" border="0" style="margin-bottom: 12px;">
                                <tr>
                                    <td align="center">
                                        <a href="https://wa.me/6285823536364" target="_blank" style="display: inline-block; width: 32px; height: 32px; background-color: #6a11cb; border-radius: 50%; color: #ffffff; text-decoration: none; margin: 0 5px; line-height: 32px;">
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
                                <a href="https://wa.me/6285823536364" target="_blank" style="color: #6a11cb; text-decoration: none;">WhatsApp Support</a> or 
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