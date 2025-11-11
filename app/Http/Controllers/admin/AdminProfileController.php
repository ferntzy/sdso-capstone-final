<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\UserProfile;

class AdminProfileController extends Controller
{
      public function show()
    {
        $admin = Auth::user();

        // Create empty profile if not exists
        $admin->profile()->firstOrCreate([
            'user_id' => $admin->user_id
        ]);

        $admin->load('profile');

        return view('admin.profile.admin_profile', compact('admin'));
    }

    public function update(Request $request)
    {
        $user = Auth::user();

        $validated = $request->validate([
            'first_name' => 'required|string|max:100',
            'middle_name' => 'required|string|max:100',
            'last_name' => 'required|string|max:100',
            'contact_number' => 'nullable|string|max:20',
            'address' => 'nullable|string|max:255',
            'office' => 'required|string|max:100',
            'avatar' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
        ]);

        // Handle avatar file upload
        if ($request->hasFile('avatar')) {
            $file = $request->file('avatar');
            $filename = time() . '_' . $file->getClientOriginalName();
            $file->move(public_path('images/user_profile'), $filename);

            // Save relative path for database
            $validated['profile_picture_path'] = 'images/user_profile/' . $filename;
        }

        // Update or create the profile
        UserProfile::updateOrCreate(
            ['user_id' => $user->user_id], // assuming 'id' is your primary key in users table
            $validated
        );

        return redirect()->route('admin.profile.show')->with('success', 'Profile updated successfully!');

    }

    public function profile() {
    $admin = Auth::user();
    return view('admin.profile.admin_profile', compact('admin'));
}

}
