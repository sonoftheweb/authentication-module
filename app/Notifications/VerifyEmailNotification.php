<?php

namespace Modules\Authentication\Notifications;

use Illuminate\Auth\Notifications\VerifyEmail;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;

class VerifyEmailNotification extends VerifyEmail implements ShouldQueue
{
    use Queueable;

    /**
     * Get the notification's delivery delay.
     *
     * @return array<string, \Illuminate\Support\Carbon|\DateTimeInterface|int|null>
     */
    public function withDelay(): array
    {
        return [
            'mail' => now()->addSeconds(10),
        ];
    }
}
