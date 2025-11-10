<?php

namespace App\Http\Controllers;

use App\Models\Organization;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\UserProfile;

class OrganizationController extends Controller
{
    public function index()
    {
        // Get all organizations with members count and adviser relation
        $organizations = Organization::with(['adviserUser.profile', 'members'])
                                     ->withCount('members')
                                     ->get();

        // Get all advisers from users table with their profile
        $advisers = User::where('account_role', 'Faculty_Adviser')
                        ->with('profile')
                        ->get();

        return view('admin.organizations.organizations', compact('organizations','advisers'));
    }

    public function store(Request $request)
    {
        // Validate input
        $validated = $request->validate([
            'organization_name' => 'required|string|max:255',
            'organization_type' => 'required|string|max:255',
            'adviser_name' => 'nullable|integer|exists:users,user_id',
            'description' => 'nullable|string',
        ]);

        // Assign current logged-in user as creator
        $validated['user_id'] = auth()->id();

        // Create organization
        Organization::create($validated);

        return redirect()->back()->with('success', 'Organization added successfully!');
    }
}
