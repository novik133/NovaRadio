<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class ProfileController extends Controller
{
    public function edit()
    {
        $user = Auth::user();
        
        return view('admin.profile.edit', compact('user'));
    }
    
    public function update(Request $request)
    {
        $user = Auth::user();
        
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $user->id,
            'phone' => 'nullable|string|max:50',
            'bio' => 'nullable|string|max:1000',
            'timezone' => 'nullable|string|max:50',
            'avatar' => 'nullable|url|max:500',
        ]);
        
        $user->update($validated);
        
        return redirect()->route('admin.profile.edit')
            ->with('success', 'Profile updated successfully!');
    }
    
    public function updatePassword(Request $request)
    {
        $user = Auth::user();
        
        $validated = $request->validate([
            'current_password' => 'required|string',
            'password' => 'required|string|min:8|confirmed',
        ]);
        
        if (!Hash::check($validated['current_password'], $user->password)) {
            return back()->withErrors(['current_password' => 'Current password is incorrect.']);
        }
        
        $user->update(['password' => Hash::make($validated['password'])]);
        
        return redirect()->route('admin.profile.edit')
            ->with('success', 'Password updated successfully!');
    }
    
    public function uploadAvatar(Request $request)
    {
        $user = Auth::user();
        
        $request->validate([
            'avatar' => 'required|image|mimes:jpg,jpeg,png,webp|max:2048'
        ]);
        
        $file = $request->file('avatar');
        $filename = 'user_' . $user->id . '_' . time() . '.' . $file->getClientOriginalExtension();
        $path = $file->storeAs('public/images/avatars', $filename);
        
        $user->update(['avatar' => str_replace('public/', 'storage/', $path)]);
        
        return response()->json([
            'success' => true,
            'url' => asset(str_replace('public/', 'storage/', $path))
        ]);
    }
}
