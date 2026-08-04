<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('data_ips', function (Blueprint $table): void {
            $table->string('data_source', 150)
                ->nullable()
                ->after('catatan');
            $table->string('preprocessing_method', 80)
                ->nullable()
                ->after('data_source');
            $table->boolean('is_estimated')
                ->default(false)
                ->after('preprocessing_method');
            $table->json('source_terms')
                ->nullable()
                ->after('is_estimated');

            $table->index('data_source');
            $table->index('is_estimated');
        });
    }

    public function down(): void
    {
        Schema::table('data_ips', function (Blueprint $table): void {
            $table->dropIndex(['data_source']);
            $table->dropIndex(['is_estimated']);
            $table->dropColumn([
                'data_source',
                'preprocessing_method',
                'is_estimated',
                'source_terms',
            ]);
        });
    }
};
