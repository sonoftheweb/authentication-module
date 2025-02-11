<?php

namespace Modules\Authentication\Notifications;

use Illuminate\Auth\Notifications\ResetPassword;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;

class ResetPasswordNotification extends ResetPassword implements ShouldQueue
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
