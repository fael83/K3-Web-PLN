<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('sop_steps', function (Blueprint $table) {
            $table->id();
            $table->string('sector'); // sebelum, saat, pembangkitan, transmisi, setelah
            $table->integer('step_order')->default(0);
            $table->string('title');
            $table->text('description')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('sop_steps');
    }
};
