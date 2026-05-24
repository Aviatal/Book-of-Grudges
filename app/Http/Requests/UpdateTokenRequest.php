<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateTokenRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'name'    => ['nullable', 'string', 'max:255'],
            'hero_id' => ['nullable', 'exists:heroes,id'],
            'file'    => ['nullable', 'image'],
        ];
    }

    public function messages(): array
    {
        return [
            'name.required' => 'Nazwa tokenu musi być uzupełniona.',
            'hero_id.exists' => 'Bohater nie istnieje w bazie danych.',
            'file.image' => 'Zdjęcie tokenu musi być plikiem graficznym.',
        ];
    }
}
