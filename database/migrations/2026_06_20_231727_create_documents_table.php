<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Tabel utama dokumen
        Schema::create('documents', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->string('document_number')->unique();
            $table->string('category'); 
            // kategori: policy, standard, procedure, legal, form_template, record, emergency
            $table->string('owner_department')->nullable();
            $table->string('status')->default('draft');
            // status: draft, under_review, approved, obsolete
            $table->string('file_url');
            $table->string('file_name');
            $table->string('file_type');  // pdf, docx, xlsx, jpg, png
            $table->unsignedBigInteger('file_size'); // dalam bytes
            $table->integer('revision_number')->default(1);
            $table->date('effective_date')->nullable();
            $table->date('review_date')->nullable(); // untuk alert expired
            $table->text('description')->nullable();
            $table->foreignId('uploaded_by')->constrained('users')->onDelete('cascade');
            $table->foreignId('approved_by')->nullable()->constrained('users')->onDelete('set null');
            $table->timestamp('approved_at')->nullable();
            $table->timestamps();
        });

        // Tabel riwayat versi dokumen
        Schema::create('document_versions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('document_id')->constrained('documents')->onDelete('cascade');
            $table->integer('revision_number');
            $table->string('file_url');
            $table->string('file_name');
            $table->string('file_type');
            $table->unsignedBigInteger('file_size');
            $table->string('status');
            $table->text('change_notes')->nullable(); // catatan perubahan revisi
            $table->foreignId('uploaded_by')->constrained('users')->onDelete('cascade');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('document_versions');
        Schema::dropIfExists('documents');
    }
};