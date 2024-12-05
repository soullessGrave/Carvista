<?php

namespace App\Http\Requests;

use App\Models\Car;
use Illuminate\Foundation\Http\FormRequest;

class carRequest extends FormRequest
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

       'brandName' => ['required' , 'max:255'],

       'modelName' => ['required' ,'max:255'],

       'manufactureYear' => ['required' , 'numeric' , 'min:1950' , 'max:'.(date('Y') + 1)],

       'distance' => ['required' , 'numeric'],

       'condition' => ['required' , 'string'],

       'price' => ['required' , 'string'],
                   
       'description' => ['max:400'],
        ];
    }
}
