<?php

namespace App\Http\Requests\Sensor;

use Illuminate\Foundation\Http\FormRequest;

class SensorRequest extends FormRequest
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
            'name' => 'required',
            'description' => 'required',
            'ubication' => 'required',
            'latitude' => 'required',
            'longitude' => 'required',
            'id_type_medition'=> 'required',
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
