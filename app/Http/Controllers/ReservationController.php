<?php

namespace App\Http\Controllers;

// Eliminamos 'Professional' de aquí
use App\Models\Reservation;
use App\Models\Service;
use App\Models\Schedule;
use App\Notifications\ReservationConfirmed;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use Illuminate\Validation\ValidationException;

class ReservationController extends Controller
{
    public function index()
    {
        // La relación 'professional' ya no es necesaria aquí, pero no daña tenerla
        $reservations = Auth::user()->reservations()->with(['service', 'professional'])->latest()->get();
        return view('reservations.index', compact('reservations'));
    }

    public function create()
    {
        $services = Service::all();
        // Ya no necesitamos enviar los profesionales a la vista
        return view('reservations.create', compact('services'));
    }

    public function getAvailability(Request $request)
    {
        $validated = $request->validate(['date' => 'required|date']);
        $date = Carbon::parse($validated['date']);
        $dayOfWeek = $date->dayOfWeek;

        $schedule = Schedule::where('day_of_week', $dayOfWeek)->where('is_active', true)->first();
        if (!$schedule) {
            return response()->json([]);
        }

        // Volvemos a la lógica simple: obtenemos TODAS las reservas de ese día
        $reservationsOnDate = Reservation::whereDate('start_time', $date)->get();

        $slots = [];
        $startTime = Carbon::parse($schedule->start_time);
        $endTime = Carbon::parse($schedule->end_time);
        $interval = 30;

        while ($startTime < $endTime) {
            $isAvailable = true;
            foreach ($reservationsOnDate as $reservation) {
                if ($startTime >= $reservation->start_time && $startTime < $reservation->end_time) {
                    $isAvailable = false;
                    break;
                }
            }

            if ($isAvailable) {
                if (!$date->isToday() || ($date->isToday() && $startTime->isFuture())) {
                    $slots[] = $startTime->format('H:i');
                }
            }

            $startTime->addMinutes($interval);
        }

        return response()->json($slots);
    }

    public function store(Request $request)
    {
        // Eliminamos la validación de 'professional_id'
        $validated = $request->validate([
            'service_id' => 'required|exists:services,id',
            'start_time' => 'required|date',
        ]);

        $service = Service::find($validated['service_id']);
        $startTime = Carbon::parse($validated['start_time']);
        $endTime = $startTime->copy()->addMinutes($service->duration_minutes);

        $existingReservation = Reservation::where('start_time', '<', $endTime)
                                          ->where('end_time', '>', $startTime)
                                          ->exists();

        if ($existingReservation) {
            throw ValidationException::withMessages([
               'start_time' => 'Esta franja horaria ya no está disponible.',
            ]);
        }

        // Creamos la reserva sin el professional_id
        $reservation = Reservation::create([
            'user_id' => Auth::id(),
            'service_id' => $service->id,
            'start_time' => $startTime,
            'end_time' => $endTime,
        ]);

        $reservation->user->notify(new ReservationConfirmed($reservation));

        return redirect()->route('reservations.index')->with('success', 'Reserva creada con éxito.');
    }

    public function destroy(Reservation $reservation)
    {
        if ($reservation->user_id !== Auth::id()) {
            abort(403);
        }
        $reservation->delete();
        return back()->with('success', 'Reserva cancelada con éxito.');
    }
}