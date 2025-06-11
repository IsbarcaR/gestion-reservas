<?php
namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Reservation;
use App\Notifications\AppointmentReminder; 
use Carbon\Carbon;

class SendAppointmentReminders extends Command
{
    protected $signature = 'app:send-reminders';
    protected $description = 'Envía recordatorios de citas próximas';

    public function handle()
    {
        $this->info('Buscando citas para enviar recordatorios...');

        // Citas que son mañana y para las que no se ha enviado recordatorio
        $reservations = Reservation::where('reminder_sent', false)
            ->whereBetween('start_time', [Carbon::now(), Carbon::now()->addHours(24)])
            ->get();

        if($reservations->isEmpty()) {
            $this->info('No se encontraron citas próximas.');
            return;
        }

        foreach ($reservations as $reservation) {
            $reservation->user->notify(new AppointmentReminder($reservation));
            $reservation->update(['reminder_sent' => true]);
            $this->info("Recordatorio enviado para la reserva #{$reservation->id}");
        }

        $this->info('Proceso completado.');
    }
}