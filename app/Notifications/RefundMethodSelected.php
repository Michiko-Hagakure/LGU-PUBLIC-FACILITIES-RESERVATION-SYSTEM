<?php

namespace App\Notifications;

use Illuminate\Notifications\Notification;

class RefundMethodSelected extends Notification
{
    public $refund;

    public function __construct($refund)
    {
        $this->refund = $refund;
    }

    public function via(object $notifiable): array
    {
        return ['database'];
    }

    public function toArray(object $notifiable): array
    {
        return [
            'refund_id' => $this->refund->id,
            'booking_reference' => $this->refund->booking_reference,
            'applicant_name' => $this->refund->applicant_name,
            'refund_amount' => $this->refund->refund_amount,
            'refund_method' => ucfirst(str_replace('_', ' ', $this->refund->refund_method)),
            'message' => "Refund request {$this->refund->booking_reference} is ready to process. {$this->refund->applicant_name} selected " . ucfirst(str_replace('_', ' ', $this->refund->refund_method)) . " for ₱" . number_format($this->refund->refund_amount, 2) . ".",
        ];
    }
}
