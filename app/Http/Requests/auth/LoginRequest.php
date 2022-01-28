<?php

namespace App\Http\Requests\auth;

use Illuminate\Foundation\Http\FormRequest;

class LoginRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'email' => 'email|required',
            'password' => 'required'
        ];
    }

    /**
     * Retorna una respuesta según los errores 
     * de validación correspondientes
     * 
     * @return response
     */
    public function response(array $errors) 
    {
        return response()->json($errors, 422);
    }
}
