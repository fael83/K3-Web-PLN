<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('incidents', function (Blueprint $table) {
            $table->id();
            $table->string('incident_type'); // near_miss, kecelakaan_ringan, kecelakaan_berat, kebakaran, lainnya
            $table->string('title');
            $table->text('description')->nullable();
            $table->string('location')->nullable();
            $table->date('incident_date')->nullable();
            $table->string('status')->default('open'); // open, investigasi, selesai
            $table->text('corrective_action')->nullable();
            $table->string('evidence_url')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('incidents');
    }
};
