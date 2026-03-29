<?php

declare(strict_types=1);

namespace App\Services;

use PHPMailer\PHPMailer\PHPMailer;

class EmailService
{
    private PHPMailer $mail;

    public function __construct(private array $config)
    {
        $this->mail = new PHPMailer(true);
        $this->setSettings();
    }
    private function setSettings(): void
    {
        $this->mail->SMTPDebug = (int) ($this->config['debug'] ?? 0);
        $this->mail->isSMTP();
        $this->mail->CharSet = PHPMailer::CHARSET_UTF8;
        $this->mail->Host = $this->config['host'];
        $this->mail->SMTPAuth = (bool) ($this->config['auth'] ?? false);
        $this->mail->Username = (string) ($this->config['username'] ?? '');
        $this->mail->Password = (string) ($this->config['password'] ?? '');
        $this->mail->Port = (int) $this->config['port'];

        // Mailpit / local SMTP: plain text on 1025. Real providers: tls/ssl via MAIL_ENCRYPTION.
        $enc = strtolower((string) ($this->config['encryption'] ?? ''));
        if ($enc === 'ssl' || $enc === 'smtps') {
            $this->mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;
        } elseif ($enc === 'tls' || $enc === 'starttls') {
            $this->mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        } else {
            $this->mail->SMTPSecure = false;
            $this->mail->SMTPAutoTLS = false;
        }
    }

    public function sendMessage(string $toEmail, string $message): void
    {

        $this->mail->setFrom($this->config['fromEmail'], $this->config['fromName']);
        $this->mail->addAddress($toEmail);

        $this->mail->isHTML(true);
        $this->mail->Subject = 'Цена на товар изменилась! Pricerr';
        $this->mail->Body = $message;
        $this->mail->AltBody = strip_tags($message);

        $isSended = $this->mail->send();
        if (!$isSended) {
            throw new \Exception('Failed to send a message. ' . $this->mail->ErrorInfo);
        }
    }
}