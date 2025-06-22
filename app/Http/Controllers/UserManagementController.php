<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Hash;

class UserManagementController extends Controller
{
    public function index()
    {
        $users = User::latest()->paginate(10);
        return view('admin.users.index', compact('users'));
    }

    public function edit(User $user)
    {
        $regions = $this->getRegions();
        return view('admin.users.edit', compact('user', 'regions'));
    }

    public function update(Request $request, User $user)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => ['required', 'email', Rule::unique('users')->ignore($user->id)],
            'phone' => 'required|string|max:20',
            'region' => 'required|string|max:100',
            'village' => 'required|string|max:100',
            'is_active' => 'boolean',
            'role' => ['required', Rule::in(['admin', 'veo', 'farmer'])],
            'crops' => 'nullable|array',
        ]);

        $user->update([
            ...$validated,
            'crops' => $validated['crops'] ?? [],
        ]);

        return redirect()->route('admin.users.index')->with('success', 'User updated successfully.');
    }

    public function destroy(User $user)
    {
        if ($user->id === Auth::id()) {
            return back()->with('error', "You can't delete yourself.");
        }

        $user->delete();
        return back()->with('success', 'User deleted.');
    }

    private function getRegions(): array
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
