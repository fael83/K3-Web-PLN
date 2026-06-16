<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('hazards', function (Blueprint $table) {
            $table->id();
            $table->string('category'); // fisik_mekanik, kimia, biomekanik, lingkungan, psikologi_sosial
            $table->string('name');
            $table->text('description')->nullable();
            $table->string('location')->nullable();
            $table->string('likelihood')->default('sedang'); // rendah, sedang, tinggi
            $table->string('severity')->default('sedang');   // rendah, sedang, tinggi
            $table->text('control_measure')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('hazards');
    }
};
