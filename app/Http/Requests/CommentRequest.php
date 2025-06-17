<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class CommentRequest extends FormRequest
{
    public function authorize()
    {
        return Auth::check() && in_array(Auth::user()->role, ['veo', 'farmer']);
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