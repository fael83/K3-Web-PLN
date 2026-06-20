<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('audit_checklists', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->text('description')->nullable();
            $table->string('audit_type')->default('internal'); // internal, eksternal, smk3, iso45001
            $table->date('audit_date')->nullable();
            $table->string('auditor_name')->nullable();
            $table->enum('status', ['draft', 'in_progress', 'completed'])->default('draft');
            $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();
        });

        Schema::create('audit_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('audit_id')->constrained('audit_checklists')->cascadeOnDelete();
            $table->string('item_name');
            $table->text('description')->nullable();
            $table->enum('status', ['pending', 'conformance', 'minor_nc', 'major_nc', 'observation'])
                  ->default('pending');
            $table->text('finding')->nullable();
            $table->text('corrective_action')->nullable();
            $table->string('evidence_ref')->nullable(); // referensi ke modul lain
            $table->integer('sort_order')->default(0);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('audit_items');
        Schema::dropIfExists('audit_checklists');
    }
};
