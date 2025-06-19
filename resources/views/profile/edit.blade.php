@extends('layouts.app')

@section('title', 'Edit Profile - Agricom')

@section('content')
    <div class="container mt-5">
        <div class="row">
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
                                        <input type="hidden" name="role" value="{{ auth()->user()->role }}">
                                        <input type="text" class="form-control"
                                            value="{{ ucfirst(auth()->user()->role) }}" disabled>
                                        <small class="form-text text-muted">Role cannot be changed</small>
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label class="form-label">Region</label>
                                        @php
                                            $regions = [
                                                'Arusha',
                                                'Dar es Salaam',
                                                'Dodoma',
                                                'Geita',
                                                'Iringa',
                                                'Kagera',
                                                'Katavi',
                                                'Kigoma',
                                                'Kilimanjaro',
                                                'Lindi',
                                                'Manyara',
                                                'Mara',
                                                'Mbeya',
                                                'Morogoro',
                                                'Mtwara',
                                                'Mwanza',
                                                'Njombe',
                                                'Pemba North',
                                                'Pemba South',
                                                'Pwani',
                                                'Rukwa',
                                                'Ruvuma',
                                                'Shinyanga',
                                                'Simiyu',
                                                'Singida',
                                                'Songwe',
                                                'Tabora',
                                                'Tanga',
                                                'Unguja North',
                                                'Unguja South',
                                                'Zanzibar West',
                                            ];
                                        @endphp
                                        <select name="region" class="form-select @error('region') is-invalid @enderror"
                                            required>
                                            <option value="">Select Region</option>
                                            @foreach ($regions as $region)
                                                <option value="{{ $region }}"
                                                    {{ old('region', auth()->user()->region) == $region ? 'selected' : '' }}>
                                                    {{ $region }}
                                                </option>
                                            @endforeach
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
                                            // $userCrops = old('crops') ?? json_decode(auth()->user()->crops ?? '[]', true);
                                            $userCrops = old('crops') ?? (auth()->user()->crops ?? []);
                                        @endphp
                                        @foreach ($availableCrops as $crop)
                                            <div class="col-md-6">
                                                <div class="form-check">
                                                    <input class="form-check-input" type="checkbox" name="crops[]"
                                                        value="{{ $crop }}" id="{{ $crop }}"
                                                        {{ is_array($userCrops) && in_array($crop, $userCrops) ? 'checked' : '' }}>
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
                                    @error('crops.*')
                                        <div class="text-danger">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="mb-3">
                                    <label class="form-label">Farm Size (Hectares)</label>
                                    <input type="number" name="farm_size" step="0.1" min="0"
                                        class="form-control @error('farm_size') is-invalid @enderror"
                                        value="{{ old('farm_size', auth()->user()->farm_size) }}">
                                    @error('farm_size')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            @endif

                            <div class="mb-3">
                                <label class="form-label">Change Password</label>
                                <small class="form-text text-muted">Leave blank if you don't want to change your
                                    password</small>
                            </div>

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label class="form-label">New Password</label>
                                        <input type="password" name="password"
                                            class="form-control @error('password') is-invalid @enderror">
                                        @error('password')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label class="form-label">Confirm New Password</label>
                                        <input type="password" name="password_confirmation" class="form-control">
                                    </div>
                                </div>
                            </div>

                            <div class="d-flex justify-content-between">
                                <a href="{{ route('profile.show') }}" class="btn btn-secondary">
                                    <i class="fas fa-arrow-left"></i> Back to Profile
                                </a>
                                <button type="submit" class="btn btn-primary">
                                    <i class="fas fa-save"></i> Update Profile
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
