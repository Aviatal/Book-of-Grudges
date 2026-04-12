<?php

namespace App\Http\Requests;

use App\Models\Drawing;
use Illuminate\Foundation\Http\FormRequest;

class StoreDrawingRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'type' => ['in:' . implode(',', Drawing::DRAWING_TYPES)],
            'data.type' => ['in:' . implode(',', Drawing::DRAWING_TYPES)],
            'data.points' => ['nullable', 'array'],
            'data.x' => ['nullable', 'numeric'],
            'data.y' => ['nullable', 'numeric'],
            'data.width' => ['nullable', 'numeric'],
            'data.height' => ['nullable', 'numeric'],
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
