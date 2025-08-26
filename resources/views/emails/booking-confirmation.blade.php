@component('mail::message')
<div style="font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; line-height: 1.6; color: #333; background-color: #f8f9fa; margin: 0; padding: 0;">
    <div style="max-width: 650px; margin: 30px auto; background: white; border-radius: 12px; overflow: hidden; box-shadow: 0 8px 25px rgba(0,0,0,0.1); border: 1px solid #e9ecef;">
        <div style="background: linear-gradient(135deg, #6a11cb 0%, #2575fc 100%); color: white; padding: 30px 30px 20px; text-align: center;">
            <div style="width: 60px; height: 60px; background: white; border-radius: 50%; display: flex; align-items: center; justify-content: center; margin: 0 auto 15px; box-shadow: 0 4px 15px rgba(0,0,0,0.1);">
                <div style="font-size: 24px; font-weight: 700; color: #6a11cb;">VOS</div>
            </div>
            <h1 style="margin: 0; font-size: 28px; font-weight: 700; letter-spacing: -0.5px;">Ticket Order Confirmed</h1>
            <p style="margin: 8px 0 0; opacity: 0.9; font-size: 16px;">Your booking has been successfully processed</p>
        </div>

        <div style="padding: 30px;">
            <div style="margin-bottom: 25px; padding-bottom: 20px; border-bottom: 1px solid #eee;">
                <h2 style="color: #2c3e50; font-size: 20px; font-weight: 600; margin: 0 0 15px 0; display: flex; align-items: center;">
                    <i style="margin-right: 10px; color: #6a11cb;">✓</i> Booking Details
                </h2>
                <div style="background: #f8f9ff; border-radius: 8px; padding: 15px 20px; border: 1px solid #e7eaff;">
                    <div style="display: flex; margin-bottom: 10px; font-size: 15px;">
                        <div style="width: 180px; font-weight: 600; color: #444;">Booking Code</div>
                        <div style="flex: 1; color: #2c3e50;"><strong>{{ $bookingCode }}</strong></div>
                    </div>
                    <div style="display: flex; margin-bottom: 10px; font-size: 15px;">
                        <div style="width: 180px; font-weight: 600; color: #444;">Event</div>
                        <div style="flex: 1; color: #2c3e50;">VOS Interval | Pre Competition Concert</div>
                    </div>
                    <div style="display: flex; margin-bottom: 10px; font-size: 15px;">
                        <div style="width: 180px; font-weight: 600; color: #444;">Date & Time</div>
                        <div style="flex: 1; color: #2c3e50;">{{ $eventDate }}, {{ $eventTime }} WIB</div>
                    </div>
                    <div style="display: flex; margin-bottom: 10px; font-size: 15px;">
                        <div style="width: 180px; font-weight: 600; color: #444;">Seat(s)</div>
                        <div style="flex: 1; color: #2c3e50;">{{ $seats }}</div>
                    </div>
                    <div style="display: flex; margin-bottom: 10px; font-size: 15px;">
                        <div style="width: 180px; font-weight: 600; color: #444;">Total Amount</div>
                        <div style="flex: 1; color: #6a11cb; font-weight: 700; font-size: 18px;">{{ $totalAmount }}</div>
                    </div>
                </div>
            </div>

            <div style="margin-bottom: 25px; padding-bottom: 20px; border-bottom: 1px solid #eee;">
                <h2 style="color: #2c3e50; font-size: 20px; font-weight: 600; margin: 0 0 15px 0; display: flex; align-items: center;">
                    <i style="margin-right: 10px; color: #6a11cb;">💳</i> Payment Instructions
                </h2>
                <div style="background: #fff8e6; border-radius: 8px; padding: 18px 20px; border: 1px solid #ffe0a3;">
                    <div style="font-weight: 600; color: #d97706; margin-bottom: 12px; display: flex; align-items: center;">
                        💳 Bank Transfer - BCA
                    </div>
                    <div style="margin-top: 10px; font-size: 14px;">
                        <div style="display: flex; margin-bottom: 10px; font-size: 15px;">
                            <div style="width: 180px; font-weight: 600; color: #444;">Account Number</div>
                            <div style="flex: 1; color: #2c3e50;">3420184785</div>
                        </div>
                        <div style="display: flex; margin-bottom: 10px; font-size: 15px;">
                            <div style="width: 180px; font-weight: 600; color: #444;">Account Name</div>
                            <div style="flex: 1; color: #2c3e50;">Ratno Juniarto MS</div>
                        </div>
                        <div style="display: flex; margin-bottom: 10px; font-size: 15px;">
                            <div style="width: 180px; font-weight: 600; color: #444;">Amount</div>
                            <div style="flex: 1; color: #6a11cb; font-weight: 700; font-size: 18px;">{{ $totalAmount }}</div>
                        </div>
                    </div>
                </div>
                <p style="margin-top: 15px; font-size: 14px;">
                    Please complete your payment within 3 days to secure your booking. 
                    Upload your payment proof through your transaction page.
                </p>
            </div>

            <div style="text-align: center; margin: 30px 0;">
                @component('mail::button', ['url' => $paymentUrl, 'color' => 'primary'])
                Complete Payment Now
                @endcomponent

                @component('mail::button', ['url' => $transactionUrl, 'color' => 'secondary'])
                View Transaction
                @endcomponent
            </div>

            <div style="margin-bottom: 0; padding-bottom: 0;">
                <h2 style="color: #2c3e50; font-size: 20px; font-weight: 600; margin: 0 0 15px 0; display: flex; align-items: center;">
                    <i style="margin-right: 10px; color: #6a11cb;">ℹ️</i> Next Steps
                </h2>
                <ol style="padding-left: 20px; font-size: 15px;">
                    <li style="margin-bottom: 8px;">Complete your payment using the bank details above</li>
                    <li style="margin-bottom: 8px;">Upload payment proof at your transaction page</li>
                    <li style="margin-bottom: 8px;">Receive e-ticket confirmation via email</li>
                    <li>Bring your e-ticket to the event venue</li>
                </ol>
            </div>
        </div>

        <div style="background: #f8f9fa; padding: 25px 30px; text-align: center; color: #6c757d; font-size: 14px; border-top: 1px solid #eee;">
            <div style="margin: 15px 0;">
                <a href="https://wa.me/6285823536364" style="display: inline-block; width: 36px; height: 36px; background: #6a11cb; color: white; border-radius: 50%; line-height: 36px; text-align: center; margin: 0 6px; font-size: 16px; text-decoration: none;">💬</a>
                <a href="#" style="display: inline-block; width: 36px; height: 36px; background: #6a11cb; color: white; border-radius: 50%; line-height: 36px; text-align: center; margin: 0 6px; font-size: 16px; text-decoration: none;">📱</a>
                <a href="#" style="display: inline-block; width: 36px; height: 36px; background: #6a11cb; color: white; border-radius: 50%; line-height: 36px; text-align: center; margin: 0 6px; font-size: 16px; text-decoration: none;">📷</a>
            </div>
            
            <div style="margin: 15px 0;">
                <a href="{{ $helpCenterUrl }}" style="color: #6a11cb; text-decoration: none; margin: 0 10px; font-weight: 500;">Help Center</a>
                <a href="{{ $termsUrl }}" style="color: #6a11cb; text-decoration: none; margin: 0 10px; font-weight: 500;">Terms & Conditions</a>
                <a href="{{ $privacyUrl }}" style="color: #6a11cb; text-decoration: none; margin: 0 10px; font-weight: 500;">Privacy Policy</a>
            </div>
            
            <p>
                Need assistance? Contact our customer support:<br>
                <a href="https://wa.me/6285823536364" style="color: #6a11cb; text-decoration: none;">WhatsApp Support</a> or 
                <a href="mailto:cs@voiceofsoulchoir.id" style="color: #6a11cb; text-decoration: none;">cs@voiceofsoulchoir.id</a>
            </p>
            
            <div style="font-size: 12px; color: #999; margin-top: 20px; line-height: 1.5; text-align: center;">
                This is an automated message. Please do not reply directly to this email.<br>
                © 2024 Voice of Soul Choir. All rights reserved.
            </div>
        </div>
    </div>
</div>

Thanks,<br>
{{ config('app.name') }}
@endcomponent