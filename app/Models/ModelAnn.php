<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class ModelAnn extends Model
{
    use HasFactory;

    protected $table = 'model_anns';

    protected $fillable = [
        'kode_model',
        'nama_model',
        'versi',
        'input_neurons',
        'hidden_layers',
        'output_neurons',
        'hidden_activation',
        'output_activation',
        'learning_rate',
        'max_epoch',
        'target_error',
        'training_ratio',
        'testing_ratio',
        'random_seed',
        'weights',
        'biases',
        'normalization_params',
        'total_data',
        'training_data_count',
        'testing_data_count',
        'mae',
        'mse',
        'status',
        'is_active',
        'training_started_at',
        'training_finished_at',
        'trained_by',
        'catatan',
    ];

    protected $attributes = [
        'nama_model' => 'Artificial Neural Network Prediksi IPK',
        'versi' => 1,
        'input_neurons' => 5,
        'output_neurons' => 1,
        'hidden_activation' => 'sigmoid',
        'output_activation' => 'sigmoid',
        'learning_rate' => 0.1,
        'max_epoch' => 1000,
        'target_error' => 0.001,
        'training_ratio' => 80,
        'testing_ratio' => 20,
        'random_seed' => 42,
        'total_data' => 0,
        'training_data_count' => 0,
        'testing_data_count' => 0,
        'status' => 'draft',
        'is_active' => false,
    ];

    protected function casts(): array
    {
        return [
            'versi' => 'integer',
            'input_neurons' => 'integer',
            'hidden_layers' => 'array',
            'output_neurons' => 'integer',
            'learning_rate' => 'decimal:6',
            'max_epoch' => 'integer',
            'target_error' => 'decimal:8',
            'training_ratio' => 'decimal:2',
            'testing_ratio' => 'decimal:2',
            'random_seed' => 'integer',
            'weights' => 'array',
            'biases' => 'array',
            'normalization_params' => 'array',
            'total_data' => 'integer',
            'training_data_count' => 'integer',
            'testing_data_count' => 'integer',
            'mae' => 'decimal:6',
            'mse' => 'decimal:6',
            'is_active' => 'boolean',
            'training_started_at' => 'datetime',
            'training_finished_at' => 'datetime',
            'trained_by' => 'integer',
            'created_at' => 'datetime',
            'updated_at' => 'datetime',
        ];
    }

    public function trainedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'trained_by');
    }

    public function datasets(): HasMany
    {
        return $this->hasMany(ModelAnnDataset::class, 'model_ann_id');
    }

    public function prediksiIpks(): HasMany
    {
        return $this->hasMany(PrediksiIpk::class, 'model_ann_id');
    }

    public function scopeActive(Builder $query): Builder
    {
        return $query->where('is_active', true);
    }

    public function scopeTrained(Builder $query): Builder
    {
        return $query->whereIn('status', ['trained', 'completed', 'selesai']);
    }

    public function scopeSuccessful(Builder $query): Builder
    {
        return $query->trained()->whereNotNull('mae')->whereNotNull('mse');
    }

    public function getUuidAttribute(): ?string
    {
        return $this->kode_model;
    }

    public function getArchitectureAttribute(): array
    {
        return [
            'input' => (int) $this->input_neurons,
            'hidden' => $this->hiddenLayerValues(),
            'output' => (int) $this->output_neurons,
        ];
    }

    public function getConfigurationAttribute(): array
    {
        return [
            'hidden_neurons' => $this->primaryHiddenNeuron(),
            'learning_rate' => $this->learningRate(),
            'epochs' => $this->requestedEpochs(),
            'max_epoch' => $this->requestedEpochs(),
            'target_error' => $this->target_error !== null ? (float) $this->target_error : null,
            'training_percentage' => $this->trainingPercentage(),
            'testing_percentage' => $this->testingPercentage(),
            'test_percentage' => $this->testingPercentage(),
            'random_seed' => $this->randomSeed(),
        ];
    }

    public function getNormalizationAttribute(): array
    {
        return is_array($this->normalization_params)
            ? $this->normalization_params
            : [];
    }

    public function getTrainCountAttribute(): int
    {
        return (int) $this->training_data_count;
    }

    public function getTestCountAttribute(): int
    {
        return (int) $this->testing_data_count;
    }

    public function hiddenLayerValues(): array
    {
        $layers = $this->hidden_layers;

        if (is_string($layers)) {
            $decoded = json_decode($layers, true);

            if (is_array($decoded)) {
                $layers = $decoded;
            } else {
                $layers = preg_split('/\s*,\s*/', $layers) ?: [];
            }
        }

        if (! is_array($layers)) {
            return [];
        }

        return array_values(array_filter(array_map(
            static fn (mixed $value): int => (int) $value,
            $layers
        ), static fn (int $value): bool => $value > 0));
    }

    public function primaryHiddenNeuron(): int
    {
        return $this->hiddenLayerValues()[0] ?? 0;
    }

    public function architectureLabel(): string
    {
        $parts = [(int) $this->input_neurons];

        foreach ($this->hiddenLayerValues() as $hiddenNeuron) {
            $parts[] = $hiddenNeuron;
        }

        $parts[] = (int) $this->output_neurons;

        return implode(' – ', $parts);
    }

    public function statusLabel(): string
    {
        return match ($this->status) {
            'trained', 'completed', 'selesai' => 'Berhasil Dilatih',
            'training' => 'Sedang Dilatih',
            'failed', 'gagal' => 'Gagal',
            'draft' => 'Draft',
            default => ucwords(str_replace(['_', '-'], ' ', (string) $this->status)),
        };
    }

    public function statusColor(): string
    {
        return match ($this->status) {
            'trained', 'completed', 'selesai' => 'emerald',
            'training' => 'blue',
            'failed', 'gagal' => 'red',
            default => 'slate',
        };
    }

    public function learningRate(): ?float
    {
        return $this->learning_rate !== null
            ? (float) $this->learning_rate
            : null;
    }

    public function requestedEpochs(): ?int
    {
        return $this->max_epoch !== null
            ? (int) $this->max_epoch
            : null;
    }

    public function randomSeed(): ?int
    {
        return $this->random_seed !== null
            ? (int) $this->random_seed
            : null;
    }

    public function trainingPercentage(): int
    {
        return $this->normalizeRatioToPercentage($this->training_ratio);
    }

    public function testingPercentage(): int
    {
        return $this->normalizeRatioToPercentage($this->testing_ratio);
    }

    private function normalizeRatioToPercentage(mixed $value): int
    {
        $ratio = (float) $value;

        if ($ratio > 0 && $ratio <= 1) {
            $ratio *= 100;
        }

        return max(0, min(100, (int) round($ratio)));
    }

    public function totalDataset(): int
    {
        if ((int) $this->total_data > 0) {
            return (int) $this->total_data;
        }

        return (int) $this->training_data_count + (int) $this->testing_data_count;
    }

    public function maeValue(): ?float
    {
        return $this->mae !== null ? (float) $this->mae : null;
    }

    public function mseValue(): ?float
    {
        return $this->mse !== null ? (float) $this->mse : null;
    }

    public function isTrained(): bool
    {
        return in_array($this->status, ['trained', 'completed', 'selesai'], true);
    }

    public function isTraining(): bool
    {
        return $this->status === 'training';
    }

    public function isFailed(): bool
    {
        return in_array($this->status, ['failed', 'gagal'], true);
    }

    public function isReadyForPrediction(): bool
    {
        return $this->is_active
            && $this->isTrained()
            && is_array($this->weights)
            && $this->weights !== []
            && is_array($this->biases)
            && $this->biases !== []
            && is_array($this->normalization_params)
            && $this->normalization_params !== [];
    }

    public function trainingDurationInSeconds(): ?int
    {
        if ($this->training_started_at === null || $this->training_finished_at === null) {
            return null;
        }

        return (int) $this->training_started_at->diffInSeconds($this->training_finished_at);
    }

    public function trainingDurationLabel(): string
    {
        $seconds = $this->trainingDurationInSeconds();

        if ($seconds === null) {
            return '-';
        }

        if ($seconds < 60) {
            return "{$seconds} detik";
        }

        $minutes = intdiv($seconds, 60);
        $remainingSeconds = $seconds % 60;

        return $remainingSeconds === 0
            ? "{$minutes} menit"
            : "{$minutes} menit {$remainingSeconds} detik";
    }
}
