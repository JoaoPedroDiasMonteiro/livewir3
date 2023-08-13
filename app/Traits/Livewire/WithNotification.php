<?php

namespace App\Traits\Livewire;

use App\Support\Notification;

trait WithNotification
{
    public function notify(string $content): Notification
    {
        return new Notification($content, $this);
    }
}
