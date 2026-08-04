<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PrediksiIpk extends Model
{
    use HasFactory;

    protected $table = 'prediksi_ipks';

    protected $fillable = [
        'nomor_prediksi',
        'mahasiswa_id',
        'data_ips_id',
        'model_ann_id',
        'ips_1',
        'ips_2',
        'ips_3',
        'ips_4',
        'ips_5',
        'ipk_prediksi',
        'ipk_aktual',
        'absolute_error',
        'squared_error',
        'input_normalized',
        'keterangan',
        'predicted_by',
        'predicted_at',
    ];

    protected function casts(): array
    {
        return [
            'ips_1' => 'decimal:2',
            'ips_2' => 'decimal:2',
            'ips_3' => 'decimal:2',
            'ips_4' => 'decimal:2',
            'ips_5' => 'decimal:2',
            'ipk_prediksi' => 'decimal:3',
            'ipk_aktual' => 'decimal:3',
            'absolute_error' => 'decimal:6',
            'squared_error' => 'decimal:6',
            'input_normalized' => 'array',
            'predicted_at' => 'datetime',
            'created_at' => 'datetime',
            'updated_at' => 'datetime',
        ];
    }

    public function mahasiswa(): BelongsTo
    {
        return $this->belongsTo(
            Mahasiswa::class,
            'mahasiswa_id'
        );
    }

    public function dataIps(): BelongsTo
    {
        return $this->belongsTo(
            DataIps::class,
            'data_ips_id'
        );
    }

    public function modelAnn(): BelongsTo
    {
        return $this->belongsTo(
            ModelAnn::class,
            'model_ann_id'
        );
    }

    public function predictedBy(): BelongsTo
    {
        return $this->belongsTo(
            User::class,
            'predicted_by'
        );
    }

    public function scopeLatestPrediction(
        Builder $query
    ): Builder {
        return $query->latest('predicted_at');
    }

    public function ipsValues(): array
    {
        return [
            (float) $this->ips_1,
            (float) $this->ips_2,
            (float) $this->ips_3,
            (float) $this->ips_4,
            (float) $this->ips_5,
        ];
    }

    public function averageIps(): float
    {
        $values = $this->ipsValues();

        if (count($values) === 0) {
            return 0;
        }

        return round(
            array_sum($values) / count($values),
            3
        );
    }

    public function predictedIpkValue(): float
    {
        return (float) $this->ipk_prediksi;
    }

    public function actualIpkValue(): ?float
    {
        return $this->ipk_aktual !== null
            ? (float) $this->ipk_aktual
            : null;
    }

    public function absoluteErrorValue(): ?float
    {
        return $this->absolute_error !== null
            ? (float) $this->absolute_error
            : null;
    }

    public function squaredErrorValue(): ?float
    {
        return $this->squared_error !== null
            ? (float) $this->squared_error
            : null;
    }

    public function hasActualIpk(): bool
    {
        return $this->ipk_aktual !== null;
    }

    public function isEvaluated(): bool
    {
        return $this->ipk_aktual !== null
            && $this->absolute_error !== null
            && $this->squared_error !== null;
    }
}