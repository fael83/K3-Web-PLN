<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Hazard;
use App\Support\AuditLogger;
use Illuminate\Http\Request;

class HazardController extends Controller
{
    public function index()
    {
        $items = Hazard::orderBy('category')->orderBy('name')->paginate(10);

        return view('admin.hazard.index', compact('items'));
    }

    public function create()
    {
        return view('admin.hazard.form', ['item' => new Hazard()]);
    }

    public function store(Request $request)
    {
        $item = Hazard::create($this->validateData($request));
        AuditLogger::record('Hazard', 'create', "Menambah bahaya: {$item->name}");

        return redirect()->route('admin.hazard.index')->with('success', 'Data bahaya berhasil ditambahkan.');
    }

    public function edit(Hazard $hazard)
    {
        return view('admin.hazard.form', ['item' => $hazard]);
    }

    public function update(Request $request, Hazard $hazard)
    {
        $hazard->update($this->validateData($request));
        AuditLogger::record('Hazard', 'update', "Memperbarui bahaya: {$hazard->name}");

        return redirect()->route('admin.hazard.index')->with('success', 'Data bahaya berhasil diperbarui.');
    }

    public function destroy(Hazard $hazard)
    {
        $name = $hazard->name;
        $hazard->delete();
        AuditLogger::record('Hazard', 'delete', "Menghapus bahaya: {$name}");

        return redirect()->route('admin.hazard.index')->with('success', 'Data bahaya berhasil dihapus.');
    }

    protected function validateData(Request $request): array
    {
        return $request->validate([
            'category'        => ['required', 'in:'.implode(',', array_keys(Hazard::CATEGORIES))],
            'name'            => ['required', 'string', 'max:255'],
            'description'     => ['nullable', 'string'],
            'location'        => ['nullable', 'string', 'max:255'],
            'likelihood'      => ['required', 'in:'.implode(',', array_keys(Hazard::LEVELS))],
            'severity'        => ['required', 'in:'.implode(',', array_keys(Hazard::LEVELS))],
            'control_measure' => ['nullable', 'string'],
        ]);
    }
}
