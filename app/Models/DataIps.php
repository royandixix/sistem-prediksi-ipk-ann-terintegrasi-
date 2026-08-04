<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class DataIps extends Model
{
    use HasFactory;

    protected $table = 'data_ips';

    protected $fillable = [
        'mahasiswa_id',
        'ips_1',
        'ips_2',
        'ips_3',
        'ips_4',
        'ips_5',
        'ipk_akhir_aktual',
        'is_complete',
        'validated_at',
        'catatan',
        'data_source',
        'preprocessing_method',
        'is_estimated',
        'source_terms',
        'created_by',
    ];

    protected $attributes = [
        'is_complete' => false,
    ];

    protected function casts(): array
    {
        return [
            'mahasiswa_id' => 'integer',

            'ips_1' => 'decimal:2',
            'ips_2' => 'decimal:2',
            'ips_3' => 'decimal:2',
            'ips_4' => 'decimal:2',
            'ips_5' => 'decimal:2',

            'ipk_akhir_aktual' => 'decimal:3',

            'is_complete' => 'boolean',
            'is_estimated' => 'boolean',
            'source_terms' => 'array',
            'validated_at' => 'datetime',

            'created_by' => 'integer',

            'created_at' => 'datetime',
            'updated_at' => 'datetime',
        ];
    }

    /*
    |--------------------------------------------------------------------------
    | Model events
    |--------------------------------------------------------------------------
    */

    protected static function booted(): void
    {
        static::saving(function (DataIps $dataIps): void {
            $dataIps->is_complete =
                $dataIps->hasCompleteIpsValues();
        });
    }

    /*
    |--------------------------------------------------------------------------
    | Relationships
    |--------------------------------------------------------------------------
    */

    public function mahasiswa(): BelongsTo
    {
        return $this->belongsTo(
            Mahasiswa::class,
            'mahasiswa_id'
        );
    }

    public function createdBy(): BelongsTo
    {
        return $this->belongsTo(
            User::class,
            'created_by'
        );
    }

    public function modelDatasets(): HasMany
    {
        return $this->hasMany(
            ModelAnnDataset::class,
            'data_ips_id'
        );
    }

    public function prediksiIpks(): HasMany
    {
        return $this->hasMany(
            PrediksiIpk::class,
            'data_ips_id'
        );
    }

    /*
    |--------------------------------------------------------------------------
    | Query scopes
    |--------------------------------------------------------------------------
    */

    public function scopeSearch(
        Builder $query,
        ?string $keyword
    ): Builder {
        $keyword = trim((string) $keyword);

        if ($keyword === '') {
            return $query;
        }

        return $query->whereHas(
            'mahasiswa',
            function (Builder $studentQuery) use ($keyword): void {
                $studentQuery->where(
                    function (Builder $searchQuery) use ($keyword): void {
                        $searchQuery
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
        );
    }

    public function scopeComplete(
        Builder $query
    ): Builder {
        return $query->where(
            'is_complete',
            true
        );
    }

    /**
     * Data yang mempunyai IPS Semester 1 sampai 5 lengkap.
     */
    public function scopeReadyForPrediction(
        Builder $query
    ): Builder {
        foreach ($this->ipsColumns() as $column) {
            $query
                ->whereNotNull($column)
                ->whereBetween(
                    $column,
                    [0, 4]
                );
        }

        return $query;
    }

    /**
     * Data yang dapat digunakan untuk training ANN.
     *
     * Training membutuhkan:
     * - IPS Semester 1 sampai 5 lengkap
     * - IPK akhir aktual sebagai target
     */
    public function scopeEligibleForTraining(
        Builder $query
    ): Builder {
        return $query
            ->readyForPrediction()
            ->whereNotNull('ipk_akhir_aktual')
            ->whereBetween(
                'ipk_akhir_aktual',
                [0, 4]
            );
    }

    public function scopeHasActualIpk(
        Builder $query
    ): Builder {
        return $query
            ->whereNotNull('ipk_akhir_aktual')
            ->whereBetween(
                'ipk_akhir_aktual',
                [0, 4]
            );
    }

    /*
    |--------------------------------------------------------------------------
    | IPS helpers
    |--------------------------------------------------------------------------
    */

    public function ipsColumns(): array
    {
        return [
            'ips_1',
            'ips_2',
            'ips_3',
            'ips_4',
            'ips_5',
        ];
    }

    /**
     * Mengambil nilai IPS asli tanpa mengubah null menjadi 0.
     */
    public function nullableInputs(): array
    {
        return [
            $this->ips_1 !== null
                ? (float) $this->ips_1
                : null,

            $this->ips_2 !== null
                ? (float) $this->ips_2
                : null,

            $this->ips_3 !== null
                ? (float) $this->ips_3
                : null,

            $this->ips_4 !== null
                ? (float) $this->ips_4
                : null,

            $this->ips_5 !== null
                ? (float) $this->ips_5
                : null,
        ];
    }

    /**
     * Input numerik untuk ANN.
     *
     * Method ini sebaiknya digunakan setelah data dipastikan lengkap.
     */
    public function rawInputs(): array
    {
        return array_map(
            static fn (?float $value): float =>
                $value ?? 0.0,
            $this->nullableInputs()
        );
    }

    public function averageIps(): float
    {
        $validInputs = collect(
            $this->nullableInputs()
        )->filter(
            static fn (mixed $value): bool =>
                $value !== null
        );

        if ($validInputs->isEmpty()) {
            return 0.0;
        }

        return round(
            (float) $validInputs->avg(),
            3
        );
    }

    public function hasCompleteIpsValues(): bool
    {
        return collect(
            $this->nullableInputs()
        )->every(
            static fn (mixed $value): bool =>
                $value !== null
                && is_numeric($value)
                && (float) $value >= 0
                && (float) $value <= 4
        );
    }

    public function isCompleteForPrediction(): bool
    {
        return $this->hasCompleteIpsValues();
    }

    public function hasActualIpk(): bool
    {
        if ($this->ipk_akhir_aktual === null) {
            return false;
        }

        $actualIpk = (float) $this->ipk_akhir_aktual;

        return $actualIpk >= 0
            && $actualIpk <= 4;
    }

    public function isEligibleForTraining(): bool
    {
        return $this->hasCompleteIpsValues()
            && $this->hasActualIpk();
    }

    public function actualIpkValue(): ?float
    {
        return $this->ipk_akhir_aktual !== null
            ? (float) $this->ipk_akhir_aktual
            : null;
    }
}