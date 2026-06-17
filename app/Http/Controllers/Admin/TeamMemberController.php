<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Services\SupabaseStorageService;
use App\Support\AuditLogger;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class TeamMemberController extends Controller
{
    public function __construct(protected SupabaseStorageService $storage) {}

    public function index()
    {
        $items = DB::table('k3_team')->orderBy('sort_order')->paginate(15);
        return view('admin.team.index', compact('items'));
    }

    public function create()
    {
        return view('admin.team.form');
    }

    public function store(Request $request)
    {
        $data = $this->validateData($request);

        $fotoUrl = null;
        if ($request->hasFile('foto')) {
            $fotoUrl = $this->storage->upload($request->file('foto'), 'foto-tim_k3');
        }

        DB::table('k3_team')->insert([
            'nama'           => $data['nama'],
            'jabatan'        => $data['jabatan'],
            'responsibility' => $data['responsibility'] ?? null,
            'sort_order'     => $data['sort_order'],
            'foto'           => $fotoUrl,
            'status'         => 'active',
            'created_at'     => now(),
            'updated_at'     => now(),
        ]);

        AuditLogger::record('Tim K3', 'create', "Menambah anggota tim: {$data['nama']}");
        return redirect()->route('admin.team.index')->with('success', 'Anggota tim berhasil ditambahkan.');
    }

    public function edit($id)
    {
        $item = DB::table('k3_team')->where('id', $id)->firstOrFail();
        return view('admin.team.form', compact('item'));
    }

    public function update(Request $request, $id)
    {
        $data     = $this->validateData($request);
        $existing = DB::table('k3_team')->where('id', $id)->firstOrFail();

        $fotoUrl = $existing->foto;
        if ($request->hasFile('foto')) {
            $this->storage->delete($existing->foto);
            $fotoUrl = $this->storage->upload($request->file('foto'), 'foto-tim_k3');
        }

        DB::table('k3_team')->where('id', $id)->update([
            'nama'           => $data['nama'],
            'jabatan'        => $data['jabatan'],
            'responsibility' => $data['responsibility'] ?? null,
            'sort_order'     => $data['sort_order'],
            'foto'           => $fotoUrl,
            'updated_at'     => now(),
        ]);

        AuditLogger::record('Tim K3', 'update', "Update anggota tim: {$data['nama']}");
        return redirect()->route('admin.team.index')->with('success', 'Anggota tim berhasil diperbarui.');
    }

    public function destroy($id)
    {
        $item = DB::table('k3_team')->where('id', $id)->firstOrFail();
        $this->storage->delete($item->foto);
        DB::table('k3_team')->where('id', $id)->delete();
        AuditLogger::record('Tim K3', 'delete', "Menghapus anggota tim: {$item->nama}");
        return redirect()->route('admin.team.index')->with('success', 'Anggota tim berhasil dihapus.');
    }

    protected function validateData(Request $request): array
    {
        return $request->validate([
            'nama'           => ['required', 'string', 'max:255'],
            'jabatan'        => ['required', 'string', 'max:255'],
            'responsibility' => ['nullable', 'string'],
            'sort_order'     => ['required', 'integer', 'min:0'],
            'foto'           => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:2048'],
        ]);
    }
}