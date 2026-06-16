<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Apd;
use App\Services\SupabaseStorageService;
use App\Support\AuditLogger;
use Illuminate\Http\Request;

class ApdController extends Controller
{
    public function __construct(protected SupabaseStorageService $storage) {}

    public function index()
    {
        $items = Apd::orderBy('name')->paginate(10);

        return view('admin.apd.index', compact('items'));
    }

    public function create()
    {
        return view('admin.apd.form', ['item' => new Apd(['is_active' => true])]);
    }

    public function store(Request $request)
    {
        $data = $this->validateData($request);

        if ($request->hasFile('image')) {
            $data['image_url'] = $this->storage->upload($request->file('image'), 'apd');
        }

        $apd = Apd::create($data);
        AuditLogger::record('APD', 'create', "Menambah APD: {$apd->name}");

        return redirect()->route('admin.apd.index')->with('success', 'Data APD berhasil ditambahkan.');
    }

    public function edit(Apd $apd)
    {
        return view('admin.apd.form', ['item' => $apd]);
    }

    public function update(Request $request, Apd $apd)
    {
        $data = $this->validateData($request);

        if ($request->hasFile('image')) {
            $this->storage->delete($apd->image_url);
            $data['image_url'] = $this->storage->upload($request->file('image'), 'apd');
        }

        $apd->update($data);
        AuditLogger::record('APD', 'update', "Memperbarui APD: {$apd->name}");

        return redirect()->route('admin.apd.index')->with('success', 'Data APD berhasil diperbarui.');
    }

    public function destroy(Apd $apd)
    {
        $this->storage->delete($apd->image_url);
        $name = $apd->name;
        $apd->delete();
        AuditLogger::record('APD', 'delete', "Menghapus APD: {$name}");

        return redirect()->route('admin.apd.index')->with('success', 'Data APD berhasil dihapus.');
    }

    protected function validateData(Request $request): array
    {
        $validated = $request->validate([
            'name'       => ['required', 'string', 'max:255'],
            'function'   => ['nullable', 'string'],
            'usage_area' => ['nullable', 'string', 'max:255'],
            'standard'   => ['nullable', 'string', 'max:255'],
            'image'      => ['nullable', 'image', 'max:4096'],
            'is_active'  => ['nullable', 'boolean'],
        ]);

        $validated['is_active'] = $request->boolean('is_active');
        unset($validated['image']);

        return $validated;
    }
}
