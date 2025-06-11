<?php

namespace App\Notifications;

use App\Models\Reservation;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class AppointmentReminder extends Notification implements ShouldQueue
{
    use Queueable;

    /**
     * La instancia de la reserva.
     *
     * @var \App\Models\Reservation
     */
    public $reservation;

    /**
     * Crea una nueva instancia de la notificación.
     *
     * @param \App\Models\Reservation $reservation
     * @return void
     */
    public function __construct(Reservation $reservation)
    {
        // Pasamos el objeto de la reserva al constructor para tener sus datos disponibles
        $this->reservation = $reservation;
    }

    /**
     * Obtiene los canales de envío de la notificación.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function via(object $notifiable): array
    {
        // Especificamos que esta notificación se enviará por email
        return ['mail'];
    }

    /**
     * Construye el mensaje de correo para la notificación.
     *
     * @param  mixed  $notifiable
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
    public function toMail(object $notifiable): MailMessage
    {
        // Usamos la clase MailMessage para construir un email de forma fluida
        return (new MailMessage)
                    ->subject('Recordatorio de tu cita de mañana')
                    ->greeting('¡Hola ' . $notifiable->name . '!')
                    ->line('Te escribimos para recordarte los detalles de tu cita programada para mañana.')
                    ->line('**Servicio:** ' . $this->reservation->service->name)
                    ->line('**Día y Hora:** ' . $this->reservation->start_time->format('d/m/Y \a \l\a\s H:i'))
                    ->lineIf($this->reservation->professional, '**Profesional:** ' . optional($this->reservation->professional)->name)
                    ->action('Ver mis reservas', url('/mis-reservas'))
                    ->line('Si necesitas cancelar o reprogramar, por favor, hazlo con antelación desde nuestra plataforma.')
                    ->salutation('¡Te esperamos!');
    }

    /**
     * Obtiene la representación en array de la notificación.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function toArray(object $notifiable): array
    {
        return [
            // Aquí podrías definir datos para otros canales, como notificaciones en la base de datos
        ];
    }
}