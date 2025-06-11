<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Mis Reservas
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="flex justify-end mb-4">
                <a href="{{ route('reservations.create') }}" class="inline-flex items-center px-4 py-2 bg-blue-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-500">
                    Nueva Reserva
                </a>
            </div>
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                    @if($reservations->isEmpty())
                        <p>No tienes ninguna reserva.</p>
                    @else
                        <ul class="divide-y divide-gray-200">
                            @foreach($reservations as $reservation)
                                <li class="py-4 flex justify-between items-center">
                                    <div>
                                        <p class="text-lg font-medium text-gray-900">{{ $reservation->service->name }}</p>
                                        <p class="text-sm text-gray-600">
                                            {{ $reservation->start_time->format('d/m/Y \a \l\a\s H:i') }}
                                            @if($reservation->professional)
                                                con {{ $reservation->professional->name }}
                                            @endif
                                        </p>
                                    </div>
                                    <form action="{{ route('reservations.destroy', $reservation) }}" method="POST">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="text-sm text-red-600 hover:text-red-800" onclick="return confirm('¿Seguro que quieres cancelar esta reserva?')">Cancelar</button>
                                    </form>
                                </li>
                            @endforeach
                        </ul>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>