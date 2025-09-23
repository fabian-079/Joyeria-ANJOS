<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreCustomizationRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true; // Cualquier usuario autenticado puede solicitar personalizaciones
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'jewelry_type' => 'required|string|max:255',
            'design' => 'required|string|max:255',
            'stones' => 'required|string|max:255',
            'finish' => 'required|string|max:255',
            'color' => 'required|string|max:255',
            'material' => 'required|string|max:255',
            'engraving' => 'nullable|string|max:255',
            'special_instructions' => 'nullable|string|max:1000'
        ];
    }

    /**
     * Get custom messages for validator errors.
     */
    public function messages(): array
    {
        return [
            'jewelry_type.required' => 'El tipo de joya es obligatorio.',
            'design.required' => 'El estilo de diseño es obligatorio.',
            'stones.required' => 'La selección de piedras es obligatoria.',
            'finish.required' => 'El acabado es obligatorio.',
            'color.required' => 'El color es obligatorio.',
            'material.required' => 'El material es obligatorio.',
            'engraving.max' => 'El grabado no puede tener más de 255 caracteres.',
            'special_instructions.max' => 'Las instrucciones especiales no pueden tener más de 1000 caracteres.'
        ];
    }
}









