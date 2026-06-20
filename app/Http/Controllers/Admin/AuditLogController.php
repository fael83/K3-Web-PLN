<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AuditLog;
use App\Models\User;
use Illuminate\Http\Request;

class AuditLogController extends Controller
{
    public function index(Request $request)
    {
        $query = AuditLog::with('user')->latest('created_at');

        // Filter tanggal
        if ($request->filled('date_from')) {
            $query->whereDate('created_at', '>=', $request->date_from);
        }
        if ($request->filled('date_to')) {
            $query->whereDate('created_at', '<=', $request->date_to);
        }

        // Filter user
        if ($request->filled('user_id')) {
            $query->where('user_id', $request->user_id);
        }

        // Filter modul
        if ($request->filled('module')) {
            $query->where('module', $request->module);
        }

        // Filter aksi
        if ($request->filled('action')) {
            $query->where('action', $request->action);
        }

        $logs    = $query->paginate(25)->withQueryString();
        $users   = User::orderBy('name')->get();
        $modules = AuditLog::distinct()->pluck('module')->sort()->values();

        return view('admin.audit.index', compact('logs', 'users', 'modules'));
    }
}