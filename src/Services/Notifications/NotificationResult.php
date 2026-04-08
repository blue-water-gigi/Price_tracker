<?php

declare(strict_types=1);

namespace App\Services\Notifications;

readonly class NotificationResult
{
    /**
     * Summary of __construct
     * @param string $notification_channel
     * @param string $status
     * @param string $msg
     */
    public function __construct(public string $notification_channel, public string $status, public string $msg)
    {
    }
}