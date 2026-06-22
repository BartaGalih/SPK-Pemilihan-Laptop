<?php

namespace App\Http\Controllers;

use App\Models\Laptop;
use Illuminate\Http\Request;

class LaptopController extends Controller
{
    public function index()
    {
        $laptops = Laptop::latest()->paginate(10);
        return view('laptops.index', compact('laptops'));
    }

    public function create()
    {
        return view('laptops.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name'       => 'required|string|max:200',
            'price'      => 'required|integer|min:1000000',
            'ram'        => 'required|integer|min:1',
            'cpu_score'  => 'required|integer|min:1',
            'weight_kg'  => 'required|numeric|min:0.1|max:10',
            'storage'    => 'required|integer|min:1',
            'battery'    => 'required|numeric|min:0.5',
        ]);

        Laptop::create($validated);

        return redirect()->route('laptops.index')
                         ->with('success', 'Data laptop berhasil ditambahkan.');
    }

    public function show(Laptop $laptop)
    {
        return view('laptops.show', compact('laptop'));
    }

    public function edit(Laptop $laptop)
    {
        return view('laptops.edit', compact('laptop'));
    }

    public function update(Request $request, Laptop $laptop)
    {
        $validated = $request->validate([
            'name'       => 'required|string|max:200',
            'price'      => 'required|integer|min:1000000',
            'ram'        => 'required|integer|min:1',
            'cpu_score'  => 'required|integer|min:1',
            'weight_kg'  => 'required|numeric|min:0.1|max:10',
            'storage'    => 'required|integer|min:1',
            'battery'    => 'required|numeric|min:0.5',
        ]);

        $laptop->update($validated);

        return redirect()->route('laptops.index')
                         ->with('success', 'Data laptop berhasil diperbarui.');
    }

    public function destroy(Laptop $laptop)
    {
        $laptop->delete();
        return redirect()->route('laptops.index')
                         ->with('success', 'Data laptop berhasil dihapus.');
    }
}
