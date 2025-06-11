<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Service;
use Illuminate\Http\Request;

class ServiceController extends Controller
{
    public function index()
    {
        $services = Service::latest()->paginate(10);
        return view('admin.services.index', compact('services'));
    }

    public function create()
    {
        return view('admin.services.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'duration_minutes' => 'required|integer|min:1',
            'cost' => 'required|numeric|min:0',
        ]);

        Service::create($validated);
        return redirect()->route('admin.services.index')->with('success', 'Servicio creado con éxito.');
    }

    public function edit(Service $service)
    {
        return view('admin.services.edit', compact('service'));
    }

    public function update(Request $request, Service $service)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'duration_minutes' => 'required|integer|min:1',
            'cost' => 'required|numeric|min:0',
        ]);

        $service->update($validated);
        return redirect()->route('admin.services.index')->with('success', 'Servicio actualizado con éxito.');
    }

    public function destroy(Service $service)
    {
        $service->delete();
        return back()->with('success', 'Servicio eliminado con éxito.');
    }
}