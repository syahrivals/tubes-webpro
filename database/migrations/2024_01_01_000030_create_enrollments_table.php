<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('enrollments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('mahasiswa_id')->constrained('mahasiswas')->onDelete('cascade');
            $table->foreignId('matkul_id')->constrained('matkuls')->onDelete('cascade');
            $table->timestamps();
            
            $table->unique(['mahasiswa_id', 'matkul_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('enrollments');
    }
};

