<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Incident;
use App\Services\SupabaseStorageService;
use App\Support\AuditLogger;
use Illuminate\Http\Request;

class IncidentController extends Controller
{
    public function __construct(protected SupabaseStorageService $storage) {}

    public function index()
    {
        $items = Incident::orderByDesc('incident_date')->paginate(10);

        return view('admin.incident.index', compact('items'));
    }

    public function create()
    {
        return view('admin.incident.form', ['item' => new Incident(['status' => 'open'])]);
    }

    public function store(Request $request)
    {
        $data = $this->validateData($request);

        if ($request->hasFile('evidence')) {
            $data['evidence_url'] = $this->storage->upload($request->file('evidence'), 'incidents');
        }

        $item = Incident::create($data);
        AuditLogger::record('Insiden', 'create', "Mencatat insiden: {$item->title}");

        return redirect()->route('admin.incident.index')->with('success', 'Data insiden berhasil ditambahkan.');
    }

    public function edit(Incident $incident)
    {
        return view('admin.incident.form', ['item' => $incident]);
    }

    public function update(Request $request, Incident $incident)
    {
        $data = $this->validateData($request);

        if ($request->hasFile('evidence')) {
            $this->storage->delete($incident->evidence_url);
            $data['evidence_url'] = $this->storage->upload($request->file('evidence'), 'incidents');
        }

        $incident->update($data);
        AuditLogger::record('Insiden', 'update', "Memperbarui insiden: {$incident->title}");

        return redirect()->route('admin.incident.index')->with('success', 'Data insiden berhasil diperbarui.');
    }

    public function destroy(Incident $incident)
    {
        $this->storage->delete($incident->evidence_url);
        $title = $incident->title;
        $incident->delete();
        AuditLogger::record('Insiden', 'delete', "Menghapus insiden: {$title}");

        return redirect()->route('admin.incident.index')->with('success', 'Data insiden berhasil dihapus.');
    }

    protected function validateData(Request $request): array
    {
        $validated = $request->validate([
            'incident_type'     => ['required', 'in:'.implode(',', array_keys(Incident::TYPES))],
            'title'             => ['required', 'string', 'max:255'],
            'description'       => ['nullable', 'string'],
            'location'          => ['nullable', 'string', 'max:255'],
            'incident_date'     => ['nullable', 'date'],
            'status'            => ['required', 'in:'.implode(',', array_keys(Incident::STATUSES))],
            'corrective_action' => ['nullable', 'string'],
            'evidence'          => ['nullable', 'image', 'max:4096'],
        ]);

        unset($validated['evidence']);

        return $validated;
    }
}
