<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CommentRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'content' => ['required', 'string', 'min:10', 'max:1000'],
            'parent_id' => ['nullable', 'exists:comments,id'],
        ];
    }

    public function messages()
    {
        return [
            'content.required' => 'Comment content is required.',
            'content.min' => 'Comment must be at least 10 characters.',
            'content.max' => 'Comment must not exceed 1000 characters.',
        ];
    }
}