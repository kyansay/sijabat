<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('email_logs', function (Blueprint $table) {
            $table->id();
            // Menghubungkan log dengan tabel pejabats
            $table->foreignId('pejabat_id')->constrained('pejabats')->onDelete('cascade');
            $table->string('email_tujuan');
            $table->string('status'); // Berhasil atau Gagal
            $table->text('keterangan')->nullable(); // Alasan jika gagal
            $table->timestamps(); // Mencatat tanggal dan waktu pengiriman (created_at)
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('email_logs');
    }
};
