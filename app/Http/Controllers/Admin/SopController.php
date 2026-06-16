<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\SopStep;
use App\Support\AuditLogger;
use Illuminate\Http\Request;

class SopController extends Controller
{
    public function index()
    {
        $items = SopStep::orderBy('sector')->orderBy('step_order')->paginate(15);

        return view('admin.sop.index', compact('items'));
    }

    public function create()
    {
        return view('admin.sop.form', ['item' => new SopStep()]);
    }

    public function store(Request $request)
    {
        $data = $this->validateData($request);
        $item = SopStep::create($data);
        AuditLogger::record('SOP', 'create', "Menambah langkah SOP: {$item->title}");

        return redirect()->route('admin.sop.index')->with('success', 'Langkah SOP berhasil ditambahkan.');
    }

    public function edit(SopStep $sop)
    {
        return view('admin.sop.form', ['item' => $sop]);
    }

    public function update(Request $request, SopStep $sop)
    {
        $sop->update($this->validateData($request));
        AuditLogger::record('SOP', 'update', "Memperbarui langkah SOP: {$sop->title}");

        return redirect()->route('admin.sop.index')->with('success', 'Langkah SOP berhasil diperbarui.');
    }

    public function destroy(SopStep $sop)
    {
        $title = $sop->title;
        $sop->delete();
        AuditLogger::record('SOP', 'delete', "Menghapus langkah SOP: {$title}");

        return redirect()->route('admin.sop.index')->with('success', 'Langkah SOP berhasil dihapus.');
    }

    protected function validateData(Request $request): array
    {
        return $request->validate([
            'sector'      => ['required', 'string', 'in:'.implode(',', array_keys(SopStep::SECTORS))],
            'step_order'  => ['required', 'integer', 'min:0'],
            'title'       => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
        ]);
    }
}
