<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table): void {
            $table->string('username', 50)
                ->unique()
                ->after('name');

            $table->string('role', 20)
                ->default('operator')
                ->index()
                ->after('password');

            $table->boolean('is_active')
                ->default(true)
                ->index()
                ->after('role');

            $table->timestamp('last_login_at')
                ->nullable()
                ->after('is_active');

            $table->string('last_login_ip', 45)
                ->nullable()
                ->after('last_login_at');
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table): void {
            $table->dropUnique('users_username_unique');
            $table->dropIndex('users_role_index');
            $table->dropIndex('users_is_active_index');

            $table->dropColumn([
                'username',
                'role',
                'is_active',
                'last_login_at',
                'last_login_ip',
            ]);
        });
    }
};