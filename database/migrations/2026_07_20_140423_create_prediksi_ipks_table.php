<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('prediksi_ipks', function (Blueprint $table): void {
            $table->id();
            $table->uuid('nomor_prediksi')->unique();

            $table->foreignId('mahasiswa_id')
                ->constrained('mahasiswas')
                ->cascadeOnUpdate()
                ->cascadeOnDelete();

            $table->foreignId('data_ips_id')
                ->nullable()
                ->constrained('data_ips')
                ->nullOnDelete();

            $table->foreignId('model_ann_id')
                ->constrained('model_anns')
                ->cascadeOnUpdate()
                ->restrictOnDelete();

            $table->decimal('ips_1', 4, 2);
            $table->decimal('ips_2', 4, 2);
            $table->decimal('ips_3', 4, 2);
            $table->decimal('ips_4', 4, 2);
            $table->decimal('ips_5', 4, 2);

            $table->decimal('ipk_prediksi', 5, 3);
            $table->decimal('ipk_aktual', 5, 3)->nullable();
            $table->decimal('absolute_error', 16, 12)->nullable();
            $table->decimal('squared_error', 16, 12)->nullable();

            $table->json('input_normalized')->nullable();
            $table->text('keterangan')->nullable();

            $table->foreignId('predicted_by')
                ->nullable()
                ->constrained('users')
                ->nullOnDelete();

            $table->timestamp('predicted_at');
            $table->timestamps();

            $table->index(
                ['mahasiswa_id', 'predicted_at'],
                'prediksi_mahasiswa_waktu_index'
            );

            $table->index(
                ['model_ann_id', 'predicted_at'],
                'prediksi_model_waktu_index'
            );
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('prediksi_ipks');
    }
};