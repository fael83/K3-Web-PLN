<?php

namespace App\Support;

use App\Models\AuditLog;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Request;

/**
 * Pencatat audit trail sederhana untuk aksi create/update/delete.
 *
 * Contoh penggunaan di controller:
 *   AuditLogger::record('APD', 'create', "Menambah APD: {$apd->name}");
 */
class AuditLogger
{
    public static function record(string $module, string $action, string $description = ''): void
    {
        AuditLog::create([
            'user_id'     => Auth::id(),
            'module'      => $module,
            'action'      => $action,
            'description' => $description,
            'ip_address'  => Request::ip(),
            'created_at'  => now(),
        ]);
    }
}
