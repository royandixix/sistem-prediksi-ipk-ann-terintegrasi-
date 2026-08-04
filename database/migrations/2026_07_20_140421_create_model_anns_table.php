<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('model_anns', function (Blueprint $table): void {
            $table->id();
            $table->uuid('kode_model')->unique();
            $table->string('nama_model', 150);
            $table->unsignedInteger('versi')->default(1);

            $table->unsignedSmallInteger('input_neurons')->default(5);
            $table->json('hidden_layers');
            $table->unsignedSmallInteger('output_neurons')->default(1);

            $table->string('hidden_activation', 50)->default('sigmoid');
            $table->string('output_activation', 50)->default('linear');

            $table->decimal('learning_rate', 12, 10)->default(0.01);
            $table->unsignedInteger('max_epoch')->default(1000);
            $table->decimal('target_error', 16, 12)->default(0.001);

            $table->decimal('training_ratio', 5, 2)->default(80.00);
            $table->decimal('testing_ratio', 5, 2)->default(20.00);
            $table->integer('random_seed')->nullable();

            $table->json('weights')->nullable();
            $table->json('biases')->nullable();
            $table->json('normalization_params')->nullable();

            $table->unsignedInteger('total_data')->default(0);
            $table->unsignedInteger('training_data_count')->default(0);
            $table->unsignedInteger('testing_data_count')->default(0);

            $table->decimal('mae', 16, 12)->nullable();
            $table->decimal('mse', 16, 12)->nullable();

            $table->string('status', 30)->default('draft');
            $table->boolean('is_active')->default(false);

            $table->timestamp('training_started_at')->nullable();
            $table->timestamp('training_finished_at')->nullable();

            $table->foreignId('trained_by')
                ->nullable()
                ->constrained('users')
                ->nullOnDelete();

            $table->text('catatan')->nullable();
            $table->timestamps();

            $table->index('status');
            $table->index('is_active');
            $table->index('training_finished_at');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('model_anns');
    }
};