<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Auth;

class ArticleRequest extends FormRequest
{
    public function authorize()
    {
        return Auth::check() && (Auth::user()->isVeo() || Auth::user()->isAdmin());
    }

    public function rules()
    {
        return [
            'title' => ['required', 'string', 'max:255'],
            'content' => ['required', 'string', 'min:50'],
            'category' => ['required', Rule::in([
                'pest_control',
                'disease_management',
                'farming_techniques',
                'weather',
                'market_prices',
                'general'
            ])],
            'target_crops' => ['nullable', 'array'],
            'target_crops.*' => ['exists:crops,id'],
            'featured_image' => ['nullable', 'image', 'mimes:jpeg,png,jpg', 'max:5120'],
            'attachments' => ['nullable', 'array', 'max:5'],
            'attachments.*' => ['file', 'mimes:pdf,doc,docx,xls,xlsx', 'max:10240'],
            'is_published' => ['nullable', 'boolean'],
            'is_urgent' => ['nullable', 'boolean'],
        ];
    }

    public function messages()
    {
        return [
            'title.required' => 'Article title is required.',
            'content.required' => 'Article content is required.',
            'content.min' => 'Article content must be at least 50 characters.',
            'category.required' => 'Please select a category for this article.',
            'featured_image.image' => 'Featured image must be an image file.',
            'featured_image.max' => 'Featured image must not exceed 5MB.',
            'attachments.max' => 'You can upload maximum 5 attachments.',
            'attachments.*.file' => 'Each attachment must be a valid file.',
            'attachments.*.max' => 'Each attachment must not exceed 10MB.',
        ];
    }
}
