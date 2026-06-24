<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('audit_evidence_packages', function (Blueprint $table) {
            $table->id();
            $table->foreignId('audit_checklist_id')->constrained('audit_checklists')->cascadeOnDelete();
            $table->string('package_code')->unique();
            $table->enum('status', ['draft', 'final'])->default('draft');
            $table->text('notes')->nullable();
            $table->foreignId('generated_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamp('generated_at')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('audit_evidence_packages');
    }
};