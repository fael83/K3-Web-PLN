<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('incidents', function (Blueprint $table) {
            // User yang melaporkan insiden
            $table->foreignId('reporter_id')
                  ->nullable()
                  ->after('id')
                  ->constrained('users')
                  ->nullOnDelete();

            // Departemen terkait insiden
            $table->foreignId('department_id')
                  ->nullable()
                  ->after('reporter_id')
                  ->constrained('departments')
                  ->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::table('incidents', function (Blueprint $table) {
            $table->dropConstrainedForeignId('reporter_id');
            $table->dropConstrainedForeignId('department_id');
        });
    }
};