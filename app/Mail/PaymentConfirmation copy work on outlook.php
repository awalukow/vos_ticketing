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
        // Generate QR codes for each seat and convert to base64
        $base64QrCodes = [];
        $seats = explode(', ', $this->bookingData['seats']);
        foreach ($seats as $seat) {
            $qrCode = new QrCode($this->bookingData['bookingCode'] . '_' . trim($seat));
            $writer = new PngWriter();

            // Generate the PNG result
            $result = $writer->write($qrCode);

            // Get the raw PNG data as a string
            $pngData = $result->getString(); // Use getString() to get the raw PNG data

            // Convert the PNG data to base64
            $base64QrCodes[] = base64_encode($pngData); // Convert PNG to base64 and store
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
                        'base64QrCodes' => $base64QrCodes, // Pass base64 QR codes to view
                    ]);
    }
}
