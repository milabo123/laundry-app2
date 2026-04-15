<?php

namespace App\Http\Controllers;

use App\Models\TypeOfService;
use Illuminate\Http\Request;

class TypeOfServiceController extends Controller
{
    public function index(Request $request)
    {
        $search   = $request->get('search');
        $services = TypeOfService::when($search, fn($q) => $q->where('service_name', 'like', "%$search%"))
            ->orderByDesc('created_at')
            ->paginate(10)
            ->withQueryString();

        return view('services.index', compact('services', 'search'));
    }

    public function create()
    {
        return view('services.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'service_name' => 'required|string|max:50',
            'price'        => 'required|integer|min:0',
            'description'  => 'nullable|string',
        ]);

        TypeOfService::create($request->only('service_name', 'price', 'description'));

        return redirect()->route('services.index')
            ->with('success', 'Layanan berhasil ditambahkan.');
    }

    public function edit(TypeOfService $service)
    {
        return view('services.edit', compact('service'));
    }

    public function update(Request $request, TypeOfService $service)
    {
        $request->validate([
            'service_name' => 'required|string|max:50',
            'price'        => 'required|integer|min:0',
            'description'  => 'nullable|string',
        ]);

        $service->update($request->only('service_name', 'price', 'description'));

        return redirect()->route('services.index')
            ->with('success', 'Data layanan berhasil diperbarui.');
    }

    public function destroy(TypeOfService $service)
    {
        $service->delete();

        return redirect()->route('services.index')
            ->with('success', 'Layanan berhasil dihapus.');
    }
}
