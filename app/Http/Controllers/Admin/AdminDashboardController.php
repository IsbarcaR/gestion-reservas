<?php

namespace App\Http\Controllers\Admin;

use App\Exports\ReservationsExport;
use App\Http\Controllers\Controller;
use App\Models\Reservation;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;

class AdminDashboardController extends Controller
{
    /**
     * Display the admin dashboard with a summary of recent reservations.
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        // Obtiene las 10 reservas más recientes para mostrar un resumen
        $recentReservations = Reservation::with(['user', 'service'])
            ->latest() // Ordena por fecha de creación, las más nuevas primero
            ->take(10)
            ->get();

        return view('admin.dashboard', compact('recentReservations'));
    }

    /**
     * Gestiona la petición para exportar todas las reservas a un archivo Excel.
     *
     * @return \Symfony\Component\HttpFoundation\BinaryFileResponse
     */
    public function exportReservations()
    {
        // Usa la clase ReservationsExport que creamos para generar y descargar el archivo
        return Excel::download(new ReservationsExport, 'reservas.xlsx');
    }
}