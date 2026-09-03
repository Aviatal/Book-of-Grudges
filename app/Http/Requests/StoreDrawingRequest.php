<?php

namespace App\Http\Requests;

use App\Models\Drawing;
use Illuminate\Foundation\Http\FormRequest;

class StoreDrawingRequest extends FormRequest
{
    public function rules(): array
    {
        // Przy tworzeniu (POST /store) type jest wymagany; przy PATCH aktualizujemy tylko data.
        $typeRequired = $this->isMethod('post') ? 'required' : 'sometimes';

        return [
            'type'  => [$typeRequired, 'string', 'in:' . implode(',', Drawing::DRAWING_TYPES)],
            'layer' => ['nullable', 'string', 'in:' . implode(',', Drawing::LAYERS)],
            'data'  => ['sometimes', 'array'],
            'data.type' => ['nullable', 'string', 'in:' . implode(',', Drawing::DRAWING_TYPES)],
            'data.points' => ['nullable', 'array'],
            'data.x' => ['nullable', 'numeric'],
            'data.y' => ['nullable', 'numeric'],
            'data.width' => ['nullable', 'numeric'],
            'data.height' => ['nullable', 'numeric'],
            'data.src' => ['nullable', 'string'],
        ];
    }

    public function messages(): array
    {
        return [
            'type.in' => 'Nieprawidłowy typ rysunku.',
            'data.x' => 'Współrzędna x musi być numeryczna.',
            'data.y' => 'Współrzędna y musi być numeryczna.',
            'data.width' => 'Szerokość musi być numeryczna.',
            'data.height' => 'Wysokość musi być numeryczna.',
        ];
    }
}
