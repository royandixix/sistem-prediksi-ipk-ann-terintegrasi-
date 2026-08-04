<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class TrainModelAnnRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    protected function prepareForValidation(): void
    {
        $this->merge([
            'hidden_neurons' => $this->input('hidden_neurons', 8),
            'learning_rate' => $this->input('learning_rate', 0.1),
            'epochs' => $this->input('epochs', 1000),
            'target_error' => $this->input('target_error', 0.001),
            'test_percentage' => $this->input('test_percentage', 20),
            'random_seed' => $this->input('random_seed', 42),
            'nama_model' => $this->input('nama_model', 'ANN Prediksi IPK'),
            'catatan' => $this->input('catatan'),
        ]);
    }

    public function rules(): array
    {
        return [
            'hidden_neurons' => ['required', 'integer', 'min:3', 'max:32'],
            'learning_rate' => ['required', 'numeric', 'min:0.001', 'max:1'],
            'epochs' => ['required', 'integer', 'min:100', 'max:10000'],
            'target_error' => ['required', 'numeric', 'min:0.000001', 'max:1'],
            'test_percentage' => ['required', 'integer', 'min:10', 'max:40'],
            'random_seed' => ['required', 'integer', 'min:1', 'max:999999'],
            'nama_model' => ['required', 'string', 'max:150'],
            'catatan' => ['nullable', 'string', 'max:1000'],
        ];
    }

    public function messages(): array
    {
        return [
            'hidden_neurons.required' => 'Jumlah neuron hidden layer wajib diisi.',
            'hidden_neurons.integer' => 'Jumlah neuron harus berupa bilangan bulat.',
            'hidden_neurons.min' => 'Hidden layer minimal memiliki 3 neuron.',
            'hidden_neurons.max' => 'Hidden layer maksimal memiliki 32 neuron.',
            'learning_rate.required' => 'Learning rate wajib diisi.',
            'learning_rate.numeric' => 'Learning rate harus berupa angka.',
            'learning_rate.min' => 'Learning rate minimal 0.001.',
            'learning_rate.max' => 'Learning rate maksimal 1.',
            'epochs.required' => 'Jumlah epoch wajib diisi.',
            'epochs.integer' => 'Jumlah epoch harus berupa bilangan bulat.',
            'epochs.min' => 'Jumlah epoch minimal 100.',
            'epochs.max' => 'Jumlah epoch maksimal 10.000.',
            'target_error.required' => 'Target error wajib diisi.',
            'target_error.numeric' => 'Target error harus berupa angka.',
            'target_error.min' => 'Target error minimal 0.000001.',
            'target_error.max' => 'Target error maksimal 1.',
            'test_percentage.required' => 'Persentase data testing wajib diisi.',
            'test_percentage.integer' => 'Persentase testing harus berupa bilangan bulat.',
            'test_percentage.min' => 'Data testing minimal 10%.',
            'test_percentage.max' => 'Data testing maksimal 40%.',
            'random_seed.required' => 'Random seed wajib diisi.',
            'random_seed.integer' => 'Random seed harus berupa bilangan bulat.',
            'nama_model.required' => 'Nama model wajib diisi.',
            'nama_model.max' => 'Nama model maksimal 150 karakter.',
            'catatan.max' => 'Catatan maksimal 1.000 karakter.',
        ];
    }
}
