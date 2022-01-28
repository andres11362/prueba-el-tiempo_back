<?php

namespace App\Http\Requests\Sensor;

use Illuminate\Foundation\Http\FormRequest;

class MeditionRequest extends FormRequest
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
            'time' => 'required|date_format:Y-m-d H:i:s',
            'value' => 'required|regex:/^\d+(\.\d{1,2})?$/|between:0,99.99',
            'id_sensor' => 'required|exists:sensors,id'
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
