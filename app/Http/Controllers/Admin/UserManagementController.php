<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Region;
use App\Models\Crop;
use App\Models\FarmerProfile;
use App\Models\ActivityLog as Activity;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Storage;

class UserManagementController extends Controller
{
    /**
     * Display a listing of users.
     */
    public function index(Request $request)
    {
        // Allow only users with Admin role
        if (!$request->user() || !$request->user()->isAdmin()) {
            abort(403, 'Unauthorized access');
        }

        $query = User::query();

        // Search functionality
        if ($request->has('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%$search%")
                    ->orWhere('email', 'like', "%$search%")
                    ->orWhere('phone', 'like', "%$search%");
            });
        }

        // Filter by role
        if ($request->has('role') && in_array($request->role, ['admin', 'veo', 'farmer'])) {
            $query->where('role', $request->role);
        }

        // Filter by status
        if ($request->has('status')) {
            $query->where('is_active', $request->status === 'active');
        }

        $users = $query->latest()->paginate(15);

        return view('users.index', compact('users'));
    }

    /**
     * Show the form for creating a new user.
     */
    public function create()
    {
        $regions = Region::all();
        $crops = Crop::all();
        return view('users.create', compact('regions', 'crops'));
    }

    /**
     * Store a newly created user in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'phone' => 'required|string|max:20|unique:users',
            'password' => 'required|string|min:8|confirmed',
            'role' => 'required|in:admin,veo,farmer',
            'region_id' => 'required|exists:regions,id',
            'village' => 'required|string|max:255',
            'bio' => 'nullable|string|max:500',
            'crops' => 'nullable|array',
            'crops.*' => 'exists:crops,id',
            'is_active' => 'boolean',
            'avatar' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'farm_size_acres' => 'nullable|numeric|min:0.1|max:1000',
            'farming_experience' => 'nullable|in:beginner,intermediate,expert',
        ]);

        // Handle avatar upload
        if ($request->hasFile('avatar')) {
            $validated['avatar'] = $request->file('avatar')->store('avatars', 'public');
            
            // Copy to public directory to ensure web access
            $sourcePath = storage_path('app/public/' . $validated['avatar']);
            $publicPath = public_path('storage/' . $validated['avatar']);
            $publicDir = dirname($publicPath);
            
            if (!is_dir($publicDir)) {
                mkdir($publicDir, 0755, true);
            }
            
            if (file_exists($sourcePath)) {
                copy($sourcePath, $publicPath);
            }
        }

        $validated['password'] = Hash::make($validated['password']);
        $validated['is_active'] = $request->has('is_active');

        $user = User::create($validated);

        activity()
            ->causedBy(Auth::user())
            ->performedOn($user)
            ->withProperties(['action' => 'user_created', 'ip' => request()->ip()])
            ->log('A new user was created: ' . $user->name . ' by ' . Auth::user()->name);

        return redirect()->route('users.index')->with('success', 'User created successfully.');
    }

    /**
     * Display the specified user.
     */
    public function show(User $user)
    {
        $activities = Activity::where('causer_id', $user->id)
            ->orWhere('subject_id', $user->id)
            ->latest()
            ->take(10)
            ->get();

        return view('users.show', compact('user', 'activities'));
    }

    /**
     * Show the form for editing the specified user.
     */
    public function edit(User $user)
    {
        $regions = Region::all();
        $crops = Crop::all();
        return view('users.edit', compact('user', 'regions', 'crops'));
    }

    /**
     * Update the specified user in storage.
     */
    public function update(Request $request, User $user)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => ['required', 'string', 'email', 'max:255', Rule::unique('users')->ignore($user->id)],
            'phone' => ['required', 'string', 'max:20', Rule::unique('users')->ignore($user->id)],
            'password' => 'nullable|string|min:8|confirmed',
            'role' => 'required|in:admin,veo,farmer',
            'region_id' => 'required|exists:regions,id',
            'village' => 'required|string|max:255',
            'bio' => 'nullable|string|max:500',
            'crops' => 'nullable|array',
            'crops.*' => 'exists:crops,id',
            'is_active' => 'boolean',
            'avatar' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'farm_size_acres' => 'nullable|numeric|min:0.1|max:1000',
            'farming_experience' => 'nullable|in:beginner,intermediate,expert',
        ]);

        // Handle avatar upload
        if ($request->hasFile('avatar')) {
            if ($user->avatar) {
                Storage::disk('public')->delete($user->avatar);
                // Also delete from public directory if it exists
                $publicPath = public_path('storage/' . $user->avatar);
                if (file_exists($publicPath)) {
                    unlink($publicPath);
                }
            }
            $validated['avatar'] = $request->file('avatar')->store('avatars', 'public');
            
            // Copy to public directory to ensure web access
            $sourcePath = storage_path('app/public/' . $validated['avatar']);
            $publicPath = public_path('storage/' . $validated['avatar']);
            $publicDir = dirname($publicPath);
            
            if (!is_dir($publicDir)) {
                mkdir($publicDir, 0755, true);
            }
            
            if (file_exists($sourcePath)) {
                copy($sourcePath, $publicPath);
            }
        }

        // Only update password if it was provided
        if ($request->filled('password')) {
            $validated['password'] = Hash::make($validated['password']);
        } else {
            unset($validated['password']);
        }

        $validated['is_active'] = $request->has('is_active');

        // Only update user fields that exist on the User model
        $userFields = [
            'name', 'email', 'phone', 'bio', 'avatar', 'is_active', 'role', 'village', 'password'
        ];
        $userData = array_intersect_key($validated, array_flip($userFields));
        $user->update($userData);

        // Update farmer profile if user is a farmer
        if ($user->role === 'farmer') {
            $profileData = $request->only([
                'region_id',
                'farm_size_acres',
                'farming_experience',
                'farming_methods'
            ]);

            if ($user->farmerProfile) {
                $user->farmerProfile->update($profileData);
            } else {
                $profileData['user_id'] = $user->id;
                FarmerProfile::create($profileData);
            }

            // Update farmer crops
            if ($request->has('crops')) {
                $user->farmerProfile->farmerCrops()->delete();
                foreach ($request->crops as $cropId) {
                    $user->farmerProfile->farmerCrops()->create([
                        'crop_id' => $cropId,
                        'area_planted_acres' => $request->input("crop_areas.{$cropId}", 0),
                    ]);
                }
            }
        }

        activity()
            ->causedBy(Auth::user())
            ->performedOn($user)
            ->withProperties(['action' => 'user_updated', 'ip' => request()->ip()])
            ->log('User ' . $user->name . ' was updated by ' . Auth::user()->name);

        return redirect()->route('users.index')->with('success', 'User updated successfully.');
    }

    /**
     * Remove the specified user from storage.
     */
    public function destroy(User $user)
    {
        // Prevent admin from deleting themselves
        if ($user->id === Auth::id()) {
            return back()->with('error', 'You cannot delete your own account.');
        }

        $user->delete();

        activity()
            ->causedBy(Auth::user())
            ->performedOn($user)
            ->withProperties(['action' => 'user_deleted', 'ip' => request()->ip()])
            ->log('User ' . $user->name . ' was deleted by ' . Auth::user()->name);

        return redirect()->route('users.index')->with('success', 'User deleted successfully.');
    }

    /**
     * Toggle user active status
     */
    public function toggleStatus(User $user)
    {
        $user->update(['is_active' => !$user->is_active]);

        $status = $user->is_active ? 'activated' : 'deactivated';

        activity()
            ->causedBy(Auth::user())
            ->performedOn($user)
            ->withProperties(['action' => 'user_' . $status, 'ip' => request()->ip()])
            ->log("User account for " . $user->name . " was " . $status . " by " . Auth::user()->name);

        return back()->with('success', "User $status successfully.");
    }

    /**
     * Get Tanzania regions list
     */
    private function getTanzaniaRegions()
    {
        return [
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
            'Zanzibar West'
        ];
    }
}
