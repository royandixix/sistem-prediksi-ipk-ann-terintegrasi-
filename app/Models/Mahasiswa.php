<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Mahasiswa extends Model
{
    use HasFactory;

    protected $table = 'mahasiswas';

    protected $fillable = [
        'nim',
        'nama',
        'angkatan',
        'program_studi',
        'status',
        'created_by',
    ];

    protected $attributes = [
        'angkatan' => 2023,
        'program_studi' => 'Teknik Informatika',
        'status' => 'aktif',
    ];

    protected function casts(): array
    {
        return [
            'angkatan' => 'integer',
            'created_at' => 'datetime',
            'updated_at' => 'datetime',
        ];
    }

    public function getRouteKeyName(): string
    {
        return 'nim';
    }

    public function createdBy(): BelongsTo
    {
        return $this->belongsTo(
            User::class,
            'created_by'
        );
    }

    public function dataIps(): HasOne
    {
        return $this->hasOne(DataIps::class);
    }

    public function prediksiIpks(): HasMany
    {
        return $this->hasMany(PrediksiIpk::class);
    }

    public function prediksiTerbaru(): HasOne
    {
        return $this->prediksiIpks()
            ->one()
            ->latestOfMany('predicted_at');
    }

    public function scopeSearch(
        Builder $query,
        ?string $keyword
    ): Builder {
        $keyword = trim((string) $keyword);

        if ($keyword === '') {
            return $query;
        }

        return $query->where(
            function (Builder $query) use ($keyword): void {
                $query
                    ->where(
                        'nim',
                        'like',
                        "%{$keyword}%"
                    )
                    ->orWhere(
                        'nama',
                        'like',
                        "%{$keyword}%"
                    );
            }
        );
    }

    public function scopeAngkatan(
        Builder $query,
        int|string|null $angkatan
    ): Builder {
        if ($angkatan === null || $angkatan === '') {
            return $query;
        }

        return $query->where(
            'angkatan',
            (int) $angkatan
        );
    }

    public function scopeStatus(
        Builder $query,
        ?string $status
    ): Builder {
        if ($status === null || $status === '') {
            return $query;
        }

        return $query->where(
            'status',
            $status
        );
    }

    public function scopeAktif(
        Builder $query
    ): Builder {
        return $query->where(
            'status',
            'aktif'
        );
    }
}