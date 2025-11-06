<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('presences', function (Blueprint $table) {
            $table->id();
            $table->foreignId('matkul_id')->constrained('matkuls')->onDelete('cascade');
            $table->foreignId('mahasiswa_id')->constrained('mahasiswas')->onDelete('cascade');
            $table->date('tanggal');
            $table->enum('status', ['alpha', 'izin', 'sakit', 'hadir'])->default('alpha');
            $table->text('note')->nullable();
            $table->foreignId('recorded_by')->constrained('users')->onDelete('cascade');
            $table->timestamps();
            
            $table->unique(['matkul_id', 'mahasiswa_id', 'tanggal']);
            $table->index(['matkul_id', 'tanggal']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('presences');
    }
};

