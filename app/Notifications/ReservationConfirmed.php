<?php

namespace App\Notifications;

use App\Models\Reservation;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class ReservationConfirmed extends Notification
{
    use Queueable;

    public $reservation;

    public function __construct(Reservation $reservation)
    {
        $this->reservation = $reservation;
    }

    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
                    ->subject('Confirmación de tu reserva')
                    ->greeting('¡Hola ' . $notifiable->name . '!')
                    ->line('Tu reserva ha sido confirmada con éxito.')
                    ->line('**Servicio:** ' . $this->reservation->service->name)
                    ->line('**Día y Hora:** ' . $this->reservation->start_time->format('d/m/Y \a \l\a\s H:i'))
                    ->action('Ver mis reservas', url('/mis-reservas'))
                    ->line('¡Gracias por confiar en nosotros!');
    }
}