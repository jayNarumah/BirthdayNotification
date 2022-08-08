<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateProfileRequest extends FormRequest
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
     * @return array<string, mixed>
     */
    public function rules()
    {
        return [
            'name' => 'required|min:3|max:200',
            'email' => 'required|email|min:6|email',
            'phone_number' => 'required|min:11|max:13',
            'gender' => 'min:3|max:20',
            'dob' => 'required|min:4',
        ];
    }
}
