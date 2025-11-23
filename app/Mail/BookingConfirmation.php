<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class BookingConfirmation extends Mailable
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
        return $this->subject('[VOS] Pesanan Tiket Konser VOS anda telah berhasil - Kode Booking : ' . ($this->bookingData['bookingCode'] ?? ''))
                    ->markdown('emails.booking-confirmation', [
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
                        'cs' => $this->bookingData['cs'] ?? '',
                    ]);
    }
}