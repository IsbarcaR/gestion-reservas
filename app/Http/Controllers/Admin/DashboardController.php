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
        // Get the 10 most recent reservations to show a summary on the dashboard
        $recentReservations = Reservation::with(['user', 'service'])
            ->latest() // Order by creation date, newest first
            ->take(10)
            ->get();

        return view('admin.dashboard', compact('recentReservations'));
    }

    /**
     * Handle the request to export all reservations to an Excel file.
     *
     * @return \Symfony\Component\HttpFoundation\BinaryFileResponse
     */
    public function exportReservations()
    {
        // Use the ReservationsExport class we created earlier to generate and download the file
        return Excel::download(new ReservationsExport, 'reservas.xlsx');
    }
}