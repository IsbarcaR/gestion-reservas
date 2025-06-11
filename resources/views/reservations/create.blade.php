<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Crear Nueva Reserva
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-2xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-8 bg-white border-b border-gray-200">
                    <form id="reservationForm" action="{{ route('reservations.store') }}" method="POST">
                        @csrf

                        <div class="mt-4">
                            <label for="service_id" class="block font-medium text-sm text-gray-700">Servicio</label>
                            <select name="service_id" id="service_id" class="block mt-1 w-full rounded-md shadow-sm border-gray-300" required>
                                <option value="">-- Elige un servicio --</option>
                                @foreach($services as $service)
                                    <option value="{{ $service->id }}">{{ $service->name }} ({{ $service->cost }}€)</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="mt-4">
                            <label for="date" class="block font-medium text-sm text-gray-700">Fecha</label>
                            <input type="date" id="date" class="block mt-1 w-full rounded-md shadow-sm border-gray-300" required>
                        </div>

                         <div class="mt-4">
                            <label for="time_slot" class="block font-medium text-sm text-gray-700">Hora Disponible</label>
                            <select name="time_slot" id="time_slot" class="block mt-1 w-full rounded-md shadow-sm border-gray-300" required>
                                <option value="">-- Primero selecciona una fecha --</option>
                            </select>
                        </div>

                        <input type="hidden" name="start_time" id="start_time">

                        <div class="flex items-center justify-end mt-6">
                            <button type="submit" class="inline-flex items-center px-4 py-2 bg-blue-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-500">
                                Confirmar Reserva
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
    <script>
        // Volvemos al script original y más simple
        document.getElementById('date').addEventListener('change', function() {
            const date = this.value;
            const timeSlotSelect = document.getElementById('time_slot');
            timeSlotSelect.innerHTML = '<option value="">Cargando...</option>';

            fetch(`{{ route('reservations.availability') }}?date=${date}`)
                .then(response => response.json())
                .then(slots => {
                    timeSlotSelect.innerHTML = '<option value="">-- Selecciona una hora --</option>';
                    if (slots.length > 0) {
                        slots.forEach(slot => {
                            const option = document.createElement('option');
                            option.value = slot;
                            option.textContent = slot;
                            timeSlotSelect.appendChild(option);
                        });
                    } else {
                        timeSlotSelect.innerHTML = '<option value="">No hay horas disponibles</option>';
                    }
                });
        });

        document.getElementById('reservationForm').addEventListener('submit', function(e) {
            const date = document.getElementById('date').value;
            const time = document.getElementById('time_slot').value;
            if(date && time) {
                document.getElementById('start_time').value = `${date} ${time}`;
            } else {
                e.preventDefault();
                alert('Por favor, selecciona una fecha y una hora.');
            }
        });
    </script>
    @endpush
</x-app-layout>