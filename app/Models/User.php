<?php

namespace App\Models;

use App\Enums\UserRole;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory;
    use Notifiable;

    protected $fillable = [
        'name',
        'username',
        'email',
        'password',
        'role',
        'is_active',
        'last_login_at',
        'last_login_ip',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'role' => UserRole::class,
            'is_active' => 'boolean',
            'last_login_at' => 'datetime',
        ];
    }

    public function createdMahasiswas(): HasMany
    {
        return $this->hasMany(Mahasiswa::class, 'created_by');
    }

    public function createdDataIps(): HasMany
    {
        return $this->hasMany(DataIps::class, 'created_by');
    }

    public function trainedModels(): HasMany
    {
        return $this->hasMany(ModelAnn::class, 'trained_by');
    }

    public function predictions(): HasMany
    {
        return $this->hasMany(PrediksiIpk::class, 'predicted_by');
    }

    public function generatedSummaries(): HasMany
    {
        return $this->hasMany(
            RingkasanPrediksiAngkatan::class,
            'generated_by'
        );
    }

    public function isAdmin(): bool
    {
        return $this->role === UserRole::ADMIN;
    }

    public function isOperator(): bool
    {
        return $this->role === UserRole::OPERATOR;
    }
}