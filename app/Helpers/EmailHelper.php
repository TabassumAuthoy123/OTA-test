<?php

namespace App\Helpers;

use App\Models\EmailConfigure;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class EmailHelper
{
    /**
     * Configure SMTP dynamically from EmailConfigure table (falls back to .env) and send a Mailable.
     */
    public static function send(string $toEmail, $mailable): bool
    {
        if (empty(trim($toEmail))) {
            Log::warning('EmailHelper: Empty recipient address, skipping.');
            return false;
        }

        $emailConfig = EmailConfigure::where('id', 1)->first();

        // Build SMTP settings — prefer DB, fall back to .env
        $host       = ($emailConfig && !empty($emailConfig->host))           ? $emailConfig->host           : env('MAIL_HOST', 'localhost');
        $port       = ($emailConfig && !empty($emailConfig->port))           ? (int) $emailConfig->port     : (int) env('MAIL_PORT', 587);
        $username   = ($emailConfig && !empty($emailConfig->email))          ? $emailConfig->email          : env('MAIL_USERNAME');
        $password   = ($emailConfig && !empty($emailConfig->password))       ? $emailConfig->password       : env('MAIL_PASSWORD');
        $fromName   = ($emailConfig && !empty($emailConfig->mail_from_name)) ? $emailConfig->mail_from_name : env('MAIL_FROM_NAME', config('app.name'));
        $fromEmail  = ($emailConfig && !empty($emailConfig->mail_from_email))? $emailConfig->mail_from_email: env('MAIL_FROM_ADDRESS', $username);

        $encRaw     = $emailConfig ? $emailConfig->encryption : null;
        $encryption = match((int) $encRaw) {
            1       => 'tls',
            2       => 'ssl',
            default => env('MAIL_ENCRYPTION') ?: null,
        };

        try {
            config([
                'mail.default'                      => 'smtp',
                'mail.mailers.smtp.transport'       => 'smtp',
                'mail.mailers.smtp.host'            => $host,
                'mail.mailers.smtp.port'            => $port,
                'mail.mailers.smtp.encryption'      => $encryption,
                'mail.mailers.smtp.username'        => $username,
                'mail.mailers.smtp.password'        => $password,
                'mail.from.address'                 => $fromEmail,
                'mail.from.name'                    => $fromName,
            ]);

            Mail::to(trim($toEmail))->send($mailable);
            return true;
        } catch (\Exception $e) {
            Log::error('EmailHelper: Failed to send email — ' . $e->getMessage(), [
                'to'       => $toEmail,
                'mailable' => get_class($mailable),
                'host'     => $host,
            ]);
            return false;
        }
    }
}
