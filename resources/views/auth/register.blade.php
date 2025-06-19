@extends('layouts.app')

@section('title', 'Register - Agricom')

@section('content')
    <div class="row justify-content-center mt-5">
        <div class="col-md-6">
            <div class="hero-text mb-5">
                <h1 class="text-center">
                    <i class="fas fa-leaf text-success"></i> Agricom | Register
                </h1>
            </div>
            <div class="card shadow p-4">
                <div class="card-body">
                    <form method="POST" action="{{ route('register') }}">
                        @csrf

                        <div class="mb-3">
                            <label class="form-label"><i class="fas fa-user"></i> Full Name</label>
                            <input type="text" name="name" class="form-control @error('name') is-invalid @enderror"
                                value="{{ old('name') }}" placeholder="John Doe" required>
                            @error('name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label class="form-label"><i class="fas fa-envelope"></i> Email Address</label>
                            <input type="email" name="email" class="form-control @error('email') is-invalid @enderror"
                                value="{{ old('email') }}" placeholder="john.doe@example.com" required>
                            @error('email')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label class="form-label"><i class="fas fa-phone"></i> Phone Number</label>
                            <input type="tel" name="phone" class="form-control @error('phone') is-invalid @enderror"
                                value="{{ old('phone') }}" placeholder="e.g., +255712345678" required>
                            @error('phone')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        {{-- <div class="mb-3">
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
                        </div> --}}

                        <!-- Role Selection -->
                        <div class="mb-3">
                            <label class="form-label">
                                <i class="fas fa-user-tag"></i> Role
                            </label>
                            <div class="row">
                                <div class="col-6">
                                    <div class="card border-2 role-card" data-role="farmer">
                                        <div class="card-body text-center">
                                            <i class="fas fa-seedling fa-2x text-success mb-2"></i>
                                            <h6>Farmer</h6>
                                            <small class="text-muted">I am a farmer seeking agricultural guidance</small>
                                            <input type="radio" name="role" value="farmer"
                                                class="form-check-input d-none"
                                                {{ old('role') == 'farmer' ? 'checked' : '' }}>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-6">
                                    <div class="card border-2 role-card" data-role="veo">
                                        <div class="card-body text-center">
                                            <i class="fas fa-user-tie fa-2x text-primary mb-2"></i>
                                            <h6>VEO</h6>
                                            <small class="text-muted">I am a Village Extension Officer</small>
                                            <input type="radio" name="role" value="veo"
                                                class="form-check-input d-none" {{ old('role') == 'veo' ? 'checked' : '' }}>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            @error('role')
                                <div class="text-danger small mt-1">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label class="form-label"><i class="fas fa-map-marker-alt"></i> Region</label>
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
                                <option value="Unguja South" {{ old('region') == 'Unguja South' ? 'selected' : '' }}>
                                    Unguja South
                                </option>
                                <option value="Unguja North" {{ old('region') == 'Unguja North' ? 'selected' : '' }}>
                                    Unguja North
                                </option>
                            </select>
                            @error('region')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label class="form-label"><i class="fas fa-home"></i> Village</label>
                            <input type="text" name="village"
                                class="form-control @error('village') is-invalid @enderror" value="{{ old('village') }}"
                                required>
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

                        {{-- === PASSWORD === --}}
                        <div class="mb-3">
                            <label class="form-label"><i class="fas fa-lock"></i> Password</label>
                            <div class="input-group">
                                <input type="password" name="password" id="password"
                                    class="form-control @error('password') is-invalid @enderror" required>
                                <span class="input-group-text" style="cursor: pointer;"
                                    onclick="togglePassword('password', this)">
                                    <i class="fas fa-eye text-secondary" id="toggle-password-icon"></i>
                                </span>
                            </div>
                            @error('password')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        {{-- === CONFIRM PASSWORD === --}}
                        <div class="mb-3">
                            <label class="form-label"><i class="fas fa-lock"></i> Confirm Password</label>
                            <div class="input-group">
                                <input type="password" name="password_confirmation" id="password_confirmation"
                                    class="form-control" required>
                                <span class="input-group-text" style="cursor: pointer;"
                                    onclick="togglePassword('password_confirmation', this)">
                                    <i class="fas fa-eye text-secondary" id="toggle-confirm-password-icon"></i>
                                </span>
                            </div>
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

@section('styles')
    <style>
        .role-card {
            cursor: pointer;
            transition: 0.2s ease-in-out;
        }

        .role-card:hover {
            border-color: #28a745;
            box-shadow: 0 0 10px rgba(40, 167, 69, 0.3);
        }

        .role-card.border-success {
            border-color: #198754 !important;
        }
    </style>
@endsection


@section('scripts')
    <script>
        // ROLE CARDS
        document.addEventListener('DOMContentLoaded', function() {
            const roleCards = document.querySelectorAll('.role-card');

            roleCards.forEach(card => {
                card.addEventListener('click', () => {
                    // Unselect all cards
                    roleCards.forEach(c => c.classList.remove('border-success', 'shadow-sm'));

                    // Select clicked card
                    card.classList.add('border-success', 'shadow-sm');

                    // Set the corresponding radio input as checked
                    const input = card.querySelector('input[type="radio"]');
                    input.checked = true;

                    // Show/hide crops section based on role
                    if (input.value === 'farmer') {
                        document.getElementById('crops-section').style.display = 'block';
                    } else {
                        document.getElementById('crops-section').style.display = 'none';
                    }
                });

                // Trigger card styling if preselected
                const radio = card.querySelector('input[type="radio"]');
                if (radio.checked) {
                    card.classList.add('border-success', 'shadow-sm');

                    if (radio.value === 'farmer') {
                        document.getElementById('crops-section').style.display = 'block';
                    }
                }
            });
        });

        // HIDE/SHOW PASSWORD
        function togglePassword(fieldId, iconElement) {
            const input = document.getElementById(fieldId);
            const icon = iconElement.querySelector('i');

            if (input.type === "password") {
                input.type = "text";
                icon.classList.remove("fa-eye");
                icon.classList.add("fa-eye-slash");
            } else {
                input.type = "password";
                icon.classList.remove("fa-eye-slash");
                icon.classList.add("fa-eye");
            }
        }
    </script>
@endsection
