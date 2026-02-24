<?php

namespace App\Helpers;

use App\Models\EmailConfigure;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class EmailHelper
{
    /**
     * Configure SMTP dynamically from EmailConfigure table and send a Mailable.
     *
     * @param string $toEmail
     * @param \Illuminate\Mail\Mailable $mailable
     * @return bool
     */
    public static function send(string $toEmail, $mailable): bool
    {
        $emailConfig = EmailConfigure::where('id', 1)->first();

        if (!$emailConfig) {
            Log::warning('EmailHelper: No email configuration found.');
            return false;
        }

        try {
            config([
                'mail.default' => 'smtp',
                'mail.mailers.smtp.transport' => 'smtp',
                'mail.mailers.smtp.host' => $emailConfig->host,
                'mail.mailers.smtp.port' => $emailConfig->port,
                'mail.mailers.smtp.encryption' => $emailConfig->encryption == 1 ? 'tls' : ($emailConfig->encryption == 2 ? 'ssl' : null),
                'mail.mailers.smtp.username' => $emailConfig->email,
                'mail.mailers.smtp.password' => $emailConfig->password,
                'mail.from.address' => $emailConfig->mail_from_email,
                'mail.from.name' => $emailConfig->mail_from_name ?? env('APP_NAME'),
            ]);

            Mail::to(trim($toEmail))->send($mailable);
            return true;
        } catch (\Exception $e) {
            Log::error('EmailHelper: Failed to send email - ' . $e->getMessage(), [
                'to' => $toEmail,
                'mailable' => get_class($mailable),
            ]);
            return false;
        }
    }
}
