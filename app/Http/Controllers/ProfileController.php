<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProfileUpdateRequest;
use App\Models\Region;
use App\Models\Village;
use App\Models\Crop;
use App\Models\FarmerProfile;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class ProfileController extends Controller
{
    public function show()
    {
        $user = Auth::user();
        $profile = $user->farmerProfile;

        return view('profile.show', compact('user', 'profile'));
    }

    public function edit()
    {
        $user = Auth::user();
        $profile = $user->farmerProfile;
        $regions = Region::all();
        $villages = Village::all();
        $crops = Crop::all();

        return view('profile.edit', compact('user', 'profile', 'regions', 'villages', 'crops'));
    }

    public function update(ProfileUpdateRequest $request)
    {
        $user = Auth::user();
        $validated = $request->validated();

        // Handle avatar upload
        if ($request->hasFile('avatar')) {
            if ($user->avatar) {
                Storage::disk('public')->delete($user->avatar);
            }
            $validated['avatar'] = $request->file('avatar')->store('avatars', 'public');
        }

        $user->update($validated);

        // Update farmer profile if user is a farmer
        if ($user->isFarmer) {
            $profileData = $request->only([
                'region_id',
                'village_id',
                'farm_size_acres',
                'farming_experience',
                'farming_methods'
            ]);

            if ($user->farmerProfile) {
                $user->farmerProfile->update($profileData);
            } else {
                FarmerProfile::create([
                    'user_id' => $user->id,
                    ...$profileData
                ]);
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
            ->causedBy($user)
            ->log('Profile updated');

        return redirect()->route('profile.show')->with('success', 'Profile updated successfully!');
    }
}
