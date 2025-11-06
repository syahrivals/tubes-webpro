<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('matkuls', function (Blueprint $table) {
            $table->id();
            $table->string('kode')->unique();
            $table->string('nama');
            $table->foreignId('dosen_id')->constrained('users')->onDelete('cascade');
            $table->integer('semester');
            $table->integer('credits');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('matkuls');
    }
};

