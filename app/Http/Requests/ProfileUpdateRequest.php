<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Auth;

class ProfileUpdateRequest extends FormRequest
{
    public function authorize()
    {
        return Auth::check() && in_array(Auth::user()->role, ['admin', 'veo', 'farmer']);
    }

    public function rules()
    {
        $user = $this->user();

        $rules = [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', Rule::unique('users')->ignore($user->id)],
            'password' => ['nullable', 'string', 'max:255'],
            'phone' => ['nullable', 'string', 'max:20'],
            'bio' => ['nullable', 'string', 'max:500'],
            'avatar' => ['nullable', 'image', 'mimes:jpeg,png,jpg', 'max:2048'],
        ];

        // Add farmer-specific validation rules
        if ($user && $user->isFarmerRole()) {
            $rules = array_merge($rules, [
                'region_id' => ['required', 'exists:regions,id'],
                'village' => ['required', 'string', 'max:255'],
                'farm_size_acres' => ['nullable', 'numeric', 'min:0.1', 'max:1000'],
                'farming_experience' => ['required', Rule::in(['beginner', 'intermediate', 'expert'])],
                'farming_methods' => ['nullable', 'string', 'max:1000'],
                'crops' => ['nullable', 'array'],
                'crops.*' => ['exists:crops,id'],
            ]);
        }

        return $rules;
    }

    public function messages()
    {
        return [
            'name.required' => 'Name is required.',
            'email.required' => 'Email address is required.',
            'email.email' => 'Please enter a valid email address.',
            'email.unique' => 'This email is already taken.',
            'avatar.image' => 'Avatar must be an image file.',
            'avatar.mimes' => 'Avatar must be a JPEG or PNG file.',
            'avatar.max' => 'Avatar file size must not exceed 2MB.',
            'region_id.required' => 'Please select your region.',
            'village.required' => 'Please enter your village.',
            'farm_size_acres.numeric' => 'Farm size must be a number.',
            'farming_experience.required' => 'Please select your farming experience level.',
        ];
    }
}
