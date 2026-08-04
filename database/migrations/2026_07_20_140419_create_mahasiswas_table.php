<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('mahasiswas', function (Blueprint $table): void {
            $table->id();
            $table->string('nim', 30)->unique();
            $table->string('nama', 150);
            $table->unsignedSmallInteger('angkatan')->default(2023);
            $table->string('program_studi', 100)->default('Teknik Informatika');
            $table->string('status', 20)->default('aktif');

            $table->foreignId('created_by')
                ->nullable()
                ->constrained('users')
                ->nullOnDelete();

            $table->timestamps();

            $table->index('nama');
            $table->index('angkatan');
            $table->index('status');
            $table->index(
                ['angkatan', 'program_studi'],
                'mahasiswa_angkatan_prodi_index'
            );
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('mahasiswas');
    }
};