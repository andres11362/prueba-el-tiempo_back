<?php

namespace App\Http\Requests\user;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class UserRequest extends FormRequest
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

        $user = Auth::user();

        return [
            'name' => 'required|max:55',
            'email' => 'email|unique:users,email,'.$user->id,
            'password' => 'required|confirmed',
            'phone' => 'required|max:10',
            'mobile' => 'required|max:15',
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
