<?php

namespace App\Exports;

use App\Models\Reservation;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class ReservationsExport implements FromCollection, WithHeadings, WithMapping
{
    public function collection()
    {
        return Reservation::with(['user', 'service', 'professional'])->get();
    }

    public function headings(): array
    {
        return [
            'ID Reserva',
            'Cliente',
            'Email Cliente',
            'Servicio',
            'Profesional',
            'Fecha y Hora',
            'Coste',
            'Estado',
        ];
    }

    public function map($reservation): array
    {
        return [
            $reservation->id,
            $reservation->user->name,
            $reservation->user->email,
            $reservation->service->name,
            $reservation->professional ? $reservation->professional->name : 'N/A',
            $reservation->start_time->format('d-m-Y H:i'),
            $reservation->service->cost,
            $reservation->status,
        ];
    }
}