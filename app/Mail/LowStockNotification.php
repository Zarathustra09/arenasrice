<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class LowStockNotification extends Mailable
{
    use Queueable, SerializesModels;

    public $containers;

    /**
     * Create a new message instance.
     *
     * @param array $containers
     */
    public function __construct($containers)
    {
        $this->containers = $containers;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->view('email.low_stock_notification')
            ->with('containers', $this->containers)
            ->subject('Low Stock Notification');
    }
}
