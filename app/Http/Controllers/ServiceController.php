<?php

namespace App\Http\Controllers;

use App\Models\Service;
use Illuminate\Http\Request;

class ServiceController extends Controller
{
    // Display all services
    public function index()
    {
        $services = Service::orderBy('id', 'desc')->get();
        return view('services.index', compact('services'));
    }

    public function create()
    {
    }

    // Store new service
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'price' => 'required|numeric|min:0',
        ]);

        Service::create([
            'name' => $request->name,
            'price' => $request->price,
        ]);

        return redirect()->back()->with('success', 'Service added successfully!');
    }

    // Show data for editing (optional for AJAX)
    public function edit(Service $service)
    {
        return response()->json($service);
    }

    // Update service
    public function update(Request $request, Service $service)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'price' => 'required|numeric|min:0',
        ]);

        $service->update([
            'name' => $request->name,
            'price' => $request->price,
        ]);

        return redirect()->back()->with('success', 'Service updated successfully!');
    }

    // Delete service
    public function destroy(Service $service)
    {
        $service->delete();
        return redirect()->back()->with('success', 'Service deleted successfully!');
    }
}
