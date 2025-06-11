<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Gestionar Horarios de Atención
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">

            @if (session('success'))
                <div class="mb-4 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative" role="alert">
                    <span class="block sm:inline">{{ session('success') }}</span>
                </div>
            @endif

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                    <form action="{{ route('admin.schedules.store') }}" method="POST">
                        @csrf
                        <div class="space-y-6">

                            @foreach ($daysOfWeek as $day_number => $day_name)
                                @php
                                    // Busca el horario para el día actual, si no existe, será null
                                    $schedule = $schedules->get($day_number);
                                @endphp
                                <div class="flex items-center justify-between p-4 border rounded-lg">
                                    <div class="flex items-center space-x-4">
                                        <input type="checkbox"
                                               name="schedules[{{ $day_number }}][is_active]"
                                               id="active_{{ $day_number }}"
                                               @if(old('schedules.'.$day_number.'.is_active', optional($schedule)->is_active)) checked @endif
                                               class="h-5 w-5 rounded border-gray-300 text-indigo-600 focus:ring-indigo-500">

                                        <label for="active_{{ $day_number }}" class="font-medium text-gray-700 w-24">{{ $day_name }}</label>
                                    </div>

                                    <div class="flex items-center space-x-2">
                                        <div>
                                            <label for="start_{{ $day_number }}" class="text-sm">Apertura</label>
                                            <input type="time"
                                                   name="schedules[{{ $day_number }}][start_time]"
                                                   id="start_{{ $day_number }}"
                                                   value="{{ old('schedules.'.$day_number.'.start_time', optional($schedule)->start_time) }}"
                                                   class="block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                                        </div>
                                        <div>
                                            <label for="end_{{ $day_number }}" class="text-sm">Cierre</label>
                                            <input type="time"
                                                   name="schedules[{{ $day_number }}][end_time]"
                                                   id="end_{{ $day_number }}"
                                                   value="{{ old('schedules.'.$day_number.'.end_time', optional($schedule)->end_time) }}"
                                                   class="block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>

                        <div class="flex justify-end mt-8">
                            <button type="submit" class="inline-flex items-center px-6 py-3 bg-gray-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700">
                                Guardar Horarios
                            </button>
                        </div>

                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>