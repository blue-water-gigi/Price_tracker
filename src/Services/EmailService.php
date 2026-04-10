<?php

declare(strict_types=1);

namespace App\Services;

use PHPMailer\PHPMailer\PHPMailer;

class EmailService
{
    public function __construct(private array $config)
    {
    }

    private function createMailer(): PHPMailer
    {
        $mail = new PHPMailer(true);
        $mail->SMTPDebug = (int) ($this->config['debug'] ?? 0);
        $mail->isSMTP();
        $mail->CharSet = PHPMailer::CHARSET_UTF8;
        $mail->Host = $this->config['host'];
        $mail->SMTPAuth = (bool) ($this->config['auth'] ?? false);
        $mail->Username = (string) ($this->config['username'] ?? '');
        $mail->Password = (string) ($this->config['password'] ?? '');
        $mail->Port = (int) $this->config['port'];

        // Mailpit / local SMTP: plain text on 1025. Real providers: tls/ssl via MAIL_ENCRYPTION.
        $enc = strtolower((string) ($this->config['encryption'] ?? ''));
        if ($enc === 'ssl' || $enc === 'smtps') {
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;
        } elseif ($enc === 'tls' || $enc === 'starttls') {
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        } else {
            $mail->SMTPSecure = false;
            $mail->SMTPAutoTLS = false;
        }
        return $mail;
    }

    public function sendMessage(string $toEmail, string $message): void
    {
        $mail = $this->createMailer();

        $mail->setFrom($this->config['fromEmail'], $this->config['fromName']);
        $mail->addAddress($toEmail);

        $mail->isHTML(true);
        $mail->Subject = 'Цена на товар изменилась! Pricerr';
        $mail->Body = $message;
        $mail->AltBody = strip_tags($message);

        $isSended = $mail->send();
        if (!$isSended) {
            throw new \Exception('Failed to send a message. ' . $mail->ErrorInfo);
        }
    }
}