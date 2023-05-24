<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CommentRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return auth('accounts')->user() ? true : false;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\Rule|array|string>
     */
    public function rules(): array
    {
        return [
            'content' => 'required|string'
        ];
    }

    public function messages()
    {
        return [
            'required' => 'Trường này là bắt buộc'
        ];
    }

    public function attributes()
    {
        return [
            'content' => 'nội dung'
        ];
    }
}
