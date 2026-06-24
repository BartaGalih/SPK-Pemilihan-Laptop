<?php

namespace App\Http\Controllers;

use App\Models\Criteria;
use App\Models\Laptop;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class LaptopController extends Controller
{
    public function index()
    {
        $criteria = Criteria::orderBy('code')->orderBy('id')->get();
        $laptops  = Laptop::with('values')->latest()->paginate(10);

        return view('laptops.index', compact('laptops', 'criteria'));
    }

    public function create()
    {
        $criteria = Criteria::orderBy('code')->orderBy('id')->get();
        return view('laptops.create', compact('criteria'));
    }

    public function store(Request $request)
    {
        $criteria = Criteria::orderBy('code')->orderBy('id')->get();
        $data     = $this->validateLaptop($request, $criteria);

        DB::transaction(function () use ($data, $criteria) {
            $laptop = Laptop::create(['name' => $data['name']]);
            foreach ($criteria as $c) {
                $laptop->values()->create([
                    'criteria_id' => $c->id,
                    'value'       => $data['values'][$c->id] ?? 0,
                ]);
            }
        });

        return redirect()->route('laptops.index')
            ->with('success', 'Data laptop berhasil ditambahkan.');
    }

    public function show(Laptop $laptop)
    {
        $criteria = Criteria::orderBy('code')->orderBy('id')->get();
        $laptop->load(['values', 'mfepResult.details']);

        return view('laptops.show', compact('laptop', 'criteria'));
    }

    public function edit(Laptop $laptop)
    {
        $criteria = Criteria::orderBy('code')->orderBy('id')->get();
        $laptop->load('values');

        return view('laptops.edit', compact('laptop', 'criteria'));
    }

    public function update(Request $request, Laptop $laptop)
    {
        $criteria = Criteria::orderBy('code')->orderBy('id')->get();
        $data     = $this->validateLaptop($request, $criteria);

        DB::transaction(function () use ($laptop, $data, $criteria) {
            $laptop->update(['name' => $data['name']]);
            foreach ($criteria as $c) {
                $laptop->values()->updateOrCreate(
                    ['criteria_id' => $c->id],
                    ['value' => $data['values'][$c->id] ?? 0]
                );
            }
        });

        return redirect()->route('laptops.index')
            ->with('success', 'Data laptop berhasil diperbarui.');
    }

    public function destroy(Laptop $laptop)
    {
        $laptop->delete(); // cascade ke laptop_values & mfep_results
        return redirect()->route('laptops.index')
            ->with('success', 'Data laptop berhasil dihapus.');
    }

    /**
     * Validasi dinamis: name + satu nilai numerik per kriteria aktif.
     */
    private function validateLaptop(Request $request, $criteria): array
    {
        $rules     = ['name' => 'required|string|max:200'];
        $attributes = [];
        foreach ($criteria as $c) {
            $rules["values.{$c->id}"] = 'required|numeric|min:0';
            $attributes["values.{$c->id}"] = $c->name;
        }

        return $request->validate($rules, [], $attributes);
    }
}
