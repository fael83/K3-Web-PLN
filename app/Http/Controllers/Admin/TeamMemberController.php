<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\TeamMember;
use App\Support\AuditLogger;
use Illuminate\Http\Request;

class TeamMemberController extends Controller
{
    public function index()
    {
        $items = TeamMember::orderBy('sort_order')->paginate(15);

        return view('admin.team.index', compact('items'));
    }

    public function create()
    {
        return view('admin.team.form', ['item' => new TeamMember()]);
    }

    public function store(Request $request)
    {
        $item = TeamMember::create($this->validateData($request));
        AuditLogger::record('Tim K3', 'create', "Menambah anggota tim: {$item->position}");

        return redirect()->route('admin.team.index')->with('success', 'Anggota tim berhasil ditambahkan.');
    }

    public function edit(TeamMember $team)
    {
        return view('admin.team.form', ['item' => $team]);
    }

    public function update(Request $request, TeamMember $team)
    {
        $team->update($this->validateData($request));
        AuditLogger::record('Tim K3', 'update', "Memperbarui anggota tim: {$team->position}");

        return redirect()->route('admin.team.index')->with('success', 'Anggota tim berhasil diperbarui.');
    }

    public function destroy(TeamMember $team)
    {
        $position = $team->position;
        $team->delete();
        AuditLogger::record('Tim K3', 'delete', "Menghapus anggota tim: {$position}");

        return redirect()->route('admin.team.index')->with('success', 'Anggota tim berhasil dihapus.');
    }

    protected function validateData(Request $request): array
    {
        return $request->validate([
            'position'       => ['required', 'string', 'max:255'],
            'name'           => ['nullable', 'string', 'max:255'],
            'responsibility' => ['nullable', 'string'],
            'level'          => ['required', 'in:'.implode(',', array_keys(TeamMember::LEVELS))],
            'sort_order'     => ['required', 'integer', 'min:0'],
        ]);
    }
}
