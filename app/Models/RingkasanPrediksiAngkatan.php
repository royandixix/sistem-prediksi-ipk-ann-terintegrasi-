<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class RingkasanPrediksiAngkatan extends Model
{
    use HasFactory;

    protected $table = 'ringkasan_prediksi_angkatans';

    protected $fillable = [
        'model_ann_id',
        'angkatan',
        'program_studi',
        'total_mahasiswa',
        'rata_rata_ipk_prediksi',
        'rata_rata_ipk_aktual',
        'ipk_prediksi_minimum',
        'ipk_prediksi_maksimum',
        'mae',
        'mse',
        'distribusi_prediksi',
        'generated_by',
        'generated_at',
    ];

    protected $attributes = [
        'program_studi' => 'Teknik Informatika',
        'total_mahasiswa' => 0,
    ];

    protected function casts(): array
    {
        return [
            'angkatan' => 'integer',
            'total_mahasiswa' => 'integer',
            'rata_rata_ipk_prediksi' => 'decimal:3',
            'rata_rata_ipk_aktual' => 'decimal:3',
            'ipk_prediksi_minimum' => 'decimal:3',
            'ipk_prediksi_maksimum' => 'decimal:3',
            'mae' => 'decimal:12',
            'mse' => 'decimal:12',
            'distribusi_prediksi' => 'array',
            'generated_at' => 'datetime',
            'created_at' => 'datetime',
            'updated_at' => 'datetime',
        ];
    }

    protected static function booted(): void
    {
        static::creating(
            function (RingkasanPrediksiAngkatan $ringkasan): void {
                if ($ringkasan->generated_at === null) {
                    $ringkasan->generated_at = now();
                }
            }
        );
    }

    public function modelAnn(): BelongsTo
    {
        return $this->belongsTo(ModelAnn::class);
    }

    public function generatedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'generated_by');
    }
}