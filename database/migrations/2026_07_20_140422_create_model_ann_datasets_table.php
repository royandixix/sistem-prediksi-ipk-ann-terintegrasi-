<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('model_ann_datasets', function (Blueprint $table): void {
            $table->id();

            $table->foreignId('model_ann_id')
                ->constrained('model_anns')
                ->cascadeOnUpdate()
                ->cascadeOnDelete();

            $table->foreignId('data_ips_id')
                ->constrained('data_ips')
                ->cascadeOnUpdate()
                ->restrictOnDelete();

            $table->string('subset', 20);
            $table->json('input_raw');
            $table->json('input_normalized');

            $table->decimal('target_actual', 5, 3);
            $table->decimal('output_predicted', 5, 3)->nullable();
            $table->decimal('absolute_error', 16, 12)->nullable();
            $table->decimal('squared_error', 16, 12)->nullable();

            $table->timestamp('processed_at')->nullable();
            $table->timestamps();

            $table->unique(
                ['model_ann_id', 'data_ips_id'],
                'model_dataset_unique'
            );

            $table->index(
                ['model_ann_id', 'subset'],
                'model_dataset_subset_index'
            );
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('model_ann_datasets');
    }
};