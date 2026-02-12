<?php

namespace App\Notifications;

use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class BookingAdminRejected extends Notification
{
    public $booking;
    public $reason;

    public function __construct($booking, $reason)
    {
        $this->booking = $booking;
        $this->reason = $reason;
    }

    public function via(object $notifiable): array
    {
        return ['mail', 'database'];
    }

    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
                    ->subject('Booking Requires Your Action — ' . ($this->booking->booking_reference ?? 'N/A'))
                    ->greeting('Hello ' . $notifiable->first_name . ',')
                    ->line('The admin has flagged an issue with your booking for **' . ($this->booking->facility_name ?? 'your facility') . '**.')
                    ->line('**Reason:** ' . $this->reason)
                    ->line('You can choose to **reschedule** your booking to a new date/time, or **cancel** it.')
                    ->line('Please note that **payments are non-refundable** per our policy.')
                    ->action('View Booking', url('/citizen/reservations/' . $this->booking->id))
                    ->salutation('LGU Public Facilities');
    }

    public function toArray(object $notifiable): array
    {
        return [
            'booking_id' => $this->booking->id,
            'facility_name' => $this->booking->facility_name ?? null,
            'booking_reference' => $this->booking->booking_reference ?? null,
            'reason' => $this->reason,
            'message' => 'Your booking has been rejected by the admin. Please reschedule or cancel (no refund).',
        ];
    }
}
