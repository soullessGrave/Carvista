<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class userUpdateRequest extends FormRequest
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
            'name' => ['required' , 'max:255'],

            'email' => ['required' , 'email:filter' , 'max:40'],

            'username' => ['required' , 'alpha_num' , 'min:5' , 'max:20'],

            'phoneNumber' => ['required' , 'numeric'],

            'city' => ['required' , 'string'],
        ];
    }
}
