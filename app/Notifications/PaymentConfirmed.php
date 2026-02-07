<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class PaymentConfirmed extends Notification
{
    use Queueable;

    public $booking;
    public $paymentSlip;

    /**
     * Create a new notification instance.
     */
    public function __construct($booking, $paymentSlip)
    {
        $this->booking = $booking;
        $this->paymentSlip = $paymentSlip;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['mail', 'database'];
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail(object $notifiable): MailMessage
    {
        $facilityName = $this->booking->facility_name ?? 'Facility';
        $amount = number_format($this->paymentSlip->amount_due ?? 0, 2);
        $referenceNumber = $this->paymentSlip->reference_number ?? 'N/A';

        return (new MailMessage)
                    ->subject('Payment Confirmed - ' . $facilityName)
                    ->greeting('Hello ' . $notifiable->full_name . '!')
                    ->line('Your payment has been confirmed successfully.')
                    ->line('**Facility:** ' . $facilityName)
                    ->line('**Amount Paid:** ₱' . $amount)
                    ->line('**Reference Number:** ' . $referenceNumber)
                    ->action('View Reservation', url('/citizen/reservations'))
                    ->line('Thank you for using our facility reservation system!');
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            'type' => 'payment_confirmed',
            'booking_id' => $this->booking->id ?? null,
            'facility_name' => $this->booking->facility_name ?? 'Facility',
            'amount' => $this->paymentSlip->amount_due ?? 0,
            'reference_number' => $this->paymentSlip->reference_number ?? null,
            'message' => 'Your payment for ' . ($this->booking->facility_name ?? 'your reservation') . ' has been confirmed.',
        ];
    }
}
