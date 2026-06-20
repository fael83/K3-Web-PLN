<?php

namespace App\Helpers;

use App\Models\AuditLog;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Request;

class AuditLogger
{
    public static function record(
        string $module,
        string $action,
        string $description = '',
        ?int $targetId = null
    ): void {
        try {
            AuditLog::create([
                'user_id'     => Auth::id(),
                'module'      => $module,
                'action'      => $action,
                'description' => $description,
                'target_id'   => $targetId,
                'ip_address'  => Request::ip(),
                'user_agent'  => Request::userAgent(),
            ]);
        } catch (\Exception $e) {
            // Jangan sampai audit log error menghentikan flow utama
            logger()->error('AuditLogger error: ' . $e->getMessage());
        }
    }
}