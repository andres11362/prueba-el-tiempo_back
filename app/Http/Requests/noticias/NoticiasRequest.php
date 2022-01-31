<?php

namespace App\Http\Requests\noticias;

use Illuminate\Foundation\Http\FormRequest;

class NoticiasRequest extends FormRequest
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
            'titulo' => 'required',
            'contenido' => 'required',
            'imagen' => 'required|max:10000|mimes:jpeg,png',
            'id_seccion' => 'required|exists:secciones,id'
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
