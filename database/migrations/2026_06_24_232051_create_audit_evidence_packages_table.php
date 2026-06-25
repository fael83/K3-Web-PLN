<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Hanya jalankan jika kolom audit_id masih ada (belum di-rename)
        if (Schema::hasColumn('audit_items', 'audit_id') &&
            !Schema::hasColumn('audit_items', 'audit_checklist_id')) {

            Schema::table('audit_items', function (Blueprint $table) {
                $table->renameColumn('audit_id', 'audit_checklist_id');
            });
        }
    }

    public function down(): void
    {
        if (Schema::hasColumn('audit_items', 'audit_checklist_id') &&
            !Schema::hasColumn('audit_items', 'audit_id')) {

            Schema::table('audit_items', function (Blueprint $table) {
                $table->renameColumn('audit_checklist_id', 'audit_id');
            });
        }
    }
};