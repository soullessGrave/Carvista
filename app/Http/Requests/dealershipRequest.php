<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class dealershipRequest extends FormRequest
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
        'dealershipName' => ['required' , 'max:255'],

        'email' => ['required' , 'email:filter' , 'unique:users,email' , 'unique:dealerships,email' , 'max:40'],

        'username' => ['required' , 'alpha_num' , 'unique:users,username' , 'unique:dealerships,username' , 'min:5' , 'max:20'],

        'phoneNumber' => ['required' , 'numeric' , 'unique:users,phoneNumber' , 'unique:dealerships,phoneNumber'],

        'password' => ['required', 'string' , 'min:12' , 'max:32', 'confirmed'],

        'city' => ['required'],

        'locationUrl' => ['url'],
        ];
    }
}
