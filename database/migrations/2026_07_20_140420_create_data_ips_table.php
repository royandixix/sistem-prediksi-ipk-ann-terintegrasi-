<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('data_ips', function (Blueprint $table): void {
            $table->id();

            $table->foreignId('mahasiswa_id')
                ->unique()
                ->constrained('mahasiswas')
                ->cascadeOnUpdate()
                ->cascadeOnDelete();

            $table->decimal('ips_1', 4, 2);
            $table->decimal('ips_2', 4, 2);
            $table->decimal('ips_3', 4, 2);
            $table->decimal('ips_4', 4, 2);
            $table->decimal('ips_5', 4, 2);

            $table->decimal('ipk_akhir_aktual', 5, 3)->nullable();
            $table->boolean('is_complete')->default(false);
            $table->timestamp('validated_at')->nullable();
            $table->text('catatan')->nullable();

            $table->foreignId('created_by')
                ->nullable()
                ->constrained('users')
                ->nullOnDelete();

            $table->timestamps();

            $table->index('is_complete');
            $table->index('ipk_akhir_aktual');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('data_ips');
    }
};