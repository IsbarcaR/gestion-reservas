<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Schedule;
use Illuminate\Http\Request;

class ScheduleController extends Controller
{
    /**
     * Muestra el formulario para ver y editar los horarios.
     */
    public function index()
    {
        // Obtenemos todos los horarios y los agrupamos por día de la semana para fácil acceso
        $schedules = Schedule::all()->keyBy('day_of_week');

        // Creamos un array con los días para iterar en la vista
        $daysOfWeek = [
            1 => 'Lunes',
            2 => 'Martes',
            3 => 'Miércoles',
            4 => 'Jueves',
            5 => 'Viernes',
            6 => 'Sábado',
            0 => 'Domingo',
        ];

        return view('admin.schedules.index', compact('schedules', 'daysOfWeek'));
    }

    /**
     * Guarda los horarios enviados desde el formulario.
     */
    public function store(Request $request)
    {
        $request->validate([
            'schedules.*.start_time' => 'nullable|date_format:H:i',
            'schedules.*.end_time' => 'nullable|date_format:H:i|after:schedules.*.start_time',
        ]);

        $schedulesData = $request->input('schedules', []);

        foreach ($schedulesData as $day => $data) {
            Schedule::updateOrCreate(
                ['day_of_week' => $day],
                [
                    'is_active' => isset($data['is_active']),
                    'start_time' => $data['start_time'] ?? '00:00',
                    'end_time' => $data['end_time'] ?? '00:00',
                ]
            );
        }

        return redirect()->route('admin.schedules.index')->with('success', 'Horarios actualizados correctamente.');
    }
}