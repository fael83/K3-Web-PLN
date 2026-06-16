<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\HealthProgram;
use App\Support\AuditLogger;
use Illuminate\Http\Request;

class HealthProgramController extends Controller
{
    public function index()
    {
        $items = HealthProgram::orderBy('sort_order')->paginate(15);

        return view('admin.health.index', compact('items'));
    }

    public function create()
    {
        return view('admin.health.form', ['item' => new HealthProgram()]);
    }

    public function store(Request $request)
    {
        $item = HealthProgram::create($this->validateData($request));
        AuditLogger::record('Program Kesehatan', 'create', "Menambah program: {$item->program_name}");

        return redirect()->route('admin.health.index')->with('success', 'Program kesehatan berhasil ditambahkan.');
    }

    public function edit(HealthProgram $health)
    {
        return view('admin.health.form', ['item' => $health]);
    }

    public function update(Request $request, HealthProgram $health)
    {
        $health->update($this->validateData($request));
        AuditLogger::record('Program Kesehatan', 'update', "Memperbarui program: {$health->program_name}");

        return redirect()->route('admin.health.index')->with('success', 'Program kesehatan berhasil diperbarui.');
    }

    public function destroy(HealthProgram $health)
    {
        $name = $health->program_name;
        $health->delete();
        AuditLogger::record('Program Kesehatan', 'delete', "Menghapus program: {$name}");

        return redirect()->route('admin.health.index')->with('success', 'Program kesehatan berhasil dihapus.');
    }

    protected function validateData(Request $request): array
    {
        return $request->validate([
            'program_name' => ['required', 'string', 'max:255'],
            'description'  => ['nullable', 'string'],
            'sort_order'   => ['required', 'integer', 'min:0'],
        ]);
    }
}
