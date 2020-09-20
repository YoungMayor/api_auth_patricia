<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ValidateRegistration extends FormRequest
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
            'name' => [
                'required', 
                'min:2',
                'max:255', 
                'string'
            ], 
            'email' => [
                'required', 
                'email', 
                'string', 
                'unique:users,email'
            ], 
            'password' => [
                'required', 
                'string', 
                'confirmed', 
                'min:4'
            ]
        ];
    }

    public function messages()
    {
        return [
            'name.required' => "Name is required during registration", 
            'name.min' => "The given name is unrealistically too short", 
            'name.max' => "The given name exceeds the maximum allowed length for name. Please truncate it", 
            'name.string' => "Invalid name",

            'email.required' => "E-Mail Address is required during registration. This would be used for your login actions and account verification",
            'email.unique' => "The given email is already registered. Please Login", 
            'email.*' => "Invalid email address supplied",

            'password.required' => "A secure password is required during registration", 
            'password.confirmed' => "The given passwords do not match", 
            'password.min' => "Password too short", 
            'password.string' => "Invalid password"
        ];
    }
}
