<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ModelAnnDataset extends Model
{
    use HasFactory;

    protected $table = 'model_ann_datasets';

    protected $fillable = [
        'model_ann_id',
        'data_ips_id',
        'subset',
        'input_raw',
        'input_normalized',
        'target_actual',
        'output_predicted',
        'absolute_error',
        'squared_error',
        'processed_at',
    ];

    protected function casts(): array
    {
        return [
            'input_raw' => 'array',
            'input_normalized' => 'array',
            'target_actual' => 'decimal:3',
            'output_predicted' => 'decimal:3',
            'absolute_error' => 'decimal:12',
            'squared_error' => 'decimal:12',
            'processed_at' => 'datetime',
            'created_at' => 'datetime',
            'updated_at' => 'datetime',
        ];
    }

    public function modelAnn(): BelongsTo
    {
        return $this->belongsTo(ModelAnn::class);
    }

    public function dataIps(): BelongsTo
    {
        return $this->belongsTo(DataIps::class);
    }

    public function isTraining(): bool
    {
        return $this->subset === 'training';
    }

    public function isTesting(): bool
    {
        return $this->subset === 'testing';
    }
}