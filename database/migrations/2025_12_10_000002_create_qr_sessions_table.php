<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('qr_sessions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('matkul_id')->constrained('matkuls')->onDelete('cascade');
            $table->string('token')->unique();
            $table->timestamp('expires_at');
            $table->foreignId('created_by')->constrained('users')->onDelete('cascade');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('qr_sessions');
    }
};
