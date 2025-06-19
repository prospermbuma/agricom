@extends('layouts.app')

@section('title', 'Register - Agricom')

@section('content')
    <div class="row justify-content-center mt-5">
        <div class="col-md-6">
            <div class="hero-text mb-5">
                <h1 class="text-center">
                    Agricom | Register
                </h1>
            </div>
            <div class="card shadow p-4">
                <div class="card-body">
                    <form method="POST" action="{{ route('register') }}">
                        @csrf

                        <div class="mb-3">
                            <label class="form-label">Full Name</label>
                            <input type="text" name="name" class="form-control @error('name') is-invalid @enderror"
                                value="{{ old('name') }}" required>
                            @error('name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Email Address</label>
                            <input type="email" name="email" class="form-control @error('email') is-invalid @enderror"
                                value="{{ old('email') }}" required>
                            @error('email')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Phone Number</label>
                            <input type="tel" name="phone" class="form-control @error('phone') is-invalid @enderror"
                                value="{{ old('phone') }}" required>
                            @error('phone')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Role</label>
                            <select name="role" class="form-select @error('role') is-invalid @enderror" required>
                                <option value="">Select Role</option>
                                <option value="farmer" {{ old('role') == 'farmer' ? 'selected' : '' }}>Farmer</option>
                                <option value="veo" {{ old('role') == 'veo' ? 'selected' : '' }}>Village Extension
                                    Officer</option>
                            </select>
                            @error('role')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Region</label>
                            <select name="region" class="form-select @error('region') is-invalid @enderror" required>
                                <option value="">Select Region</option>
                                <option value="Arusha" {{ old('region') == 'Arusha' ? 'selected' : '' }}>Arusha</option>
                                <option value="Dar es Salaam" {{ old('region') == 'Dar es Salaam' ? 'selected' : '' }}>Dar
                                    es Salaam</option>
                                <option value="Dodoma" {{ old('region') == 'Dodoma' ? 'selected' : '' }}>Dodoma</option>
                                <option value="Geita" {{ old('region') == 'Geita' ? 'selected' : '' }}>Geita</option>
                                <option value="Iringa" {{ old('region') == 'Iringa' ? 'selected' : '' }}>Iringa</option>
                                <option value="Kagera" {{ old('region') == 'Kagera' ? 'selected' : '' }}>Kagera</option>
                                <option value="Katavi" {{ old('region') == 'Katavi' ? 'selected' : '' }}>Katavi</option>
                                <option value="Kigoma" {{ old('region') == 'Kigoma' ? 'selected' : '' }}>Kigoma</option>
                                <option value="Kilimanjaro" {{ old('region') == 'Kilimanjaro' ? 'selected' : '' }}>
                                    Kilimanjaro</option>
                                <option value="Lindi" {{ old('region') == 'Lindi' ? 'selected' : '' }}>Lindi</option>
                                <option value="Manyara" {{ old('region') == 'Manyara' ? 'selected' : '' }}>Manyara</option>
                                <option value="Mara" {{ old('region') == 'Mara' ? 'selected' : '' }}>Mara</option>
                                <option value="Mbeya" {{ old('region') == 'Mbeya' ? 'selected' : '' }}>Mbeya</option>
                                <option value="Morogoro" {{ old('region') == 'Morogoro' ? 'selected' : '' }}>Morogoro
                                </option>
                                <option value="Mtwara" {{ old('region') == 'Mtwara' ? 'selected' : '' }}>Mtwara</option>
                                <option value="Mwanza" {{ old('region') == 'Mwanza' ? 'selected' : '' }}>Mwanza</option>
                                <option value="Njombe" {{ old('region') == 'Njombe' ? 'selected' : '' }}>Njombe</option>
                                <option value="Pwani" {{ old('region') == 'Pwani' ? 'selected' : '' }}>Pwani</option>
                                <option value="Rukwa" {{ old('region') == 'Rukwa' ? 'selected' : '' }}>Rukwa</option>
                                <option value="Ruvuma" {{ old('region') == 'Ruvuma' ? 'selected' : '' }}>Ruvuma</option>
                                <option value="Shinyanga" {{ old('region') == 'Shinyanga' ? 'selected' : '' }}>Shinyanga
                                </option>
                                <option value="Simiyu" {{ old('region') == 'Simiyu' ? 'selected' : '' }}>Simiyu</option>
                                <option value="Singida" {{ old('region') == 'Singida' ? 'selected' : '' }}>Singida</option>
                                <option value="Songwe" {{ old('region') == 'Songwe' ? 'selected' : '' }}>Songwe</option>
                                <option value="Tabora" {{ old('region') == 'Tabora' ? 'selected' : '' }}>Tabora</option>
                                <option value="Tanga" {{ old('region') == 'Tanga' ? 'selected' : '' }}>Tanga</option>
                                <option value="Zanzibar Central/South"
                                    {{ old('region') == 'Zanzibar Central/South' ? 'selected' : '' }}>Zanzibar
                                    Central/South</option>
                                <option value="Zanzibar North" {{ old('region') == 'Zanzibar North' ? 'selected' : '' }}>
                                    Zanzibar North</option>
                                <option value="Zanzibar Urban/West"
                                    {{ old('region') == 'Zanzibar Urban/West' ? 'selected' : '' }}>Zanzibar Urban/West
                                </option>
                                <option value="Unguja South" {{ old('region') == 'Unguja South' ? 'selected' : '' }}>Unguja
                                    South</option>
                                <option value="Unguja North" {{ old('region') == 'Unguja North' ? 'selected' : '' }}>Unguja
                                    North</option>

                                </option>
                                <!-- Add more regions as needed -->
                            </select>
                            @error('region')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Village</label>
                            <input type="text" name="village" class="form-control @error('village') is-invalid @enderror"
                                value="{{ old('village') }}" required>
                            @error('village')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3" id="crops-section" style="display: none;">
                            <label class="form-label">Types of Crops</label>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" name="crops[]" value="maize"
                                            id="maize">
                                        <label class="form-check-label" for="maize">Maize</label>
                                    </div>
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" name="crops[]" value="rice"
                                            id="rice">
                                        <label class="form-check-label" for="rice">Rice</label>
                                    </div>
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" name="crops[]" value="beans"
                                            id="beans">
                                        <label class="form-check-label" for="beans">Beans</label>
                                    </div>
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" name="crops[]" value="cassava"
                                            id="cassava">
                                        <label class="form-check-label" for="cassava">Cassava</label>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" name="crops[]" value="coffee"
                                            id="coffee">
                                        <label class="form-check-label" for="coffee">Coffee</label>
                                    </div>
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" name="crops[]" value="cotton"
                                            id="cotton">
                                        <label class="form-check-label" for="cotton">Cotton</label>
                                    </div>
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" name="crops[]" value="sunflower"
                                            id="sunflower">
                                        <label class="form-check-label" for="sunflower">Sunflower</label>
                                    </div>
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" name="crops[]" value="other"
                                            id="other">
                                        <label class="form-check-label" for="other">Other</label>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Password</label>
                            <input type="password" name="password"
                                class="form-control @error('password') is-invalid @enderror" required>
                            @error('password')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Confirm Password</label>
                            <input type="password" name="password_confirmation" class="form-control" required>
                        </div>

                        <div class="d-grid">
                            <button type="submit" class="btn btn-success">
                                <i class="fas fa-user-plus"></i> Register
                            </button>
                        </div>
                    </form>

                    <div class="text-center mt-3">
                        <p>Already have an account? <a href="{{ route('login') }}" class="login-link">Login here</a></p>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    <script>
        $(document).ready(function() {
            $('select[name="role"]').change(function() {
                if ($(this).val() === 'farmer') {
                    $('#crops-section').show();
                } else {
                    $('#crops-section').hide();
                }
            });

            // Trigger change on page load if role is already selected
            if ($('select[name="role"]').val() === 'farmer') {
                $('#crops-section').show();
            }
        });
    </script>
@endsection
