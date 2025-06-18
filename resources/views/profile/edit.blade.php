@extends('layouts.app')

@section('title', 'Edit Profile - Agricom')

@section('content')
    <div class="row mt-4">
        <div class="col-md-8 mx-auto">
            <div class="card">
                <div class="card-header">
                    <h4><i class="fas fa-user-edit"></i> Edit Profile</h4>
                </div>
                <div class="card-body">
                    <form method="POST" action="{{ route('profile.update') }}">
                        @csrf
                        @method('PUT')

                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label">Full Name</label>
                                    <input type="text" name="name"
                                        class="form-control @error('name') is-invalid @enderror"
                                        value="{{ old('name', auth()->user()->name) }}" required>
                                    @error('name')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label">Email Address</label>
                                    <input type="email" name="email"
                                        class="form-control @error('email') is-invalid @enderror"
                                        value="{{ old('email', auth()->user()->email) }}" required>
                                    @error('email')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label">Phone Number</label>
                                    <input type="tel" name="phone"
                                        class="form-control @error('phone') is-invalid @enderror"
                                        value="{{ old('phone', auth()->user()->phone) }}" required>
                                    @error('phone')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label">Role</label>
                                    <input type="text" class="form-control" value="{{ ucfirst(auth()->user()->role) }}"
                                        disabled>
                                    <small class="form-text text-muted">Role cannot be changed</small>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label">Region</label>
                                    <select name="region" class="form-select @error('region') is-invalid @enderror"
                                        required>
                                        <option value="">Select Region</option>
                                        <option value="Arusha"
                                            {{ old('region', auth()->user()->region) == 'Arusha' ? 'selected' : '' }}>Arusha
                                        </option>
                                        <option value="Dar es Salaam"
                                            {{ old('region', auth()->user()->region) == 'Dar es Salaam' ? 'selected' : '' }}>
                                            Dar es Salaam</option>
                                        <option value="Dodoma"
                                            {{ old('region', auth()->user()->region) == 'Dodoma' ? 'selected' : '' }}>
                                            Dodoma</option>
                                        <option value="Kilimanjaro"
                                            {{ old('region', auth()->user()->region) == 'Kilimanjaro' ? 'selected' : '' }}>
                                            Kilimanjaro</option>
                                        <option value="Mbeya"
                                            {{ old('region', auth()->user()->region) == 'Mbeya' ? 'selected' : '' }}>Mbeya
                                        </option>
                                        <option value="Morogoro"
                                            {{ old('region', auth()->user()->region) == 'Morogoro' ? 'selected' : '' }}>
                                            Morogoro</option>
                                        <option value="Mwanza"
                                            {{ old('region', auth()->user()->region) == 'Mwanza' ? 'selected' : '' }}>
                                            Mwanza</option>
                                        <option value="Pwani"
                                            {{ old('region', auth()->user()->region) == 'Pwani' ? 'selected' : '' }}>Pwani
                                        </option>
                                        <option value="Tabora"
                                            {{ old('region', auth()->user()->region) == 'Tabora' ? 'selected' : '' }}>
                                            Tabora</option>
                                        <option value="Tanga"
                                            {{ old('region', auth()->user()->region) == 'Tanga' ? 'selected' : '' }}>Tanga
                                        </option>
                                    </select>
                                    @error('region')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label">Village</label>
                                    <input type="text" name="village"
                                        class="form-control @error('village') is-invalid @enderror"
                                        value="{{ old('village', auth()->user()->village) }}" required>
                                    @error('village')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        @if (auth()->user()->role === 'farmer')
                            <div class="mb-3">
                                <label class="form-label">Types of Crops</label>
                                <div class="row">
                                    @php
                                        $userCrops = auth()->user()->crops ? json_decode(auth()->user()->crops) : [];
                                        $availableCrops = [
                                            'maize',
                                            'rice',
                                            'beans',
                                            'cassava',
                                            'coffee',
                                            'cotton',
                                            'sunflower',
                                            'other',
                                        ];
                                    @endphp
                                    @foreach ($availableCrops as $crop)
                                        <div class="col-md-6">
                                            <div class="form-check">
                                                <input class="form-check-input" type="checkbox" name="crops[]"
                                                    value="{{ $crop }}" id="{{ $crop }}"
                                                    {{ in_array($crop, $userCrops) ? 'checked' : '' }}>
                                                <label class="form-check-label" for="{{ $crop }}">
                                                    {{ ucfirst($crop) }}
                                                </label>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                                @error('crops')
                                    <div class="text-danger">{{ $message }}</div>
                                @enderror
