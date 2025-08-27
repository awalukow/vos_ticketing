<?php

namespace App\Mail;

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
     * Path to the PDF attachment.
     *
     * @var string|null
     */
    private $pdfPath;

    /**
     * Create a new message instance.
     *
     * @param array $bookingData
     * @param string|null $pdfPath
     * @return void
     */
    public function __construct($bookingData, $pdfPath = null)
    {
        $this->bookingData = $bookingData;
        $this->pdfPath = $pdfPath;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        $mail = $this->subject('[VOS] Pembayaran Berhasil! - Kode Booking : ' . ($this->bookingData['bookingCode'] ?? ''))
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
                    ]);

        // Add PDF attachment if path is provided and file exists
        if ($this->pdfPath && file_exists($this->pdfPath)) {
            $mail->attach($this->pdfPath, [
                'as' => 'E-Ticket_VOS_' . ($this->bookingData['bookingCode'] ?? 'ticket') . '.pdf',
                'mime' => 'application/pdf'
            ]);
        }

        return $mail;
    }
}