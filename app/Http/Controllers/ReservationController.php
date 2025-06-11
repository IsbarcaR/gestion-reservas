<?php

namespace App\Http\Controllers;

use App\Models\Professional;
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
        $reservations = Auth::user()->reservations()->with(['service', 'professional'])->latest()->get();
        return view('reservations.index', compact('reservations'));
    }

    public function create()
    {
        $services = Service::all();
        $professionals = Professional::all();
        return view('reservations.create', compact('services', 'professionals'));
    }

    public function getAvailability(Request $request)
    {
        // Ahora también validamos que se envíe el ID del profesional
        $validated = $request->validate([
            'date' => 'required|date',
            'professional_id' => 'required|exists:professionals,id', // <-- NUEVA VALIDACIÓN
        ]);
    
        $date = Carbon::parse($validated['date']);
        $professionalId = $validated['professional_id'];
        $dayOfWeek = $date->dayOfWeek;
    
        // 1. Obtener el horario para ese día de la semana
        // (En un futuro, esto podría depender del profesional también)
        $schedule = Schedule::where('day_of_week', $dayOfWeek)->where('is_active', true)->first();
        if (!$schedule) {
            return response()->json([]); // No hay horario, no hay citas
        }
    
        // 2. Obtener las reservas para esa fecha Y ESE PROFESIONAL ESPECÍFICO
        $reservationsOnDate = Reservation::where('professional_id', $professionalId) // <-- FILTRO AÑADIDO
                                         ->whereDate('start_time', $date)
                                         ->get();
    
        // 3. Generar franjas horarias (la lógica interna no cambia)
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
        $validated = $request->validate([
            'service_id' => 'required|exists:services,id',
            'start_time' => 'required|date',
            'professional_id' => 'nullable|exists:professionals,id', // <-- Añadido para validación
        ]);

        $service = Service::find($validated['service_id']);
        $startTime = Carbon::parse($validated['start_time']);
        $endTime = $startTime->copy()->addMinutes($service->duration_minutes);

        $existingReservation = Reservation::where('start_time', '<', $endTime)
                                          ->where('end_time', '>', $startTime)
                                          ->exists();

        if ($existingReservation) {
            throw ValidationException::withMessages([
               'start_time' => 'Esta franja horaria ya no está disponible. Por favor, selecciona otra.',
            ]);
        }

        // Guardamos la nueva reserva en una variable
        $reservation = Reservation::create([
            'user_id' => Auth::id(),
            'service_id' => $service->id,
            'professional_id' => $validated['professional_id'] ?? null,
            'start_time' => $startTime,
            'end_time' => $endTime,
        ]);

        // --- LÍNEA CLAVE AÑADIDA ---
        // Notificamos al usuario que hizo la reserva
        $reservation->user->notify(new ReservationConfirmed($reservation));
        // -------------------------

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