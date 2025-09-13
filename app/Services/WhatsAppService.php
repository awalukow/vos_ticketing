<?php

namespace App\Services;

use Twilio\Rest\Client;

class WhatsAppService
{
    protected $twilio;

    public function __construct()
    {
        $this->twilio = new Client(
            config('services.twilio.sid'),
            config('services.twilio.auth_token')
        );
    }

    /**
     * Send a freeform WhatsApp message (only valid within 24-hour window)
     */
    public function sendMessage($to, $message)
    {
        $this->twilio->messages->create(
            "whatsapp:$to",
            [
                'from' => config('services.twilio.whatsapp_number'),
                'body' => $message
            ]
        );
    }

    /**
     * Send a WhatsApp template message (outside 24-hour window)
     * 
     * @param string $to Recipient phone number (without 'whatsapp:' prefix)
     * @param string $contentSid The Content SID (template ID) from Twilio
     * @param array $variables Variables to fill the template placeholders
     */
    public function sendWA($to, $contentSid, array $variables)
    {
        $this->twilio->messages->create(
            "whatsapp:+$to",
            [
                'from' => config('services.twilio.whatsapp_number'),
                'contentSid' => $contentSid,
                'contentVariables' => json_encode($variables),
            ]
        );
    }

    /**
     * Send an SMS message
     */
    public function sendSms($to, $message)
    {
        $this->twilio->messages->create(
            "+$to",
            [
                'from' => config('services.twilio.sms_number'), // Must be a Twilio SMS-enabled number
                'body' => $message
            ]
        );
    }
}
