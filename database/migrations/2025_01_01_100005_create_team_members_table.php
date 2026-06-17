<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('k3_team', function (Blueprint $table) {
            $table->id();

            $table->string('nama')->nullable();

            // ini SEKALIGUS struktur organisasi
            $table->string('jabatan');

            $table->text('responsibility')->nullable();

            $table->integer('sort_order')->default(0);

            $table->string('foto')->nullable();

            $table->enum('status', ['active', 'inactive'])->default('active');

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('k3_team');
    }
};
