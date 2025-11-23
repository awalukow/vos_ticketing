<?php

namespace App\Mail;

use Endroid\QrCode\QrCode;
use Endroid\QrCode\Writer\PngWriter;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class PaymentConfirmation extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * The booking data.
     *
     * @var array
     */
    public $bookingData;

    /**
     * Create a new message instance.
     *
     * @param array $bookingData
     * @return void
     */
    public function __construct($bookingData)
    {
        $this->bookingData = $bookingData;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        // Generate QR codes for each seat and embed them as attachments
        $seats = explode(', ', $this->bookingData['seats']);
        $attachments = [];
        
        foreach ($seats as $index => $seat) {
            $qrCode = new QrCode($this->bookingData['bookingCode'] . '_' . trim($seat));
            $writer = new PngWriter();

            // Generate the PNG result
            $result = $writer->write($qrCode);

            // Get the raw PNG data
            $pngData = $result->getString();

            // Create a unique CID for each QR code
            $cid = 'qr_' . $index . '@' . config('app.url');

            // Convert the PNG data to base64
            $base64QrCodes[] = base64_encode($pngData); // Convert PNG to base64 and store

            // Attach the PNG image with the CID reference
            $attachments[] = [
                'data' => $pngData,
                'cid' => $cid,
                'filename' => "seat_{$seat}.png"
            ];

            // Attach each image to the email
            $this->attachData($pngData, "seat_{$seat}.png", [
                'mime' => 'image/png',
                'cid' => $cid,
            ]);
        }

        return $this->subject('[VOS] Pembayaran Berhasil! - Kode Booking : ' . ($this->bookingData['bookingCode'] ?? ''))
                    ->markdown('emails.payment-confirmation', [
                        'bookingCode' => $this->bookingData['bookingCode'] ?? '',
                        'eventDate' => $this->bookingData['eventDate'] ?? '',
                        'eventTime' => $this->bookingData['eventTime'] ?? '',
                        'seats' => $this->bookingData['seats'] ?? '',
                        'totalAmount' => $this->bookingData['totalAmount'] ?? '',
                        'paymentUrl' => $this->bookingData['paymentUrl'] ?? '',
                        'transactionUrl' => $this->bookingData['transactionUrl'] ?? '',
                        'helpCenterUrl' => $this->bookingData['helpCenterUrl'] ?? '',
                        'termsUrl' => $this->bookingData['termsUrl'] ?? '',
                        'privacyUrl' => $this->bookingData['privacyUrl'] ?? '',
                        'eventName' => $this->bookingData['eventName'] ?? '',
                        'paymentExpiry' => $this->bookingData['paymentExpiry'] ?? '',
                        'attachments' => $attachments, // Pass the attachments (CID)
                        'base64QrCodes' => $base64QrCodes, // Pass base64 QR codes to view
                        'cs' => $this->bookingData['cs'] ?? ''
                    ]);
    }
}
