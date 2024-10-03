<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class NoteRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'titulo' => ['required'],
            'descripcion' => ['required'],
            'categoria' => ['required'],
            'vencimiento' => ['nullable', 'date'],
            'imagen' => ['nullable', 'image', 'mimes:jpg,jpeg,png|max:2048']
        ];
    }

    public function messages()
    {
        return [
            'titulo' => 'El titulo es requerido',
            'descripcion' => 'La descripcion es requerida',
            'categoria' => 'La categoria es requerida',
            'vencimiento.date' => 'Fecha de vencimiento no valida',
            'imagen.image' => 'El archivo debe ser una imagen',
            'imagen.mimes' => 'La imagen debe ser del tipo jpg, jpeg o png',
            'imagen.max' => 'La imagen no debe pesar mas de 2 MB',
        ];
    }
}
