<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UserRequest extends FormRequest
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
        $userId = $this->route('user');

        return [
        'nim' => 'required|numeric|digits_between:8,15',
        'name' => 'required|string|max:255',
        'gender' => 'required',
        'email' => 'required|email|unique:users,email,' . $userId,
        'address' => 'nullable|string',
        'position' => 'required|string|max:255',
        'prodi' => 'required|string|max:255',
        'role_id' => 'required|exists:roles,id',
    ];
}
}