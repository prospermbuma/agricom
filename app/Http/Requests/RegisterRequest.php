<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class RegisterRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
            'role' => ['required', Rule::in(['farmer', 'veo'])],
            'phone' => ['nullable', 'string', 'max:20'],
            'region' => ['required', 'string', 'max:100'],
            'village' => ['required', 'string', 'max:100'],
            'crops' => ['nullable', 'array'],
            'crops.*' => ['string', 'max:50'],
            'bio' => ['nullable', 'string', 'max:1000'],
            'avatar' => ['nullable', 'image', 'mimes:jpeg,png,jpg,gif', 'max:2048'],
        ];
    }

    public function messages()
    {
        return [
            'name.required' => 'Name is required.',
            'email.required' => 'Email address is required.',
            'email.email' => 'Please enter a valid email address.',
            'email.unique' => 'This email is already taken.',
            'password.required' => 'Password is required.',
            'password.min' => 'Password must be at least 8 characters.',
            'password.confirmed' => 'Password confirmation does not match.',
            'role.required' => 'Please select your role.',
            'role.in' => 'Please select either Farmer or VEO.',
            'region.required' => 'Region is required.',
            'village.required' => 'Village is required.',
            'crops.array' => 'Crops must be a list.',
            'crops.*.string' => 'Each crop must be a valid text.',
            'bio.string' => 'Bio must be a valid string.',
            'bio.max' => 'Bio must not exceed 1000 characters.',
            'avatar.image' => 'Avatar must be an image file.',
            'avatar.mimes' => 'Avatar must be a jpeg, png, jpg, or gif.',
            'avatar.max' => 'Avatar must not be larger than 2MB.',
        ];
    }
}
