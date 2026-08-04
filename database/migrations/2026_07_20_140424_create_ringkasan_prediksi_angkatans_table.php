<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create(
            'ringkasan_prediksi_angkatans',
            function (Blueprint $table): void {
                $table->id();

                $table->foreignId('model_ann_id')
                    ->constrained('model_anns')
                    ->cascadeOnUpdate()
                    ->restrictOnDelete();

                $table->unsignedSmallInteger('angkatan');
                $table->string('program_studi', 100)
                    ->default('Teknik Informatika');

                $table->unsignedInteger('total_mahasiswa')->default(0);

                $table->decimal('rata_rata_ipk_prediksi', 5, 3);
                $table->decimal('rata_rata_ipk_aktual', 5, 3)->nullable();

                $table->decimal('ipk_prediksi_minimum', 5, 3)->nullable();
                $table->decimal('ipk_prediksi_maksimum', 5, 3)->nullable();

                $table->decimal('mae', 16, 12)->nullable();
                $table->decimal('mse', 16, 12)->nullable();

                $table->json('distribusi_prediksi')->nullable();

                $table->foreignId('generated_by')
                    ->nullable()
                    ->constrained('users')
                    ->nullOnDelete();

                $table->timestamp('generated_at');
                $table->timestamps();

                $table->unique(
                    ['model_ann_id', 'angkatan', 'program_studi'],
                    'ringkasan_model_angkatan_unique'
                );

                $table->index('angkatan');
            }
        );
    }

    public function down(): void
    {
        Schema::dropIfExists('ringkasan_prediksi_angkatans');
    }
};