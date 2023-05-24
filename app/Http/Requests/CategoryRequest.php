<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CategoryRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return auth()->user()->hasRole('Admin') || auth()->user()->hasRole('Manager');
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\Rule|array|string>
     */
    public function rules(): array
    {
        return [
            'name' => 'required'
        ];
    }

    public function attributes()
    {
        return [
            'name' => 'tên chủ đề'
        ];
    }

    public function messages()
    {
        return [
            'required' => 'Trường :attribute là bắt buôc'
        ];
    }
}
