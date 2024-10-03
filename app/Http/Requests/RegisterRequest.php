<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\Password;

class RegisterRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;//Autorizamos
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            //Definimos las reglas para la validacion
            'name' => ['required', 'string'],
            'email' => ['required', 'email', 'unique:users,email'],
            'password' => ['required', 'confirmed', Password::min(8)]
        ];
    }

    public function messages()
    {
        return [
            //Mensajes personalizados de la validacion
            'name' => 'El nombre es requerido',
            'email.required' => 'El email es requerido',
            'email.email' => 'Ingresa un email valido',
            'email.unique' => 'Este usuario ya existe',
            'password.required' => 'La contraseña es requerida',
            'password.confirmed' => 'La confirmación de la contraseña no coincide',
            'password.min' => 'La contraseña debe tener al menos 8 caracteres'
        ];
    }
}
