<div>
    <label for="name" class="block font-medium text-sm text-gray-700">Nombre</label>
    <input id="name" name="name" type="text" value="{{ old('name', $service->name ?? '') }}" class="block mt-1 w-full rounded-md shadow-sm border-gray-300" required>
</div>

<div class="mt-4">
    <label for="description" class="block font-medium text-sm text-gray-700">Descripción</label>
    <textarea id="description" name="description" class="block mt-1 w-full rounded-md shadow-sm border-gray-300">{{ old('description', $service->description ?? '') }}</textarea>
</div>

<div class="mt-4">
    <label for="duration_minutes" class="block font-medium text-sm text-gray-700">Duración (minutos)</label>
    <input id="duration_minutes" name="duration_minutes" type="number" value="{{ old('duration_minutes', $service->duration_minutes ?? '') }}" class="block mt-1 w-full rounded-md shadow-sm border-gray-300" required>
</div>

<div class="mt-4">
    <label for="cost" class="block font-medium text-sm text-gray-700">Coste (€)</label>
    <input id="cost" name="cost" type="number" step="0.01" value="{{ old('cost', $service->cost ?? '') }}" class="block mt-1 w-full rounded-md shadow-sm border-gray-300" required>
</div>

<div class="flex items-center justify-end mt-4">
    <button type="submit" class="inline-flex items-center px-4 py-2 bg-gray-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700">
        Guardar
    </button>
</div>